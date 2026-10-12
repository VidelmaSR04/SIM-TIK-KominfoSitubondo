<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocumentSetting extends Model
{
    protected $fillable = [
        'kop_html',
        'logo_path',
    ];

    public const BAWAAN = [
        'logo_path' => 'img/logo-situbondo.png',
    ];

    /** Folder logo unggahan di disk 'public' (storage/app/public/logo-surat). */
    public const LOGO_DIR = 'logo-surat';

    /** Kotak maksimum logo di kop (cm); logo diskalakan muat di dalamnya tanpa gepeng. */
    public const LOGO_KOTAK = ['lebar' => 1.64, 'tinggi' => 2.32];

    /**
     * Kop bawaan, disusun mengikuti Format_Surat_Dinas.docx:
     * baris 1 (12pt, tidak tebal, spasi ganda antarkata), baris 2 (16pt tebal),
     * alamat (10pt). Empat baris pertama menjorok 1,27 cm, baris terakhir tidak.
     */
    public static function kopHtmlBawaan(): string
    {
        $gaya = 'font-family: Arial, Helvetica, sans-serif; text-align: center;';

        return <<<HTML
<p style="{$gaya} margin: 0 0 0 1.27cm; font-size: 12pt; line-height: 1.15;">PEMERINTAH&nbsp; KABUPATEN&nbsp; SITUBONDO</p>
<p style="{$gaya} margin: 0 0 4.7pt 1.27cm; font-size: 16pt; line-height: 1.15;"><strong>DINAS KOMUNIKASI DAN INFORMATIKA</strong></p>
<p style="{$gaya} margin: 0 0 0 1.27cm; font-size: 10pt; line-height: 1.3;">Jalan PB. Sudirman No. 1, Kabupaten Situbondo, Jawa Timur 68312,</p>
<p style="{$gaya} margin: 0 0 0 1.27cm; font-size: 10pt; line-height: 1.3;">Telepon (0338) 674-096,</p>
<p style="{$gaya} margin: 0; font-size: 10pt; line-height: 1.3;">Laman kominfo.situbondokab.go.id, Pos-el kominfo@situbondokab.go.id</p>
HTML;
    }

    /** Pengaturan kop yang berlaku sekarang (tanpa menulis ke database). */
    public static function saatIni(): self
    {
        $setting = static::first() ?? new static();

        if (blank($setting->kop_html)) {
            $setting->kop_html = static::kopHtmlBawaan();
        }
        if (blank($setting->logo_path)) {
            $setting->logo_path = self::BAWAAN['logo_path'];
        }

        return $setting;
    }

    /** Path file logo di server: unggahan terbaru, atau logo bawaan di public/img. */
    public static function logoFile(): string
    {
        $path = static::first()?->logo_path;

        if (filled($path)) {
            $unggahan = storage_path('app/public/' . ltrim($path, '/'));
            if (is_file($unggahan)) {
                return $unggahan;
            }

            $publik = public_path($path);
            if (is_file($publik)) {
                return $publik;
            }
        }

        return public_path(self::BAWAAN['logo_path']);
    }

    /** True bila logo bawaan sudah diganti dengan file unggahan. */
    public static function logoKustom(): bool
    {
        $path = static::first()?->logo_path;

        return filled($path)
            && Str::startsWith($path, self::LOGO_DIR . '/')
            && is_file(storage_path('app/public/' . $path));
    }

    /** URL logo untuk ditampilkan di halaman edit. */
    public static function logoUrl(): string
    {
        $path = static::first()?->logo_path;

        if (static::logoKustom()) {
            return asset('storage/' . ltrim($path, '/'))
                . '?v=' . filemtime(storage_path('app/public/' . $path));
        }

        return asset(filled($path) && is_file(public_path($path)) ? $path : self::BAWAAN['logo_path']);
    }

    /**
     * Ukuran logo di kop: diskalakan muat di kotak LOGO_KOTAK dengan rasio asli.
     * $sumber = path file atau data URI (untuk preview sebelum disimpan).
     */
    public static function gayaLogo(string $sumber): string
    {
        $info = Str::startsWith($sumber, 'data:')
            ? @getimagesizefromstring((string) base64_decode(Str::after($sumber, 'base64,')))
            : @getimagesize($sumber);

        if (!$info || $info[0] < 1 || $info[1] < 1) {
            return '';   // pakai ukuran bawaan dari CSS
        }

        $skala = min(self::LOGO_KOTAK['lebar'] / $info[0], self::LOGO_KOTAK['tinggi'] / $info[1]);

        return sprintf('width: %.2Fcm; height: %.2Fcm;', $info[0] * $skala, $info[1] * $skala);
    }
}