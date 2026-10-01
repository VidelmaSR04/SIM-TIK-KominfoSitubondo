<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentSetting extends Model
{
    protected $fillable = [
        'kop_html',
        'logo_path',
    ];

    public const BAWAAN = [
        'logo_path' => 'img/logo-situbondo.png',
    ];

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
}