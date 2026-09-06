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
    ];

    protected $fillable = [
        'kategori',
        'value',
        'label',
        'urutan',
        'is_aktif',
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
}