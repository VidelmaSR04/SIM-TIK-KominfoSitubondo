<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ServerDocument extends Model
{
    /** Disk privat (storage/app/private): file tidak bisa dibuka lewat URL, hanya lewat controller. */
    public const SIGNED_DISK = 'local';

    /** Folder file bertanda tangan: server-documents/signed/{server_id}/{uuid}.pdf */
    public const SIGNED_DIR = 'server-documents/signed';

    /** Ukuran maksimum file bertanda tangan (KB). */
    public const SIGNED_MAX_KB = 10240;

    /*
     * Kolom signed_* sengaja TIDAK masuk $fillable: hanya diisi lewat forceFill()
     * oleh proses upload/hapus, jadi tidak bisa terisi dari input form biasa.
     */
    protected $fillable = [
        'server_id',
        'kop_html',
        'isi_surat',
        'surat_tanggal',
        'surat_nomor',
        'surat_sifat',
        'surat_lampiran',
        'surat_hal',
        'surat_yth',
        'ttd_kiri_master_id',
        'ttd_kiri_judul',
        'ttd_kiri_nama',
        'ttd_kiri_pangkat',
        'ttd_kiri_nip',
        'ttd_kanan_master_id',
        'ttd_kanan_judul',
        'ttd_kanan_nama',
        'ttd_kanan_pangkat',
        'ttd_kanan_nip',
        'updated_by',
    ];

    protected $casts = [
        'signed_at'   => 'datetime',
        'signed_size' => 'integer',
    ];

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    public function signer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    /**
     * Dokumen final bila ada file bertanda tangan yang tercatat.
     * Status ini yang dipakai untuk mengunci pengeditan (bukan ada/tidaknya file di disk),
     * jadi file yang hilang dari penyimpanan tidak diam-diam membuka kunci edit.
     */
    public function isSigned(): bool
    {
        return filled($this->signed_file);
    }

    /** True bila file bertanda tangan benar-benar ada di penyimpanan. */
    public function signedFileExists(): bool
    {
        return $this->isSigned() && Storage::disk(self::SIGNED_DISK)->exists($this->signed_file);
    }

    /** Ukuran file bertanda tangan dalam bentuk yang mudah dibaca. */
    public function signedSizeLabel(): string
    {
        $byte = (int) $this->signed_size;

        if ($byte >= 1048576) {
            return number_format($byte / 1048576, 2, ',', '.') . ' MB';
        }

        return number_format(max($byte, 0) / 1024, 0, ',', '.') . ' KB';
    }

    /**
     * Dokumen awal (belum disimpan) untuk perangkat yang belum pernah diedit.
     */
    public static function buatDefault(Server $server): self
    {
        $setting = DocumentSetting::saatIni();
        $kadis   = MasterData::pejabat(MasterData::JABATAN_KEPALA_DINAS);
        $kabid   = MasterData::pejabat(MasterData::JABATAN_KABID_TIK);

        return new static([
            'server_id'   => $server->id,
            'kop_html'    => $setting->kop_html,
            'isi_surat'   => static::isiSuratAwal($server),
            'surat_tanggal' => Carbon::now()->locale('id')->translatedFormat('j F Y'),
            'surat_nomor' => '..............................',
            'surat_sifat' => 'Penting',
            'surat_lampiran' => '1 (satu) berkas',
            'surat_hal' => 'Penyampaian Rincian Perangkat ' . $server->nama_perangkat,
            'surat_yth' => ($server->status_kepemilikan === 'Colocation' && $server->pemilik_perangkat)
                ? 'Kepala ' . $server->pemilik_perangkat
                : '(Daftar terlampir)',

            'ttd_kiri_master_id' => $kadis?->id,
            'ttd_kiri_judul'     => $kadis?->jabatanTtd(),
            'ttd_kiri_nama'      => $kadis?->label,
            'ttd_kiri_pangkat'   => $kadis?->pangkat,
            'ttd_kiri_nip'       => $kadis?->nip,
            'ttd_kanan_master_id' => $kabid?->id,
            'ttd_kanan_judul'     => $kabid?->jabatanTtd(),
            'ttd_kanan_nama'      => $kabid?->label,
            'ttd_kanan_pangkat'   => $kabid?->pangkat,
            'ttd_kanan_nip'       => $kabid?->nip,
        ]);
    }

    /**
     * Isi awal setelah kepala surat yang diisi melalui kolom-kolom terstruktur.
     */
    public static function isiSuratAwal(Server $server): string
    {
        $nama    = e($server->nama_perangkat);
        $kode    = e($server->kode_perangkat);

        return <<<HTML
<p style="text-indent: 1.25cm; text-align: justify;">Dengan hormat,</p>
<p style="text-indent: 1.25cm; text-align: justify;">Sehubungan dengan pendataan perangkat teknologi informasi pada Dinas Komunikasi dan Informatika Kabupaten Situbondo, bersama ini kami sampaikan rincian perangkat {$nama} dengan kode {$kode} sebagaimana terlampir.</p>
<p style="text-indent: 1.25cm; text-align: justify;">Demikian surat ini kami sampaikan, atas perhatian dan kerja samanya diucapkan terima kasih.</p>
HTML;
    }
}