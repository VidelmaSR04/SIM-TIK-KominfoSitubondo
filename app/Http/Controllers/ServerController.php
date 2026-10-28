<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\MasterData;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator as ValidationValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ServerController extends Controller
{
public function index(Request $request)
{
    // Whitelist: only supported page sizes are accepted.
    $perPage = in_array((int) $request->input('perPage'), [10, 25, 50], true)
        ? (int) $request->input('perPage')
        : 10;

    $search = $request->input('search');
    $search = is_string($search) ? trim($search) : '';
    $urutan = $request->input('urutan', 'terbaru');
    $urutan = in_array($urutan, ['terbaru', 'terlama'], true) ? $urutan : 'terbaru';
    $tanggal = $request->input('tanggal');
    $bulan = $request->input('bulan');
    $tahun = $request->input('tahun');

    $query = Server::visibleTo(Auth::user())
        ->filter($search);

    if ($tanggal && in_array((int) $tanggal, range(1, 31), true)) {
        $query->whereRaw('SUBSTRING(kode_perangkat, 7, 2) = ?', [
            str_pad((int) $tanggal, 2, '0', STR_PAD_LEFT),
        ]);
    }

    if ($bulan && in_array((int) $bulan, range(1, 12), true)) {
        $query->whereRaw('SUBSTRING(kode_perangkat, 5, 2) = ?', [
            str_pad((int) $bulan, 2, '0', STR_PAD_LEFT),
        ]);
    }

    if ($tahun && is_numeric($tahun)) {
        $query->whereRaw('SUBSTRING(kode_perangkat, 3, 2) = ?', [substr((string) $tahun, -2)]);
    }

    $dir = $urutan === 'terbaru' ? 'desc' : 'asc';
    $query->orderByRaw("COALESCE(SUBSTRING(kode_perangkat, 3), '') {$dir}")
        ->orderBy('id', $dir);

    $servers = $query
        ->paginate($perPage)
        ->withQueryString();

    $tahunList = Server::visibleTo(Auth::user())
        ->whereNotNull('kode_perangkat')
        ->selectRaw('DISTINCT SUBSTRING(kode_perangkat, 3, 2) AS yy')
        ->pluck('yy')
        ->filter()
        ->sortDesc()
        ->map(fn ($yy) => '20' . $yy)
        ->values();

    return view('server', compact('servers', 'perPage', 'tahunList'));
}

    public function create()
    {
        return view('inputdata', ['inactiveMasterValues' => []]);
    }

    /**
     * Kolom server yang nilainya berasal dari Data Master.
     * field => [kategori master, label untuk pesan, field input "lainnya" (jika ada)]
     */
    private const FIELD_MASTER = [
        'jenis_perangkat'    => ['jenis_perangkat',    'Jenis perangkat',    'jenis_lainnya'],
        'merk_perangkat'     => ['merk_perangkat',     'Merk perangkat',     'merk_lainnya'],
        'tipe_perangkat'     => ['tipe_perangkat',     'Tipe perangkat',     null],
        'status_kepemilikan' => ['status_kepemilikan', 'Status kepemilikan', null],
        'pemilik_perangkat'  => ['pemilik_perangkat',  'Pemilik perangkat',  null],
        'nomor_rack'         => ['nomor_rack',         'Nomor rack',         null],
        'kondisi_tipe'       => ['kondisi_tipe',       'Kondisi tipe',       null],
        'kondisi_status'     => ['kondisi_status',     'Kondisi status',     null],
    ];

    /**
     * Tambahkan error ke validator jika ada nilai yang dipilih/diketik
     * sudah terdaftar di Data Master tetapi berstatus NON-AKTIF.
     *
     * - store(): semua nilai diperiksa.
     * - update(): nilai nonaktif tetap ditolak, meskipun nilainya sama
     *   dengan data yang sudah tersimpan.
     */
    private function cekMasterNonAktif(
        ValidationValidator $validator,
        Request $request,
        array &$masterNonAktif = []
    ): void
    {
        $validator->after(function (ValidationValidator $validator) use ($request, &$masterNonAktif) {
            $isAdmin = Auth::check() && Auth::user()->role === 'admin';

            foreach (self::FIELD_MASTER as $field => [$kategori, $label, $fieldLainnya]) {
                $nilai = trim((string) $request->input($field));
                $keyError = $field;

                // Pilihan "lainnya": yang dicek adalah teks yang diketik user
                if ($nilai === 'lainnya' && $fieldLainnya) {
                    $nilai = trim((string) $request->input($fieldLainnya));
                    $keyError = $fieldLainnya;
                }

                if ($nilai === '') {
                    continue;
                }

                $master = MasterData::where('kategori', $kategori)
                    ->whereRaw('LOWER(TRIM(value)) = ?', [mb_strtolower($nilai)])
                    ->first();

                if ($master && !$master->is_aktif) {
                    $masterNonAktif[] = $label . ': "' . $master->value . '"';
                    $pesan = 'Aktifkan terlebih dahulu ' . mb_strtolower($label) . ' "' . $master->value
                        . '" di Data Master karena sudah terdaftar di sana (saat ini berstatus Non-Aktif)';
                    $pesan .= $isAdmin
                        ? ', atau pilih nilai lain.'
                        : '. Hubungi admin untuk mengaktifkannya, atau pilih nilai lain.';

                    $validator->errors()->add($keyError, $pesan);
                }
            }
        });
    }

    private function resolveMasterDataValue(string $kategori, string $input): string
    {
        $input = trim($input);
        if (empty($input)) {
            return '';
        }

        // Check if value already exists (case-insensitive, trimmed)
        $existing = MasterData::where('kategori', $kategori)
            ->whereRaw('LOWER(TRIM(value)) = LOWER(?)', [$input])
            ->first();

        if ($existing) {
            return $existing->value;
        }

        // Determine next urutan
        $maxUrutan = MasterData::where('kategori', $kategori)->max('urutan');
        $nextUrutan = $maxUrutan !== null ? $maxUrutan + 1 : 1;

        // Create new master data entry (value = label = input)
        MasterData::create([
            'kategori' => $kategori,
            'value' => $input,
            'label' => $input,
            'urutan' => $nextUrutan,
            'is_aktif' => true,
        ]);

        return $input;
    }

    private function masterNonAktifPada(Server $server): array
    {
        $inactiveMasterValues = [];
        foreach (self::FIELD_MASTER as $field => [$kategori, $label]) {
            $value = trim((string) $server->{$field});
            if ($value === '') {
                continue;
            }

            $master = MasterData::where('kategori', $kategori)
                ->whereRaw('LOWER(TRIM(value)) = ?', [mb_strtolower($value)])
                ->where('is_aktif', false)
                ->first();

            if ($master) {
                $inactiveMasterValues[$field] = [
                    'value' => $master->value,
                    'label' => $label,
                    'kategori' => $kategori,
                ];
            }
        }

        return $inactiveMasterValues;
    }

    public function store(Request $request)
    {
        // Determine if merk is 'lainnya' to validate alternative field
        $merkValue = $request->input('merk_perangkat');
        // Determine if jenis is 'lainnya' to validate alternative field
        $jenisValue = $request->input('jenis_perangkat');

        $validationRules = [
            'nama_perangkat'      => 'required|string|max:255',
            'jenis_perangkat'     => ['required', 'string', 'max:255'],
            'serial_number'       => 'required|string|max:255',
            'merk_perangkat'      => ['required', 'string', 'max:255'],
            'type'                => 'required|string|max:255',
            'kondisi_tipe'        => 'required|in:Standard,High Performance',
            'kondisi_status'      => 'required|in:Baru,Bekas',
            'spesifikasi'         => 'required|string',
            'tipe_perangkat'      => 'required|in:RACK MOUNT,TOWER,BLADE',
            'status_kepemilikan'  => 'required|in:Kominfo,Colocation',
            'pemilik_perangkat'   => 'required_if:status_kepemilikan,Colocation|nullable|string|max:255',
            'ip_server'           => 'required|ip',
            'ip_vps'              => 'nullable|ip',
            'status'              => 'required|in:Aktif,Non-Aktif,Maintenance,automatic',
            'ukuran_hdd'          => 'required|string|max:50',
            'ukuran_ram'          => 'required|string|max:50',
            'nomor_rack'          => 'required|string|max:50',
            'gambar_rack'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'jumlah_core'         => 'required|integer|min:1',
            'peruntukan'          => 'required|string|max:255',
            'nama_pengirim'       => $request->input('status_kepemilikan') !== 'Kominfo' ? 'required|string|max:255' : 'nullable|string|max:255',
            'nama_penerima'       => $request->input('status_kepemilikan') !== 'Kominfo' ? 'required|string|max:255' : 'nullable|string|max:255',
            'jam_pengisian'       => 'required|date',
        ];

        if ($jenisValue === 'lainnya') {
            $validationRules['jenis_lainnya'] = ['required', 'string', 'max:255'];
        }
        if ($merkValue === 'lainnya') {
            $validationRules['merk_lainnya'] = ['required', 'string', 'max:255'];
        }

        $validator = Validator::make($request->all(), $validationRules);
        $masterNonAktif = [];
        $this->cekMasterNonAktif($validator, $request, $masterNonAktif);

        if ($validator->fails()) {
            $redirect = redirect()->back()->withErrors($validator)->withInput();
            if ($masterNonAktif !== []) {
                $redirect->with('master_data_warning', array_values(array_unique($masterNonAktif)));
            }
            return $redirect;
        }

        $validated = $request->validate($validationRules);

        // Handle custom merk and jenis input: save to master data if 'lainnya' selected
        $finalMerk = $merkValue === 'lainnya' && !empty($validated['merk_lainnya'] ?? '')
            ? $this->resolveMasterDataValue('merk_perangkat', $validated['merk_lainnya'])
            : $merkValue;

        $finalJenis = $jenisValue === 'lainnya' && !empty($validated['jenis_lainnya'] ?? '')
            ? $this->resolveMasterDataValue('jenis_perangkat', $validated['jenis_lainnya'])
            : $jenisValue;

        $data = [
            'nama_perangkat' => $validated['nama_perangkat'],
            'jenis_perangkat'   => $finalJenis,
            'merk_perangkat'    => $finalMerk,
            'serial_number'     => $validated['serial_number'],
            'type'              => $validated['type'],
            'kondisi_tipe'      => $validated['kondisi_tipe'],
            'kondisi_status'    => $validated['kondisi_status'],
            'spesifikasi'       => $validated['spesifikasi'],
            'tipe_perangkat'    => $validated['tipe_perangkat'],
            'status_kepemilikan'=> $validated['status_kepemilikan'],
            'pemilik_perangkat' => $validated['pemilik_perangkat'] ?? null,
            'ip_server'         => $validated['ip_server'],
            'ip_vps'            => $validated['ip_vps'],
            'status'            => $validated['status'],
            'ukuran_hdd'        => $validated['ukuran_hdd'],
            'ukuran_ram'        => $validated['ukuran_ram'],
            'nomor_rack'        => $validated['nomor_rack'],
            'jumlah_core'       => $validated['jumlah_core'],
            'peruntukan'        => $validated['peruntukan'],
            'nama_pengirim'     => empty($validated['nama_pengirim'] ?? '') ? null : $validated['nama_pengirim'],
            'nama_penerima'     => empty($validated['nama_penerima'] ?? '') ? null : $validated['nama_penerima'],
            'jam_pengisian'     => $validated['jam_pengisian'],
            'user_id'           => Auth::user()->id,
        ];

        // Calculate status_kelengkapan FIRST
        $data['status_kelengkapan'] = Server::hitungStatusKelengkapan(
            $data + ['gambar_rack' => $request->hasFile('gambar_rack') ? 'upload' : null]
        );

        // Determine status_locked based on admin's manual status choice from form
        // We need to check the original status value BEFORE removing it from $data
        $formStatus = $data['status'] ?? null;
        // Remove 'status' from data if it's 'automatic' to prevent SQL error
        // 'automatic' is a pseudo-value used only to set status_locked = false
        if (isset($data['status']) && $data['status'] === 'automatic') {
            unset($data['status']);
        }

        // Create server instance (we'll set status_locked explicitly after creation)
        $server = Server::create($data);

        // Generate and set kode_perangkat based on submitted date (jam_pengisian)
        $dateForCode = $validated['jam_pengisian'] ?? \Illuminate\Support\Carbon::now()->toDateString();
        $server->kode_perangkat = Server::generateKodePerangkat(
            $data['status_kepemilikan'],
            $dateForCode
        );
        $server->save();

        // Determine status_locked based on admin's manual status choice from form
        // If admin selects 'Non-Aktif' or 'Maintenance' -> lock status (status_locked = true)
        // If admin selects 'Aktif', 'Pending', or 'automatic' -> unlock status (status_locked = false)
        // This allows admin to override automatic status synchronization or return to automatic mode
        if (isset($formStatus)) {
            if ($formStatus === 'Non-Aktif' || $formStatus === 'Maintenance') {
                $server->status_locked = true;
            } else {
                // 'Aktif', 'Pending', or 'automatic' -> unlock for automatic synchronization
                $server->status_locked = false;
            }
            // Save the server with the correct status_locked value
            $server->save();
        }

        // ============= UPLOAD GAMBAR =============
        if ($request->hasFile('gambar_rack')) {
            $file = $request->file('gambar_rack');

            // Validasi tambahan untuk file
            if ($file->isValid()) {
                // Hapus gambar lama jika ada (though for new server, likely none)
                if ($server->gambar_rack && Storage::disk('public')->exists($server->gambar_rack)) {
                    Storage::disk('public')->delete($server->gambar_rack);
                    Log::info('Gambar lama dihapus: ' . $server->gambar_rack);
                }

                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $filename = str_replace('..', '', $filename);
                $path = $file->storeAs('rack_images', $filename, 'public');
                $server->gambar_rack = $path;

                // Log untuk debugging
                Log::info('Gambar berhasil diupload:', [
                    'filename' => $filename,
                    'path' => $path,
                    'size' => $file->getSize()
                ]);
            } else {
                return redirect()->back()->with('error', 'File gambar tidak valid.')->withInput();
            }
        }
        // =========================================

        // Sync status berdasarkan status_kelengkapan (jika belum dikunci manual oleh admin)
        $server->syncStatusFromKelengkapan();
        $server->save();

        // Ensure status is not null (fallback)
        if ($server->status === null) {
            $server->status = $server->status_kelengkapan === 'lengkap' ? 'Aktif' : 'Pending';
            $server->save();
        }

        return redirect()->route('detailserver', ['id' => $server->id])->with('success', 'Server berhasil ditambahkan.');
    }

    public function show(int|string $id)
    {
        $server = Server::findOrFail($id);

        // If user is not admin, verify ownership
        if (!Auth::check() || (Auth::user()->role !== 'admin' && $server->user_id !== Auth::id())) {
            abort(403, 'Unauthorized action.');
        }

        return view('detailserver', compact('server'));
    }

    public function edit(int|string $id)
    {
        $server = Server::findOrFail($id);

        // If user is not admin, verify ownership
        if (!Auth::check() || (Auth::user()->role !== 'admin' && $server->user_id !== Auth::id())) {
            abort(403, 'Unauthorized action.');
        }

        $inactiveMasterValues = $this->masterNonAktifPada($server);

        return view('inputdata', compact('server', 'inactiveMasterValues'));
    }

    public function update(Request $request, int|string $id)
    {
        $server = Server::findOrFail($id);
        if (!Auth::check() || (Auth::user()->role !== 'admin' && $server->user_id !== Auth::id())) {
            abort(403, 'Unauthorized action.');
        }

        // Determine if merk is 'lainnya' to validate alternative field
        $merkValue = $request->input('merk_perangkat');
        // Determine if jenis is 'lainnya' to validate alternative field
        $jenisValue = $request->input('jenis_perangkat');

        $validationRules = [
            'nama_perangkat'      => 'required|string|max:255',
            'jenis_perangkat'     => ['required', 'string', 'max:255'],
            'serial_number'       => 'required|string|max:255',
            'merk_perangkat'      => ['required', 'string', 'max:255'],
            'type'                => 'required|string|max:255',
            'kondisi_tipe'        => 'required|in:Standard,High Performance',
            'kondisi_status'      => 'required|in:Baru,Bekas',
            'spesifikasi'         => 'required|string',
            'tipe_perangkat'      => 'required|in:RACK MOUNT,TOWER,BLADE',
            'status_kepemilikan'  => 'required|in:Kominfo,Colocation',
            'pemilik_perangkat'   => 'required_if:status_kepemilikan,Colocation|nullable|string|max:255',
            'ip_server'           => 'required|ip',
            'ip_vps'              => 'nullable|ip',
            'status'              => 'required|in:Aktif,Non-Aktif,Maintenance,automatic',
            'ukuran_hdd'          => 'required|string|max:50',
            'ukuran_ram'          => 'required|string|max:50',
            'nomor_rack'          => 'required|string|max:50',
            'gambar_rack'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'jumlah_core'         => 'required|integer|min:1',
            'peruntukan'          => 'required|string|max:255',
            'nama_pengirim'       => $request->input('status_kepemilikan') !== 'Kominfo' ? 'required|string|max:255' : 'nullable|string|max:255',
            'nama_penerima'       => $request->input('status_kepemilikan') !== 'Kominfo' ? 'required|string|max:255' : 'nullable|string|max:255',
            'jam_pengisian'       => 'required|date',
        ];

        if ($jenisValue === 'lainnya') {
            $validationRules['jenis_lainnya'] = ['required', 'string', 'max:255'];
        }
        if ($merkValue === 'lainnya') {
            $validationRules['merk_lainnya'] = ['required', 'string', 'max:255'];
        }

        $validator = Validator::make($request->all(), $validationRules);
        $masterNonAktif = [];
        $this->cekMasterNonAktif($validator, $request, $masterNonAktif);

        if ($validator->fails()) {
            $redirect = redirect()->back()->withErrors($validator)->withInput();
            if ($masterNonAktif !== []) {
                $redirect->with('master_data_warning', array_values(array_unique($masterNonAktif)));
            }
            return $redirect;
        }

        $validated = $request->validate($validationRules);

        // Handle custom merk and jenis input: save to master data if 'lainnya' selected
        $finalMerk = $merkValue === 'lainnya' && !empty($validated['merk_lainnya'] ?? '')
            ? $this->resolveMasterDataValue('merk_perangkat', $validated['merk_lainnya'])
            : $merkValue;

        $finalJenis = $jenisValue === 'lainnya' && !empty($validated['jenis_lainnya'] ?? '')
            ? $this->resolveMasterDataValue('jenis_perangkat', $validated['jenis_lainnya'])
            : $jenisValue;

        $data = [
            'nama_perangkat' => $validated['nama_perangkat'],
            'jenis_perangkat'   => $finalJenis,
            'merk_perangkat'    => $finalMerk,
            'serial_number'     => $validated['serial_number'],
            'type'              => $validated['type'],
            'kondisi_tipe'      => $validated['kondisi_tipe'],
            'kondisi_status'    => $validated['kondisi_status'],
            'spesifikasi'       => $validated['spesifikasi'],
            'tipe_perangkat'    => $validated['tipe_perangkat'],
            'status_kepemilikan'=> $validated['status_kepemilikan'],
            'pemilik_perangkat' => $validated['pemilik_perangkat'] ?? null,
            'ip_server'         => $validated['ip_server'],
            'ip_vps'            => $validated['ip_vps'],
            'status'            => $validated['status'],
            'ukuran_hdd'        => $validated['ukuran_hdd'],
            'ukuran_ram'        => $validated['ukuran_ram'],
            'nomor_rack'        => $validated['nomor_rack'],
            'jumlah_core'       => $validated['jumlah_core'],
            'peruntukan'        => $validated['peruntukan'],
            'nama_pengirim'     => empty($validated['nama_pengirim'] ?? '') ? null : $validated['nama_pengirim'],
            'nama_penerima'     => empty($validated['nama_penerima'] ?? '') ? null : $validated['nama_penerima'],
            'jam_pengisian'     => $validated['jam_pengisian'],
        ];

        // Calculate status_kelengkapan FIRST
        $hapusFotoDiminta = $request->input('remove_image') == '1';
        $adaFoto = $request->hasFile('gambar_rack') || (!empty($server->gambar_rack) && !$hapusFotoDiminta);
        $data['status_kelengkapan'] = Server::hitungStatusKelengkapan(
            $data + ['gambar_rack' => $adaFoto ? ($server->gambar_rack ?: 'upload') : null]
        );

        // Determine status_locked based on admin's manual status choice from form
        // We need to check the original status value BEFORE removing it from $data
        $formStatus = $data['status'] ?? null;
        // Remove 'status' from data if it's 'automatic' to prevent SQL error
        // 'automatic' is a pseudo-value used only to set status_locked = false
        if (isset($data['status']) && $data['status'] === 'automatic') {
            unset($data['status']);
        }

        // Update server with base data (excluding status_locked for mass assignment)
        $server->update($data);

        // Regenerate kode_perangkat based on submitted date (jam_pengisian)
        $dateForCode = $validated['jam_pengisian'];
        $server->kode_perangkat = Server::generateKodePerangkat(
            $data['status_kepemilikan'],
            $dateForCode
        );
        $server->save();

        // Determine status_locked based on admin's manual status choice from form
        // If admin selects 'Non-Aktif' or 'Maintenance' -> lock status (status_locked = true)
        // If admin selects 'Aktif', 'Pending', or 'automatic' -> unlock status (status_locked = false)
        // This allows admin to override automatic status synchronization or return to automatic mode
        if (isset($formStatus)) {
            if ($formStatus === 'Non-Aktif' || $formStatus === 'Maintenance') {
                $server->status_locked = true;
            } else {
                // 'Aktif', 'Pending', or 'automatic' -> unlock for automatic synchronization
                $server->status_locked = false;
            }
            // Save the server with the correct status_locked value
            $server->save();
        }

        // ============= UPLOAD GAMBAR =============
        $hasNewFile = $request->hasFile('gambar_rack');
        $removeImage = $request->has('remove_image') && $request->remove_image == '1';

        if ($hasNewFile) {
            $file = $request->file('gambar_rack');

            if ($file->isValid()) {
                // Hapus gambar lama jika ada
                if ($server->gambar_rack && Storage::disk('public')->exists($server->gambar_rack)) {
                    Storage::disk('public')->delete($server->gambar_rack);
                    Log::info('Gambar lama dihapus: ' . $server->gambar_rack);
                }

                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $filename = str_replace('..', '', $filename);
                $path = $file->storeAs('rack_images', $filename, 'public');
                $server->gambar_rack = $path;

                Log::info('Gambar baru diupload: ' . $path);

                // Reset remove_image karena ada file baru
                $removeImage = false;
            } else {
                return redirect()->back()->with('error', 'File gambar tidak valid.')->withInput();
            }
        }

        // Hapus gambar jika diminta DAN tidak ada file baru
        if ($removeImage) {
            if ($server->gambar_rack && Storage::disk('public')->exists($server->gambar_rack)) {
                Storage::disk('public')->delete($server->gambar_rack);
                Log::info('Gambar dihapus via request: ' . $server->gambar_rack);
            }
            $server->gambar_rack = null;
        }
        // =========================================

        // Sync status berdasarkan status_kelengkapan (jika belum dikunci manual)
        $server->syncStatusFromKelengkapan();
        $server->save();

        // Ensure status is not null (fallback)
        if ($server->status === null) {
            $server->status = $server->status_kelengkapan === 'lengkap' ? 'Aktif' : 'Pending';
            $server->save();
        }

        return redirect()->route('server.index')->with('success', 'Server berhasil diperbarui.');
    }

    public function destroy(int|string $id)
    {
        $server = Server::findOrFail($id);
        if (!Auth::check() || (Auth::user()->role !== 'admin' && $server->user_id !== Auth::id())) {
            abort(403, 'Unauthorized action.');
        }

        // Server harus dinonaktifkan dulu sebelum dihapus
        if (($server->status ?? 'Pending') !== 'Non-Aktif') {
            $msg = 'Server berstatus "' . ($server->status ?? 'Pending') . '". Ubah status menjadi Non-Aktif terlebih dahulu untuk menghapus.';
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('hapus_error', $msg);
        }

        // ============= HAPUS GAMBAR =============
        if ($server->gambar_rack && Storage::disk('public')->exists($server->gambar_rack)) {
            Storage::disk('public')->delete($server->gambar_rack);
            Log::info('Gambar dihapus saat delete server: ' . $server->gambar_rack);
        }
        // =========================================

        $server->delete();

        return redirect()->route('server.index')->with('success', 'Server berhasil dihapus.');
    }

    // ============= METHOD UNTUK HAPUS GAMBAR =============
    public function removeImage(int|string $id)
    {
        try {
            $server = Server::findOrFail($id);
            if (!Auth::check() || (Auth::user()->role !== 'admin' && $server->user_id !== Auth::id())) {
                return response()->json(['success' => false, 'message' => 'Tidak diizinkan'], 403);
            }

            if ($server->gambar_rack && Storage::disk('public')->exists($server->gambar_rack)) {
                Storage::disk('public')->delete($server->gambar_rack);
                $server->gambar_rack = null;
                $server->save();

                Log::info('Gambar berhasil dihapus via AJAX: ' . $server->id);

                return response()->json([
                    'success' => true,
                    'message' => 'Gambar berhasil dihapus'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gambar tidak ditemukan'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error hapus gambar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    // =====================================================

    /**
     * Form khusus admin untuk melengkapi data teknis server yang
     * didaftarkan user (masih menggunakan form "inputdata" yang sama
     * dengan create/edit, supaya tidak ada view ganda yang bentrok).
     */
    public function lengkapi(int|string $id)
    {
        $server = Server::findOrFail($id);
        if (!Auth::check() || (Auth::user()->role !== 'admin' && $server->user_id !== Auth::id())) {
            abort(403, 'Unauthorized action.');
        }

        $inactiveMasterValues = $this->masterNonAktifPada($server);

        return view('inputdata', compact('server', 'inactiveMasterValues'));
    }

    /**
     * Simpan hasil "melengkapi data" oleh admin.
     * Logic-nya sama persis dengan update() (validasi penuh + hitung ulang
     * status_kelengkapan), jadi cukup delegasikan ke situ supaya tidak ada
     * duplikasi/​potensi bentrok logic.
     */
    public function updateLengkapi(Request $request, int|string $id)
    {
        return $this->update($request, $id);
    }

    public function exportPdf(int|string $id)
    {
        $server = Server::findOrFail($id);
        $pdf = Pdf::loadView('pdf.detailserver', compact('server'));
        return $pdf->download('detail-server-' . $server->id . '.pdf');
    }

    /**
     * Unlock status and sync with data completeness - for admin one-click fix
     */
    public function unlockAndSync(Request $request, int|string $id)
    {
        $server = Server::findOrFail($id);

        // Unlock the status
        $server->status_locked = false;

        // Sync status based on data completeness
        $server->syncStatusFromKelengkapan();

        // Save the changes
        $server->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Status berhasil dibuka dan disinkronisasi dengan data kelengkapan.');
    }

    /**
     * Return next kode_perangkat for given status_kepemilikan and date (YYYY-MM-DD)
     * via AJAX.
     */
    public function nextCode(Request $request)
    {
        $status = $request->input('status_kepemilikan');
        $date = $request->input('date'); // expected format Y-m-d

        if (!$status || !in_array($status, ['Kominfo', 'Colocation'])) {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        $dateObj = $date ? \Illuminate\Support\Carbon::parse($date) : \Illuminate\Support\Carbon::now();
        $kode = Server::generateKodePerangkat($status, $dateObj->toDateString());

        return response()->json(['kode_perangkat' => $kode]);
    }
}
