<!DOCTYPE html><html lang="id"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Pendaftaran Perangkat Server - SIM TIK</title>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Tailwind Configuration -->
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "on-tertiary-container": "#ffeedd",
                    "tertiary-fixed-dim": "#ffb95f",
                    "on-surface": "#191c1e",
                    "secondary-container": "#dae2fd",
                    "surface": "#f7f9fb",
                    "secondary-fixed": "#dae2fd",
                    "on-surface-variant": "#434655",
                    "inverse-on-surface": "#eff1f3",
                    "on-primary-fixed-variant": "#003ea8",
                    "primary-container": "#2563eb",
                    "outline-variant": "#c3c6d7",
                    "on-primary-container": "#eeefff",
                    "primary-fixed-dim": "#b4c5ff",
                    "primary": "#004ac6",
                    "inverse-surface": "#2d3133",
                    "primary-fixed": "#dbe1ff",
                    "outline": "#737686",
                    "on-error-container": "#93000a",
                    "on-primary-fixed": "#00174b",
                    "error-container": "#ffdad6",
                    "tertiary-fixed": "#ffddb8",
                    "on-tertiary-fixed-variant": "#653e00",
                    "tertiary-container": "#996100",
                    "surface-dim": "#d8dadc",
                    "on-background": "#191c1e",
                    "on-tertiary-fixed": "#2a1700",
                    "surface-bright": "#f7f9fb",
                    "surface-container": "#eceef0",
                    "on-secondary": "#ffffff",
                    "surface-variant": "#e0e3e5",
                    "on-secondary-fixed": "#131b2e",
                    "surface-tint": "#0053db",
                    "on-secondary-fixed-variant": "#3f465c",
                    "surface-container-low": "#f2f4f6",
                    "tertiary": "#784b00",
                    "on-error": "#ffffff",
                    "on-tertiary": "#ffffff",
                    "surface-container-lowest": "#ffffff",
                    "background": "#f7f9fb",
                    "on-primary": "#ffffff",
                    "error": "#ba1a1a",
                    "surface-container-highest": "#e0e3e5",
                    "inverse-primary": "#b4c5ff",
                    "surface-container-high": "#e6e8ea",
                    "secondary-fixed-dim": "#bec6e0",
                    "on-secondary-container": "#5c647a",
                    "secondary": "#565e74"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "gutter": "16px",
                    "base": "4px",
                    "sidebar-width": "260px",
                    "container-padding": "24px",
                    "topbar-height": "64px"
            },
            "fontFamily": {
                    "data-tabular": [
                            "Inter"
                    ],
                    "headline-lg": [
                            "Inter"
                    ],
                    "headline-md": [
                            "Inter"
                    ],
                    "label-md": [
                            "Inter"
                    ],
                    "body-lg": [
                            "Inter"
                    ],
                    "display": [
                            "Inter"
                    ],
                    "body-md": [
                            "Inter"
                    ]
            },
            "fontSize": {
                    "data-tabular": [
                            "13px",
                            {
                                    "lineHeight": "18px",
                                    "fontWeight": "400"
                            }
                    ],
                    "headline-lg": [
                            "24px",
                            {
                                    "lineHeight": "32px",
                                    "letterSpacing": "-0.01em",
                                    "fontWeight": "600"
                            }
                    ],
                    "headline-md": [
                            "20px",
                            {
                                    "lineHeight": "28px",
                                    "fontWeight": "600"
                            }
                    ],
                    "label-md": [
                            "12px",
                            {
                                    "lineHeight": "16px",
                                    "letterSpacing": "0.05em",
                                    "fontWeight": "600"
                            }
                    ],
                    "body-lg": [
                            "16px",
                            {
                                    "lineHeight": "24px",
                                    "fontWeight": "400"
                            }
                    ],
                    "display": [
                            "36px",
                            {
                                    "lineHeight": "44px",
                                    "letterSpacing": "-0.02em",
                                    "fontWeight": "700"
                            }
                    ],
                    "body-md": [
                            "14px",
                            {
                                    "lineHeight": "20px",
                                    "fontWeight": "400"
                            }
                    ]
            }
        },
        },
      }
    </script>
<style>
        /* Base styles */
        body {
            @apply bg-background text-on-background font-body-md;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            @apply bg-surface-variant rounded-full;
        }
        ::-webkit-scrollbar-thumb:hover {
            @apply bg-outline-variant;
        }

        /* Form Inputs */
        .form-input, .form-textarea, .form-select {
            @apply w-full rounded-lg border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-md text-on-surface shadow-sm focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-50 transition-shadow duration-200;
        }
        .form-label {
            @apply block mb-1 font-label-md text-label-md text-on-surface-variant;
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden antialiased selection:bg-primary-container selection:text-on-primary-container">
<!-- SideNavBar (Suppressed for transactional/form page as per semantic shell mandate: "Linear/Transactional: Login, Sign-up, Onboarding, Checkout, or "Success/Confirmation" splash screens... Any sub-page featuring a 'Close' or 'Back' action that implies a temporary departure from the main navigation flow." - A registration form is a focused task/transactional.) -->
<!-- Navigation Shell Suppressed to focus on the canvas -->
<!-- TopAppBar (Suppressed for same reason) -->
<!-- Main Content Area (Full width since nav is suppressed) -->
<main class="flex-1 flex flex-col h-full overflow-y-auto bg-background">
<!-- Header Section -->
<header class="bg-surface-container-lowest border-b border-outline-variant px-container-padding py-6 sticky top-0 z-10">
<div class="max-w-5xl mx-auto flex items-center justify-between">
<div>
<div class="flex items-center gap-2 mb-2">
<button aria-label="Kembali" class="text-on-surface-variant hover:bg-surface-container-low p-2 rounded-full transition-colors flex items-center justify-center h-8 w-8">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">arrow_back</span>
</button>
<h1 class="font-headline-lg text-headline-lg text-on-surface">Pendaftaran Perangkat Server</h1>
</div>
<p class="font-body-md text-body-md text-on-surface-variant ml-10">SIM TIK - Form registrasi perangkat baru ke dalam Data Center</p>
</div>
</div>
</header>
<!-- Form Container -->
<div class="flex-1 px-container-padding py-8">
<div class="max-w-5xl mx-auto">
<form action="#" class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden flex flex-col" method="POST">
<div class="p-8 flex-1">
<div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
<!-- Kolom Kiri -->
<div class="space-y-6">
<h2 class="font-headline-md text-headline-md text-on-surface border-b border-outline-variant pb-2 mb-4">Informasi Teknis</h2>
<div>
<label class="form-label" for="type">Type</label>
<input class="form-input" id="type" name="type" placeholder="Masukkan tipe server (misal: Rack Mount, Tower)" type="text">
</div>
<div>
<label class="form-label mb-2">Status SSL</label>
<input class="form-input" id="ssl_status" name="ssl_status" placeholder="Masukkan status atau versi SSL" type="text">
</div>
<div>
<label class="form-label" for="spesifikasi">Spesifikasi</label>
<textarea class="form-textarea resize-none" id="spesifikasi" name="spesifikasi" placeholder="Masukkan detail spesifikasi server (CPU, RAM, Storage, dll)..." rows="4"></textarea>
</div>
<div>
<label class="form-label" for="dinas">Dinas / OPD Instansi</label>
<input class="form-input" id="dinas" name="dinas" placeholder="Masukkan nama Dinas / OPD Instansi" type="text">
</div>
</div>
<!-- Kolom Kanan -->
<div class="space-y-6">
<h2 class="font-headline-md text-headline-md text-on-surface border-b border-outline-variant pb-2 mb-4">Penanggung Jawab &amp; Lokasi</h2>
<div>
<label class="form-label" for="penanggung_jawab">Penanggung Jawab</label>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<span class="material-symbols-outlined text-outline" style="font-variation-settings: 'FILL' 0;">person</span>
</div>
<input class="form-input pl-10" id="penanggung_jawab" name="penanggung_jawab" placeholder="Nama lengkap penanggung jawab" type="text">
</div>
</div>
<div>
<label class="form-label" for="penerima_server">Penerima Server</label>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<span class="material-symbols-outlined text-outline" style="font-variation-settings: 'FILL' 0;">badge</span>
</div>
<input class="form-input pl-10" id="penerima_server" name="penerima_server" placeholder="Nama petugas penerima" type="text">
</div>
</div>
<div>
<label class="form-label" for="lokasi_rak">Lokasi Rak Server</label>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<span class="material-symbols-outlined text-outline" style="font-variation-settings: 'FILL' 0;">dns</span>
</div>
<select class="form-select pl-10" id="lokasi_rak" name="lokasi_rak">
<option disabled="" selected="" value="">Pilih Rak...</option>
<option value="rack_1">Rack 1</option>
<option value="rack_2">Rack 2</option>
<option value="rack_3">Rack 3</option>
<option value="rack_4">Rack 4</option>
<option value="rack_5">Rack 5</option>
<option value="rack_6">Rack 6</option>
<option value="rack_7">Rack 7</option>
<option value="rack_8">Rack 8</option>
</select>
</div>
</div>
<!-- Decorative element to fill space nicely -->
<div class="mt-8 p-4 bg-surface-container-low rounded-lg border border-outline-variant/50">
<div class="flex items-start gap-3">
<span class="material-symbols-outlined text-primary mt-0.5" style="font-variation-settings: 'FILL' 1;">info</span>
<p class="font-body-md text-body-md text-on-surface-variant">
                                            Pastikan semua data diisi dengan benar sesuai dengan dokumen serah terima perangkat. Data yang disimpan akan langsung terintegrasi dengan sistem monitoring NOC.
                                        </p>
</div>
</div>
</div>
</div>
</div>
<!-- Footer Form / Actions -->
<div class="bg-surface-container-low border-t border-outline-variant px-8 py-5 flex items-center justify-end gap-4">
<button class="px-6 py-2.5 rounded-lg border border-outline font-label-md text-label-md text-secondary hover:bg-surface-variant hover:text-on-surface transition-colors focus:ring-2 focus:ring-outline focus:outline-none" type="button">
                            Batal
                        </button>
<button class="px-6 py-2.5 rounded-lg bg-primary font-label-md text-label-md text-on-primary hover:bg-on-primary-fixed-variant transition-colors shadow-sm focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:outline-none flex items-center gap-2" type="submit">
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">save</span>
                            Simpan
                        </button>
</div>
</form>
</div>
</div>
</main>
<script>
        // Simple interaction for SSL toggle label
        const sslToggle = document.getElementById('ssl_status');
        const sslLabel = document.getElementById('ssl_label');

        if(sslToggle && sslLabel) {
            sslToggle.addEventListener('change', function() {
                if(this.checked) {
                    sslLabel.textContent = 'Aktif';
                    sslLabel.classList.remove('text-on-surface-variant');
                    sslLabel.classList.add('text-on-surface');
                } else {
                    sslLabel.textContent = 'Tidak Aktif';
                    sslLabel.classList.remove('text-on-surface');
                    sslLabel.classList.add('text-on-surface-variant');
                }
            });

            // Sync UI state for tailwind pseudo classes on the custom toggle
            const block = sslToggle.nextElementSibling;
            const dot = block.nextElementSibling;

            sslToggle.addEventListener('change', function() {
                if(this.checked) {
                    block.classList.replace('bg-surface-variant', 'bg-primary');
                    dot.classList.add('translate-x-6');
                } else {
                    block.classList.replace('bg-primary', 'bg-surface-variant');
                    dot.classList.remove('translate-x-6');
                }
            });
        }
    </script>


</body></html>
