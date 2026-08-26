<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect based on user role
        if (auth()->user()->isAdmin()) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        // For regular users, redirect to their most recent server detail or to create server if none
        $user = auth()->user();
        $latestServer = $user->servers()->latest()->first();

        if ($latestServer) {
            return redirect()->intended(route('detailserver', ['id' => $latestServer->id], absolute: false));
        }

        return redirect()->intended(route('server.create', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
