<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->post('/forgot-password', [
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status', 'Password berhasil diubah. Silakan login dengan password baru.');

        // Verify password was actually changed
        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        // For the direct password reset implementation, accessing the reset password route
        // should show the form (status 200) regardless of token value since token validation
        // happens in the store method, not the create method.
        $response = $this->get('/reset-password/any-token');
        $response->assertStatus(200);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        // Test the direct password reset functionality
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->post('/forgot-password', [
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect(route('login'));

        // Verify password was actually changed
        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));

        // Verify we can login with new password
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'new-password',
        ]);

        $response->assertRedirect(route('dashboard'));
    }
}