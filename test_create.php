<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);
$kernel->terminate($request, $response);
use App\Models\Server;
$s = new Server();
$s->nama_perangkat = 'test';
$s->jenis_perangkat = 'router';
$s->merk_perangkat = 'cisco';
$s->status_kepemilikan = 'Kominfo';
$s->save();
echo $s->id;