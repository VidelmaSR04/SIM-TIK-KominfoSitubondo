<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Determine role based on the URL
        // If the path starts with 'admin/register', it's admin registration
        $isAdminRegistration = $request->path() === 'admin/register';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $isAdminRegistration ? 'admin' : 'user',
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on role
        if ($isAdminRegistration) {
            return redirect()->route('admin.dashboard')->with('success', 'Admin registered successfully.');
        }

        // For regular users, redirect to input data page after registration
        return redirect()->route('inputdatauser.create')->with('success', 'Registered successfully. Please input your server data.');
    }
}