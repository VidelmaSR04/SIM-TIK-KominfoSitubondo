<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Rincian Server - {{ $server->nama_perangkat }}</title>

    <style>

        /* =========================================================
           PENGATURAN HALAMAN
           ========================================================= */

        @page {
            size: 8.5in 13in;

            margin-top: 0.394in;
            margin-right: 0.7875in;
            margin-bottom: 1.181in;
            margin-left: 1.181in;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            padding: 0;
            background: #fff;
            color: #1e293b;
            line-height: 1.5;
        }


        /* =========================================================
           KOP SURAT
           ========================================================= */

        .kop-image {
            width: 100%;
            display: block;
            margin-bottom: 12px;
        }


        /* =========================================================
           AREA ISI SURAT
           
           Dibuat mengikuti garis horizontal pada kop surat.
           Semua isi halaman 2 berada di dalam area ini.
           ========================================================= */

        .content-surat {
            width: 77.8%;
            margin-left: 13.4%;
            margin-right: 8.8%;
        }


        /* =========================================================
           PEMISAH HALAMAN
           ========================================================= */

        .page-break {
            page-break-after: always;
            break-after: page;
        }


        /* =========================================================
           HALAMAN 1
           ========================================================= */

        .halaman-pertama {
            min-height: 100%;
        }


        /* =========================================================
           JUDUL LAMPIRAN
           ========================================================= */

        .judul {
            text-align: center;

            font-family: 'Times New Roman', Times, serif;

            font-size: 14pt;

            font-weight: bold;

            letter-spacing: 1px;

            color: #004ac6;

            margin: 0 0 4px 0;

            text-transform: none;
        }


        /* =========================================================
           TABEL RINCIAN SERVER
           ========================================================= */

        .table-rincian {
            width: 100%;

            border-collapse: collapse;

            border-spacing: 0;

            margin: 0 0 16px 0;

            table-layout: fixed;

            font-family: 'Times New Roman', Times, serif;

            font-size: 10pt;
        }

        .table-rincian td {
            padding: 3px 5px;

            border: 1px solid #cbd5e1;

            vertical-align: top;

            word-wrap: break-word;

            overflow-wrap: break-word;
        }


        /* =========================================================
           KOLOM LABEL
           ========================================================= */

        .table-rincian .label {
            font-weight: bold;
        }


        /* Kolom 1 - Label kiri */
        .table-rincian .col-left {
            width: 20%;
        }


        /* Kolom 2 - Nilai kiri */
        .table-rincian td:nth-child(2) {
            width: 30%;
        }


        /* Kolom 3 - Label kanan */
        .table-rincian .col-right {
            width: 20%;
        }


        /* Kolom 4 - Nilai kanan */
        .table-rincian td:nth-child(4) {
            width: 30%;
        }


        /* =========================================================
           APLIKASI TERPASANG
           ========================================================= */

        .aplikasi-wrapper {
            width: 100%;

            margin: 8px 0 10px 0;

            page-break-inside: avoid;

            break-inside: avoid;
        }

        .aplikasi-title {
            font-size: 10pt;

            font-weight: bold;

            margin-bottom: 4px;
        }

        .table-aplikasi {
            width: 100%;

            border-collapse: collapse;

            border-spacing: 0;

            table-layout: fixed;

            font-family: 'Times New Roman', Times, serif;

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


        /* =========================================================
           TANDA TANGAN
           ========================================================= */

        .ttd-wrapper {
            width: 100%;

            margin-top: 25px;

            text-align: right;

            page-break-inside: avoid;

            break-inside: avoid;
        }

        .ttd-box {
            display: inline-block;

            width: 220px;

            max-width: 100%;

            text-align: center;

            vertical-align: top;
        }

        .ttd-box .ttd-label {
            font-size: 10pt;

            font-weight: bold;

            line-height: 1.25;
        }

        .ttd-box .ttd-ruang {
            height: 65px;
        }

        .ttd-box .ttd-garis {
            width: 100%;

            margin: 5px 0 0 0;

            border-top: 1px solid #1e293b;
        }

        .ttd-box .ttd-nama {
            font-size: 10pt;

            font-weight: bold;

            margin: 2px 0;
        }

        .ttd-box .ttd-nip {
            font-size: 9pt;

            color: #475569;
        }


        /* =========================================================
           FOOTER
           ========================================================= */

        .footer {
            width: 100%;

            margin-top: 20px;

            padding-top: 8px;

            border-top: 1px solid #cbd5e1;

            font-family: 'Times New Roman', Times, serif;

            font-size: 8pt;

            color: #64748b;
        }

        .footer-table {
            width: 100%;

            border-collapse: collapse;

            border-spacing: 0;
        }

        .footer-table td {
            padding: 0;

            border: none;

            vertical-align: top;
        }

        .footer-left {
            width: 60%;

            text-align: left;
        }

        .footer-right {
            width: 40%;

            text-align: right;
        }


        /* =========================================================
           MENCEGAH TABEL TERPOTONG
           ========================================================= */

        .table-rincian tr,
        .table-aplikasi tr {
            page-break-inside: avoid;

            break-inside: avoid;
        }


        /* =========================================================
           PRINT
           ========================================================= */

        @media print {

            body {
                background: #fff;
            }

            .page-break {
                page-break-after: always;
            }

        }

    </style>
</head>


<body>


    <!-- =========================================================
         HALAMAN 1
         KOP SURAT SAJA
         ========================================================= -->

    <div class="halaman-pertama">

        <img
            src="{{ public_path('img/kop-surat.png') }}"
            class="kop-image"
            alt="Kop Surat Dinas Kominfo Situbondo"
        >

    </div>


    <!-- =========================================================
         PAKSA PINDAH KE HALAMAN 2
         ========================================================= -->

    <div class="page-break"></div>


    <!-- =========================================================
         HALAMAN 2
         KOP SURAT
         ========================================================= -->

    <img
        src="{{ public_path('img/kop-surat.png') }}"
        class="kop-image"
        alt="Kop Surat Dinas Kominfo Situbondo"
    >


    <!-- =========================================================
         SELURUH ISI MENGIKUTI LEBAR GARIS KOP
         ========================================================= -->

    <div class="content-surat">


        <!-- =====================================================
             JUDUL
             ===================================================== -->

        <div class="judul">
            Lampiran Rincian Server
        </div>


        <!-- =====================================================
             TABEL RINCIAN SERVER
             ===================================================== -->

        <table class="table-rincian">

            <!-- BARIS 1 -->
            <tr>

                <td class="col-left">
                    <span class="label">
                        Nama Server
                    </span>
                </td>

                <td>
                    {{ $server->nama_perangkat }}
                </td>

                <td class="col-right">
                    <span class="label">
                        Tipe Perangkat
                    </span>
                </td>

                <td>
                    {{ $server->tipe_perangkat ?? '-' }}
                </td>

            </tr>


            <!-- BARIS 2 -->
            <tr>

                <td class="col-left">
                    <span class="label">
                        Jenis Perangkat
                    </span>
                </td>

                <td>
                    {{ $server->jenis_perangkat ?? '-' }}
                </td>

                <td class="col-right">
                    <span class="label">
                        Serial Number
                    </span>
                </td>

                <td>
                    {{ $server->serial_number ?? '-' }}
                </td>

            </tr>


            <!-- BARIS 3 -->
            <tr>

                <td class="col-left">
                    <span class="label">
                        Merk Perangkat
                    </span>
                </td>

                <td>
                    {{ $server->merk_perangkat ?? '-' }}
                </td>

                <td class="col-right">
                    <span class="label">
                        Type
                    </span>
                </td>

                <td>
                    {{ $server->type ?? '-' }}
                </td>

            </tr>


            <!-- BARIS 4 -->
            <tr>

                <td class="col-left">
                    <span class="label">
                        Kondisi Tipe
                    </span>
                </td>

                <td>
                    {{ $server->kondisi_tipe ?? '-' }}
                </td>

                <td class="col-right">
                    <span class="label">
                        Kondisi Status
                    </span>
                </td>

                <td>
                    {{ $server->kondisi_status ?? '-' }}
                </td>

            </tr>


            <!-- BARIS 5 -->
            <tr>

                <td class="col-left">
                    <span class="label">
                        Spesifikasi
                    </span>
                </td>

                <td>
                    {{ $server->spesifikasi ?? '-' }}
                </td>

                <td class="col-right">
                    <span class="label">
                        Status Kepemilikan
                    </span>
                </td>

                <td>
                    {{ $server->status_kepemilikan ?? '-' }}
                </td>

            </tr>


            <!-- BARIS 6 -->
            <tr>

                <td class="col-left">
                    <span class="label">
                        Pemilik Perangkat
                    </span>
                </td>

                <td>
                    {{ $server->pemilik_perangkat ?? '-' }}
                </td>

                <td class="col-right">
                    <span class="label">
                        IP Server
                    </span>
                </td>

                <td>
                    {{ $server->ip_server ?? '-' }}
                </td>

            </tr>


            <!-- BARIS 7 -->
            <tr>

                <td class="col-left">
                    <span class="label">
                        IP VPS
                    </span>
                </td>

                <td>
                    {{ $server->ip_vps ?? '-' }}
                </td>

                <td class="col-right">
                    <span class="label">
                        Status Server
                    </span>
                </td>

                <td>
                    {{ $server->status ?? '-' }}
                </td>

            </tr>


            <!-- BARIS 8 -->
            <tr>

                <td class="col-left">
                    <span class="label">
                        Ukuran HDD
                    </span>
                </td>

                <td>
                    {{ $server->ukuran_hdd ?? '-' }}
                </td>

                <td class="col-right">
                    <span class="label">
                        Ukuran RAM
                    </span>
                </td>

                <td>
                    {{ $server->ukuran_ram ?? '-' }}
                </td>

            </tr>


            <!-- BARIS 9 -->
            <tr>

                <td class="col-left">
                    <span class="label">
                        Nomor RACK
                    </span>
                </td>

                <td>
                    {{ $server->nomor_rack ?? '-' }}
                </td>

                <td class="col-right">
                    <span class="label">
                        Jumlah Core
                    </span>
                </td>

                <td>
                    {{ $server->jumlah_core ?? '-' }}
                </td>

            </tr>


            <!-- BARIS 10 -->
            <tr>

                <td class="col-left">
                    <span class="label">
                        Peruntukan
                    </span>
                </td>

                <td>
                    {{ $server->peruntukan ?? '-' }}
                </td>

                <td class="col-right">
                    <span class="label">
                        Nama Pengirim
                    </span>
                </td>

                <td>
                    {{ $server->nama_pengirim ?? '-' }}
                </td>

            </tr>


            <!-- BARIS 11 -->
            <tr>

                <td class="col-left">
                    <span class="label">
                        Nama Penerima
                    </span>
                </td>

                <td>
                    {{ $server->nama_penerima ?? '-' }}
                </td>

                <td class="col-right">
                    <span class="label">
                        Jam Pengisian
                    </span>
                </td>

                <td>
                    {{
                        $server->jam_pengisian
                        ? \Carbon\Carbon::parse($server->jam_pengisian)->format('d M Y, H:i')
                        : '-'
                    }}
                </td>

            </tr>


            <!-- BARIS 12 -->
            <tr>

                <td class="col-left">
                    <span class="label">
                        Tanggal Dibuat
                    </span>
                </td>

                <td>
                    {{
                        $server->created_at
                        ? $server->created_at->format('d M Y, H:i')
                        : '-'
                    }}
                </td>

                <td class="col-right">
                    <span class="label">
                        Terakhir Update
                    </span>
                </td>

                <td>
                    {{
                        $server->updated_at
                        ? $server->updated_at->format('d M Y, H:i')
                        : '-'
                    }}
                </td>

            </tr>


            <!-- BARIS 13 -->
            <tr>

                <td class="col-left">
                    <span class="label">
                        ID Server
                    </span>
                </td>

                <td>
                    {{ $server->id }}
                </td>

                <td class="col-right"></td>

                <td></td>

            </tr>

        </table>


        <!-- =====================================================
             APLIKASI TERPASANG
             ===================================================== -->

        @if(isset($server->aplikasis) && $server->aplikasis->count() > 0)

            <div class="aplikasi-wrapper">

                <div class="aplikasi-title">
                    Aplikasi Terpasang :
                </div>


                <table class="table-aplikasi">

                    <thead>

                        <tr>

                            <th>
                                IP Local
                            </th>

                            <th>
                                IP Public
                            </th>

                            <th>
                                Nama Aplikasi
                            </th>

                            <th>
                                URL
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($server->aplikasis as $app)

                            <tr>

                                <td>
                                    {{ $app->pivot->ip_local ?? '-' }}
                                </td>

                                <td>
                                    {{ $app->pivot->ip_public ?? '-' }}
                                </td>

                                <td>
                                    {{ $app->nama }}
                                </td>

                                <td>
                                    {{ $app->pivot->url ?? '-' }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif


        <!-- =====================================================
             TANDA TANGAN
             ===================================================== -->

        <div class="ttd-wrapper">

            <div class="ttd-box">

                <div class="ttd-label">
                    Mengetahui,
                </div>

                <div class="ttd-label">
                    Kepala Dinas Komunikasi dan Informatika<br>
                    Kabupaten Situbondo
                </div>


                <!-- RUANG TANDA TANGAN -->
                <div class="ttd-ruang"></div>


                <!-- GARIS TTD -->
                <div class="ttd-garis"></div>


                <!-- NAMA -->
                <div class="ttd-nama">
                    Drs. Sugiyono, M.Pd.I
                </div>


                <!-- NIP -->
                <div class="ttd-nip">
                    NIP. 19680312 199403 1 001
                </div>

            </div>

        </div>


        <!-- =====================================================
             FOOTER
             ===================================================== -->

        <div class="footer">

            <table class="footer-table">

                <tr>

                    <td class="footer-left">
                        Dokumen ini dicetak dari SIM TIK – Kominfo Situbondo
                    </td>

                    <td class="footer-right">
                        Tanggal Cetak :
                        {{ now()->format('d M Y H:i') }}
                    </td>

                </tr>

            </table>

        </div>


    </div>


</body>
</html>