<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PhotoCardService
{
    protected $width = 600;
    protected $height = 900;
    protected $gradientStart = '#f5f8fc';
    protected $gradientEnd = '#dbe6f0';
    protected $photoMaxWidth = 540;
    protected $photoMaxHeight = 650;
    protected $padding = 30;

    /**
     * Create a new ImageManager instance with GD driver.
     */
    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Get GD native resource from an Intervention Image.
     */
    protected function gdResource(\Intervention\Image\Image $image)
    {
        return $image->core()->native();
    }

    /**
     * Generate a PNG string representing the photo card.
     *
     * @param \App\Models\Server $server
     * @return string PNG image data
     */
    public function generateCard(\App\Models\Server $server): string
    {
        // Create base image (blank) then apply gradient background
        $base = $this->manager->create($this->width, $this->height);
        $this->applyGradient($base, $this->gradientStart, $this->gradientEnd, 160);

        // Load and prepare photo
        $photoPath = Storage::disk('public')->path($server->gambar_rack);
        if (!file_exists($photoPath)) {
            // fallback: create a placeholder grey image
            $photo = $this->manager->create(200, 200);
            $gd = $this->gdResource($photo);
            imagefilledrectangle($gd, 0, 0, 199, 199, imagecolorallocate($gd, 0xdd, 0xdd, 0xdd));
        } else {
            $photo = $this->manager->read($photoPath);
            // Resize to fit within max dimensions while maintaining aspect ratio (no upsize)
            $origW = $photo->width();
            $origH = $photo->height();
            if ($origW > $this->photoMaxWidth || $origH > $this->photoMaxHeight) {
                $ratio = min($this->photoMaxWidth / $origW, $this->photoMaxHeight / $origH);
                $newW = (int)($origW * $ratio);
                $newH = (int)($origH * $ratio);
                $photo->resize($newW, $newH);
            }
            // Note: orientate() not available in v3; assume image already oriented or EXIF handled elsewhere.
        }

        // Calculate positions
        $headerHeight = 100; // approximate space for header text
        $footerHeight = 50; // approximate space for footer text
        $photoTop = $this->padding + $headerHeight + 20; // padding top + header + gap
        $photoLeft = ($this->width - $photo->width()) / 2;

        // Get GD resources for base and photo
        $gdBase = $this->gdResource($base);
        $gdPhoto = $this->gdResource($photo);
        // Copy photo onto base
        imagecopy($gdBase, $gdPhoto, $photoLeft, $photoTop, 0, 0, $photo->width(), $photo->height());

        // Prepare text properties
        $kepemilikan = $server->nama_opd ?? 'Kominfo';
        $kode = $server->kode_perangkat ?? $server->id;
        $tanggal = $server->jam_pengisian ? $server->jam_pengisian->format('d M Y') :
                   ($server->tanggal_input ? $server->tanggal_input->format('d M Y') : $server->created_at->format('d M Y'));
        $rack = $server->nomor_rack ?? '-';

        // Get GD core for text rendering
        $gd = $this->gdResource($base);
        // Define font paths
        $fontRegular = public_path('fonts/Inter/Inter-Regular.ttf');
        $fontMedium = public_path('fonts/Inter/Inter-Medium.ttf');
        $fontSemiBold = public_path('fonts/Inter/Inter-SemiBold.ttf');
        $fontBold = public_path('fonts/Inter/Inter-Bold.ttf');
        $fallbackFont = resource_path('fonts/fallback.ttf');

        // Select font files with fallback
        $fontBoldToUse = file_exists($fontBold) ? $fontBold : (file_exists($fontRegular) ? $fontRegular : $fallbackFont);
        $fontSemiBoldToUse = file_exists($fontSemiBold) ? $fontSemiBold : (file_exists($fontRegular) ? $fontRegular : $fallbackFont);
        $fontMediumToUse = file_exists($fontMedium) ? $fontMedium : (file_exists($fontRegular) ? $fontRegular : $fallbackFont);
        $fontRegularToUse = file_exists($fontRegular) ? $fontRegular : $fallbackFont;

        // Validate that we have a usable font file
        if (!file_exists($fontRegularToUse)) {
            throw new \RuntimeException('Tidak ada font valid ditemukan untuk generate kartu foto. Pastikan folder public/fonts/Inter/ berisi file .ttf atau resources/fonts/fallback.ttf tersedia.');
        }

        // Log warnings if fallback used
        if ($fontBoldToUse !== $fontBold) {
            \Log::warning('Using fallback font for Bold: '.$fontBoldToUse);
        }
        if ($fontSemiBoldToUse !== $fontSemiBold) {
            \Log::warning('Using fallback font for SemiBold: '.$fontSemiBoldToUse);
        }
        if ($fontMediumToUse !== $fontMedium) {
            \Log::warning('Using fallback font for Medium: '.$fontMediumToUse);
        }
        if ($fontRegularToUse !== $fontRegular) {
            \Log::warning('Using fallback font for Regular: '.$fontRegularToUse);
        }

        $fontBold = $fontBoldToUse;
        $fontSemiBold = $fontSemiBoldToUse;
        $fontMedium = $fontMediumToUse;
        $fontRegular = $fontRegularToUse;

        // We'll draw two lines: first nama_opd, then kode_perangkat below it
        $headerCenterX = $this->width / 2;
        $headerY = $this->padding + 40; // baseline for first line

        // Nama OPD/kepemilikan (judul besar, bold) - ~36px
        $fontSizeNama = 36;
        $boxNama = imagettfbbox($fontSizeNama, 0, $fontBold, $kepemilikan);
        $textWidthNama = $boxNama[2] - $boxNama[0];
        $xNama = $headerCenterX - ($textWidthNama / 2);
        imagettftext($gd, $fontSizeNama, 0, $xNama, $headerY, 0x000000, $fontBold, $kepemilikan);

        // Kode Perangkat (medium weight) - ~22px
        $fontSizeKode = 22;
        $kodeY = $headerY + 40; // space between lines
        $boxKode = imagettfbbox($fontSizeKode, 0, $fontMedium, $kode);
        $textWidthKode = $boxKode[2] - $boxKode[0];
        $xKode = $headerCenterX - ($textWidthKode / 2);
        imagettftext($gd, $fontSizeKode, 0, $xKode, $kodeY, 0x000000, $fontMedium, $kode);

        // Footer: Tanggal (left) and Nomor Rack (right)
        $footerY = $this->height - $this->padding - 20;
        $leftX = $this->padding;
        $rightX = $this->width - $this->padding;
        $fontSizeFooter = 18;

        // Tanggal (left)
        $boxTanggal = imagettfbbox($fontSizeFooter, 0, $fontRegular, $tanggal);
        $textWidthTanggal = $boxTanggal[2] - $boxTanggal[0];
        $xTanggal = $leftX;
        imagettftext($gd, $fontSizeFooter, 0, $xTanggal, $footerY, 0x000000, $fontRegular, $tanggal);

        // Nomor Rack (right)
        $boxRack = imagettfbbox($fontSizeFooter, 0, $fontRegular, $rack);
        $textWidthRack = $boxRack[2] - $boxRack[0];
        $xRack = $rightX - $textWidthRack;
        imagettftext($gd, $fontSizeFooter, 0, $xRack, $footerY, 0x000000, $fontRegular, $rack);

        // Return PNG binary string
        return (string) $base->encodeByExtension('png');
    }

    /**
     * Apply a linear gradient to an Intervention Image canvas.
     * Angle in degrees.
     */
    protected function applyGradient(\Intervention\Image\Image $image, $startColor, $endColor, $angle)
    {
        $width = $image->width();
        $height = $image->height();

        // Convert hex to rgb
        $start = $this->hex2rgb($startColor);
        $end = $this->hex2rgb($endColor);

        // We'll draw lines from top to bottom approximating the angle by shifting horizontally.
        // For simplicity, we do vertical gradient (0 deg). For 160 deg, we could adjust.
        // We'll implement vertical gradient only.
        $gd = $this->gdResource($image);
        for ($y = 0; $y < $height; $y++) {
            $ratio = $y / ($height - 1);
            $r = (int)($start['r'] + $ratio * ($end['r'] - $start['r']));
            $g = (int)($start['g'] + $ratio * ($end['g'] - $start['g']));
            $b = (int)($start['b'] + $ratio * ($end['b'] - $start['b']));
            $color = imagecolorallocate($gd, $r, $g, $b);
            imagefilledrectangle($gd, 0, $y, $width, $y+1, $color);
        }
    }

    protected function hex2rgb($hex)
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = str_repeat(substr($hex,0,1),2) . str_repeat(substr($hex,1,1),2) . str_repeat(substr($hex,2,1),2);
        }
        $rgb = [];
        $rgb['r'] = hexdec(substr($hex,0,2));
        $rgb['g'] = hexdec(substr($hex,2,2));
        $rgb['b'] = hexdec(substr($hex,4,2));
        return $rgb;
    }
}
