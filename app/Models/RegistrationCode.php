<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegistrationCode extends Model
{
    protected $fillable = [
        'code',
        'label',
        'max_uses',
        'used_count',
        'expires_at',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'is_active'  => 'boolean',
            'max_uses'   => 'integer',
            'used_count' => 'integer',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'registration_code_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isExhausted(): bool
    {
        return $this->max_uses !== null && $this->used_count >= $this->max_uses;
    }

    public function isUsable(): bool
    {
        return $this->is_active && ! $this->isExpired() && ! $this->isExhausted();
    }

    public function unusableReason(): ?string
    {
        return match (true) {
            ! $this->is_active   => 'Nonaktif',
            $this->isExpired()   => 'Kedaluwarsa',
            $this->isExhausted() => 'Kuota habis',
            default              => null,
        };
    }

    public static function normalize(?string $code): string
    {
        return strtoupper(trim((string) $code));
    }

    /** Kode acak unik tanpa karakter membingungkan. Contoh: K7QX-M4TD */
    public static function generateUniqueCode(): string
    {
        $alphabet = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

        do {
            $raw = '';
            for ($i = 0; $i < 8; $i++) {
                $raw .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }
            $code = substr($raw, 0, 4).'-'.substr($raw, 4, 4);
        } while (static::where('code', $code)->exists());

        return $code;
    }
}
