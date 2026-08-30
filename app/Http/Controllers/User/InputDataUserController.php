<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Server;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InputDataUserController extends Controller
{
    /**
     * Tampilkan form pendaftaran perangkat baru untuk user.
     */
    public function create(): View
    {
        return view('user.inputdatauser');
    }

    /**
     * Simpan perangkat baru yang didaftarkan user ke tabel `servers` yang sama
     * dengan yang dipakai admin, supaya data otomatis muncul di halaman
     * "Perangkat & Server" milik admin begitu disimpan.
     *
     * - user_id otomatis diisi dari user yang sedang login (bukan input manual).
     * - Field teknis lanjutan (serial number, IP, RAM, dsb) sengaja dikosongkan
     *   dulu di sini karena akan dilengkapi oleh admin lewat menu "Lengkapi Data".
     * - status_kelengkapan dihitung otomatis lewat Server::hitungStatusKelengkapan()
     *   (akan menghasilkan 'dilengkapi' karena field teknis wajib belum terisi).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'jenis'          => ['required', 'string', 'in:server,switch,router'],
            'merk'           => ['required', 'string', 'max:255'],
            'dinas'          => ['nullable', 'string', 'max:255'],
            'nama_pengirim'  => ['nullable', 'string', 'max:255'],
            'nama_penerima'  => ['nullable', 'string', 'max:255'],
            'rack'           => ['nullable', 'string', 'max:255'],
        ]);

        $data = [
            'nama_perangkat'    => $validated['merk'],
            'jenis_perangkat'   => $validated['jenis'],
            'merk_perangkat'    => $validated['merk'],
            'pemilik_perangkat' => $validated['dinas'] ?? null,
            'nama_pengirim'     => $validated['nama_pengirim'] ?? null,
            'nama_penerima'     => $validated['nama_penerima'] ?? null,
            'nomor_rack'        => $validated['rack'] ?? null,
            'user_id'           => Auth::id(),
        ];

        // Otomatis 'dilengkapi' (menunggu admin) karena field wajib teknis belum diisi.
        $data['status_kelengkapan'] = Server::hitungStatusKelengkapan($data);

        Server::create($data);

        return redirect()
            ->route('user.dashboarduser')
            ->with('success', 'Perangkat berhasil didaftarkan dan menunggu verifikasi admin.');
    }
}
