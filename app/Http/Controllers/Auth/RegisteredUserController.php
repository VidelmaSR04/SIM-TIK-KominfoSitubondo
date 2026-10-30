<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RegistrationCode;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Akun baru berstatus PENDING dan tidak langsung login.
     * Role selalu 'user'; kode registrasi tidak pernah memberi role admin.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'registration_code' => ['required', 'string', 'max:32'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'registration_code.required' => 'Kode registrasi wajib diisi.',
        ]);

        $input = RegistrationCode::normalize($validated['registration_code']);

        $user = DB::transaction(function () use ($input, $validated) {
            // lockForUpdate: kuota tidak terlampaui saat banyak yang daftar bersamaan
            $code = RegistrationCode::where('code', $input)->lockForUpdate()->first();

            if (! $code || ! $code->isUsable()) {
                throw ValidationException::withMessages([
                    'registration_code' => 'Kode registrasi tidak valid, kedaluwarsa, atau kuotanya sudah habis.',
                ]);
            }

            $user = new User([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => 'user',
                'password' => Hash::make($validated['password']),
            ]);
            $user->forceFill([
                'status' => User::STATUS_PENDING,
                'registration_code_id' => $code->id,
            ])->save();

            $code->increment('used_count');

            return $user;
        });

        event(new Registered($user));

        return redirect()->route('login')->with(
            'status',
            'Pendaftaran berhasil. Akun Anda menunggu persetujuan admin sebelum bisa digunakan.'
        );
    }
}
