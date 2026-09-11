<!DOCTYPE html><html lang="id"><head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Daftarkan Perangkat Baru - SIM TIK</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
            tailwind.config = {
                darkMode: "class",
                theme: {
                    extend: {
                        "colors": {
                            "surface-container-low": "#f2f4f6",
                            "primary-fixed-dim": "#b4c5ff",
                            "on-primary": "#ffffff",
                            "primary": "#004ac6",
                            "inverse-primary": "#b4c5ff",
                            "background": "#f7f9fb",
                            "inverse-surface": "#2d3133",
                            "secondary-fixed-dim": "#bec6e0",
                            "surface": "#f7f9fb",
                            "on-surface": "#191c1e",
                            "on-tertiary-fixed": "#2a1700",
                            "secondary-container": "#dae2fd",
                            "primary-fixed": "#dbe1ff",
                            "surface-tint": "#0053db",
                            "on-error-container": "#93000a",
                            "surface-container": "#eceef0",
                            "on-primary-fixed-variant": "#003ea8",
                            "inverse-primary": "#b4c5ff",
                            "surface-container-highest": "#e0e3e5",
                            "background": "#f7f9fb",
                            "on-secondary-container": "#5c647a",
                            "inverse-surface": "#2d3133",
                            "tertiary-fixed": "#ffddb8",
                            "on-primary": "#ffffff",
                            "on-secondary-fixed-variant": "#3f465c",
                            "on-secondary": "#ffffff",
                            "primary-fixed-dim": "#b4c5ff",
                            "surface-container-low": "#f2f4f6",
                            "primary-container": "#2563eb",
                            "secondary-fixed-dim": "#bec6e0",
                            "outline": "#737686",
                            "surface-container-lowest": "#ffffff",
                            "tertiary-container": "#996100",
                            "outline-variant": "#c3c6d7",
                            "inverse-on-surface": "#eff1f3",
                            "on-tertiary": "#ffffff",
                            "error-container": "#ffdad6",
                            "error": "#ba1a1a",
                            "surface-variant": "#e0e3e5",
                            "on-tertiary-fixed-variant": "#653e00",
                            "surface": "#f7f9fb",
                            "tertiary": "#784b00",
                            "on-primary-fixed": "#00174b"
                        },
                        "borderRadius": {
                            "DEFAULT": "0.25rem",
                            "lg": "0.5rem",
                            "xl": "0.75rem",
                            "full": "9999px"
                        },
                        "spacing": {
                            "topbar-height": "64px",
                            "sidebar-width": "260px",
                            "container-padding": "24px",
                            "gutter": "16px",
                            "base": "4px"
                        },
                        "fontFamily": {
                            "display": ["Inter"],
                            "body-md": ["Inter"],
                            "data-tabular": ["Inter"],
                            "headline-lg": ["Inter"],
                            "label-md": ["Inter"],
                            "display": ["Inter"],
                            "headline-md": ["Inter"]
                        },
                        "fontSize": {
                            "display": ["36px", { "lineHeight": "44px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                            "body-md": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                            "data-tabular": ["13px", { "lineHeight": "18px", "fontWeight": "400" }],
                            "headline-lg": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                            "label-md": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600" }],
                            "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                            "headline-md": ["20px", { "lineHeight": "28px", "fontWeight": "600" }]
                        }
                    }
                }
            }
        </script>
</head>
<body class="font-body-md text_on_background bg_background antialiased flex h_screen overflow_hidden">
<!-- Main Content Area -->
<div class="flex-1 flex flex_col w_full h_full relative">
<!-- Page Content -->
<main class="flex-1 overflow_y_auto p_container_padding bg_background">
<div class="max-w-5xl mx-auto">
<!-- Back to Dashboard -->
<a class="inline-flex items-center gap-2 text_on_surface-variant hover:text_primary font-body-md text-body-md transition-colors mb-4" href="{{ route('user.dashboarduser') }}">
<span class="material-symbols-outlined text-[20px]">arrow_back</span>
                Kembali ke Dashboard
            </a>
<!-- Header -->
<div class="mb-6">
<h2 class="font-headline_lg text-headline_lg text_on_surface mb-2">Daftarkan Perangkat Baru</h2>
<p class="font-body-md text-body-md text_on_surface-variant">Isi data dasar perangkat. Tim admin akan melengkapi detail teknis lanjutan.</p>
</div>
<!-- Info Banner -->
<div class="bg-[#DBEAFE] border border-blue-200 rounded-lg p-4 mb-8 flex items_start gap-3 shadow-sm">
<span class="material-symbols-outlined text_primary mt-0.5">info</span>
<p class="font-body-md text-body-md text_blue-900">Data akan berstatus <strong>PENDING</strong> hingga dilengkapi oleh admin.</p>
</div>
@if ($errors->any())
<div class="bg-error_container border border-error/30 rounded-lg p-4 mb-8 flex_items_start gap-3 shadow-sm">
<span class="material-symbols_outined text_error mt-0.5">error</span>
<div class="font-body-md text-body-md text_on_error_container">
<p class="font-semibold mb-1">Periksa kembali data yang diisi:</p>
<ul class="list-disc list_inside">
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
</div>
@endif
<!-- Form Card -->
<div class="bg-surface_container_lowest border border-outline-variant rounded-xl shadow-sm p-8">
<form action="{{ route('inputdatauser.store') }}" method="POST">
@csrf

        <?php
            // Determine default values for kode_perangkat preview (user form)
            $opd = old('opd') ?? '';
            $statusKepemilikan = ($opd === 'Kominfo') ? 'Kominfo' : 'Colocation';
            $dateString = old('tanggal_input') ?? now()->toDateString();
            $defaultKode = \App\Models\Server::generateKodePerangkat($statusKepemilikan, $dateString);
        ?>

<div class="space-y-6">

    <!-- Nama Pengirim -->
    <div>
        <label class="block font-label-md text-label-md text_on_surface mb-2" for="nama_pengirim">Nama Pengirim @if ($opd !== 'Kominfo') <span class="text-error">*</span> @endif</label>
        <input type="text" id="nama_pengirim" name="nama_pengirim"
            class="w-full bg_surface border border-outline-variant rounded-lg py-2.5 px-4 font-body-md text-body-md text_on_surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
            value="{{ old('nama_pengirim') }}" placeholder="Nama pengirim"
            @if ($opd === 'Kominfo') disabled @endif
            @if ($opd !== 'Kominfo') required @endif>
    </div>

    <!-- Nama Dinas/OPD -->
    <div>
        <label class="block font-label-md text-label-md text_on_surface mb-2" for="opd">Nama Dinas/OPD <span class="text-error">*</span></label>
        <select id="opd" name="opd"
            class="w-full bg_surface border border-outline-variant rounded-lg py-2.5 px-4 font-body-md text-body-md text_on_surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
            required>
            <option value="" disabled selected>Pilih Dinas/OPD</option>
            @foreach($opdList as $value => $label)
                <option value="{{ $value }}" {{ old('opd') === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Nama Penerima -->
    <div>
        <label class="block font-label-md text-label-md text_on_surface mb-2" for="nama_penerima">Nama Penerika (diisi admin)</label>
        <input type="text" id="nama_penerima" name="nama_penerima"
            class="w-full bg_surface border border-outline-variant rounded-lg py-2.5 px-4 font-body-md text-body-md text_on_surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
            value="{{ old('nama_penerima') }}" placeholder="Akan diisi oleh admin">
    </div>

    <!-- Kode Perangkat (readonly preview) -->
    <div>
        <label class="block font-label-md text-label-md text_on_surface mb-2" for="kode_perangkat">Kode Perangkat <span class="text-error">*</span></label>
        <div class="flex items-center space-x-3">
            <input type="text" id="kode_perangkat" name="kode_perangkat" readonly
                class="w-full bg_surface border border-outline-variant rounded-lg py-2.5 px-4 font-body-md text-body-md text_on_surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                value="{{ old('kode_perangkat', $defaultKode) }}"
                placeholder="Kode perangkat akan dibuat otomatis mengikuti format sistem"
            >
        </div>
        <p class="mt-1 text-xs text_on_surface-variant">Kode perangkat akan dibuat otomatis setelah submit berdasarkan tanggal input dan pilihan OPD.</p>
    </div>

    <!-- Nama Perangkat -->
    <div>
        <label class="block font-label-md text-label-md text_on_surface mb-2" for="nama_perangkat">Nama Perangkat <span class="text-error">*</span></label>
        <input type="text" id="nama_perangkat" name="nama_perangkat"
            class="w-full bg_surface border border-outline-variant rounded-lg py-2.5 px-4 font-body-md text-body-md text_on_surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
            value="{{ old('nama_perangkat') }}" placeholder="Masukkan nama perangkat" required>
    </div>

    <!-- Jenis Perangkat -->
    <div>
        <label class="block font-label-md text-label-md text_on_surface mb-2" for="jenis_perangkat">Jenis Perangkat <span class="text-error">*</span></label>
        <div id="jenis_wrapper" class="space-y-2">
            <select id="jenis_perangkat" name="jenis_perangkat"
                class="w-full bg_surface border border-outline-variant rounded-lg py-2.5 px-4 font-body-md text-body-md text_on_surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                required>
                <option value="" disabled selected>Pilih jenis perangkat</option>
                @foreach($jenisList as $value => $label)
                    <option value="{{ $value }}" {{ old('jenis_perangkat') === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
                <option value="lainnya" {{ old('jenis_perangkat') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>

            <!-- Alternative input for Lainnya -->
            <div id="jenis-lainnya_wrapper" class="hidden">
                <label class="block font-label-md text-label-md text_on_surface mb-1" for="jenis_lainnya">Jenis Perangkat (Lainnya) <span class="text-error">*</span></label>
                <input type="text" id="jenis_lainnya" name="jenis_lainnya"
                    class="w-full bg_surface border border-outline-variant rounded-lg py-2.5 px-4 font-body-md text-body-md text_on_surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                    value="{{ old('jenis_lainnya') }}" placeholder="Masukkan jenis perangkat lain" required>
            </div>
        </div>
    </div>

    <!-- Merek Perangkat -->
    <div>
        <label class="block font-label-md text-label-md text_on_surface mb-2" for="merk_perangkat">Merk Perangkat <span class="text-error">*</span></label>
        <div id="merk_wrapper" class="space-y-2">
            <select id="merk_perangkat" name="merk_perangkat"
                class="w-full bg_surface border border-outline-variant rounded-lg py-2.5 px-4 font-body-md text-body-md text_on_surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                required>
                <option value="" disabled selected>Pilih merk perangkat</option>
                @foreach($merkList as $value => $label)
                    <option value="{{ $value }}" {{ old('merk_perangkat') === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
                <option value="lainnya" {{ old('merk_perangkat') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>

            <!-- Alternative input for Lainnya -->
            <div id="merk-lainnya_wrapper" class="hidden">
                <label class="block font-label-md text-label-md text_on_surface mb-1" for="merk_lainnya">Merk Perangkat (Lainnya) <span class="text-error">*</span></label>
                <input type="text" id="merk_lainnya" name="merk_lainnya"
                    class="w-full bg_surface border border-outline-variant rounded-lg py-2.5 px-4 font-body-md text-body-md text_on_surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                    value="{{ old('merk_lainnya') }}" placeholder="Masukkan merk perangkat lain" required>
            </div>
        </div>
    </div>

    <!-- Tanggal Input -->
    <div>
        <label class="block font-label-md text-label-md text_on_surface mb-2" for="tanggal_input">Tanggal Input <span class="text-error">*</span></label>
        <input type="date" id="tanggal_input" name="tanggal_input"
            class="w-full bg_surface border border-outline-variant rounded-lg py-2.5 px-4 font-body-md text-body-md text_on_surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
            value="{{ old('tanggal_input', now()->toDateString()) }}" required>
    </div>
</div>

<!-- Actions -->
<div class="mt-8 pt-6 border-t border-outline-variant flex justify-end gap-4">
    <a href="{{ route('user.dashboarduser') }}" class="px-6 py-2.5 rounded-lg border border-outline text_on_surface-variant font-label-md text-body-md hover:bg-surface_container_high transition-colors focus:ring-2 focus:ring-outline inline-flex items-center">
        Batal
    </a>
    <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary-container text-on-primary-container font-label-md text-body-md hover:bg-primary hover:text-white transition-colors shadow-sm focus:ring-2 focus:ring-primary focus:ring-offset-2 flex items-center gap-2">
        <span class="material-symbols-outlined text-sm">save</span>
        Simpan & Daftarkan
    </button>
</div>
</form>
</div>
</div>
</main>
</div>
</body><script>
    document.addEventListener('DOMContentLoaded', function () {
        const jenisSelect = document.getElementById('jenis_perangkat');
        const jenisLainnyaWrapper = document.getElementById('jenis-lainnya_wrapper');
        const merkSelect = document.getElementById('merk_perangkat');
        const merkLainnyaWrapper = document.getElementById('merk-lainnya_wrapper');

        function toggleLainnya(select, wrapper) {
            const input = wrapper.querySelector('input');
            if (select.value === 'lainnya') {
                wrapper.classList.remove('hidden');
                input.required = true;
            } else {
                wrapper.classList.add('hidden');
                input.required = false;
                input.value = '';
            }
        }

        // Initial state
        toggleLainnya(jenisSelect, jenisLainnyaWrapper);
        toggleLainnya(merkSelect, merkLainnyaWrapper);

        // Event listeners
        jenisSelect.addEventListener('change', function () {
            toggleLainnya(jenisSelect, jenisLainnyaWrapper);
        });
        merkSelect.addEventListener('change', function () {
            toggleLainnya(merkSelect, merkLainnyaWrapper);
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const opdSelect = document.getElementById('opd');
        const tanggalInput = document.getElementById('tanggal_input');
        const kodePerangkat = document.getElementById('kode_perangkat');

        async function updateKodePerangkatPreview() {
            if (!opdSelect || !tanggalInput || !kodePerangkat) return;
            const isKominfo = opdSelect.value === 'Kominfo';
            const prefix = isKominfo ? 'KO' : 'CO';
            let dateParam = '';
            if (tanggalInput.value) {
                dateParam = tanggalInput.value; // Y-m-d
            } else {
                dateParam = ''; // empty => today
            }
            try {
                const response = await fetch('/server/next-code?status_kepemilikan=' + encodeURIComponent(opdSelect.value) + (dateParam ? '&date=' + encodeURIComponent(dateParam) : ''));
                const data = await response.json();
                if (data.kode_perangkat) {
                    kodePerangkat.value = data.kode_perangkat;
                } else {
                    // fallback
                    const datePart = dateParam ? dateParam.replace(/-/g, '').slice(2) : (() => { const today = new Date(); return String(today.getFullYear()).slice(-2) + String(today.getMonth()+1).padStart(2,'0') + String(today.getDate()).padStart(2,'0'); })();
                    kodePerangkat.value = prefix + datePart + 'A';
                }
            } catch (e) {
                console.error(e);
                // fallback
                const datePart = tanggalInput.value ? tanggalInput.value.replace(/-/g, '').slice(2) : (() => { const today = new Date(); return String(today.getFullYear()).slice(-2) + String(today.getMonth()+1).padStart(2,'0') + String(today.getDate()).padStart(2,'0'); })();
                kodePerangkat.value = prefix + datePart + 'A';
            }
        }

        // Initial update
        updateKodePerangkatPreview();
        // Update on change
        if (opdSelect) opdSelect.addEventListener('change', updateKodePerangkatPreview);
        if (tanggalInput) tanggalInput.addEventListener('change', updateKodePerangkatPreview);
    });
</script>
</html>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jenisSelect = document.getElementById('jenis_perangkat');
        const jenisLainnyaWrapper = document.getElementById('jenis-lainnya_wrapper');
        const merkSelect = document.getElementById('merk_perangkat');
        const merkLainnyaWrapper = document.getElementById('merk-lainnya_wrapper');

        function toggleLainnya(select, wrapper) {
            const input = wrapper.querySelector('input');
            if (select.value === 'lainnya') {
                wrapper.classList.remove('hidden');
                input.required = true;
            } else {
                wrapper.classList.add('hidden');
                input.required = false;
                input.value = '';
            }
        }

        // Initial state
        toggleLainnya(jenisSelect, jenisLainnyaWrapper);
        toggleLainnya(merkSelect, merkLainnyaWrapper);

        // Event listeners
        jenisSelect.addEventListener('change', function () {
            toggleLainnya(jenisSelect, jenisLainnyaWrapper);
        });
        merkSelect.addEventListener('change', function () {
            toggleLainnya(merkSelect, merkLainnyaWrapper);
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const opdSelect = document.getElementById('opd');
        const tanggalInput = document.getElementById('tanggal_input');
        const kodePerangkat = document.getElementById('kode_perangkat');

        function updateKodePerangkatPreview() {
            if (!opdSelect || !tanggalInput || !kodePerangkat) return;
            const isKominfo = opdSelect.value === 'Kominfo';
            const prefix = isKominfo ? 'KO' : 'CO';
            let datePart = '';
            if (tanggalInput.value) {
                // format YYYY-MM-DD to YYMMDD
                const [year, month, day] = tanggalInput.value.split('-');
                datePart = year.slice(2) + month.padStart(2, '0') + day.padStart(2, '0');
            } else {
                const today = new Date();
                const yy = String(today.getFullYear()).slice(-2);
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const dd = String(today.getDate()).padStart(2, '0');
                datePart = yy + mm + dd;
            }
            // placeholder sequence A
            kodePerangkat.value = prefix + datePart + 'A';
        }

        // Initial update
        updateKodePerangkatPreview();
        // Update on change
        if (opdSelect) opdSelect.addEventListener('change', updateKodePerangkatPreview);
        if (tanggalInput) tanggalInput.addEventListener('change', updateKodePerangkatPreview);
    });
</script>