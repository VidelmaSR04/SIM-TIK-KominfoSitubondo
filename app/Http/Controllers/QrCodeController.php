<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Response;

class QrCodeController extends Controller
{
    public function download($id)
    {
        $server = Server::findOrFail($id);
        $url = route('detailserver', $id);
        
        // Generate QR Code sebagai PNG
        $qrCode = QrCode::format('png')
                        ->size(300)
                        ->generate($url);
        
        return Response::make($qrCode, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="qr-server-' . $server->id . '.png"'
        ]);
    }
}