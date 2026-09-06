<?php

namespace App\Http\Controllers;

use App\Models\MasterData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class MasterDataController extends Controller
{
    /**
     * Display a listing of the resource, optionally filtered by category.
     */
    public function index(Request $request)
    {
        $kategori = $request->query('kategori', key(MasterData::KATEGORI));
        // Validate category
        if (!array_key_exists($kategori, MasterData::KATEGORI)) {
            $kategori = key(MasterData::KATEGORI);
        }

        $items = MasterData::where('kategori', $kategori)
            ->orderBy('urutan')
            ->orderBy('label')
            ->get();

        return view('master-data.index', [
            'kategori' => $kategori,
            'kategoriList' => MasterData::KATEGORI,
            'items' => $items,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => ['required', Rule::in(array_keys(MasterData::KATEGORI))],
            'value' => ['required', 'string', 'max:255'],
            'label' => ['nullable', 'string', 'max:255'],
            'urutan' => ['nullable', 'integer'],
            'is_aktif' => ['boolean'],
        ], [
            'kategori.in' => 'Kategori tidak valid.',
        ]);

        // Ensure unique per kategori
        $validated['is_aktif'] = $validated['is_aktif'] ?? false;

        MasterData::create($validated);

        return Redirect::back()->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterData $masterDatum)
    {
        $validated = $request->validate([
            'kategori' => ['required', Rule::in(array_keys(MasterData::KATEGORI))],
            'value' => ['required', 'string', 'max:255'],
            'label' => ['nullable', 'string', 'max:255'],
            'urutan' => ['nullable', 'integer'],
            'is_aktif' => ['boolean'],
        ], [
            'kategori.in' => 'Kategori tidak valid.',
        ]);

        // Ensure unique per kategori, but ignore current record
        $existing = MasterData::where('kategori', $validated['kategori'])
            ->where('value', $validated['value'])
            ->where('id', '!=', $masterDatum->id)
            ->exists();

        if ($existing) {
            return Redirect::back()->withErrors(['value' => 'Nilai sudah ada untuk kategori ini.'])->withInput();
        }

        $validated['is_aktif'] = $validated['is_aktif'] ?? false;

        $masterDatum->update($validated);

        return Redirect::back()->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Toggle the active status of the resource.
     */
    public function toggleAktif(MasterData $masterDatum)
    {
        $masterDatum->update([
            'is_aktif' => !$masterDatum->is_aktif,
        ]);

        return Redirect::back()->with('success', 'Status berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterData $masterDatum)
    {
        $masterDatum->delete();

        return Redirect::back()->with('success', 'Data berhasil dihapus.');
    }
}