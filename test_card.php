<?php

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\PhotoCardService;
use App\Models\Server;

// Grab a server that has gambar_rack
$server = Server::whereNotNull('gambar_rack')->first();
if (!$server) {
    echo "No server with gambar_rack found. Creating dummy data.\n";
    $server = new Server();
    $server->nama_opd = 'Dinas Perhubungan';
    $server->kode_perangkat = 'CO260916A';
    $server->jam_pisian = null;
    $server->tanggal_input = null;
    $server->created_at = now();
    $server->nomor_rack = 'R3';
    $server->gambar_rack = 'rack_images/default.jpg'; // placeholder
}

$service = new PhotoCardService();
try {
    $png = $service->generateCard($server);
    if ($png && strlen($png) > 100) {
        echo "SUCCESS: PNG generated, length: ".strlen($png)." bytes\n";
        // Save to file for inspection
        file_put_contents(__DIR__.'/test_output.png', $png);
        echo "Saved to test_output.png\n";
    } else {
        echo "FAILED: PNG empty or too small\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: ".$e->getMessage()."\n";
    echo $e->getTraceAsString();
}
