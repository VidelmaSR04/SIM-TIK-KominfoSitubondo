<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Server;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardUserController extends Controller
{
    /**
     * Tampilkan dashboard perangkat milik user yang sedang login.
     * Hanya menampilkan perangkat yang user_id-nya cocok dengan user login,
     * sehingga tiap user cuma melihat perangkat miliknya sendiri.
     */
    public function index(): View
    {
        $devices = Server::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.dashboarduser', compact('devices'));
    }
}