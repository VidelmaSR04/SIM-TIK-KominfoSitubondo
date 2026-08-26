<!DOCTYPE html>

<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>SIM TIK - Admin Setup</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
                        "on-surface-variant": "#434655",
                        "secondary-fixed": "#dae2fd",
                        "surface-container-lowest": "#ffffff",
                        "error-container": "#ffdad6",
                        "secondary": "#565e74",
                        "on-secondary-fixed": "#131b2e",
                        "on-secondary-fixed-variant": "#3f465c",
                        "outline-variant": "#c3c6d7",
                        "surface-container-high": "#e6e8ea",
                        "surface-container-highest": "#e0e3e5",
                        "on-tertiary-fixed-variant": "#653e00",
                        "tertiary": "#784b00",
                        "on-background": "#191c1e",
                        "tertiary-container": "#996100",
                        "primary-container": "#2563eb",
                        "on-primary-container": "#eeefff",
                        "surface-container": "#eceef0",
                        "secondary-container": "#dae2fd",
                        "tertiary-fixed": "#ffddb8",
                        "outline": "#737686",
                        "tertiary-fixed-dim": "#ffb95f",
                        "on-primary-fixed": "#00174b",
                        "surface-dim": "#d8dadc",
                        "on-secondary-container": "#5c647a",
                        "on-error-container": "#93000a",
                        "on-primary-fixed-variant": "#003ea8",
                        "on-tertiary": "#ffffff",
                        "surface-tint": "#0053db",
                        "surface-bright": "#f7f9fb",
                        "primary-fixed": "#dbe1ff",
                        "on-tertiary-container": "#ffeedd",
                        "inverse-on-surface": "#eff1f3",
                        "on-error": "#ffffff",
                        "on-secondary": "#ffffff",
                        "error": "#ba1a1a",
                        "surface-variant": "#e0e3e5"
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
                        "body-lg": ["Inter"],
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
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-on-secondary-fixed min-h-screen flex items-center justify-center p-4 sm:p-8 font-body-md text-body-md relative overflow-hidden">
<div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_-20%,_var(--tw-gradient-stops))] from-on-secondary-fixed-variant/50 via-transparent to-transparent pointer-events-none"></div>
<main class="w-full max-w-[480px] bg-surface-container-lowest rounded-xl shadow-[0_10px_25px_-5px_rgba(0,0,0,0.5)] flex flex-col border border-outline-variant overflow-hidden relative z-10">
<div class="bg-tertiary-fixed p-4 sm:p-5 flex items-start gap-3 border-b border-tertiary-fixed-dim">
<span class="material-symbols-outlined text-tertiary font-headline-md mt-0.5" data-weight="fill" style="font-variation-settings: 'FILL' 1;">warning</span>
<p class="font-body-md text-body-md text-on-tertiary-fixed font-semibold leading-relaxed">
                Halaman internal — hanya untuk kebutuhan testing/setup awal sistem. Tidak untuk pendaftaran umum.
            </p>
</div>
<div class="p-6 sm:p-8 flex flex-col gap-8">
<div class="flex flex-col gap-4">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center text-on-primary">
<span class="material-symbols-outlined">dns</span>
</div>
<div>
<h1 class="font-headline-lg text-headline-lg text-on-surface m-0">SIM TIK Setup</h1>
<p class="font-body-md text-body-md text-on-surface-variant m-0">Inisialisasi Sistem</p>
</div>
</div>
<div class="inline-flex items-center gap-2 bg-secondary-container/50 px-3 py-1.5 rounded-full self-start border border-secondary-fixed-dim">
<span class="material-symbols-outlined text-[16px] text-primary">admin_panel_settings</span>
<span class="font-label-md text-label-md text-on-secondary-container">Role: Administrator</span>
</div>
</div>
<form class="flex flex-col gap-5">
<div class="flex flex-col gap-1.5">
<label class="font-label-md text-label-md text-on-surface" for="nama_lengkap">Nama Lengkap</label>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<span class="material-symbols-outlined text-outline text-[20px]">person</span>
</div>
<input class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-outline-variant bg-surface-container-lowest text-on-surface font-body-md text-body-md focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none transition-all placeholder:text-outline/70" id="nama_lengkap" placeholder="Masukkan nama lengkap" type="text"/>
</div>
</div>
<div class="flex flex-col gap-1.5">
<label class="font-label-md text-label-md text-on-surface" for="email">Email</label>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<span class="material-symbols-outlined text-outline text-[20px]">mail</span>
</div>
<input class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-outline-variant bg-surface-container-lowest text-on-surface font-body-md text-body-md focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none transition-all placeholder:text-outline/70" id="email" placeholder="admin@situbondokab.go.id" type="email"/>
</div>
</div>
<div class="flex flex-col gap-1.5">
<label class="font-label-md text-label-md text-on-surface" for="password">Password</label>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<span class="material-symbols-outlined text-outline text-[20px]">lock</span>
</div>
<input class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-outline-variant bg-surface-container-lowest text-on-surface font-body-md text-body-md focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none transition-all placeholder:text-outline/70" id="password" placeholder="••••••••" type="password"/>
</div>
</div>
<div class="flex flex-col gap-1.5">
<label class="font-label-md text-label-md text-on-surface" for="konfirmasi_password">Konfirmasi Password</label>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<span class="material-symbols-outlined text-outline text-[20px]">enhanced_encryption</span>
</div>
<input class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-outline-variant bg-surface-container-lowest text-on-surface font-body-md text-body-md focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none transition-all placeholder:text-outline/70" id="konfirmasi_password" placeholder="••••••••" type="password"/>
</div>
</div>
<div class="mt-2">
<button class="w-full bg-on-surface text-surface-container-lowest font-label-md text-label-md py-3.5 rounded-lg hover:bg-inverse-surface active:bg-black transition-all flex justify-center items-center gap-2 shadow-sm" type="button">
                        Daftar sebagai Adminn
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
</button>
</div>
</form>
</div>
</main>
</body></html>
