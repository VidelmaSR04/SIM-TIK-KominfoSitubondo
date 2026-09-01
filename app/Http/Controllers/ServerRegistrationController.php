<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServerRegistrationController extends Controller
{
    /**
     * Tampilkan form register server
     */
    public function create()
    {
        // Cek jika user harus login dulu
        // if (!Auth::check()) {
        //     return redirect()->route('login')->with('message', 'Silakan login terlebih dahulu untuk mendaftarkan server');
        // }

        return view('register-server'); // view untuk form server
    }

    /**
     * Simpan data server
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'ssl_status' => 'nullable|string|max:255',
            'spesifikasi' => 'required|string',
            'dinas' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'penerima_server' => 'required|string|max:255',
            'lokasi_rak' => 'required|string|max:255',
        ]);

        // Tambahkan user_id jika ingin menghubungkan dengan user yang login
        // $validated['user_id'] = Auth::id();

        Server::create($validated);

        return redirect()->route('manajemen-server')->with('success', 'Server berhasil didaftarkan!');
    }
}
