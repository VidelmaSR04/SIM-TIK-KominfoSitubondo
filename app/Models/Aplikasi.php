<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aplikasi extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'ip_local', 'ip_public', 'pic', 'status'];

    public function servers()
    {
        return $this->belongsToMany(Server::class, 'server_aplikasi')
                    ->withPivot('ip_local', 'ip_public', 'url')
                    ->withTimestamps();
    }
}