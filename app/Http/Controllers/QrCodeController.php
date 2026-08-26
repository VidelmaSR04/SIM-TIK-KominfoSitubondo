<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Response;

class QrCodeController extends Controller
{
    /**
     * Tampilkan QR Code (dipakai oleh <img src="{{ route('qr.show', $id) }}"> di detailserver.blade.php)
     */
    public function show($id)
    {
        $server = Server::findOrFail($id);

        // Guard: QR cuma boleh tampil kalau data sudah ditandai lengkap oleh admin
        if (!$server->is_lengkap) {
            abort(403, 'QR Code belum tersedia — data server belum lengkap.');
        }

        $url = route('detailserver', $id);

        $qrCode = QrCode::format('png')
                        ->size(300)
                        ->generate($url);

        return Response::make($qrCode, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * Download QR Code sebagai file PNG
     */
    public function download($id)
    {
        $server = Server::findOrFail($id);

        if (!$server->is_lengkap) {
            abort(403, 'QR Code belum tersedia — data server belum lengkap.');
        }

        $url = route('detailserver', $id);

        $qrCode = QrCode::format('png')
                        ->size(400)
                        ->generate($url);

        return Response::make($qrCode, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="qr-server-' . $server->id . '.png"'
        ]);
    }
}
