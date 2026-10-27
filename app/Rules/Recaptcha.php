<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Validasi Google reCAPTCHA v2 Checkbox.
 *
 * Rule ini memverifikasi token g-recaptcha-response ke endpoint
 * resmi Google dan menolak token yang tidak valid, kosong, atau
 * sudah kedaluwarsa.
 *
 * Perilaku ketika RECAPTCHA_ENABLED=false:
 * Rule ini TIDAK boleh ditambahkan ke validation rules sama sekali
 * (dikontrol di LoginRequest). Jika tetap terpanggil saat disabled,
 * rule akan selalu gagal sebagai pengaman.
 */
class Recaptcha implements ValidationRule
{
    /**
     * Jalankan validasi.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Token kosong atau bukan string
        if (empty($value) || !is_string($value)) {
            $fail('Silakan selesaikan verifikasi CAPTCHA.');
            return;
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->connectTimeout(5)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => config('services.recaptcha.secret_key'),
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);

            if (!$response->successful()) {
                // HTTP error dari Google — jangan lewatkan CAPTCHA
                Log::warning('reCAPTCHA verification: HTTP error dari Google.', [
                    'status' => $response->status(),
                    // Jangan log secret key atau token
                ]);
                $fail('Verifikasi CAPTCHA gagal. Silakan coba lagi.');
                return;
            }

            $body = $response->json();

            if (!is_array($body) || empty($body['success'])) {
                // Token tidak valid, kedaluwarsa, atau sudah digunakan
                $errorCodes = $body['error-codes'] ?? [];
                Log::info('reCAPTCHA verification gagal.', [
                    'error-codes' => $errorCodes,
                    // Jangan log token atau secret key
                ]);
                $fail('Verifikasi CAPTCHA gagal. Silakan coba lagi.');
                return;
            }

            // success === true → token valid
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Timeout atau koneksi ke Google gagal
            Log::warning('reCAPTCHA verification: koneksi ke Google gagal.', [
                'message' => $e->getMessage(),
            ]);
            $fail('Verifikasi CAPTCHA gagal karena masalah koneksi. Silakan coba lagi.');
        } catch (\Throwable $e) {
            // Exception tak terduga
            Log::error('reCAPTCHA verification: error tak terduga.', [
                'message' => $e->getMessage(),
            ]);
            $fail('Verifikasi CAPTCHA gagal. Silakan coba lagi.');
        }
    }
}

