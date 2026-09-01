@extends('layouts.app')

@push('styles')
    <style>
        .input-group {
            display: flex;
            align-items: stretch;
            border: 1px solid #CBD5E1;
            border-radius: 0.375rem;
            overflow: hidden;
            background: white;
            transition: all 0.2s;
        }

        .input-group:focus-within {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }

        .input-group-addon {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #F1F5F9;
            border-right: 1px solid #CBD5E1;
            padding: 0 12px;
            color: #64748B;
        }

        .input-group-input {
            flex: 1;
            border: none;
            padding: 8px 12px;
            outline: none;
            font-size: 14px;
        }

        .form-label {
            font-weight: 600;
            color: #191c1e;
            margin-bottom: 4px;
            display: block;
            font-size: 14px;
        }

        .required-star {
            color: #ef4444;
            margin-left: 2px;
            font-weight: 700;
        }

        .standard-input {
            width: 100%;
            border: 1px solid #CBD5E1;
            border-radius: 0.375rem;
            padding: 8px 12px;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
        }

        .standard-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }

        .standard-select {
            width: 100%;
            border: 1px solid #CBD5E1;
            border-radius: 0.375rem;
            padding: 8px 12px;
            font-size: 14px;
            outline: none;
            background-color: white;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748B%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
            background-repeat: no-repeat;
            background-position: right 12px top 50%;
            background-size: 10px auto;
        }

        .standard-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }

        .form-error {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 4px;
        }

        .helper-text {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 4px;
        }

        /* Tambahan style untuk upload gambar */
        .upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #f9fafb;
        }
        .upload-area:hover {
            border-color: #2563eb;
            background: #eff6ff;
        }
        .upload-area.dragover {
            border-color: #2563eb;
            background: #dbeafe;
        }
        .preview-image {
            max-height: 200px;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
        }
        .remove-image-btn {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .remove-image-btn:hover {
            background: #dc2626;
            transform: scale(1.1);
        }
        .image-preview-container {
            position: relative;
            display: inline-block;
        }
        .loading-spinner {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* ============= STEPPER (naik-turun, pilihan terkunci) ============= */
        .stepper {
            display: flex;
            align-items: stretch;
            border: 1px solid #CBD5E1;
            border-radius: 0.375rem;
            overflow: hidden;
            background: white;
            transition: all 0.2s;
        }
        .stepper:focus-within {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }
        .stepper-value {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 8px 12px;
            font-size: 14px;
            color: #191c1e;
            user-select: none;
        }
        .stepper-arrows {
            display: flex;
            flex-direction: column;
            border-left: 1px solid #CBD5E1;
            width: 34px;
        }
        .stepper-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #F1F5F9;
            border: none;
            cursor: pointer;
            color: #64748B;
            transition: background 0.15s, color 0.15s;
            padding: 0;
        }
        .stepper-btn:first-child {
            border-bottom: 1px solid #CBD5E1;
        }
        .stepper-btn:hover:not(:disabled) {
            background: #E2E8F0;
            color: #2563eb;
        }
        .stepper-btn:disabled {
            opacity: 0.35;
            cursor: not-allowed;
        }
        .stepper-btn svg {
            width: 14px;
            height: 14px;
        }
        .stepper-disabled {
            opacity: 0.5;
            pointer-events: none;
            background: #F8FAFC;
        }

        /* ============= SEARCHABLE SELECT (dropdown dengan pencarian) ============= */
        .searchable-select {
            position: relative;
        }
        .searchable-select-trigger {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #CBD5E1;
            border-radius: 0.375rem;
            padding: 8px 12px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            text-align: left;
            color: #191c1e;
            transition: all 0.2s;
        }
        .searchable-select-trigger:focus,
        .searchable-select.is-open .searchable-select-trigger {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }
        .searchable-select-trigger:disabled {
            cursor: not-allowed;
            color: #94A3B8;
        }
        .searchable-select-trigger svg {
            width: 16px;
            height: 16px;
            color: #64748B;
            flex-shrink: 0;
            margin-left: 8px;
        }
        .searchable-select-panel {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #CBD5E1;
            border-radius: 0.375rem;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15), 0 8px 10px -6px rgba(0,0,0,0.1);
            z-index: 50;
            overflow: hidden;
        }
        .searchable-select-panel.hidden {
            display: none;
        }
        .searchable-select-search {
            width: 100%;
            border: none;
            border-bottom: 1px solid #E2E8F0;
            padding: 10px 12px;
            font-size: 14px;
            outline: none;
        }
        .searchable-select-search:focus {
            border-bottom-color: #2563eb;
        }
        .searchable-select-options {
            max-height: 240px;
            overflow-y: auto;
        }
        .searchable-select-option {
            padding: 9px 12px;
            font-size: 14px;
            color: #191c1e;
            cursor: pointer;
        }
        .searchable-select-option.is-active {
            background: #2563eb;
            color: white;
        }
        .searchable-select-option.is-selected:not(.is-active) {
            background: #EFF6FF;
            color: #2563eb;
            font-weight: 600;
        }
        .searchable-select-empty {
            padding: 10px 12px;
            font-size: 14px;
            color: #94A3B8;
            text-align: center;
        }
    </style>
@endpush

@section('content')
    @php
        $isEdit = isset($server);
        $route = $isEdit ? route('server.update', $server->id) : route('server.store');
        $method = $isEdit ? 'PUT' : 'POST';
        $pageTitle = $isEdit ? 'Edit Perangkat Server' : 'Buat Perangkat Server Baru';
        $breadcrumbTitle = $isEdit ? 'Edit Perangkat' : 'Buat Perangkat Baru';

        // Ubah nilai lama seperti "128 GB" / "1.5 TB" jadi angka GB murni, dan pastikan selalu ada nilai (minimal default)
        if (!function_exists('simtik_extract_gb')) {
            function simtik_extract_gb($value, $default, $step = 1, $max = null) {
                $gb = $default;
                if ($value && preg_match('/([\d.]+)\s*(GB|TB)/i', $value, $m)) {
                    $num = (float) $m[1];
                    $unit = strtoupper($m[2]);
                    $gb = $unit === 'TB' ? (int) round($num * 1024) : (int) round($num);
                }
                // Bulatkan ke kelipatan terdekat, jaga tetap >= default (minimum) dan <= max (jika ada)
                $gb = $default + round(($gb - $default) / $step) * $step;
                if ($gb < $default) $gb = $default;
                if ($max && $gb > $max) $gb = $max;
                return (int) $gb;
            }
        }

        $ramGB = simtik_extract_gb(old('ukuran_ram', isset($server) ? $server->ukuran_ram : null), 8, 8);
        $hddGB = simtik_extract_gb(old('ukuran_hdd', isset($server) ? $server->ukuran_hdd : null), 256, 256, 20480);

        // Daftar OPD/Dinas untuk Pemilik Perangkat (dipakai saat Status Kepemilikan = Colocation)
        $opdOptions = [
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
            'Dinas Koperasi dan Usaha Mikro',
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

        $pemilikSelected = old('pemilik_perangkat', $server->pemilik_perangkat ?? $opdOptions[0]);
        if (!in_array($pemilikSelected, $opdOptions)) { $pemilikSelected = $opdOptions[0]; }
    @endphp

    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" class="flex text-sm text-secondary mb-4 font-body-md">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li><a class="hover:text-primary transition-colors" href="{{ route('manajemen-server') }}">Dashboard</a></li>
            <li><span class="material-symbols-outlined text-sm mx-1">chevron_right</span><a
                    class="hover:text-primary transition-colors" href="{{ route('server.index') }}">Perangkat & Server</a>
            </li>
            <li><span class="material-symbols-outlined text-sm mx-1">chevron_right</span><span
                    class="text-on-surface">{{ $breadcrumbTitle }}</span></li>
        </ol>
    </nav>

    <h1 class="font-headline-lg text-headline-lg text-on-surface mb-6">{{ $pageTitle }}</h1>

    <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant overflow-hidden mb-8">
        <div class="px-6 py-4 bg-primary-container">
            <h2 class="text-white font-headline-md text-headline-md m-0">Form Data Server</h2>
        </div>
        <div class="p-6">
            <form action="{{ $route }}" method="POST" enctype="multipart/form-data" id="serverForm">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <!-- Hidden input untuk remove_image (selalu ada) -->
                <input type="hidden" name="remove_image" id="remove_image" value="0">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Nama Perangkat (full width) -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="form-label" for="nama_perangkat">Nama Perangkat <span class="required-star">*</span></label>
                        <div class="input-group">
                            <div class="input-group-addon"><span class="material-symbols-outlined text-xl">list</span></div>
                            <input class="input-group-input @error('nama_perangkat') border-red-500 @enderror"
                                id="nama_perangkat" name="nama_perangkat" placeholder="Masukkan nama perangkat"
                                type="text" value="{{ old('nama_perangkat', $server->nama_perangkat ?? '') }}">
                        </div>
                        @error('nama_perangkat')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Perangkat & Serial Number -->
                    <div>
                        <label class="form-label" for="jenis_perangkat">Jenis Perangkat <span class="required-star">*</span></label>
                        <div class="stepper">
                            <div class="stepper-value" id="jenis_perangkat_display"></div>
                            <div class="stepper-arrows">
                                <button type="button" class="stepper-btn" data-stepper-up="jenis_perangkat" aria-label="Ganti ke jenis perangkat berikutnya">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7" /></svg>
                                </button>
                                <button type="button" class="stepper-btn" data-stepper-down="jenis_perangkat" aria-label="Ganti ke jenis perangkat sebelumnya">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                            </div>
                            <select class="hidden @error('jenis_perangkat') border-red-500 @enderror"
                                id="jenis_perangkat" name="jenis_perangkat">
                                <option value="router" {{ old('jenis_perangkat', $server->jenis_perangkat ?? 'router') == 'router' ? 'selected' : '' }}>Router</option>
                                <option value="switch" {{ old('jenis_perangkat', $server->jenis_perangkat ?? '') == 'switch' ? 'selected' : '' }}>Switch</option>
                                <option value="server" {{ old('jenis_perangkat', $server->jenis_perangkat ?? '') == 'server' ? 'selected' : '' }}>Server</option>
                            </select>
                        </div>
                        @error('jenis_perangkat')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label" for="serial_number">Serial Number <span class="required-star">*</span></label>
                        <div class="input-group">
                            <div class="input-group-addon"><span class="material-symbols-outlined text-xl">key</span></div>
                            <input class="input-group-input @error('serial_number') border-red-500 @enderror"
                                id="serial_number" name="serial_number" placeholder="Masukkan serial number" type="text"
                                value="{{ old('serial_number', $server->serial_number ?? '') }}">
                        </div>
                        @error('serial_number')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Merk & TYPE -->
                    <div>
                        <label class="form-label" for="merk_perangkat">Merk Perangkat <span class="required-star">*</span></label>
                        <select class="standard-select @error('merk_perangkat') border-red-500 @enderror"
                            id="merk_perangkat" name="merk_perangkat">
                            <option value="MIKROTIK" {{ old('merk_perangkat', $server->merk_perangkat ?? '') == 'MIKROTIK' ? 'selected' : '' }}>MIKROTIK</option>
                            <option value="CISCO" {{ old('merk_perangkat', $server->merk_perangkat ?? '') == 'CISCO' ? 'selected' : '' }}>CISCO</option>
                            <option value="DELL" {{ old('merk_perangkat', $server->merk_perangkat ?? '') == 'DELL' ? 'selected' : '' }}>DELL</option>
                            <option value="HP" {{ old('merk_perangkat', $server->merk_perangkat ?? '') == 'HP' ? 'selected' : '' }}>HP</option>
                            <option value="LENOVO" {{ old('merk_perangkat', $server->merk_perangkat ?? '') == 'LENOVO' ? 'selected' : '' }}>LENOVO</option>
                            <option value="HUAWEI" {{ old('merk_perangkat', $server->merk_perangkat ?? '') == 'HUAWEI' ? 'selected' : '' }}>HUAWEI</option>
                        </select>
                        @error('merk_perangkat')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label" for="type">TYPE <span class="required-star">*</span></label>
                        <input class="standard-input @error('type') border-red-500 @enderror" id="type" name="type"
                            placeholder="Masukkan tipe spesifik" type="text"
                            value="{{ old('type', $server->type ?? '') }}">
                        @error('type')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kondisi Server (2 select) & Spesifikasi -->
                    <div>
                        <label class="form-label">Kondisi Server <span class="required-star">*</span></label>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-xs text-secondary">Tipe</label>
                                <select class="standard-select @error('kondisi_tipe') border-red-500 @enderror" name="kondisi_tipe">
                                    <option value="Standard" {{ old('kondisi_tipe', $server->kondisi_tipe ?? '') == 'Standard' ? 'selected' : '' }}>Standard</option>
                                    <option value="High Performance" {{ old('kondisi_tipe', $server->kondisi_tipe ?? '') == 'High Performance' ? 'selected' : '' }}>High Performance</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs text-secondary">Status</label>
                                <select class="standard-select @error('kondisi_status') border-red-500 @enderror" name="kondisi_status">
                                    <option value="Baru" {{ old('kondisi_status', $server->kondisi_status ?? '') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="Bekas" {{ old('kondisi_status', $server->kondisi_status ?? '') == 'Bekas' ? 'selected' : '' }}>Bekas</option>
                                </select>
                            </div>
                        </div>
                        @error('kondisi_tipe')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        @error('kondisi_status')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label" for="spesifikasi">Spesifikasi <span class="required-star">*</span></label>
                        <input class="standard-input @error('spesifikasi') border-red-500 @enderror" id="spesifikasi"
                            name="spesifikasi" placeholder="Detail spesifikasi" type="text"
                            value="{{ old('spesifikasi', $server->spesifikasi ?? '') }}">
                        @error('spesifikasi')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipe Perangkat (full width) -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="form-label" for="tipe_perangkat">Tipe Perangkat <span class="required-star">*</span></label>
                        <select class="standard-select @error('tipe_perangkat') border-red-500 @enderror"
                            id="tipe_perangkat" name="tipe_perangkat">
                            <option value="RACK MOUNT" {{ old('tipe_perangkat', $server->tipe_perangkat ?? '') == 'RACK MOUNT' ? 'selected' : '' }}>RACK MOUNT</option>
                            <option value="TOWER" {{ old('tipe_perangkat', $server->tipe_perangkat ?? '') == 'TOWER' ? 'selected' : '' }}>TOWER</option>
                            <option value="BLADE" {{ old('tipe_perangkat', $server->tipe_perangkat ?? '') == 'BLADE' ? 'selected' : '' }}>BLADE</option>
                        </select>
                        @error('tipe_perangkat')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Kepemilikan & Pemilik -->
                    <div>
                        <label class="form-label" for="status_kepemilikan">Status Kepemilikan <span class="required-star">*</span></label>
                        <select class="standard-select @error('status_kepemilikan') border-red-500 @enderror"
                            id="status_kepemilikan" name="status_kepemilikan">
                            <option value="Kominfo" {{ old('status_kepemilikan', $server->status_kepemilikan ?? 'Kominfo') == 'Kominfo' ? 'selected' : '' }}>Kominfo</option>
                            <option value="Colocation" {{ old('status_kepemilikan', $server->status_kepemilikan ?? '') == 'Colocation' ? 'selected' : '' }}>Colocation</option>
                        </select>
                        @error('status_kepemilikan')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label" for="pemilik_perangkat">Pemilik Perangkat <span class="required-star">*</span></label>
                        <div class="searchable-select" id="pemilik_perangkat_wrapper">
                            <button type="button" class="searchable-select-trigger" id="pemilik_perangkat_trigger">
                                <span id="pemilik_perangkat_label">{{ $pemilikSelected }}</span>
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div class="searchable-select-panel hidden" id="pemilik_perangkat_panel">
                                <input type="text" class="searchable-select-search" id="pemilik_perangkat_search" placeholder="Cari nama OPD...">
                                <div class="searchable-select-options" id="pemilik_perangkat_options">
                                    @foreach ($opdOptions as $opd)
                                        <div class="searchable-select-option {{ $pemilikSelected == $opd ? 'is-selected' : '' }}" data-value="{{ $opd }}">{{ $opd }}</div>
                                    @endforeach
                                </div>
                                <div class="searchable-select-empty hidden" id="pemilik_perangkat_empty">Tidak ditemukan</div>
                            </div>
                            <select class="hidden @error('pemilik_perangkat') border-red-500 @enderror" id="pemilik_perangkat" name="pemilik_perangkat">
                                @foreach ($opdOptions as $opd)
                                    <option value="{{ $opd }}" {{ $pemilikSelected == $opd ? 'selected' : '' }}>{{ $opd }}</option>
                                @endforeach
                            </select>
                        </div>
                        <p class="helper-text" id="pemilik_perangkat_hint">Hanya aktif jika Status Kepemilikan = Colocation.</p>
                        @error('pemilik_perangkat')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- IP Server & IP VPS -->
                    <div>
                        <label class="form-label" for="ip_server">IP Server <span class="required-star">*</span></label>
                        <div class="input-group">
                            <div class="input-group-addon"><span class="material-symbols-outlined text-xl">public</span></div>
                            <input class="input-group-input @error('ip_server') border-red-500 @enderror" id="ip_server"
                                name="ip_server" placeholder="192.168.x.x" type="text"
                                value="{{ old('ip_server', $server->ip_server ?? '') }}">
                        </div>
                        @error('ip_server')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label" for="ip_vps">IP VPS <span class="required-star">*</span></label>
                        <div class="input-group">
                            <div class="input-group-addon"><span class="material-symbols-outlined text-xl">cloud</span></div>
                            <input class="input-group-input @error('ip_vps') border-red-500 @enderror" id="ip_vps"
                                name="ip_vps" placeholder="10.0.x.x" type="text"
                                value="{{ old('ip_vps', $server->ip_vps ?? '') }}">
                        </div>
                        @error('ip_vps')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Perangkat (full width) -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="form-label" for="status">Status Perangkat <span class="required-star">*</span></label>
                        <select class="standard-select @error('status') border-red-500 @enderror" id="status" name="status">
                            <option value="Aktif" {{ old('status', $server->status ?? '') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Non-Aktif" {{ old('status', $server->status ?? '') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                            <option value="Maintenance" {{ old('status', $server->status ?? '') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('status')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ============= HDD & RAM (kelipatan tertentu, otomatis terisi nilai minimum) ============= -->
                    <div>
                        <label class="form-label" for="ukuran_hdd_input">Kapasitas Penyimpanan (HDD/SSD) <span class="required-star">*</span></label>
                        <div class="input-group">
                            <div class="input-group-addon"><span class="material-symbols-outlined text-xl">save</span></div>
                            <input type="number" id="ukuran_hdd_input" min="256" max="20480" step="256" inputmode="numeric"
                                class="input-group-input @error('ukuran_hdd') border-red-500 @enderror"
                                value="{{ $hddGB }}">
                            <div class="input-group-addon" style="border-right:none; border-left:1px solid #CBD5E1;">GB</div>
                        </div>
                        <input type="hidden" id="ukuran_hdd" name="ukuran_hdd" value="{{ $hddGB }} GB">
                        <p class="helper-text">Minimal 256 GB, kelipatan 256 GB, maksimal 20 TB.</p>
                        @error('ukuran_hdd')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label" for="ukuran_ram_input">Kapasitas RAM <span class="required-star">*</span></label>
                        <div class="input-group">
                            <div class="input-group-addon"><span class="material-symbols-outlined text-xl">memory</span></div>
                            <input type="number" id="ukuran_ram_input" min="8" step="8" inputmode="numeric"
                                class="input-group-input @error('ukuran_ram') border-red-500 @enderror"
                                value="{{ $ramGB }}">
                            <div class="input-group-addon" style="border-right:none; border-left:1px solid #CBD5E1;">GB</div>
                        </div>
                        <input type="hidden" id="ukuran_ram" name="ukuran_ram" value="{{ $ramGB }} GB">
                        <p class="helper-text">Minimal 8 GB, kelipatan 8 GB.</p>
                        @error('ukuran_ram')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rack (tetap dropdown biasa) & Core (STEPPER naik-turun) -->
                    <div>
                        <label class="form-label" for="nomor_rack">Nomor RACK <span class="required-star">*</span></label>
                        <div class="stepper">
                            <div class="stepper-value" id="nomor_rack_display"></div>
                            <div class="stepper-arrows">
                                <button type="button" class="stepper-btn" data-stepper-up="nomor_rack" aria-label="Naikkan nomor rack">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7" /></svg>
                                </button>
                                <button type="button" class="stepper-btn" data-stepper-down="nomor_rack" aria-label="Turunkan nomor rack">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                            </div>
                            <select class="hidden @error('nomor_rack') border-red-500 @enderror" id="nomor_rack" name="nomor_rack">
                                <option value="R1" {{ old('nomor_rack', $server->nomor_rack ?? 'R1') == 'R1' ? 'selected' : '' }}>R1</option>
                                <option value="R2" {{ old('nomor_rack', $server->nomor_rack ?? '') == 'R2' ? 'selected' : '' }}>R2</option>
                                <option value="R3" {{ old('nomor_rack', $server->nomor_rack ?? '') == 'R3' ? 'selected' : '' }}>R3</option>
                                <option value="R4" {{ old('nomor_rack', $server->nomor_rack ?? '') == 'R4' ? 'selected' : '' }}>R4</option>
                                <option value="R5" {{ old('nomor_rack', $server->nomor_rack ?? '') == 'R5' ? 'selected' : '' }}>R5</option>
                                <option value="R6" {{ old('nomor_rack', $server->nomor_rack ?? '') == 'R6' ? 'selected' : '' }}>R6</option>
                                <option value="R7" {{ old('nomor_rack', $server->nomor_rack ?? '') == 'R7' ? 'selected' : '' }}>R7</option>
                                <option value="R8" {{ old('nomor_rack', $server->nomor_rack ?? '') == 'R8' ? 'selected' : '' }}>R8</option>
                            </select>
                        </div>
                        @error('nomor_rack')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label" for="jumlah_core">Jumlah Core <span class="required-star">*</span></label>
                        <div class="stepper">
                            <div class="stepper-value" id="jumlah_core_display"></div>
                            <div class="stepper-arrows">
                                <button type="button" class="stepper-btn" data-stepper-up="jumlah_core" aria-label="Naikkan jumlah core">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7" /></svg>
                                </button>
                                <button type="button" class="stepper-btn" data-stepper-down="jumlah_core" aria-label="Turunkan jumlah core">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                            </div>
                            <select class="hidden @error('jumlah_core') border-red-500 @enderror" id="jumlah_core" name="jumlah_core">
                                @for($i = 1; $i <= 24; $i++)
                                    <option value="{{ $i }}" {{ old('jumlah_core', $server->jumlah_core ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        @error('jumlah_core')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- ============================================================ -->

                    <!-- ============= UPLOAD GAMBAR RACK ============= -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="form-label">Upload Gambar Rack</label>

                        <!-- Preview gambar jika ada (saat edit) -->
                        @if($isEdit && $server->gambar_rack)
                            <div class="image-preview-container mb-3" id="image-preview-container">
                                <img src="{{ Storage::url($server->gambar_rack) }}"
                                     alt="Gambar Rack"
                                     class="preview-image"
                                     id="current-image">
                                <button type="button"
                                        onclick="removeImage({{ $server->id }})"
                                        class="remove-image-btn"
                                        id="removeImageBtn"
                                        title="Hapus gambar">
                                    <span class="material-symbols-outlined text-sm">close</span>
                                </button>
                            </div>
                        @endif

                        <!-- Area upload -->
                        <div id="upload-area" class="upload-area" onclick="document.getElementById('gambar_rack').click()">
                            <div class="flex flex-col items-center">
                                <span class="material-symbols-outlined text-5xl text-secondary mb-2">cloud_upload</span>
                                <p class="text-sm text-secondary font-medium">Klik untuk upload gambar</p>
                                <p class="text-xs text-secondary mt-1">PNG, JPG, JPEG (Max 2MB)</p>
                            </div>
                            <input id="gambar_rack" name="gambar_rack" type="file" class="hidden"
                                accept="image/*" onchange="previewImage(event)">
                        </div>

                        <!-- Preview setelah upload -->
                        <div id="preview-container" class="mt-3 hidden">
                            <div class="relative inline-block">
                                <img id="image-preview" src="#" alt="Preview" class="preview-image">
                                <button type="button" onclick="clearPreview()" class="remove-image-btn" title="Hapus">
                                    <span class="material-symbols-outlined text-sm">close</span>
                                </button>
                            </div>
                            <p class="text-xs text-secondary mt-2">Posisi perangkat akan ditampilkan berdasarkan gambar yang diupload.</p>
                        </div>

                        @error('gambar_rack')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- ============================================= -->

                    <!-- Peruntukan (full width) -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="form-label" for="peruntukan">Deskripsi <span class="required-star">*</span></label>
                        <div class="input-group">
                            <div class="input-group-addon"><span class="material-symbols-outlined text-xl">list</span></div>
                            <input class="input-group-input @error('peruntukan') border-red-500 @enderror"
                                id="peruntukan" name="peruntukan" placeholder="Masukkan Deskripsi Perangkat"
                                type="text" value="{{ old('peruntukan', $server->peruntukan ?? '') }}">
                        </div>
                        @error('peruntukan')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pengirim & Penerima -->
                    <div>
                        <label class="form-label" for="nama_pengirim">Nama Pengirim <span class="required-star">*</span></label>
                        <input class="standard-input @error('nama_pengirim') border-red-500 @enderror" id="nama_pengirim"
                            name="nama_pengirim" placeholder="Nama pengirim" type="text"
                            value="{{ old('nama_pengirim', $server->nama_pengirim ?? '') }}">
                        @error('nama_pengirim')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label" for="nama_penerima">Nama Penerima <span class="required-star">*</span></label>
                        <input class="standard-input @error('nama_penerima') border-red-500 @enderror" id="nama_penerima"
                            name="nama_penerima" placeholder="Nama penerima" type="text"
                            value="{{ old('nama_penerima', $server->nama_penerima ?? '') }}">
                        @error('nama_penerima')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Pengisian (full width) -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="form-label" for="jam_pengisian">Tanggal Pengisian <span class="required-star">*</span></label>
                        <input
                            class="w-full border border-[#CBD5E1] rounded-md py-2 pl-3 pr-10 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary @error('jam_pengisian') border-red-500 @enderror"
                            id="jam_pengisian" name="jam_pengisian" type="datetime-local"
                            value="{{ old('jam_pengisian', isset($server) && $server->jam_pengisian ? $server->jam_pengisian->format('Y-m-d\TH:i') : '') }}">
                        <p class="text-xs text-secondary mt-1">Format: DD-MM-YYYY HH:MM (contoh: 06-08-2026 14:30). Gunakan kalender atau ketik manual.</p>
                        @error('jam_pengisian')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-8 flex gap-3">
                    <button
                        class="bg-primary-container hover:bg-primary text-white font-medium py-2 px-6 rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
                        type="submit">
                        {{ $isEdit ? 'Update' : 'Simpan' }}
                    </button>
                    <a href="{{ route('server.index') }}"
                        class="bg-surface-container-high text-on-surface-variant font-medium py-2 px-6 rounded-lg transition-colors shadow-sm hover:bg-surface-container">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                const container = document.getElementById('preview-container');
                preview.src = e.target.result;
                container.classList.remove('hidden');

                // Sembunyikan upload area
                document.getElementById('upload-area').style.display = 'none';

                // Reset remove_image karena ada file baru
                document.getElementById('remove_image').value = '0';

                // Sembunyikan preview lama jika ada
                const oldContainer = document.getElementById('image-preview-container');
                if (oldContainer) {
                    oldContainer.style.display = 'none';
                }
            }
            reader.readAsDataURL(file);
        }
    }

    function clearPreview() {
        const container = document.getElementById('preview-container');
        const fileInput = document.getElementById('gambar_rack');
        container.classList.add('hidden');
        fileInput.value = '';
        document.getElementById('upload-area').style.display = 'block';

        // Reset remove_image
        document.getElementById('remove_image').value = '0';

        // Tampilkan kembali preview lama jika ada
        const oldContainer = document.getElementById('image-preview-container');
        if (oldContainer) {
            oldContainer.style.display = 'inline-block';
        }
    }

    function removeImage(serverId) {
        if (!confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
            return;
        }

        // Tampilkan loading
        const btn = document.getElementById('removeImageBtn');
        if (!btn) {
            alert('Tombol tidak ditemukan');
            return;
        }

        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined text-sm loading-spinner">progress_activity</span>';
        btn.disabled = true;

        // Ambil CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            alert('CSRF token tidak ditemukan');
            btn.innerHTML = originalHtml;
            btn.disabled = false;
            return;
        }

        fetch(`/server/${serverId}/remove-image`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Sembunyikan preview gambar
                const container = document.getElementById('image-preview-container');
                if (container) {
                    container.style.display = 'none';
                }

                // Set hidden input untuk remove
                document.getElementById('remove_image').value = '1';

                // Kosongkan file input agar tidak ada konflik
                const fileInput = document.getElementById('gambar_rack');
                if (fileInput) {
                    fileInput.value = '';
                }

                // Tampilkan kembali upload area
                const uploadArea = document.getElementById('upload-area');
                if (uploadArea) {
                    uploadArea.style.display = 'block';
                }

                // Reset preview container
                const previewContainer = document.getElementById('preview-container');
                if (previewContainer) {
                    previewContainer.classList.add('hidden');
                }

                showToast('Gambar berhasil dihapus. Upload gambar baru jika diperlukan.', 'success');
            } else {
                alert('Gagal menghapus gambar: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus gambar. Silakan coba lagi.\nError: ' + error.message);
        })
        .finally(() => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        });
    }

    function showToast(message, type = 'success') {
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500'
        };

        // Hapus toast yang sudah ada
        const existingToast = document.querySelector('.toast-message');
        if (existingToast) {
            existingToast.remove();
        }

        const toast = document.createElement('div');
        toast.className = `toast-message fixed top-4 right-4 z-50 px-4 py-2 rounded-lg text-white ${colors[type] || 'bg-gray-500'} shadow-lg transition-opacity duration-300`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }

    // Drag and drop support
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('upload-area');
        if (uploadArea) {
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    document.getElementById('gambar_rack').files = files;
                    previewImage({ target: { files: files } });
                }
            });
        }
    });

    // ============= HDD & RAM: input angka dengan kelipatan tertentu (step) =============
    function bindCapacityField(inputId, hiddenId, minValue, step, maxValue) {
        const input = document.getElementById(inputId);
        const hidden = document.getElementById(hiddenId);
        if (!input || !hidden) return;

        function clamp(val) {
            if (isNaN(val)) return minValue;
            // Bulatkan ke kelipatan terdekat (relatif terhadap minValue)
            let rounded = minValue + Math.round((val - minValue) / step) * step;
            if (rounded < minValue) rounded = minValue;
            if (maxValue && rounded > maxValue) rounded = maxValue;
            return rounded;
        }

        function sync() {
            const val = clamp(parseInt(input.value, 10));
            input.value = val;
            hidden.value = val + ' GB';
        }

        input.addEventListener('input', function () {
            // Saat sedang mengetik, sinkronkan apa adanya dulu tanpa memaksa pembulatan
            const raw = parseInt(input.value, 10);
            hidden.value = (isNaN(raw) ? minValue : raw) + ' GB';
        });
        input.addEventListener('blur', sync);   // saat pindah field, baru dibulatkan ke kelipatan terdekat
        input.addEventListener('change', sync); // saat pakai tombol panah naik/turun

        sync(); // pastikan langsung terisi nilai minimum saat halaman dimuat
    }

    // ============= Jumlah Core: tetap pakai stepper terkunci (naik-turun) =============
    function initStepper(selectId) {
        const select = document.getElementById(selectId);
        const display = document.getElementById(selectId + '_display');
        const upBtn = document.querySelector('[data-stepper-up="' + selectId + '"]');
        const downBtn = document.querySelector('[data-stepper-down="' + selectId + '"]');
        if (!select || !display || !upBtn || !downBtn) return;

        function render() {
            const opt = select.options[select.selectedIndex];
            display.textContent = opt ? opt.text : '';
            upBtn.disabled = select.selectedIndex >= select.options.length - 1;
            downBtn.disabled = select.selectedIndex <= 0;
        }

        upBtn.addEventListener('click', function () {
            if (select.selectedIndex < select.options.length - 1) {
                select.selectedIndex += 1;
                select.dispatchEvent(new Event('change'));
                render();
            }
        });

        downBtn.addEventListener('click', function () {
            if (select.selectedIndex > 0) {
                select.selectedIndex -= 1;
                select.dispatchEvent(new Event('change'));
                render();
            }
        });

        render();
    }

    // ============= Pemilik Perangkat: searchable-select (buka panel, cari, pilih) =============
    function initSearchableSelect(prefix) {
        const wrapper = document.getElementById(prefix + '_wrapper');
        const trigger = document.getElementById(prefix + '_trigger');
        const label = document.getElementById(prefix + '_label');
        const panel = document.getElementById(prefix + '_panel');
        const search = document.getElementById(prefix + '_search');
        const optionsContainer = document.getElementById(prefix + '_options');
        const emptyMsg = document.getElementById(prefix + '_empty');
        const hiddenSelect = document.getElementById(prefix);
        if (!wrapper || !trigger || !label || !panel || !search || !optionsContainer || !hiddenSelect) return;

        const options = Array.from(optionsContainer.querySelectorAll('.searchable-select-option'));

        function openPanel() {
            if (trigger.disabled) return;
            panel.classList.remove('hidden');
            wrapper.classList.add('is-open');
            search.value = '';
            filterOptions('');
            search.focus();
        }

        function closePanel() {
            panel.classList.add('hidden');
            wrapper.classList.remove('is-open');
        }

        function selectOption(optEl) {
            const value = optEl.getAttribute('data-value');
            hiddenSelect.value = value;
            label.textContent = value;
            options.forEach(o => o.classList.remove('is-selected'));
            optEl.classList.add('is-selected');
            hiddenSelect.dispatchEvent(new Event('change'));
            closePanel();
        }

        function filterOptions(query) {
            const q = query.trim().toLowerCase();
            let anyVisible = false;
            options.forEach(o => {
                const match = o.textContent.toLowerCase().includes(q);
                o.classList.toggle('hidden', !match);
                if (match) anyVisible = true;
            });
            if (emptyMsg) emptyMsg.classList.toggle('hidden', anyVisible);
        }

        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            if (panel.classList.contains('hidden')) {
                openPanel();
            } else {
                closePanel();
            }
        });

        options.forEach(o => {
            o.addEventListener('click', function () {
                selectOption(o);
            });
        });

        search.addEventListener('input', function () {
            filterOptions(search.value);
        });

        // Cegah klik di kotak search ikut menutup panel via listener document di bawah
        search.addEventListener('click', function (e) { e.stopPropagation(); });

        // Klik di luar widget -> tutup panel
        document.addEventListener('click', function (e) {
            if (!wrapper.contains(e.target)) {
                closePanel();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closePanel();
        });
    }

    // ============= Status Kepemilikan -> aktif/nonaktifkan Pemilik Perangkat =============
    function toggleOwnerField() {
        const statusSelect = document.getElementById('status_kepemilikan');
        const ownerSelect = document.getElementById('pemilik_perangkat');
        const wrapper = document.getElementById('pemilik_perangkat_wrapper');
        const trigger = document.getElementById('pemilik_perangkat_trigger');
        if (!statusSelect || !ownerSelect || !wrapper) return;

        const isColocation = statusSelect.value === 'Colocation';
        ownerSelect.disabled = !isColocation; // kalau disabled, field ini tidak ikut terkirim saat submit
        if (trigger) trigger.disabled = !isColocation;
        wrapper.classList.toggle('stepper-disabled', !isColocation);

        if (!isColocation) {
            const panel = document.getElementById('pemilik_perangkat_panel');
            if (panel) panel.classList.add('hidden');
            wrapper.classList.remove('is-open');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        bindCapacityField('ukuran_hdd_input', 'ukuran_hdd', 256, 256, 20480); // 256 GB s/d 20 TB, kelipatan 256 GB
        bindCapacityField('ukuran_ram_input', 'ukuran_ram', 8, 8, null);      // mulai 8 GB, kelipatan 8 GB
        initStepper('jenis_perangkat');
        initStepper('nomor_rack');
        initStepper('jumlah_core');
        initSearchableSelect('pemilik_perangkat');

        toggleOwnerField();
        const statusSelect = document.getElementById('status_kepemilikan');
        if (statusSelect) {
            statusSelect.addEventListener('change', toggleOwnerField);
        }
    });
</script>
@endpush