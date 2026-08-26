<?php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\ErrorCorrectionLevel;

class QrCodeWithTemplateService
{
    protected $backgroundPath;
    protected $qrSize;
    protected $qrMargin;

    public function __construct()
    {
        $this->backgroundPath = public_path('img/qr-background.png');
        $this->qrSize = 400; // QR code size in pixels
        $this->qrMargin = 10;
    }

    /**
     * Generate QR code merged with background template
     * Returns GD image resource
     */
    public function generateWithTemplate(string $url): \GdImage
    {
        // Load background template
        $background = imagecreatefrompng($this->backgroundPath);
        if (!$background) {
            throw new \Exception('Gagal memuat background template');
        }

        $bgWidth = imagesx($background);
        $bgHeight = imagesy($background);

        // Generate QR code using endroid/qr-code with GD output
        $qrImage = $this->generateQrCodeGd($url);

        // Get actual QR image size
        $qrWidth = imagesx($qrImage);
        $qrHeight = imagesy($qrImage);

        // Calculate position to center QR on background
        $qrX = (int)(($bgWidth - $qrWidth) / 2);
        $qrY = (int)(($bgHeight - $qrHeight) / 2) + 100; // Slightly lower for design

        // Ensure QR has white background before merging
        $this->ensureWhiteBackground($qrImage);

        // Merge QR onto background with alpha blending
        imagecopy($background, $qrImage, $qrX, $qrY, 0, 0, $qrWidth, $qrHeight);

        // Clean up QR image resource
        imagedestroy($qrImage);

        return $background;
    }

    /**
     * Generate QR code as GD image using endroid/qr-code
     */
    protected function generateQrCodeGd(string $data): \GdImage
    {
        // endroid/qr-code: setSize includes the margin, so total size = size + 2*margin
        // We want final QR to be $this->qrSize, so we set size = qrSize - 2*margin
        $qrCodeSize = $this->qrSize - (2 * $this->qrMargin);

        $qrCode = new QrCode($data);
        $qrCode->setSize($qrCodeSize);
        $qrCode->setMargin($this->qrMargin);
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::High);

        // Set colors
        $qrCode->setForegroundColor(new Color(0, 0, 0));    // Black modules
        $qrCode->setBackgroundColor(new Color(255, 255, 255)); // White background

        // Use PngWriter which outputs GD image
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Get the GD image from the result
        $gdImage = $result->getImage();

        if (!$gdImage) {
            throw new \Exception('Gagal generate QR code GD image');
        }

        // The image should now be exactly qrSize x qrSize (including margins)
        $currentWidth = imagesx($gdImage);
        $currentHeight = imagesy($gdImage);

        // Verify and log
        // error_log("QR generated: {$currentWidth}x{$currentHeight}, target: {$this->qrSize}x{$this->qrSize}");

        return $gdImage;
    }

    /**
     * Ensure QR image has solid white background (not transparent)
     */
    protected function ensureWhiteBackground(\GdImage &$image): void
    {
        $width = imagesx($image);
        $height = imagesy($image);

        // Check if image has alpha channel
        if (imageistruecolor($image)) {
            // Create white background
            $whiteBg = imagecreatetruecolor($width, $height);
            $white = imagecolorallocate($whiteBg, 255, 255, 255);
            imagefilledrectangle($whiteBg, 0, 0, $width, $height, $white);

            // Copy with alpha blending
            imagealphablending($whiteBg, true);
            imagesavealpha($whiteBg, false);
            imagecopy($whiteBg, $image, 0, 0, 0, 0, $width, $height);

            imagedestroy($image);
            $image = $whiteBg;
        }
    }

    /**
     * Output image as PNG string
     */
    public function outputPng(\GdImage $image): string
    {
        ob_start();
        imagepng($image);
        $pngData = ob_get_clean();
        return $pngData;
    }

    /**
     * Generate QR without template (for display on detail page)
     * Returns plain QR code as PNG GD image
     */
    public function generatePlainQr(string $url, int $size = 300): \GdImage
    {
        $qrCode = new QrCode($url);
        $qrCode->setSize($size);
        $qrCode->setMargin(10);
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::High);
        $qrCode->setForegroundColor(new Color(0, 0, 0));
        $qrCode->setBackgroundColor(new Color(255, 255, 255));

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return $result->getImage();
    }
}