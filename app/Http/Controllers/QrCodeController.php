<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Services\QrCodeWithTemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    protected $qrTemplateService;

    public function __construct(QrCodeWithTemplateService $qrTemplateService)
    {
        $this->qrTemplateService = $qrTemplateService;
    }

    /**
     * Tampilkan QR Code (dipakai oleh <img src="{{ route('qr.show', $id) }}"> di detailserver.blade.php)
     * Versi ini TANPA template/background - hanya QR code polos
     */
    public function show($id)
    {
        $server = Server::findOrFail($id);

        // Guard: QR cuma boleh tampil kalau data sudah ditandai lengkap oleh admin
        if (!$server->is_lengkap) {
            abort(403, 'QR Code belum tersedia — data server belum lengkap.');
        }

        $url = route('detailserver', $id);

        // Generate plain QR code menggunakan endroid/qr-code (GD-based)
        // Buat QR code sederhana tanpa template
        $qrCode = new \Endroid\QrCode\QrCode($url);
        $qrCode->setSize(300);
        $qrCode->setMargin(10);
        $qrCode->setErrorCorrectionLevel(\Endroid\QrCode\ErrorCorrectionLevel::High);
        $qrCode->setForegroundColor(new \Endroid\QrCode\Color\Color(0, 0, 0));
        $qrCode->setBackgroundColor(new \Endroid\QrCode\Color\Color(255, 255, 255));

        $writer = new \Endroid\QrCode\Writer\PngWriter();
        $result = $writer->write($qrCode);

        $pngData = $result->getString();

        return Response::make($pngData, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * Download QR Code dengan background template
     * Menggunakan template di public/img/qr-background.png
     */
    public function download($id)
    {
        $server = Server::findOrFail($id);

        if (!$server->is_lengkap) {
            abort(403, 'QR Code belum tersedia — data server belum lengkap.');
        }

        $url = route('detailserver', $id);

        // Generate QR dengan template background
        $qrWithTemplate = $this->qrTemplateService->generateWithTemplate($url);

        // Output as PNG string
        $pngData = $this->qrTemplateService->outputPng($qrWithTemplate);

        // Clean up GD resource
        imagedestroy($qrWithTemplate);

        return Response::make($pngData, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="qr-server-' . $server->id . '.png"'
        ]);
    }
}