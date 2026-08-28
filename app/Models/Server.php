<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_kelengkapan',
        'user_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * True kalau data sudah ditandai "lengkap" oleh admin -> QR boleh aktif.
     */
    public function getIsLengkapAttribute(): bool
    {
        return $this->status_kelengkapan === 'lengkap';
    }

    /**
     * Field yang wajib terisi sebelum data boleh dianggap "lengkap".
     */
    public static function fieldWajibLengkap(): array
    {
        return [
            'serial_number', 'ip_server', 'nomor_rack',
            'ukuran_ram', 'ukuran_hdd', 'jumlah_core',
            'status', 'kondisi_tipe',
        ];
    }

    /**
     * Hitung status_kelengkapan otomatis berdasarkan field yang sudah terisi di $data.
     *
     * Nilai yang dihasilkan HANYA 'pending' (masih ada field wajib kosong,
     * menunggu admin melengkapi) atau 'lengkap' (semua field wajib sudah
     * terisi). Kedua nilai ini yang dipakai konsisten di seluruh aplikasi:
     * badge status di dashboard user, filter dropdown, dan query admin.
     */
    public static function hitungStatusKelengkapan(array $data): string
    {
        foreach (static::fieldWajibLengkap() as $field) {
            if (empty($data[$field] ?? null)) {
                return 'pending'; // masih ada yang kosong, menunggu admin
            }
        }
        return 'lengkap'; // semua field wajib sudah terisi
    }
}