<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RecaptchaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Login berhasil tanpa CAPTCHA ketika RECAPTCHA_ENABLED=false.
     */
    public function test_login_works_without_captcha_when_disabled(): void
    {
        Config::set('services.recaptcha.enabled', false);

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
    }

    /**
     * Login ditolak tanpa token CAPTCHA ketika RECAPTCHA_ENABLED=true.
     */
    public function test_login_rejected_without_captcha_token_when_enabled(): void
    {
        Config::set('services.recaptcha.enabled', true);
        Config::set('services.recaptcha.secret_key', 'test-secret');

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('g-recaptcha-response');
        $this->assertGuest();
    }

    /**
     * Login ditolak dengan token CAPTCHA kosong ketika enabled.
     */
    public function test_login_rejected_with_empty_captcha_token(): void
    {
        Config::set('services.recaptcha.enabled', true);
        Config::set('services.recaptcha.secret_key', 'test-secret');

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'g-recaptcha-response' => '',
        ]);

        $response->assertSessionHasErrors('g-recaptcha-response');
        $this->assertGuest();
    }

    /**
     * Login ditolak ketika Google mengembalikan success=false.
     */
    public function test_login_rejected_when_captcha_verification_fails(): void
    {
        Config::set('services.recaptcha.enabled', true);
        Config::set('services.recaptcha.secret_key', 'test-secret');

        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => false,
                'error-codes' => ['invalid-input-response'],
            ], 200),
        ]);

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'g-recaptcha-response' => 'invalid-token',
        ]);

        $response->assertSessionHasErrors('g-recaptcha-response');
        $this->assertGuest();
    }

    /**
     * Login berhasil ketika Google mengembalikan success=true.
     */
    public function test_login_succeeds_with_valid_captcha(): void
    {
        Config::set('services.recaptcha.enabled', true);
        Config::set('services.recaptcha.secret_key', 'test-secret');

        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'challenge_ts' => now()->toIso8601String(),
                'hostname' => 'localhost',
            ], 200),
        ]);

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'g-recaptcha-response' => 'valid-token',
        ]);

        $this->assertAuthenticated();
    }

    /**
     * Login gagal ketika koneksi ke Google timeout (CAPTCHA enabled).
     */
    public function test_login_rejected_when_google_connection_fails(): void
    {
        Config::set('services.recaptcha.enabled', true);
        Config::set('services.recaptcha.secret_key', 'test-secret');

        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response(null, 500),
        ]);

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'g-recaptcha-response' => 'some-token',
        ]);

        $response->assertSessionHasErrors('g-recaptcha-response');
        $this->assertGuest();
    }

    /**
     * Password salah tetap ditolak meskipun CAPTCHA valid.
     */
    public function test_wrong_password_rejected_even_with_valid_captcha(): void
    {
        Config::set('services.recaptcha.enabled', true);
        Config::set('services.recaptcha.secret_key', 'test-secret');

        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'challenge_ts' => now()->toIso8601String(),
                'hostname' => 'localhost',
            ], 200),
        ]);

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
            'g-recaptcha-response' => 'valid-token',
        ]);

        $this->assertGuest();
    }

    /**
     * Halaman login menampilkan widget reCAPTCHA ketika diaktifkan.
     */
    public function test_login_page_shows_captcha_widget_when_enabled(): void
    {
        $this->withoutVite();

        Config::set('services.recaptcha.enabled', true);
        Config::set('services.recaptcha.site_key', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI');

        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('g-recaptcha');
        $response->assertSee('6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI');
        $response->assertSee('recaptcha/api.js');
    }

    /**
     * Halaman login TIDAK menampilkan widget reCAPTCHA ketika dinonaktifkan.
     */
    public function test_login_page_hides_captcha_widget_when_disabled(): void
    {
        $this->withoutVite();

        Config::set('services.recaptcha.enabled', false);

        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertDontSee('g-recaptcha');
        $response->assertDontSee('recaptcha/api.js');
    }

    /**
     * Secret key tidak terekspos di halaman login.
     */
    public function test_secret_key_not_exposed_in_login_page(): void
    {
        $this->withoutVite();

        Config::set('services.recaptcha.enabled', true);
        Config::set('services.recaptcha.site_key', 'test-site-key');
        Config::set('services.recaptcha.secret_key', 'super-secret-key-12345');

        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertDontSee('super-secret-key-12345');
    }

    /**
     * Role redirect tetap berfungsi dengan CAPTCHA valid.
     */
    public function test_admin_redirects_correctly_with_valid_captcha(): void
    {
        Config::set('services.recaptcha.enabled', true);
        Config::set('services.recaptcha.secret_key', 'test-secret');

        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
            ], 200),
        ]);

        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
            'g-recaptcha-response' => 'valid-token',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.manajemen-server', absolute: false));
    }
}
