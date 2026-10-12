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
        // Preprocess: convert empty urutan to null so validation passes
        $input = $request->all();
        if (isset($input['urutan']) && $input['urutan'] === '') {
            $input['urutan'] = null;
        }
        $request->merge($input);

        $validated = $request->validate($this->aturan(), $this->pesan());

        // Ensure unique per kategori and value
        $existing = MasterData::where('kategori', $validated['kategori'])
            ->where('value', $validated['value'])
            ->exists();

        if ($existing) {
            return redirect()->route('master-data.index', ['kategori' => $validated['kategori']])->withErrors(['value' => 'Nilai sudah ada untuk kategori ini.'])->withInput();
        }

        MasterData::create($this->olahData($validated));

        return redirect()->route('master-data.index', ['kategori' => $validated['kategori']])->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterData $masterDatum)
    {
        // Preprocess: convert empty urutan to null so validation passes
        $input = $request->all();
        if (isset($input['urutan']) && $input['urutan'] === '') {
            $input['urutan'] = null;
        }
        $request->merge($input);

        $validated = $request->validate($this->aturan(), $this->pesan());

        // Ensure unique per kategori, but ignore current record
        $existing = MasterData::where('kategori', $validated['kategori'])
            ->where('value', $validated['value'])
            ->where('id', '!=', $masterDatum->id)
            ->exists();

        if ($existing) {
            return Redirect::back()->withErrors(['value' => 'Nilai sudah ada untuk kategori ini.'])->withInput();
        }

        $masterDatum->update($this->olahData($validated));

        return redirect()->route('master-data.index', ['kategori' => $validated['kategori']])->with('success', 'Data berhasil diperbarui.');
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
     *
     */
    public function destroy(MasterData $masterDatum)
    {
        $masterDatum->delete();

        return redirect()->route('master-data.index', ['kategori' => $masterDatum->kategori])->with('success', 'Data berhasil dihapus.');
    }

    // ------------------------------------------------------------------
    // Helper
    // ------------------------------------------------------------------

    private function aturan(): array
    {
        return [
            'kategori' => ['required', Rule::in(array_keys(MasterData::KATEGORI))],
            'value' => ['required', 'string', 'max:255'],
            'label' => ['nullable', 'string', 'max:255'],
            'urutan' => ['nullable', 'integer'],
            'is_aktif' => ['boolean'],

            // Khusus kategori pejabat
            'nip' => ['nullable', 'string', 'max:30'],
            'pangkat' => ['nullable', 'string', 'max:255', Rule::in(MasterData::PANGKAT_PNS)],
            'jabatan_ttd' => ['nullable', 'string', 'max:500'],
        ];
    }

    private function pesan(): array
    {
        return [
            'kategori.in' => 'Kategori tidak valid.',
            'nip.max' => 'NIP maksimal 30 karakter.',
            'pangkat.in' => 'Pangkat tidak valid. Pilih salah satu dari daftar.',
            'jabatan_ttd.max' => 'Jabatan di tanda tangan maksimal 500 karakter.',
        ];
    }

    /** Siapkan data untuk disimpan dan kosongkan kolom khusus pejabat untuk kategori lain. */
    private function olahData(array $validated): array
    {
        // Kolom khusus pejabat tidak dipakai kategori lain
        if ($validated['kategori'] !== MasterData::KATEGORI_PEJABAT) {
            $validated['nip'] = null;
            $validated['pangkat'] = null;
            $validated['jabatan_ttd'] = null;
            $validated['urutan'] = (int)($validated['urutan'] ?? 0);

            return $validated;
        }

        // Untuk kategori pejabat, pastikan urutan adalah integer
        $validated['urutan'] = (int)($validated['urutan'] ?? 0);

        return $validated;
    }
}