<?php
require __DIR__ . '/vendor/autoload.php';

use App\Services\PhotoCardService;
use App\Models\Server;

// Create a mock server object for testing
$server = new Server();
$server->nama_opd = 'Dinas Perhubungan';
$server->kode_perangkat = 'C0260916A';
$server->jam_pengisian = now();
$server->nomor_rack = 'R3';

$service = new PhotoCardService();
try {
    $pngData = $service->generateCard($server);
    file_put_contents('test_output.png', $pngData);
    echo "Test image generated successfully: test_output.png\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
