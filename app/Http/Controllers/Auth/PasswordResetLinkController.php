<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Reset password langsung tanpa email verifikasi.
     *
     * Catatan: ini dipakai sementara karena layanan pengiriman email
     * (mis. Firebase/SMTP) belum tersedia. User cukup memasukkan email
     * yang terdaftar + password baru, lalu password langsung diganti.
     * Kalau nanti email sudah bisa terkirim, alur ini bisa dikembalikan
     * ke Password::sendResetLink() (lihat NewPasswordController untuk
     * flow token-based yang masih tersedia).
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email tidak terdaftar di sistem.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        return redirect()->route('login')
            ->with('status', 'Password berhasil diubah. Silakan login dengan password baru.');
    }
}
