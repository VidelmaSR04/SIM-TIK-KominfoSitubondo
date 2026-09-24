<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$user = \App\Models\User::where('email', 'admin@gmail.com')->first();
$user->password = bcrypt('secret');
$user->save();
echo "Password updated\n";
