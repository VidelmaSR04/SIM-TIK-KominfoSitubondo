<?php

namespace App\Http\Controllers;

use App\Models\MasterData;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class MasterDataController extends Controller
{
    /**
     * Kategori master data => kolom di tabel `servers` yang memakai nilainya.
     * Dipakai untuk mengecek apakah sebuah master data masih dipakai perangkat.
     */
    private const KOLOM_SERVER = [
        'jenis_perangkat'    => 'jenis_perangkat',
        'merk_perangkat'     => 'merk_perangkat',
        'tipe_perangkat'     => 'tipe_perangkat',
        'status_kepemilikan' => 'status_kepemilikan',
        'pemilik_perangkat'  => 'pemilik_perangkat',
        'nomor_rack'         => 'nomor_rack',
        'kondisi_tipe'       => 'kondisi_tipe',
        'kondisi_status'     => 'kondisi_status',
        'status_perangkat'   => 'status',
    ];

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
        $this->bersihkanCache($validated['kategori']);

        return redirect()->route('master-data.index', ['kategori' => $validated['kategori']])->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     * Status aktif/non-aktif ikut diubah dari form edit (popup pemakaian ada di sisi view).
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

        $kategoriLama = $masterDatum->kategori;

        $masterDatum->update($this->olahData($validated));

        $this->bersihkanCache($kategoriLama);
        $this->bersihkanCache($validated['kategori']);

        return redirect()->route('master-data.index', ['kategori' => $validated['kategori']])->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Cek pemakaian sebuah master data (dipanggil via AJAX sebelum menonaktifkan).
     * Mengembalikan jumlah perangkat Kominfo dan Colocation yang masih memakainya.
     */
    public function pemakaian(MasterData $masterDatum)
    {
        $query = $this->queryPemakaian($masterDatum);

        $servers = $query
            ? $query->orderBy('nama_perangkat')
                ->get(['id', 'kode_perangkat', 'nama_perangkat', 'status_kepemilikan'])
            : collect();

        $perKepemilikan = $servers->countBy('status_kepemilikan');

        return response()->json([
            'dipakai'    => $servers->isNotEmpty(),
            'total'      => $servers->count(),
            'kominfo'    => (int) $perKepemilikan->get('Kominfo', 0),
            'colocation' => (int) $perKepemilikan->get('Colocation', 0),
            'daftar'     => $servers->take(10)->map(fn ($s) => [
                'kode'        => $s->kode_perangkat ?? '-',
                'nama'        => $s->nama_perangkat,
                'kepemilikan' => $s->status_kepemilikan,
            ])->values(),
        ]);
    }

    /**
     * Toggle the active status of the resource.
     */
    public function toggleAktif(MasterData $masterDatum)
    {
        $masterDatum->update([
            'is_aktif' => !$masterDatum->is_aktif,
        ]);

        $this->bersihkanCache($masterDatum->kategori);

        return Redirect::back()->with(
            'success',
            $masterDatum->is_aktif ? 'Data master berhasil diaktifkan.' : 'Data master berhasil dinonaktifkan.'
        );
    }

    // ------------------------------------------------------------------
    // Helper
    // ------------------------------------------------------------------

    /** Query perangkat yang memakai master data ini (null jika kategori tidak dipakai di server). */
    private function queryPemakaian(MasterData $item)
    {
        // Pejabat dipakai sebagai tanda tangan di dokumen server (berdasarkan id)
        if ($item->kategori === MasterData::KATEGORI_PEJABAT) {
            $serverIds = DB::table('server_documents')
                ->where('ttd_kiri_master_id', $item->id)
                ->orWhere('ttd_kanan_master_id', $item->id)
                ->pluck('server_id');

            return Server::whereIn('id', $serverIds);
        }

        $kolom = self::KOLOM_SERVER[$item->kategori] ?? null;

        return $kolom ? Server::where($kolom, $item->value) : null;
    }

    /** Hapus cache pilihan dropdown (key: master_data.select.{kategori}) agar perubahan langsung terlihat. */
    private function bersihkanCache(string $kategori): void
    {
        Cache::forget("master_data.select.{$kategori}");
    }

    private function aturan(): array
    {
        return [
            'kategori' => ['required', Rule::in(array_keys(MasterData::KATEGORI))],
            'value' => ['required', 'string', 'max:255'],
            'label' => ['nullable', 'string', 'max:255'],
            'urutan' => ['nullable', 'integer'],
            'is_aktif' => ['required', 'boolean'],

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
        }

        $validated['urutan'] = (int) ($validated['urutan'] ?? 0);
        $validated['is_aktif'] = (bool) $validated['is_aktif'];

        return $validated;
    }
}
