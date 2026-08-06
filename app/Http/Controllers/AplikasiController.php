<?php

namespace App\Http\Controllers;

use App\Models\Aplikasi;
use Illuminate\Http\Request;

class AplikasiController extends Controller
{
    public function index()
    {
        $aplikasis = Aplikasi::latest()->paginate(10);
        return view('aplikasi', compact('aplikasis'));
    }

    public function create()
    {
        return view('aplikasi-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'ip_local' => 'nullable|ip',
            'ip_public' => 'nullable|ip',
            'pic' => 'nullable|string|max:255',
            'status' => 'nullable|in:Sudah Asesmen,Belum Asesmen',
        ]);

        Aplikasi::create($validated);

        return redirect()->route('aplikasi.index')->with('success', 'Aplikasi berhasil ditambahkan.');
    }

    public function show(Aplikasi $aplikasi)
    {
        return view('aplikasi-show', compact('aplikasi'));
    }

    public function edit(Aplikasi $aplikasi)
    {
        return view('aplikasi-edit', compact('aplikasi'));
    }

    public function update(Request $request, Aplikasi $aplikasi)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'ip_local' => 'nullable|ip',
            'ip_public' => 'nullable|ip',
            'pic' => 'nullable|string|max:255',
            'status' => 'nullable|in:Sudah Asesmen,Belum Asesmen',
        ]);

        $aplikasi->update($validated);

        return redirect()->route('aplikasi.index')->with('success', 'Aplikasi berhasil diperbarui.');
    }

    public function destroy(Aplikasi $aplikasi)
    {
        $aplikasi->delete();
        return redirect()->route('aplikasi.index')->with('success', 'Aplikasi berhasil dihapus.');
    }
}