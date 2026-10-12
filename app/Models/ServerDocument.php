<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class ServerDocument extends Model
{
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

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
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