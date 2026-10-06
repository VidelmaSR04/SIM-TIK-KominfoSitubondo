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
use Illuminate\Validation\Rule;

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

        $servers = $query->paginate($perPage)->withQueryString();

        return view('server-dokumen.index', compact('servers'));
    }

    /**
     * Halaman edit dokumen (admin).
     */
    public function edit(Server $server)
    {
        $dokumen = ServerDocument::where('server_id', $server->id)->first()
            ?? ServerDocument::buatDefault($server);

        $pejabatList = MasterData::kategori(MasterData::KATEGORI_PEJABAT)
            ->aktif()
            ->orderBy('urutan')
            ->get();

        return view('server-dokumen.edit', compact('server', 'dokumen', 'pejabatList'));
    }

    /**
     * Simpan hasil edit dokumen (admin).
     */
    public function update(Request $request, Server $server)
    {
        $data    = $request->validate($this->aturan());
        $atribut = $this->susunAtribut($data);
        $atribut['updated_by'] = Auth::id();

        ServerDocument::updateOrCreate(['server_id' => $server->id], $atribut);

        if ($request->boolean('jadikan_bawaan')) {
            // Hanya kolom kop_html yang disimpan; logo memakai nilai bawaan
            // (tidak bergantung pada kolom logo_path di tabel document_settings)
            $setting = DocumentSetting::first() ?? new DocumentSetting();
            $setting->fill(['kop_html' => $atribut['kop_html']])->save();
        }

        return redirect()
            ->route('server.dokumen.edit', $server)
            ->with('success', 'Dokumen berhasil disimpan.');
    }

    /**
     * Preview PDF dari isi form yang belum disimpan (dipakai iframe di halaman edit).
     */
    public function previewDraft(Request $request, Server $server)
    {
        $data    = $request->validate($this->aturan());
        $dokumen = new ServerDocument($this->susunAtribut($data));
        $dokumen->server_id = $server->id;

        return $this->buatPdf($server, $dokumen)
            ->stream('draft-server-' . $server->id . '.pdf');
    }

    /**
     * Tampilkan PDF (versi yang sudah disimpan, atau draf awal bila belum pernah diedit).
     */
    public function streamPdf(Server $server)
    {
        $this->pastikanBolehLihat($server);

        return $this->buatPdf($server, $this->dokumenUntuk($server))
            ->stream('detail-server-' . $server->id . '.pdf');
    }

    /**
     * Unduh PDF.
     */
    public function download(Server $server)
    {
        $this->pastikanBolehLihat($server);

        return $this->buatPdf($server, $this->dokumenUntuk($server))
            ->download('detail-server-' . $server->id . '.pdf');
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

    private function dokumenUntuk(Server $server): ServerDocument
    {
        $dokumen = ServerDocument::where('server_id', $server->id)->first();

        return $dokumen
            ? $this->segarkanPenandatangan($dokumen)
            : ServerDocument::buatDefault($server);
    }

    /**
     * Samakan data penandatangan dengan Master Data terbaru (nama, pangkat, NIP, QR Code),
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

            $dokumen->{"ttd_{$sisi}_nama"}    = $pejabat->label;
            $dokumen->{"ttd_{$sisi}_pangkat"} = $pejabat->pangkat;
            $dokumen->{"ttd_{$sisi}_nip"}     = $pejabat->nip;
            $dokumen->{"ttd_{$sisi}_qrcode"}  = $pejabat->qrcode_path;
        }

        return $dokumen;
    }

    private function buatPdf(Server $server, ServerDocument $dokumen)
    {
        $server->loadMissing('aplikasis');
        $logo = public_path(DocumentSetting::saatIni()->logo_path ?: DocumentSetting::BAWAAN['logo_path']);

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

            'ttd_kiri_master_id'  => ['nullable', 'integer', $pejabat],
            'ttd_kiri_judul'      => ['nullable', 'string', 'max:500'],
            'ttd_kanan_master_id' => ['nullable', 'integer', $pejabat],
            'ttd_kanan_judul'     => ['nullable', 'string', 'max:500'],

            'jadikan_bawaan'      => ['nullable', 'boolean'],
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
        ];

        foreach (['kiri', 'kanan'] as $sisi) {
            $id      = $v["ttd_{$sisi}_master_id"] ?? null;
            $pejabat = $id ? MasterData::find($id) : null;

            $atribut["ttd_{$sisi}_master_id"] = $pejabat?->id;
            $atribut["ttd_{$sisi}_judul"]     = $v["ttd_{$sisi}_judul"] ?? null;
            $atribut["ttd_{$sisi}_nama"]      = $pejabat?->label;
            $atribut["ttd_{$sisi}_pangkat"]   = $pejabat?->pangkat;
            $atribut["ttd_{$sisi}_nip"]       = $pejabat?->nip;
            $atribut["ttd_{$sisi}_qrcode"]    = $pejabat?->qrcode_path;
        }

        return $atribut;
    }
}