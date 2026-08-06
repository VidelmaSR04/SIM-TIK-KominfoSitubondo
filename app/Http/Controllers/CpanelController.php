<?php

namespace App\Http\Controllers;

use App\Models\Cpanel;
use Illuminate\Http\Request;

class CpanelController extends Controller
{
    public function index()
    {
        $cpanels = Cpanel::latest()->paginate(10);
        return view('cpanel', compact('cpanels'));
    }

    public function create()
    {
        return view('cpanel-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'ip_local' => 'required|ip',
            'ip_public' => 'required|ip',
            'status' => 'nullable|in:Active,Inactive',
        ]);

        Cpanel::create($validated);

        return redirect()->route('cpanel.index')->with('success', 'cPanel berhasil ditambahkan.');
    }

    public function show(Cpanel $cpanel)
    {
        return view('cpanel-show', compact('cpanel'));
    }

    public function edit(Cpanel $cpanel)
    {
        return view('cpanel-edit', compact('cpanel'));
    }

    public function update(Request $request, Cpanel $cpanel)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'ip_local' => 'required|ip',
            'ip_public' => 'required|ip',
            'status' => 'nullable|in:Active,Inactive',
        ]);

        $cpanel->update($validated);

        return redirect()->route('cpanel.index')->with('success', 'cPanel berhasil diperbarui.');
    }

    public function destroy(Cpanel $cpanel)
    {
        $cpanel->delete();
        return redirect()->route('cpanel.index')->with('success', 'cPanel berhasil dihapus.');
    }
}