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

    $ttd = [
        'kiri' => [
            'judul'   => $dokumen->ttd_kiri_judul,
            'nama'    => $dokumen->ttd_kiri_nama,
            'pangkat' => $dokumen->ttd_kiri_pangkat,
            'nip'     => $dokumen->ttd_kiri_nip,
        ],
        'kanan' => [
            'judul'   => $dokumen->ttd_kanan_judul,
            'nama'    => $dokumen->ttd_kanan_nama,
            'pangkat' => $dokumen->ttd_kanan_pangkat,
            'nip'     => $dokumen->ttd_kanan_nip,
        ],
    ];
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
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12pt;
            background: #fff;
            color: #1e293b;
            line-height: 1.5;
        }

        /* Semua teks dokumen memakai Arial (termasuk isi lama yang tersimpan dengan font lain) */
        .kop-teks, .kop-teks *, .isi-surat, .isi-surat * { font-family: Arial, Helvetica, sans-serif !important; }

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
        .halaman-lampiran { page-break-before: always; padding-top: 1.5cm; }

        /* Kepala lampiran (tanpa kop): blok rata kiri di separuh kanan, bergaris bawah, lalu judul di tengah */
        .halaman-lampiran, .halaman-lampiran * { font-size: 12pt !important; }
        .lampiran-kepala { width: 100%; border-collapse: collapse; margin: 0 0 20pt 0; color: #000; }
        .lampiran-kepala td { padding: 0; vertical-align: top; }
        .lampiran-kepala .lk-spasi { width: 51%; }
        .lampiran-repeat { width: 100%; border-collapse: collapse; border-spacing: 0; }
        .lampiran-repeat > tbody > tr > td { padding: 0; border: 0; vertical-align: top; }
        .lampiran-repeat > tbody > tr.lampiran-ttd-row { page-break-inside: avoid; }
        .lk-kotak {
            width: 92%;
            padding-bottom: 1.5pt;
            border-bottom: 0.75pt solid #000;
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.25;
        }
        .lk-dalam { border-collapse: collapse; width: 100%; }
        .lk-dalam td { padding: 0; vertical-align: top; line-height: 1.25; }
        .lk-dalam .lk-label { width: 2.7cm; }

        .judul {
            width: 100%;
            text-align: center !important;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            text-transform: uppercase;
            color: #000;
            margin: 0 0 12pt 0;
        }

        .table-rincian {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin: 0 0 16px 0;
            table-layout: fixed;
            font-size: 11pt !important;
        }
        .table-rincian * { font-size: 11pt !important; }
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

        /* ===== TANDA TANGAN (jabatan, QR, nama bergaris bawah, pangkat, NIP) ===== */
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
        .ttd-ruang { height: 115.2pt; }
        .ttd-nama { font-weight: bold; text-decoration: underline; }
        .ttd-pangkat, .ttd-nip { font-weight: normal; }
    </style>
</head>
<body>

    {{-- ================= HALAMAN 1 : KOP + SURAT PENGANTAR ================= --}}
    @include('pdf.partials.kop', ['dokumen' => $dokumen, 'logo' => $logo])

    <div class="isi-surat">
        <p style="text-align:right;">Situbondo, {{ $dokumen->surat_tanggal }}</p>
        <table style="width:100%;border-collapse:collapse;margin-bottom:12pt;">
            <tbody>
                <tr><td style="width:20%;">Nomor</td><td style="width:3%;">:</td><td>{{ $dokumen->surat_nomor }}</td></tr>
                <tr><td>Sifat</td><td>:</td><td>{{ $dokumen->surat_sifat }}</td></tr>
                <tr><td>Lampiran</td><td>:</td><td>{{ $dokumen->surat_lampiran }}</td></tr>
                <tr><td>Hal</td><td>:</td><td>{{ $dokumen->surat_hal }}</td></tr>
            </tbody>
        </table>
        <p>Yth.&nbsp;&nbsp;&nbsp;{{ $dokumen->surat_yth }}</p>
        {!! $dokumen->isi_surat !!}
    </div>


    {{-- ================= HALAMAN 2 : LAMPIRAN ================= --}}
    <div class="halaman-lampiran">

        {{-- Halaman lampiran tidak memakai kop surat --}}
        <table class="lampiran-repeat">
            <tbody>
                <tr>
                    <td>
                        <table class="lampiran-kepala">
                            <tr>
                                <td class="lk-spasi"></td>
                                <td>
                                    <div class="lk-kotak">
                                        <div>Lampiran I</div>
                                        <table class="lk-dalam">
                                            <tr>
                                                <td class="lk-label">Surat Nomor</td>
                                                <td>: {{ $dokumen->surat_nomor }}</td>
                                            </tr>
                                            <tr>
                                                <td class="lk-label">Tanggal</td>
                                                <td>: {{ $dokumen->surat_tanggal }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
        <tr>
            <td>
                <div class="judul">Rincian Server</div>

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
            </td>
        </tr>

        @if (isset($server->aplikasis) && $server->aplikasis->count() > 0)
            <tr>
                <td>
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
                </td>
            </tr>
        @endif

        {{-- TANDA TANGAN: kiri & kanan, dipilih dari Master Data pejabat --}}
        <tr class="lampiran-ttd-row">
            <td>
                <table class="ttd-tabel">
                    {{-- Baris 1: jabatan pejabat --}}
                    <tr>
                        @foreach ($ttd as $t)
                            <td class="ttd-sel">
                                @if (filled($t['judul']))
                                    <div class="ttd-pembuka">Mengetahui,</div>
                                    @foreach (preg_split('/\r\n|\r|\n/', trim($t['judul'])) as $baris)
                                        @if (filled(trim($baris)) && !preg_match('/^mengetahui,?$/i', trim($baris)))
                                            <div class="ttd-jabatan">{{ trim($baris) }}</div>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    {{-- Baris 2: ruang delapan baris untuk QR TTD digital yang akan diisi pejabat --}}
                    <tr>
                        @foreach ($ttd as $t)
                            <td class="ttd-sel">
                                @if (filled($t['nama']))
                                    <div class="ttd-ruang"></div>
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
                                        <div class="ttd-pangkat">{{ \App\Models\MasterData::pangkatTampil($t['pangkat']) }}</div>
                                    @endif
                                    @if (filled($t['nip']))
                                        <div class="ttd-nip">NIP. {{ $t['nip'] }}</div>
                                    @endif
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </table>
            </td>
        </tr>
            </tbody>
        </table>

    </div>

</body>
</html>