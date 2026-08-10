<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class QrCodeController extends Controller
{
    /**
     * Menampilkan QR Code di halaman (dengan background)
     */
    public function show($id)
    {
        $server = Server::findOrFail($id);
        $url = route('detailserver', $id);

        $qrBinary = $this->fetchQrFromApi($url, 300);
        $pngBinary = $this->mergeWithBackground($qrBinary);

        return Response::make($pngBinary, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * Mengunduh QR Code dengan background desain
     */
    public function download($id)
    {
        $server = Server::findOrFail($id);
        $url = route('detailserver', $id);

        $qrBinary = $this->fetchQrFromApi($url, 400);
        $pngBinary = $this->mergeWithBackground($qrBinary);

        return response($pngBinary, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="qr-server-' . $server->id . '.png"'
        ]);
    }

    /**
     * Ambil binary QR PNG dari API eksternal (api.qrserver.com).
     * Ini menghindari kebutuhan extension imagick di server lokal,
     * karena generate QR dilakukan oleh server luar.
     */
    private function fetchQrFromApi(string $url, int $size): ?string
    {
        try {
            $response = Http::timeout(10)->get('https://api.qrserver.com/v1/create-qr-code/', [
                'size' => "{$size}x{$size}",
                'data' => $url,
            ]);

            if ($response->successful()) {
                return $response->body();
            }

            Log::error('QR API gagal, status: ' . $response->status());
            return null;

        } catch (\Exception $e) {
            Log::error('QR API fetch error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Gabungkan QR binary dengan background 'qr-background.png'.
     * QR diposisikan di tengah kotak putih pada background.
     */
    private function mergeWithBackground(?string $qrBinary): string
    {
        if ($qrBinary === null) {
            abort(500, 'Gagal generate QR Code, coba lagi.');
        }

        $bgPath = public_path('img/qr-background.png');

        if (!file_exists($bgPath)) {
            Log::warning('QR background tidak ditemukan di: ' . $bgPath);
            return $qrBinary;
        }

        try {
            $manager = new ImageManager(new Driver());

            $background = $manager->read($bgPath);
            $bgWidth = $background->width();
            $bgHeight = $background->height();

            // ======== SESUAIKAN 3 ANGKA INI SAMPAI QR PAS DI KOTAK PUTIH ========
    
        $qrSizeRatio = 0.37;       // pas dengan lebar kartu (358px dari 896px lebar background)
        $offsetXPercent = 0;       // kartu sudah center horizontal, tidak perlu digeser
        $offsetYPercent = 0;       // kartu sudah center vertikal, tidak perlu digeser
// ======================================================================
            // ======================================================================

            $qrFinalSize = (int) ($bgWidth * $qrSizeRatio);
            $offsetX = (int) ($bgWidth * $offsetXPercent);
            $offsetY = (int) ($bgHeight * $offsetYPercent);

            $qr = $manager->read($qrBinary)->resize($qrFinalSize, $qrFinalSize);

            $background->place($qr, 'center', $offsetX, $offsetY);

            return (string) $background->toPng();

        } catch (\Exception $e) {
            Log::error('Intervention Image error: ' . $e->getMessage());
            return $qrBinary;
        }
    }
}