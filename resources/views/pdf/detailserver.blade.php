@php
    // Cadangan bila view dipanggil tanpa variabel dari controller
    $dokumen = $dokumen ?? \App\Models\ServerDocument::buatDefault($server);
    $logo    = $logo ?? public_path('img/logo-situbondo.png');

    $fmt = fn ($v) => filled($v) ? $v : '-';
    $tgl = fn ($v) => $v ? \Carbon\Carbon::parse($v)->format('d M Y, H:i') : '-';

    $rincian = [
        ['Nama Server',       $server->nama_perangkat,    'Tipe Perangkat',     $server->tipe_perangkat],
        ['Jenis Perangkat',   $server->jenis_perangkat,   'Serial Number',      $server->serial_number],
        ['Merk Perangkat',    $server->merk_perangkat,    'Type',               $server->type],
        ['Kondisi Tipe',      $server->kondisi_tipe,      'Kondisi Status',     $server->kondisi_status],
        ['Spesifikasi',       $server->spesifikasi,       'Status Kepemilikan', $server->status_kepemilikan],
        ['Pemilik Perangkat', $server->pemilik_perangkat, 'IP Server',          $server->ip_server],
        ['IP VPS',            $server->ip_vps,            'Status Server',      $server->status],
        ['Ukuran HDD',        $server->ukuran_hdd,        'Ukuran RAM',         $server->ukuran_ram],
        ['Nomor RACK',        $server->nomor_rack,        'Jumlah Core',        $server->jumlah_core],
        ['Peruntukan',        $server->peruntukan,        'Nama Pengirim',      $server->nama_pengirim],
        ['Nama Penerima',     $server->nama_penerima,     'Jam Pengisian',      $tgl($server->jam_pengisian)],
        ['Tanggal Dibuat',    $tgl($server->created_at),  'Terakhir Update',    $tgl($server->updated_at)],
        ['ID Server',         $server->id,                '',                   ''],
    ];

    // Pangkat dicetak tanpa golongan ("Pembina Utama Muda"). Ubah ke true bila golongan ingin ikut tampil.
    $tampilkanGolongan = false;

    // Path file QR Code tanda tangan (disk 'public'); null bila tidak ada / file hilang
    $qrPath = function (?string $relatif) {
        if (blank($relatif)) {
            return null;
        }
        $abs = storage_path('app/public/' . ltrim($relatif, '/'));

        return file_exists($abs) ? $abs : null;
    };

    // Snapshot di dokumen dipakai lebih dulu; bila kosong (mis. QR baru diunggah belakangan)
    // dipakai QR terbaru milik pejabat di Master Data.
    $qrDari = function ($snapshot, $masterId) use ($qrPath) {
        return $qrPath($snapshot) ?: ($masterId ? $qrPath(\App\Models\MasterData::find($masterId)?->qrcode_path) : null);
    };

    $ttd = [
        'kiri' => [
            'judul'   => $dokumen->ttd_kiri_judul,
            'nama'    => $dokumen->ttd_kiri_nama,
            'pangkat' => $dokumen->ttd_kiri_pangkat,
            'nip'     => $dokumen->ttd_kiri_nip,
            'qrcode'  => $qrDari($dokumen->ttd_kiri_qrcode, $dokumen->ttd_kiri_master_id),
        ],
        'kanan' => [
            'judul'   => $dokumen->ttd_kanan_judul,
            'nama'    => $dokumen->ttd_kanan_nama,
            'pangkat' => $dokumen->ttd_kanan_pangkat,
            'nip'     => $dokumen->ttd_kanan_nip,
            'qrcode'  => $qrDari($dokumen->ttd_kanan_qrcode, $dokumen->ttd_kanan_master_id),
        ],
    ];

    // Catatan BSrE di kaki halaman hanya muncul bila ada penandatangan ber-QR
    $adaQr = collect($ttd)->contains(fn ($t) => filled($t['nama']) && $t['qrcode']);
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rincian Server - {{ $server->nama_perangkat }}</title>

    <style>
        /* ===== HALAMAN (Folio/F4, margin sesuai Format_Surat_Dinas.docx) ===== */
        @page {
            size: 8.5in 13in;
            margin-top: 0.394in;
            margin-right: 0.7875in;
            margin-bottom: 1.181in;
            margin-left: 1.181in;
        }

        /*
         * JANGAN memakai `* { margin:0 }` atau `html { margin:0 }`.
         * Di dompdf, style @page dipakai sebagai style elemen <html>, jadi selector
         * yang cocok dengan <html> akan menimpa margin halaman menjadi 0.
         */
        body, div, p, table, td, th, ul, ol, li, h1, h2, h3, h4, hr { margin: 0; padding: 0; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            background: #fff;
            color: #1e293b;
            line-height: 1.5;
        }

        /* ===== KOP SURAT ===== */
        .kop { position: relative; }
        .kop-logo { position: absolute; top: 0; left: 0; width: 1.64cm; height: 2.32cm; }
        .kop-teks { font-family: Arial, Helvetica, sans-serif; font-size: 10pt; line-height: 1.15; color: #000; }
        .kop-teks p { margin: 0; }
        /* garis bawah kop: selebar area teks (sedikit melebihi, seperti border paragraf di Word) */
        .kop-garis { height: 0; border-top: 1.5pt solid #000; margin: 0 -2.5pt 14pt -2.5pt; }

        /* ===== HALAMAN 1 : ISI SURAT PENGANTAR ===== */
        .isi-surat { color: #000; }
        .isi-surat p { margin: 0 0 12pt 0; }
        .isi-surat ul, .isi-surat ol { margin: 0 0 12pt 0; padding-left: 1.25cm; }
        .isi-surat table { border-collapse: collapse; margin: 0 0 12pt 0; }
        .isi-surat td, .isi-surat th { padding: 1pt 2pt; vertical-align: top; }
        .isi-surat h1 { font-size: 16pt; margin: 0 0 8pt 0; }
        .isi-surat h2 { font-size: 14pt; margin: 0 0 8pt 0; }
        .isi-surat h3, .isi-surat h4 { font-size: 12pt; margin: 0 0 8pt 0; }

        /* ===== HALAMAN 2 : LAMPIRAN ===== */
        .halaman-lampiran { page-break-before: always; }

        .judul {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 1px;
            color: #004ac6;
            margin: 0 0 4px 0;
        }

        .table-rincian {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin: 0 0 16px 0;
            table-layout: fixed;
            font-size: 10pt;
        }
        .table-rincian td {
            padding: 3px 5px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .table-rincian .label { font-weight: bold; }
        .table-rincian .col-left  { width: 20%; }
        .table-rincian td:nth-child(2) { width: 30%; }
        .table-rincian .col-right { width: 20%; }
        .table-rincian td:nth-child(4) { width: 30%; }
        .table-rincian tr, .table-aplikasi tr { page-break-inside: avoid; }

        .aplikasi-wrapper { width: 100%; margin: 8px 0 10px 0; page-break-inside: avoid; }
        .aplikasi-title { font-size: 10pt; font-weight: bold; margin-bottom: 4px; }
        .table-aplikasi {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            table-layout: fixed;
            font-size: 9pt;
        }
        .table-aplikasi th {
            padding: 3px 5px;
            border: 1px solid #cbd5e1;
            background: #f1f5f9;
            text-align: center;
            font-weight: bold;
            word-wrap: break-word;
        }
        .table-aplikasi td {
            padding: 3px 5px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* ===== TANDA TANGAN (format BSrE: jabatan, QR, nama bergaris bawah, pangkat, NIP) ===== */
        .ttd-tabel { width: 100%; margin-top: 25px; border-collapse: collapse; page-break-inside: avoid; }
        .ttd-sel {
            width: 50%;
            text-align: left;
            vertical-align: top;
            padding: 0 0.3cm;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12pt;
            line-height: 1.2;
            color: #000;
        }
        .ttd-pembuka { font-weight: normal; }
        .ttd-jabatan { font-weight: bold; text-transform: uppercase; }
        .ttd-qr { margin: 8pt 0 14pt 0.65cm; }
        .ttd-qr img { width: 2.3cm; }
        .ttd-ruang { height: 60pt; }
        .ttd-nama { font-weight: bold; text-decoration: underline; }
        .ttd-pangkat, .ttd-nip { font-weight: normal; }

        /* Catatan BSrE: di kaki setiap halaman (area margin bawah) */
        .catatan-bsre {
            position: fixed;
            left: 0;
            right: 0;
            bottom: -62pt;
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8pt;
            line-height: 1.25;
            color: #000;
        }
    </style>
</head>
<body>

    @if ($adaQr)
        <div class="catatan-bsre">
            Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik<br>
            yang diterbitkan oleh Balai Besar Sertifikasi Elektronik (BSrE), Badan Siber dan Sandi Negara (BSSN).
        </div>
    @endif

    {{-- ================= HALAMAN 1 : KOP + SURAT PENGANTAR ================= --}}
    @include('pdf.partials.kop', ['dokumen' => $dokumen, 'logo' => $logo])

    <div class="isi-surat">
        {!! $dokumen->isi_surat !!}
    </div>


    {{-- ================= HALAMAN 2 : LAMPIRAN (otomatis dari data perangkat) ================= --}}
    <div class="halaman-lampiran">

        @include('pdf.partials.kop', ['dokumen' => $dokumen, 'logo' => $logo])

        <div class="judul">Lampiran Rincian Server</div>

        <table class="table-rincian">
            @foreach ($rincian as $r)
                <tr>
                    <td class="col-left"><span class="label">{{ $r[0] }}</span></td>
                    <td>{{ $fmt($r[1]) }}</td>
                    <td class="col-right">
                        @if ($r[2] !== '')
                            <span class="label">{{ $r[2] }}</span>
                        @endif
                    </td>
                    <td>
                        @if ($r[2] !== '')
                            {{ $fmt($r[3]) }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>

        @if (isset($server->aplikasis) && $server->aplikasis->count() > 0)
            <div class="aplikasi-wrapper">
                <div class="aplikasi-title">Aplikasi Terpasang :</div>
                <table class="table-aplikasi">
                    <thead>
                        <tr>
                            <th>IP Local</th>
                            <th>IP Public</th>
                            <th>Nama Aplikasi</th>
                            <th>URL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($server->aplikasis as $app)
                            <tr>
                                <td>{{ $app->pivot->ip_local ?? '-' }}</td>
                                <td>{{ $app->pivot->ip_public ?? '-' }}</td>
                                <td>{{ $app->nama }}</td>
                                <td>{{ $app->pivot->url ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- TANDA TANGAN: kiri & kanan, dipilih dari Master Data pejabat --}}
        <table class="ttd-tabel">
            {{-- Baris 1: jabatan (huruf besar tebal); "Mengetahui," tidak tebal --}}
            <tr>
                @foreach ($ttd as $t)
                    <td class="ttd-sel">
                        @if (filled($t['judul']))
                            @foreach (preg_split('/\r\n|\r|\n/', trim($t['judul'])) as $baris)
                                @if (filled(trim($baris)))
                                    <div class="{{ preg_match('/^mengetahui,?$/i', trim($baris)) ? 'ttd-pembuka' : 'ttd-jabatan' }}">{{ trim($baris) }}</div>
                                @endif
                            @endforeach
                        @endif
                    </td>
                @endforeach
            </tr>
            {{-- Baris 2: gambar QR Code (atau ruang kosong untuk tanda tangan basah) --}}
            <tr>
                @foreach ($ttd as $t)
                    <td class="ttd-sel">
                        @if (filled($t['nama']))
                            @if ($t['qrcode'])
                                <div class="ttd-qr"><img src="{{ $t['qrcode'] }}" alt="QR Code tanda tangan"></div>
                            @else
                                <div class="ttd-ruang"></div>
                            @endif
                        @endif
                    </td>
                @endforeach
            </tr>
            {{-- Baris 3: nama (tebal, bergaris bawah), pangkat, NIP --}}
            <tr>
                @foreach ($ttd as $t)
                    <td class="ttd-sel">
                        @if (filled($t['nama']))
                            <div class="ttd-nama">{{ $t['nama'] }}</div>
                            @if (filled($t['pangkat']))
                                <div class="ttd-pangkat">{{ \App\Models\MasterData::pangkatTampil($t['pangkat'], $tampilkanGolongan) }}</div>
                            @endif
                            @if (filled($t['nip']))
                                <div class="ttd-nip">NIP. {{ $t['nip'] }}</div>
                            @endif
                        @endif
                    </td>
                @endforeach
            </tr>
        </table>

    </div>

</body>
</html>