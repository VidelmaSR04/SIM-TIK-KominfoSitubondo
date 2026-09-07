<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rincian Server - {{ $server->nama_perangkat }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            padding: 25px 30px;
            background: #fff;
            color: #1e293b;
            line-height: 1.5;
        }

        /* === KOP SURAT RESMI (berbasis tabel, lebih stabil di DomPDF) === */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .kop-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .kop-logo {
            width: 90px;
            text-align: center;
        }
        .kop-logo img {
            width: 72px;
        }
        .kop-teks {
            text-align: center;
        }
        .kop-teks .baris1 {
            font-family: Arial, sans-serif;
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 0;
            color: #1e293b;
        }
        .kop-teks .baris2 {
            font-family: Arial, sans-serif;
            font-size: 16pt;
            font-weight: bold;
            margin: 2px 0;
            color: #1e293b;
        }
        .kop-teks .alamat {
            font-family: Arial, sans-serif;
            font-size: 9.5pt;
            margin: 1px 0;
            color: #1e293b;
        }
        .kop-garis-tebal {
            border-top: 3px solid #000;
            margin-top: 8px;
        }
        .kop-garis-tipis {
            border-top: 1px solid #000;
            margin-top: 2px;
            margin-bottom: 16px;
        }

        /* === JUDUL === */
        .judul {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: 2px;
            color: #004ac6;
            margin: 12px 0 18px 0;
            text-transform: uppercase;
        }

        /* === TABEL RINCIAN 2 KOLOM === */
        .table-rincian {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 11pt;
        }
        .table-rincian td {
            padding: 4px 8px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }
        .table-rincian .label {
            font-weight: 600;
            background-color: #f1f5f9;
            width: 20%;
        }
        .table-rincian .value {
            width: 30%;
        }
        .table-rincian .col-left { width: 50%; }
        .table-rincian .col-right { width: 50%; }

        /* === TANDA TANGAN (RATA KANAN) === */
        .ttd-wrapper {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end; /* ← ini yang membuat rata kanan */
        }
        .ttd-box {
            text-align: center;
            width: 240px;
        }
        .ttd-box .ttd-garis {
            margin: 5px 0 0 0;
            border-top: 1px solid #1e293b;
            width: 100%;
        }
        .ttd-box .ttd-label {
            font-size: 11pt;
            font-weight: 600;
        }
        .ttd-box .ttd-nama {
            font-size: 12pt;
            font-weight: bold;
            margin: 2px 0;
        }
        .ttd-box .ttd-nip {
            font-size: 10pt;
            color: #475569;
        }

        /* === FOOTER === */
        .footer {
            margin-top: 20px;
            border-top: 1px solid #cbd5e1;
            padding-top: 8px;
            display: flex;
            justify-content: space-between;
            font-size: 9pt;
            color: #64748b;
        }
    </style>
</head>
<body>

    <!-- ========== KOP SURAT RESMI ========== -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                <img src="{{ public_path('img/logo-situbondo.png') }}" alt="Logo Kabupaten Situbondo">
            </td>
            <td class="kop-teks">
                <p class="baris1">PEMERINTAH KABUPATEN SITUBONDO</p>
                <p class="baris2">DINAS KOMUNIKASI DAN INFORMATIKA</p>
                <p class="alamat">Jalan PB. Sudirman No. 1, Kabupaten Situbondo, Jawa Timur 68312,</p>
                <p class="alamat">Telepon (0338) 674-096,</p>
                <p class="alamat">Laman kominfo.situbondokab.go.id, Pos-el kominfo@situbondokab.go.id</p>
            </td>
        </tr>
    </table>
    <div class="kop-garis-tebal"></div>
    <div class="kop-garis-tipis"></div>

    <!-- ========== JUDUL ========== -->
    <div class="judul">Rincian Server</div>

    <!-- ========== TABEL RINCIAN 2 KOLOM ========== -->
    <table class="table-rincian">
        <!-- BARIS 1 -->
        <tr>
            <td class="col-left"><span class="label">Nama Server</span></td>
            <td class="col-left value">{{ $server->nama_perangkat }}</td>
            <td class="col-right"><span class="label">Tipe Perangkat</span></td>
            <td class="col-right value">{{ $server->tipe_perangkat ?? '-' }}</td>
        </tr>
        <!-- BARIS 2 -->
        <tr>
            <td class="col-left"><span class="label">Jenis Perangkat</span></td>
            <td class="col-left value">{{ $server->jenis_perangkat ?? '-' }}</td>
            <td class="col-right"><span class="label">Serial Number</span></td>
            <td class="col-right value">{{ $server->serial_number ?? '-' }}</td>
        </tr>
        <!-- BARIS 3 -->
        <tr>
            <td class="col-left"><span class="label">Merk Perangkat</span></td>
            <td class="col-left value">{{ $server->merk_perangkat ?? '-' }}</td>
            <td class="col-right"><span class="label">Type</span></td>
            <td class="col-right value">{{ $server->type ?? '-' }}</td>
        </tr>
        <!-- BARIS 4 -->
        <tr>
            <td class="col-left"><span class="label">Kondisi Tipe</span></td>
            <td class="col-left value">{{ $server->kondisi_tipe ?? '-' }}</td>
            <td class="col-right"><span class="label">Kondisi Status</span></td>
            <td class="col-right value">{{ $server->kondisi_status ?? '-' }}</td>
        </tr>
        <!-- BARIS 5 -->
        <tr>
            <td class="col-left"><span class="label">Spesifikasi</span></td>
            <td class="col-left value">{{ $server->spesifikasi ?? '-' }}</td>
            <td class="col-right"><span class="label">Status Kepemilikan</span></td>
            <td class="col-right value">{{ $server->status_kepemilikan ?? '-' }}</td>
        </tr>
        <!-- BARIS 6 -->
        <tr>
            <td class="col-left"><span class="label">Pemilik Perangkat</span></td>
            <td class="col-left value">{{ $server->pemilik_perangkat ?? '-' }}</td>
            <td class="col-right"><span class="label">IP Server</span></td>
            <td class="col-right value">{{ $server->ip_server ?? '-' }}</td>
        </tr>
        <!-- BARIS 7 -->
        <tr>
            <td class="col-left"><span class="label">IP VPS</span></td>
            <td class="col-left value">{{ $server->ip_vps ?? '-' }}</td>
            <td class="col-right"><span class="label">Status Server</span></td>
            <td class="col-right value">{{ $server->status ?? '-' }}</td>
        </tr>
        <!-- BARIS 8 -->
        <tr>
            <td class="col-left"><span class="label">Ukuran HDD</span></td>
            <td class="col-left value">{{ $server->ukuran_hdd ?? '-' }}</td>
            <td class="col-right"><span class="label">Ukuran RAM</span></td>
            <td class="col-right value">{{ $server->ukuran_ram ?? '-' }}</td>
        </tr>
        <!-- BARIS 9 -->
        <tr>
            <td class="col-left"><span class="label">Nomor RACK</span></td>
            <td class="col-left value">{{ $server->nomor_rack ?? '-' }}</td>
            <td class="col-right"><span class="label">Jumlah Core</span></td>
            <td class="col-right value">{{ $server->jumlah_core ?? '-' }}</td>
        </tr>
        <!-- BARIS 10 -->
        <tr>
            <td class="col-left"><span class="label">Peruntukan</span></td>
            <td class="col-left value">{{ $server->peruntukan ?? '-' }}</td>
            <td class="col-right"><span class="label">Nama Pengirim</span></td>
            <td class="col-right value">{{ $server->nama_pengirim ?? '-' }}</td>
        </tr>
        <!-- BARIS 11 -->
        <tr>
            <td class="col-left"><span class="label">Nama Penerima</span></td>
            <td class="col-left value">{{ $server->nama_penerima ?? '-' }}</td>
            <td class="col-right"><span class="label">Jam Pengisian</span></td>
            <td class="col-right value">{{ $server->jam_pengisian ? \Carbon\Carbon::parse($server->jam_pengisian)->format('d M Y, H:i') : '-' }}</td>
        </tr>
        <!-- BARIS 12 -->
        <tr>
            <td class="col-left"><span class="label">Tanggal Dibuat</span></td>
            <td class="col-left value">{{ $server->created_at->format('d M Y, H:i') }}</td>
            <td class="col-right"><span class="label">Terakhir Update</span></td>
            <td class="col-right value">{{ $server->updated_at->format('d M Y, H:i') }}</td>
        </tr>
        <!-- BARIS 13 -->
        <tr>
            <td class="col-left"><span class="label">ID Server</span></td>
            <td class="col-left value">{{ $server->id }}</td>
            <td class="col-right"><span class="label"></span></td>
            <td class="col-right value"></td>
        </tr>
    </table>

    <!-- ========== TABEL APLIKASI TERPASANG ========== -->
    @if(isset($server->aplikasis) && $server->aplikasis->count() > 0)
    <div style="margin: 8px 0 10px 0;">
        <strong style="font-size:11pt;">Aplikasi Terpasang :</strong>
        <table style="width:100%; border-collapse:collapse; font-size:10pt; margin-top:4px;">
            <thead>
                <tr style="background:#f1f5f9; border:1px solid #cbd5e1;">
                    <th style="padding:4px 8px; border:1px solid #cbd5e1;">IP Local</th>
                    <th style="padding:4px 8px; border:1px solid #cbd5e1;">IP Public</th>
                    <th style="padding:4px 8px; border:1px solid #cbd5e1;">Nama Aplikasi</th>
                    <th style="padding:4px 8px; border:1px solid #cbd5e1;">URL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($server->aplikasis as $app)
                <tr>
                    <td style="padding:4px 8px; border:1px solid #cbd5e1;">{{ $app->pivot->ip_local ?? '-' }}</td>
                    <td style="padding:4px 8px; border:1px solid #cbd5e1;">{{ $app->pivot->ip_public ?? '-' }}</td>
                    <td style="padding:4px 8px; border:1px solid #cbd5e1;">{{ $app->nama }}</td>
                    <td style="padding:4px 8px; border:1px solid #cbd5e1;">{{ $app->pivot->url ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- ========== TANDA TANGAN (RATA KANAN) ========== -->
    <div class="ttd-wrapper">
        <div class="ttd-box">
            <div class="ttd-label">Mengetahui,</div>
            <div class="ttd-label">Kepala Dinas Komunikasi dan Informatika Kabupaten Situbondo</div>

            <div style="height:80px;"></div> <!-- ruang tanda tangan -->

            <div class="ttd-garis"></div>
            <div class="ttd-nama">Drs. Sugiyono, M.Pd.I</div>
            <div class="ttd-nip">NIP. 19680312 199403 1 001</div>
        </div>
    </div>

    <!-- ========== FOOTER ========== -->
    <div class="footer">
        <span>Dokumen ini dicetak dari SIM TIK – Kominfo Situbondo</span>
        <span>Tanggal Cetak : {{ now()->format('d M Y H:i') }}</span>
    </div>

</body>
</html>