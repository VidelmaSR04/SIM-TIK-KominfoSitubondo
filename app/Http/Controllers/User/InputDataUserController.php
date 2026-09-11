<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\MasterData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InputDataUserController extends Controller
{
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
    /**
     * Tampilkan form pendaftaran perangkat baru untuk user.
     */
    public function create(): View
    {
        // Fetch master data for dropdowns (same as admin)
        $opdList = MasterData::forSelect('pemilik_perangkat');
        if (empty($opdList)) {
            $opdOptionsArray = [
                'Dinas Pendidikan dan Kebudayaan',
                'Dinas Kesehatan',
                'Dinas Pekerjaan Umum dan Penataan Ruang',
                'Dinas Perumahan dan Kawasan Permukiman',
                'Satuan Polisi Pamong Praja',
                'Badan Kesatuan Bangsa dan Politik',
                'Badan Penanggulangan Bencana Daerah',
                'Dinas Sosial',
                'Dinas Tenaga Kerja',
                'Dinas Pemberdayaan Perempuan dan Perlindungan Anak',
                'Dinas Ketahanan Pangan',
                'Dinas Lingkungan Hidup',
                'Dinas Kependudukan dan Pencatatan Sipil',
                'Dinas Pemberdayaan Masyarakat dan Desa',
                'Dinas Pengendalian Penduduk dan Keluarga Berencana',
                'Dinas Perhubungan',
                'Dinas Komunikasi, Informatika dan Persandian',
                'Dinas Koperosi dan Usaha Mikro',
                'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu',
                'Dinas Perpustakaan dan Kearsipan',
                'Dinas Perikanan',
                'Dinas Pariwisata',
                'Dinas Tanaman Pangan, Hortikultura dan Perkebunan',
                'Dinas Peternakan dan Kesehatan Hewan',
                'Dinas Perdagangan dan Perindustrian',
                'Badan Perencanaan Pembangunan Daerah',
                'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia',
                'Badan Pendapatan, Pengelolaan Keuangan dan Aset Daerah',
                'Inspektorat Daerah',
                'Sekretariat Daerah',
                'Sekretariat DPRD',
                'Kecamatan Banyuglugur',
                'Kecamatan Jatibanteng',
                'Kecamatan Sumbermalang',
                'Kecamatan Besuki',
                'Kecamatan Suboh',
                'Kecamatan Mlandingan',
                'Kecamatan Bungatan',
                'Kecamatan Kendit',
                'Kecamatan Panarukan',
                'Kecamatan Situbondo',
                'Kecamatan Panji',
                'Kecamatan Mangaran',
                'Kecamatan Kapongan',
                'Kecamatan Arjasa',
                'Kecamatan Asembagus',
                'Kecamatan Jangkar',
                'Kecamatan Banyuputih',
                'RSAR',
                'PDAM Tirta Baluran',
                'RSUD Besuki',
                'RSUD Asembagus',
            ];
            $opdList = array_combine($opdOptionsArray, $opdOptionsArray);
        }

        $jenisList = MasterData::forSelect('jenis_perangkat');
        if (empty($jenisList)) {
            $jenisList = [
                'router' => 'Router',
                'switch' => 'Switch',
                'server' => 'Server',
            ];
        }

        $merkList = MasterData::forSelect('merk_perangkat');
        if (empty($merkList)) {
            $merkOptionsArray = [
                'MIKROTIK' => 'MIKROTIK',
                'CISCO' => 'CISCO',
                'DELL' => 'DELL',
                'HP' => 'HP',
                'LENOVO' => 'LENOVO',
                'HUAWEI' => 'HUAWEI',
            ];
            $merkList = $merkOptionsArray;
        }

        return view('user.inputdatauser', compact(
            'opdList',
            'jenisList',
            'merkList'
        ));
    }

    /**
     * Simpan perangkat baru yang didaftarkan user ke tabel `servers` yang sama
     * dengan yang dipakai admin, supaya data otomatis muncul di halaman
     * "Perangkat & Server" milik admin begitu disimpan.
     */
    public function store(Request $request): RedirectResponse
    {
        // Determine if merk is 'lainnya' to validate alternative field
        $merkValue = $request->input('merk_perangkat');
        // Determine if jenis is 'lainnya' to validate alternative field
        $jenisValue = $request->input('jenis_perangkat');

        $validationRules = [
            'nama_pengirim' => $request->input('opd') !== 'Kominfo' ? ['required', 'string', 'max:255'] : ['nullable', 'string', 'max:255'],
            'opd' => ['required', 'string', 'max:255'],
            'nama_penerima' => ['nullable', 'string', 'max:255'],
            'nama_perangkat' => ['required', 'string', 'max:255'],
            'jenis_perangkat' => ['required', 'string', 'max:255'],
            'merk_perangkat' => ['required', 'string', 'max:255'],
            'tanggal_input' => ['required', 'date'],
        ];

        if ($merkValue === 'lainnya') {
            $validationRules['merk_lainnya'] = ['required', 'string', 'max:255'];
        }
        if ($jenisValue === 'lainnya') {
            $validationRules['jenis_lainnya'] = ['required', 'string', 'max:255'];
        }

        $validated = $request->validate($validationRules);

        // Determine status kepemilikan based on selected OPD
        $statusKepemilikan = ($validated['opd'] === 'Kominfo') ? 'Kominfo' : 'Colocation';

        // Determine final merk & jenis value
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
            'pemilik_perangkat' => $validated['opd'],
            'status_kepemilikan'=> $statusKepemilikan,
            'nama_pengirim'     => $validated['nama_pengirim'],
            'nama_penerima'     => $validated['nama_penerima'] ?: null,
            'tanggal_input'     => $validated['tanggal_input'],
            'user_id'           => Auth::id(),
        ];

        // Hitung status_kelengkapan (should be 'pending' because technical fields empty)
        $data['status_kelengkapan'] = Server::hitungStatusKelengkapan($data);

        // Buat server baru
        $server = Server::create($data);

        // Generate and set kode_perangkat using tanggal_input (or today if null)
        $tanggalForCode = $validated['tanggal_input'] ?? \Illuminate\Support\Carbon::now()->toDateString();
        $server->kode_perangkat = Server::generateKodePerangkat(
            $data['status_kepemilikan'],
            $tanggalForCode
        );
        $server->save();

        // Sync status berdasarkan status_kelengkapan (jika belum dikunci manual)
        $server->syncStatusFromKelengkapan();
        $server->save();

        return redirect()
            ->route('user.dashboarduser')
            ->with('success', 'Perangkat berhasil didaftarkan dan menunggu verifikasi admin.');
    }
}