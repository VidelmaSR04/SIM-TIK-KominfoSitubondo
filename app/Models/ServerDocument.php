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
        'ttd_kiri_master_id',
        'ttd_kiri_judul',
        'ttd_kiri_nama',
        'ttd_kiri_pangkat',
        'ttd_kiri_nip',
        'ttd_kiri_qrcode',
        'ttd_kanan_master_id',
        'ttd_kanan_judul',
        'ttd_kanan_nama',
        'ttd_kanan_pangkat',
        'ttd_kanan_nip',
        'ttd_kanan_qrcode',
        'updated_by',
    ];

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * Dokumen awal (belum disimpan) untuk perangkat yang belum pernah diedit.
     * Kiri  : Kepala Dinas dengan teks "Mengetahui,"
     * Kanan : Kepala Bidang TIK
     * Silakan ubah di halaman edit.
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

            'ttd_kiri_master_id' => $kadis?->id,
            'ttd_kiri_judul'     => $kadis ? "Mengetahui,\n" . $kadis->jabatanTtd() : null,
            'ttd_kiri_nama'      => $kadis?->label,
            'ttd_kiri_pangkat'   => $kadis?->pangkat,
            'ttd_kiri_nip'       => $kadis?->nip,
            'ttd_kiri_qrcode'    => $kadis?->qrcode_path,

            'ttd_kanan_master_id' => $kabid?->id,
            'ttd_kanan_judul'     => $kabid?->jabatanTtd(),
            'ttd_kanan_nama'      => $kabid?->label,
            'ttd_kanan_pangkat'   => $kabid?->pangkat,
            'ttd_kanan_nip'       => $kabid?->nip,
            'ttd_kanan_qrcode'    => $kabid?->qrcode_path,
        ]);
    }

    /** Draf awal surat pengantar. Isinya bisa diubah sepenuhnya di editor. */
    public static function isiSuratAwal(Server $server): string
    {
        $tanggal = Carbon::now()->locale('id')->translatedFormat('j F Y');
        $nama    = e($server->nama_perangkat);
        $kode    = e($server->kode_perangkat);
        $tujuan  = ($server->status_kepemilikan === 'Colocation' && $server->pemilik_perangkat)
            ? 'Yth. Kepala ' . e($server->pemilik_perangkat)
            : 'Yth. ..............................';

        return <<<HTML
<p style="text-align: right;">Situbondo, {$tanggal}</p>
<table style="width: 100%; border-collapse: collapse;">
<tbody>
<tr><td style="width: 18%;">Nomor</td><td style="width: 3%;">:</td><td>&nbsp;</td></tr>
<tr><td>Lampiran</td><td>:</td><td>1 (satu) berkas</td></tr>
<tr><td>Hal</td><td>:</td><td>Penyampaian Rincian Perangkat {$nama}</td></tr>
</tbody>
</table>
<p>{$tujuan}<br>di<br>Tempat</p>
<p style="text-indent: 1.25cm; text-align: justify;">Dengan hormat,</p>
<p style="text-indent: 1.25cm; text-align: justify;">Sehubungan dengan pendataan perangkat teknologi informasi pada Dinas Komunikasi dan Informatika Kabupaten Situbondo, bersama ini kami sampaikan rincian perangkat {$nama} dengan kode {$kode} sebagaimana terlampir.</p>
<p style="text-indent: 1.25cm; text-align: justify;">Demikian surat ini kami sampaikan, atas perhatian dan kerja samanya diucapkan terima kasih.</p>
HTML;
    }
}