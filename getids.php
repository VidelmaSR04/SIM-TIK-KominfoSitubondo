<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$ids = \App\Models\Server::whereNotNull('gambar_rack')->pluck('id')->toArray();
print_r($ids);
