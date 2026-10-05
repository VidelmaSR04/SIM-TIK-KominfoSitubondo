<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class MasterData extends Model
{
    use HasFactory;

    /**
     * Define all categories and their human-readable labels.
     * Used for validation and seeding.
     */
    public const KATEGORI = [
        'jenis_perangkat' => 'Jenis Perangkat',
        'merk_perangkat' => 'Merk Perangkat',
        'tipe_perangkat' => 'Tipe Perangkat',
        'status_kepemilikan' => 'Status Kepemilikan',
        'pemilik_perangkat' => 'Pemilik Perangkat (OPD)',
        'status_perangkat' => 'Status Perangkat',
        'kondisi_tipe' => 'Kondisi Server - Tipe',
        'kondisi_status' => 'Kondisi Server - Status',
        'nomor_rack' => 'Nomor Rack',
        'pejabat' => 'Pejabat (Kepala Dinas & Kabid TIK)',
    ];

    // Constants for pejabat category
    public const KATEGORI_PEJABAT = 'pejabat';
    public const JABATAN_KEPALA_DINAS = 'Kepala Dinas Komunikasi dan Informatika Kabupaten Situbondo';
    public const JABATAN_KABID_TIK = 'Kepala Bidang Teknologi Informasi dan Komunikasi';

    // Pangkat / Golongan options for PNS
    public const PANGKAT_PNS = [
        'I/a Juru Muda',
        'I/b Juru Muda Tingkat I',
        'I/c Juru',
        'I/d Juru Tingkat I',
        'II/a Pengatur Muda',
        'II/b Pengatur Muda Tingkat I',
        'II/c Pengatur',
        'II/d Pengatur Tingkat I',
        'III/a Penata Muda',
        'III/b Penata Muda Tingkat I',
        'III/c Penata',
        'III/d Penata Tingkat I',
        'IV/a Pembina',
        'IV/b Pembina Tingkat I',
        'IV/c Pembina Utama Muda',
        'IV/d Pembina Utama Madya',
        'IV/e Pembina Utama',
    ];

    protected $fillable = [
        'kategori',
        'value',
        'label',
        'urutan',
        'is_aktif',
        'nip',
        'pangkat',
        'jabatan_ttd',
        'qrcode_path',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Scope a query to only include active items.
     */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    /**
     * Scope a query to match a specific category.
     */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Get select array for a given category (value => label).
     * Uses caching for 10 minutes, cleared automatically on save/delete.
     *
     * @param string $kategori
     * @return array
     */
    public static function forSelect(string $kategori): array
    {
        // Validate category
        if (!array_key_exists($kategori, static::KATEGORI)) {
            return [];
        }

        $cacheKey = "master_data.select.{$kategori}";

        // Try to get from cache
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Fetch from database
        $results = static::where('kategori', $kategori)
            ->aktif()
            ->orderBy('urutan')
            ->orderBy('label')
            ->get(['value', 'label'])
            ->keyBy('value')
            ->map(function ($item) {
                // If label is empty, use value as label
                return $item->label ?: $item->value;
            })
            ->toArray();

        // Store in cache for 10 minutes
        Cache::put($cacheKey, $results, 600);

        return $results;
    }

    /**
     * Clear cache for a category when data is saved or deleted.
     */
    protected static function booted()
    {
        static::saved(function ($model) {
            $cacheKey = "master_data.select.{$model->kategori}";
            Cache::forget($cacheKey);
        });

        static::deleted(function ($model) {
            $cacheKey = "master_data.select.{$model->kategori}";
            Cache::forget($cacheKey);
        });
    }

    /**
     * Get pejabat by jabatan (value) for category pejabat.
     * Returns the active pejabat record.
     *
     * @param string $jabatan
     * @return static|null
     */
    public static function pejabat(string $jabatan): ?self
    {
        return static::where('kategori', static::KATEGORI_PEJABAT)
            ->where('value', $jabatan)
            ->aktif()
            ->first();
    }

    /**
     * Jabatan untuk blok tanda tangan di PDF.
     * Memakai kolom jabatan_ttd bila diisi (satu baris = satu baris di PDF);
     * kalau kosong, memakai Jabatan (value) dalam huruf besar.
     */
    public function jabatanTtd(): string
    {
        $khusus = trim((string) $this->jabatan_ttd);

        return $khusus !== '' ? $khusus : mb_strtoupper((string) $this->value);
    }

    /**
     * Pangkat untuk tampilan PDF. Contoh: "IV/c Pembina Utama Muda" -> "Pembina Utama Muda".
     * Isi $denganGolongan = true bila golongan ingin ikut dicetak.
     */
    public static function pangkatTampil(?string $pangkat, bool $denganGolongan = false): string
    {
        $pangkat = trim((string) $pangkat);

        if ($denganGolongan || $pangkat === '') {
            return $pangkat;
        }

        return trim(preg_replace('/^[IVX]+\/[a-e]\s+/i', '', $pangkat));
    }

    /** URL publik gambar QR Code (butuh `php artisan storage:link`). */
    public function qrcodeUrl(): ?string
    {
        return $this->qrcode_path ? asset('storage/' . $this->qrcode_path) : null;
    }
}