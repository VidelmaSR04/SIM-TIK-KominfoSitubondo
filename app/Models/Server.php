<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_perangkat',
        'jenis_perangkat',
        'serial_number',
        'merk_perangkat',
        'type',
        'kondisi_tipe',
        'kondisi_status',
        'spesifikasi',
        'tipe_perangkat',
        'status_kepemilikan',
        'pemilik_perangkat',
        'ip_server',
        'ip_vps',
        'status',
        'ukuran_hdd',
        'ukuran_ram',
        'nomor_rack',
        'gambar_rack',
        'jumlah_core',
        'peruntukan',
        'nama_pengirim',
        'nama_penerima',
        'jam_pengisian'
    ];

    protected $casts = [
        'jam_pengisian' => 'datetime',
    ];
    public function aplikasis()
    {
        return $this->belongsToMany(Aplikasi::class, 'server_aplikasi')
            ->withPivot('ip_local', 'ip_public', 'url')
            ->withTimestamps();
    }
}
