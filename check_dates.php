<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$servers = App\Models\Server::select('id', 'kode_perangkat', 'created_at', 'updated_at', 'tanggal_input')->get();
foreach($servers as $server) {
    echo 'ID: '.$server->id.' | Kode: '.$server->kode_perangkat.' | Created: '.$server->created_at.' | Updated: '.$server->updated_at.' | Tanggal Input: '.$server->tanggal_input.PHP_EOL;
}
