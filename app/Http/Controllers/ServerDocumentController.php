<?php

namespace App\Http\Controllers;

use App\Models\DocumentSetting;
use App\Models\MasterData;
use App\Models\Server;
use App\Models\ServerDocument;
use App\Services\SuratHtmlSanitizer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class ServerDocumentController extends Controller
{
    /** Role yang boleh melihat dokumen semua perangkat. */
    private const ROLE_LIHAT_SEMUA = ['admin', 'kepala_bidang_tik'];

    public function __construct(private SuratHtmlSanitizer $sanitizer)
    {
    }

    /**
     * Daftar dokumen (hanya perangkat dengan data lengkap).
     */
    public function index(Request $request)
    {
        if (Auth::check() && Auth::user()->role === 'teknisi') {
            abort(403, 'Unauthorized action.');
        }

        $search  = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);
        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $query = Server::where('status_kelengkapan', 'lengkap')
            ->when($search, function ($query, $search) {
                // Dibungkus satu grup supaya filter "lengkap" dan "milik user" tidak ikut terlewati
                $query->where(function ($q) use ($search) {
                    $q->where('nama_perangkat', 'like', "%{$search}%")
                      ->orWhere('pemilik_perangkat', 'like', "%{$search}%")
                      ->orWhere('kode_perangkat', 'like', "%{$search}%");
                });
            });

        if (!$this->bolehLihatSemua()) {
            $query->where('user_id', Auth::id());
        }

        // Urutan tanggal
        $urutan = $request->input('urutan', 'terbaru');
        if (!in_array($urutan, ['terbaru', 'terlama'], true)) {
            $urutan = 'terbaru';
        }
        if ($urutan === 'terbaru') {
            $query->orderBy('kode_perangkat', 'desc');
        } else {
            $query->orderBy('kode_perangkat', 'asc');
        }

        // Filter tanggal, bulan, tahun
        $tanggal = $request->input('tanggal');
        $bulan   = $request->input('bulan');
        $tahun   = $request->input('tahun');

        if ($tanggal && in_array((int)$tanggal, range(1,31), true)) {
            $query->whereRaw("SUBSTRING(kode_perangkat,7,2) = ?", [str_pad((int)$tanggal,2,'0',STR_PAD_LEFT)]);
        }
        if ($bulan && in_array((int)$bulan, range(1,12), true)) {
            $query->whereRaw("SUBSTRING(kode_perangkat,5,2) = ?", [str_pad((int)$bulan,2,'0',STR_PAD_LEFT)]);
        }
        if ($tahun && is_numeric($tahun)) {
            $yy = substr($tahun,-2);
            $query->whereRaw("SUBSTRING(kode_perangkat,3,2) = ?", [$yy]);
        }

        // Daftar tahun untuk dropdown (distinct tahun dari kode_perangkat, dua digit, lalu tambahkan '20' depan)
        $tahunList = Server::where('status_kelengkapan', 'lengkap')
            ->pluck(DB::raw('SUBSTRING(kode_perangkat,3,2)'))
            ->unique()
            ->sortDesc()
            ->map(fn($y) => '20'.$y)
            ->all();

        $servers = $query->paginate($perPage)->withQueryString();

        // Status tanda tangan tiap baris (server_id => waktu upload), satu query untuk satu halaman
        $ttdMap = ServerDocument::whereIn('server_id', $servers->pluck('id'))
            ->whereNotNull('signed_file')
            ->pluck('signed_at', 'server_id');

        return view('server-dokumen.index', compact(
            'servers',
            'tahunList',
            'tanggal',
            'bulan',
            'tahun',
            'ttdMap'
        ));
    }
    public function edit(Server $server)
    {
        $dokumen = $this->dokumenTersimpan($server)
            ?? ServerDocument::buatDefault($server);

        // Dokumen final: halaman yang sama, tetapi tampil sebagai tampilan dokumen bertanda tangan.
        // Formulir edit tidak dirender sama sekali.
        if ($dokumen->isSigned()) {
            $dokumen->loadMissing('signer');

            return view('server-dokumen.edit', [
                'server'    => $server,
                'dokumen'   => $dokumen,
                'sudahTtd'  => true,
                'berkasAda' => $dokumen->signedFileExists(),
            ]);
        }

        $pejabatList = MasterData::kategori(MasterData::KATEGORI_PEJABAT)
            ->aktif()
            ->orderBy('urutan')
            ->get();

        $logoUrl    = DocumentSetting::logoUrl();
        $logoKustom = DocumentSetting::logoKustom();
        $tanggalSurat = filled($dokumen->surat_tanggal)
            ? Carbon::createFromLocaleFormat('j F Y', 'id', $dokumen->surat_tanggal)?->toDateString()
            : null;

        $sudahTtd = false;

        return view('server-dokumen.edit', compact('server', 'dokumen', 'pejabatList', 'logoUrl', 'logoKustom', 'tanggalSurat', 'sudahTtd'));
    }

    /**
     * Simpan hasil edit dokumen (admin).
     */
    public function update(Request $request, Server $server)
    {
        // Penguncian di backend: dokumen final tidak boleh diubah lewat form, URL, maupun request langsung
        $this->tolakJikaFinal($this->dokumenTersimpan($server));

        $data    = $request->validate($this->aturan());
        $atribut = $this->susunAtribut($data);
        $atribut['updated_by'] = Auth::id();

        // Dicek ulang di dalam transaksi (baris dikunci) supaya tidak bisa menyelip
        // di antara upload dokumen bertanda tangan dan penyimpanan ini.
        DB::transaction(function () use ($server, $atribut) {
            $this->tolakJikaFinal(
                ServerDocument::where('server_id', $server->id)->lockForUpdate()->first()
            );

            ServerDocument::updateOrCreate(['server_id' => $server->id], $atribut);
        });

        $this->perbaruiLogo($request);

        return redirect()
            ->route('server.dokumen.edit', $server)
            ->with('success', 'Dokumen berhasil disimpan.');
    }

    /**
     * Preview PDF dari isi form yang belum disimpan (dipakai iframe di halaman edit).
     */
    public function previewDraft(Request $request, Server $server)
    {
        $this->tolakJikaFinal($this->dokumenTersimpan($server));

        $data    = $request->validate($this->aturan());
        $dokumen = new ServerDocument($this->susunAtribut($data));
        $dokumen->server_id = $server->id;

        // Logo yang baru dipilih / dikembalikan ke bawaan ikut tampil di preview (belum disimpan)
        $logo = null;
        if ($request->hasFile('logo')) {
            $berkas = $request->file('logo');
            $logo   = 'data:' . $berkas->getMimeType() . ';base64,'
                    . base64_encode((string) file_get_contents($berkas->getRealPath()));
        } elseif ($request->boolean('hapus_logo')) {
            $logo = public_path(DocumentSetting::BAWAAN['logo_path']);
        }

        return $this->buatPdf($server, $dokumen, $logo)
            ->stream('draft-server-' . $server->id . '.pdf');
    }

    /**
     * Tampilkan PDF (versi yang sudah disimpan, atau draf awal bila belum pernah diedit).
     */
    public function streamPdf(Server $server)
    {
        $this->pastikanBolehLihat($server);

        // Dokumen final: semua role mendapat file bertanda tangan yang sama (satu sumber: ID dokumen)
        $tersimpan = $this->dokumenTersimpan($server);
        if ($tersimpan?->isSigned()) {
            return $this->kirimBerkasTtd($server, $tersimpan, 'inline');
        }

        return $this->buatPdf($server, $this->dokumenUntuk($server, $tersimpan))
            ->stream('detail-server-' . $server->id . '.pdf');
    }

    /**
     * Unduh PDF.
     */
    public function download(Server $server)
    {
        $this->pastikanBolehLihat($server);

        $tersimpan = $this->dokumenTersimpan($server);
        if ($tersimpan?->isSigned()) {
            return $this->kirimBerkasTtd($server, $tersimpan, 'attachment');
        }

        return $this->buatPdf($server, $this->dokumenUntuk($server, $tersimpan))
            ->download('detail-server-' . $server->id . '.pdf');
    }

    // ------------------------------------------------------------------
    // Dokumen bertanda tangan (final)
    // ------------------------------------------------------------------

    /**
     * Tampilkan file bertanda tangan (inline). Semua role yang boleh melihat dokumen ini.
     */
    public function signedPreview(Server $server)
    {
        $this->pastikanBolehLihat($server);

        return $this->kirimBerkasTtd($server, $this->dokumenFinalAtau404($server), 'inline');
    }

    /**
     * Unduh file bertanda tangan.
     */
    public function signedDownload(Server $server)
    {
        $this->pastikanBolehLihat($server);

        return $this->kirimBerkasTtd($server, $this->dokumenFinalAtau404($server), 'attachment');
    }

    /**
     * Unggah (atau ganti) dokumen bertanda tangan. Khusus admin (dijaga middleware di route).
     * Setelah berhasil, dokumen menjadi final dan terkunci dari editor.
     */
    public function uploadSigned(Request $request, Server $server)
    {
        $request->validate([
            'signed_file' => [
                'required', 'file', 'mimes:pdf', 'mimetypes:application/pdf',
                'max:' . ServerDocument::SIGNED_MAX_KB,
            ],
        ], [
            'signed_file.required'  => 'Pilih file dokumen bertanda tangan terlebih dahulu.',
            'signed_file.file'      => 'File dokumen bertanda tangan tidak valid atau gagal diunggah.',
            'signed_file.mimes'     => 'Dokumen bertanda tangan harus berformat PDF.',
            'signed_file.mimetypes' => 'Dokumen bertanda tangan harus berformat PDF.',
            'signed_file.max'       => 'Ukuran dokumen bertanda tangan maksimal ' . (ServerDocument::SIGNED_MAX_KB / 1024) . ' MB.',
            'signed_file.uploaded'  => 'File gagal diunggah. Periksa batas ukuran unggahan di server (upload_max_filesize).',
        ]);

        $berkas = $request->file('signed_file');

        // Ekstensi dan MIME bisa dipalsukan: periksa juga penanda awal isi file PDF
        $pegangan = @fopen($berkas->getRealPath(), 'rb');
        $kepala   = $pegangan ? fread($pegangan, 5) : '';
        if ($pegangan) {
            fclose($pegangan);
        }
        if ($kepala !== '%PDF-') {
            throw ValidationException::withMessages([
                'signed_file' => 'Isi file bukan PDF yang valid.',
            ]);
        }

        $ukuran = (int) $berkas->getSize();
        $asli   = Str::limit(basename($berkas->getClientOriginalName()), 200, '');

        // Nama acak (UUID) + folder per server: tidak pernah bentrok dan tidak menimpa file lama
        $disk     = Storage::disk(ServerDocument::SIGNED_DISK);
        $pathBaru = $berkas->storeAs(
            ServerDocument::SIGNED_DIR . '/' . $server->id,
            (string) Str::uuid() . '.pdf',
            ServerDocument::SIGNED_DISK
        );

        if (!$pathBaru) {
            throw ValidationException::withMessages([
                'signed_file' => 'File gagal disimpan di server. Silakan coba lagi.',
            ]);
        }

        $pathLama = null;

        try {
            DB::transaction(function () use ($server, $pathBaru, $ukuran, $asli, &$pathLama) {
                $dokumen = ServerDocument::where('server_id', $server->id)->lockForUpdate()->first();

                // Dokumen belum pernah disimpan: jadikan draf yang diunduh admin sebagai dokumen aslinya
                if (!$dokumen) {
                    $dokumen = ServerDocument::buatDefault($server);
                    $dokumen->updated_by = Auth::id();
                    $dokumen->save();
                }

                $pathLama = $dokumen->signed_file;

                // Hanya kolom signed_*: isi dokumen asli (kop, isi surat, tanda tangan) tidak disentuh
                $dokumen->forceFill([
                    'signed_file'          => $pathBaru,
                    'signed_original_name' => $asli,
                    'signed_mime'          => 'application/pdf',
                    'signed_size'          => $ukuran,
                    'signed_at'            => now(),
                    'signed_by'            => Auth::id(),
                ])->save();
            });
        } catch (\Throwable $e) {
            // Database gagal: jangan tinggalkan file yatim di penyimpanan
            $disk->delete($pathBaru);
            throw $e;
        }

        // Database sudah berhasil; file lama baru dihapus sesudahnya
        if (filled($pathLama) && $pathLama !== $pathBaru) {
            $disk->delete($pathLama);
        }

        return redirect()
            ->route('server.dokumen.edit', $server)
            ->with('success', filled($pathLama)
                ? 'Dokumen bertanda tangan berhasil diganti. Dokumen tetap final dan terkunci dari pengeditan.'
                : 'Dokumen bertanda tangan berhasil diunggah. Dokumen kini final dan tidak dapat diedit.');
    }

    /**
     * Hapus HANYA file bertanda tangan. Baris dokumen asli (kop, isi surat, tanda tangan) tetap utuh
     * dan dokumen kembali ke kondisi "belum ditandatangani" sehingga bisa diedit lagi.
     */
    public function destroySigned(Server $server)
    {
        $pathLama = null;

        DB::transaction(function () use ($server, &$pathLama) {
            $dokumen = ServerDocument::where('server_id', $server->id)->lockForUpdate()->first();

            abort_unless($dokumen && $dokumen->isSigned(), 404, 'Tidak ada dokumen bertanda tangan untuk dihapus.');

            $pathLama = $dokumen->signed_file;

            $dokumen->forceFill([
                'signed_file'          => null,
                'signed_original_name' => null,
                'signed_mime'          => null,
                'signed_size'          => null,
                'signed_at'            => null,
                'signed_by'            => null,
            ])->save();
        });

        // Catatan database dibersihkan lebih dulu; bila penghapusan file gagal, yang tersisa
        // hanya file yatim yang tidak terhubung ke apa pun (bukan status final yang salah).
        Storage::disk(ServerDocument::SIGNED_DISK)->delete($pathLama);

        return redirect()
            ->route('server.dokumen.edit', $server)
            ->with('success', 'Dokumen bertanda tangan dihapus. Dokumen kembali dapat diedit.');
    }

    // ------------------------------------------------------------------
    // Helper
    // ------------------------------------------------------------------

    private function bolehLihatSemua(): bool
    {
        return Auth::check() && in_array(Auth::user()->role, self::ROLE_LIHAT_SEMUA, true);
    }

    private function pastikanBolehLihat(Server $server): void
    {
        if (!Auth::check() || (!$this->bolehLihatSemua() && $server->user_id !== Auth::id())) {
            abort(403, 'Unauthorized action.');
        }
    }

    private function dokumenTersimpan(Server $server): ?ServerDocument
    {
        return ServerDocument::where('server_id', $server->id)->first();
    }

    /** Hentikan request bila dokumen sudah final (berisi file bertanda tangan). */
    private function tolakJikaFinal(?ServerDocument $dokumen): void
    {
        abort_if(
            $dokumen?->isSigned(),
            423,
            'Dokumen sudah ditandatangani (final) dan tidak dapat diubah. Hapus dokumen bertanda tangan terlebih dahulu bila perlu mengedit.'
        );
    }

    private function dokumenFinalAtau404(Server $server): ServerDocument
    {
        $dokumen = $this->dokumenTersimpan($server);

        abort_unless($dokumen?->isSigned(), 404, 'Dokumen belum ditandatangani.');

        return $dokumen;
    }

    private function kirimBerkasTtd(Server $server, ServerDocument $dokumen, string $disposisi)
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk(ServerDocument::SIGNED_DISK);

        // Tidak pernah jatuh kembali ke PDF buatan sistem: itu akan menampilkan dokumen yang salah
        abort_unless(
            $dokumen->isSigned() && $disk->exists($dokumen->signed_file),
            404,
            'File dokumen bertanda tangan tidak ditemukan di penyimpanan. Hubungi admin.'
        );

        $nama = 'dokumen-ttd-' . (Str::slug((string) $server->kode_perangkat) ?: $server->id) . '.pdf';

        return $disk->response($dokumen->signed_file, $nama, [
            'Content-Type'           => 'application/pdf',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control'          => 'private, no-store, max-age=0',
        ], $disposisi);
    }

    private function dokumenUntuk(Server $server, ?ServerDocument $dokumen = null): ServerDocument
    {
        $dokumen ??= $this->dokumenTersimpan($server);

        return $dokumen
            ? $this->segarkanPenandatangan($dokumen)
            : ServerDocument::buatDefault($server);
    }

    /**
     * Samakan data penandatangan dengan Master Data terbaru (nama, pangkat, dan NIP),
     * persis seperti preview di halaman edit. Hanya untuk tampilan: tidak disimpan ke database.
     * Judul/jabatan tetap memakai isi yang sudah disimpan. Bila pejabatnya sudah dihapus
     * dari Master Data, salinan lama yang dipakai.
     */
    private function segarkanPenandatangan(ServerDocument $dokumen): ServerDocument
    {
        foreach (['kiri', 'kanan'] as $sisi) {
            $id      = $dokumen->{"ttd_{$sisi}_master_id"};
            $pejabat = $id ? MasterData::find($id) : null;

            if (!$pejabat) {
                continue;
            }

            $dokumen->{"ttd_{$sisi}_judul"}   = $pejabat->jabatanTtd();
            $dokumen->{"ttd_{$sisi}_nama"}    = $pejabat->label;
            $dokumen->{"ttd_{$sisi}_pangkat"} = $pejabat->pangkat;
            $dokumen->{"ttd_{$sisi}_nip"}     = $pejabat->nip;
        }

        return $dokumen;
    }

    /**
     * Simpan logo kop yang diunggah (berlaku untuk semua dokumen) atau kembalikan ke logo bawaan.
     */
    private function perbaruiLogo(Request $request): void
    {
        $berkas = $request->file('logo');

        if (!$berkas && !$request->boolean('hapus_logo')) {
            return;
        }

        $setting = DocumentSetting::first() ?? new DocumentSetting();
        $lama    = $setting->logo_path;

        $setting->logo_path = $berkas ? $berkas->store(DocumentSetting::LOGO_DIR, 'public') : null;
        $setting->save();

        // Hapus file unggahan lama (logo bawaan di public/img tidak pernah dihapus)
        if (filled($lama) && str_starts_with($lama, DocumentSetting::LOGO_DIR . '/')) {
            Storage::disk('public')->delete($lama);
        }
    }

    private function buatPdf(Server $server, ServerDocument $dokumen, ?string $logo = null)
    {
        $server->loadMissing('aplikasis');
        $logo ??= DocumentSetting::logoFile();

        // Folio / F4: 8,5 x 13 inci = 612 x 936 pt
        return Pdf::loadView('pdf.detailserver', compact('server', 'dokumen', 'logo'))
            ->setPaper([0, 0, 612, 936], 'portrait');
    }

    private function aturan(): array
    {
        $pejabat = Rule::exists('master_data', 'id')->where('kategori', MasterData::KATEGORI_PEJABAT);

        return [
            'kop_html'  => ['required', 'string', 'max:20000'],
            'isi_surat' => ['nullable', 'string', 'max:100000'],
            'surat_tanggal' => ['required', 'date_format:Y-m-d'],
            'surat_nomor' => ['required', 'string', 'max:150'],
            'surat_sifat' => ['required', 'string', 'max:100'],
            'surat_lampiran' => ['required', 'string', 'max:100'],
            'surat_hal' => ['required', 'string', 'max:255'],
            'surat_yth' => ['required', 'string', 'max:500'],

            'logo'       => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'hapus_logo' => ['nullable', 'boolean'],

            'ttd_kiri_master_id'  => ['nullable', 'integer', $pejabat],
            'ttd_kanan_master_id' => ['nullable', 'integer', $pejabat],
        ];
    }

    /**
     * Susun atribut dokumen dari input form.
     * Nama/pangkat/NIP/QR Code penandatangan selalu diambil dari master data di server,
     * bukan dari input form, lalu disalin (snapshot) ke dokumen.
     */
    private function susunAtribut(array $v): array
    {
        $atribut = [
            'kop_html'  => $this->sanitizer->bersihkan($v['kop_html']),
            'isi_surat' => $this->sanitizer->bersihkan($v['isi_surat'] ?? ''),
            'surat_tanggal' => Carbon::createFromFormat('!Y-m-d', $v['surat_tanggal'])
                ->locale('id')
                ->translatedFormat('j F Y'),
            'surat_nomor' => trim($v['surat_nomor']),
            'surat_sifat' => trim($v['surat_sifat']),
            'surat_lampiran' => trim($v['surat_lampiran']),
            'surat_hal' => trim($v['surat_hal']),
            'surat_yth' => trim($v['surat_yth']),
        ];

        foreach (['kiri', 'kanan'] as $sisi) {
            $id      = $v["ttd_{$sisi}_master_id"] ?? null;
            $pejabat = $id ? MasterData::find($id) : null;

            $atribut["ttd_{$sisi}_master_id"] = $pejabat?->id;
            $atribut["ttd_{$sisi}_judul"]     = $pejabat?->jabatanTtd();
            $atribut["ttd_{$sisi}_nama"]      = $pejabat?->label;
            $atribut["ttd_{$sisi}_pangkat"]   = $pejabat?->pangkat;
            $atribut["ttd_{$sisi}_nip"]       = $pejabat?->nip;
        }

        return $atribut;
    }
}