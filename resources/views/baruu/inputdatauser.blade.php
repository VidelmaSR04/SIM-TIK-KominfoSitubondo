<!DOCTYPE html><html lang="id" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Daftarkan Perangkat Baru - SIM TIK</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
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
        body { background-color: theme('colors.background'); }
    </style>
</head>
<body class="font-body-md text-on-background bg-background antialiased flex h-screen overflow-hidden">
<!-- SideNavBar -->
<nav class="fixed left-0 top-0 h-full w-[260px] bg-[#0F172A] border-r border-outline-variant shadow-sm flex flex-col overflow-y-auto z-20">
<div class="p-6 border-b border-slate-700">
<div class="flex items-center gap-4 mb-2">
<div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center">
<span class="material-symbols-outlined text-white">dns</span>
</div>
<div>
<h1 class="font-headline-md text-headline-md text-white font-bold tracking-tight">SIM TIK</h1>
<p class="text-slate-400 text-xs">Dinas Kominfo Situbondo</p>
</div>
</div>
</div>
<div class="p-4">
<button class="w-full bg-primary hover:bg-primary/90 text-white font-label-md text-label-md py-3 rounded-lg flex items-center justify-center gap-2 transition-colors mb-6 shadow-sm">
<span class="material-symbols-outlined text-sm">add</span>
                Tambah Aset
            </button>
<ul class="space-y-1">
<li class="">
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors cursor-pointer active:opacity-80" href="#">
<span class="material-symbols-outlined">dashboard</span>
<span class="font-body-md text-body-md">Dashboard</span>
</a>
</li>


<li class="">
<a class="flex items-center gap-3 px-4 py-3 rounded-lg border-l-4 border-primary bg-primary/10 text-white font-semibold cursor-pointer active:opacity-80" href="#">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">inventory_2</span>
<span class="font-body-md text-body-md">Aset TIK</span>
</a>
</li>
<li class="">

</li>
<li class="">

</li>
</ul>
</div>
<div class="mt-auto p-4 border-t border-slate-700">
<ul class="space-y-1">
<li class="">

</li>
<li class="">
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors cursor-pointer active:opacity-80" href="#">
<span class="material-symbols-outlined">logout</span>
<span class="font-body-md text-body-md">Keluar</span>
</a>
</li>
</ul>
</div>
</nav>
<!-- Main Content Area -->
<div class="flex-1 flex flex-col ml-[260px] w-[calc(100%-260px)] h-full relative">
<!-- TopNavBar -->
<header class="docked full-width top-0 h-[topbar-height] bg-surface-container-lowest border-b border-outline-variant shadow-sm flex justify-between items-center px-container-padding z-10">
<div class="flex items-center gap-4 flex-1">
<div class="relative w-96 max-w-full group focus-within:ring-2 focus-within:ring-primary rounded-lg">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
<input class="w-full pl-10 pr-4 py-2 bg-surface border border-outline-variant rounded-lg font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary transition-colors" placeholder="Pencarian..." type="text">
</div>
</div>
<div class="flex items-center gap-4">
<button class="p-2 rounded-full text-on-surface-variant hover:bg-surface-container-high transition-all relative">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-1.5 right-1.5 w-2 h-2 bg-error rounded-full"></span>
</button>
<button class="p-2 rounded-full text-on-surface-variant hover:bg-surface-container-high transition-all">
<span class="material-symbols-outlined">help</span>
</button>
<div class="w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center overflow-hidden border border-outline-variant ml-2 cursor-pointer">
<img alt="Admin Profile" class="w-full h-full object-cover" data-alt="A clean, professional headshot photo of an administrator wearing a dark suit against a neutral white background. Studio lighting, sharp focus, 8k resolution, photorealistic corporate style." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCZaNMUFJiIknFgX9ZLSFn_Kh0fI8pZRPf5B-v9YWXBSPGmzhLMogGR9jJE1rO82xPjE3NcHq0ZiUrvl4w4-Q4qLphHDgqoWkK4aXMfbwGg7tQ5gu_GIZKEWwSPl9Liww9mZt7oicBePz3wtBTQ7Ly-82m0CeXhdjiwLaXuOz91fCwwmOAP60u3Xq4rjPFZeFZoR8cm5QoDCQLIGMqVSzHRWVJQSTGD8UAlRMFBgQ58OSgZZOz_HjKB">
</div>
</div>
</header>
<!-- Page Content -->
<main class="flex-1 overflow-y-auto p-container-padding bg-background">
<div class="max-w-5xl mx-auto">
<!-- Header -->
<div class="mb-6">
<h2 class="font-headline-lg text-headline-lg text-on-surface mb-2">Daftarkan Perangkat Baru</h2>
<p class="font-body-md text-body-md text-on-surface-variant">Isi data dasar perangkat. Tim admin akan melengkapi detail teknis lanjutan.</p>
</div>
<!-- Info Banner -->
<div class="bg-[#DBEAFE] border border-blue-200 rounded-lg p-4 mb-8 flex items-start gap-3 shadow-sm">
<span class="material-symbols-outlined text-primary mt-0.5">info</span>
<p class="font-body-md text-body-md text-blue-900">Data akan berstatus <strong>PENDING</strong> hingga dilengkapi oleh admin.</p>
</div>
<!-- Form Card -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-8">
<form action="#" method="POST">
<div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
<!-- Jenis Perangkat -->
<div class="col-span-1">
<label class="block font-label-md text-label-md text-on-surface mb-2" for="jenis">Jenis Perangkat <span class="text-error">*</span></label>
<div class="relative">
<select class="w-full appearance-none bg-surface border border-outline-variant rounded-lg py-2.5 pl-4 pr-10 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors" id="jenis" name="jenis">
<option disabled="" selected="" value="">Pilih jenis perangkat</option>
<option value="server">Server</option>
<option value="switch">Switch</option>
<option value="router">Router</option>
<option value="firewall">Firewall</option>
<option value="ups">UPS</option>
</select>
<span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline pointer-events-none">expand_more</span>
</div>
</div>
<!-- Merk Perangkat -->
<div class="col-span-1">
<label class="block font-label-md text-label-md text-on-surface mb-2" for="merk">Merk Perangkat <span class="text-error">*</span></label>
<input class="w-full bg-surface border border-outline-variant rounded-lg py-2.5 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors" id="merk" name="merk" placeholder="Misal: Dell PowerEdge, Cisco..." type="text">
</div>
<!-- Nama Dinas/OPD -->
<div class="col-span-1">
<label class="block font-label-md text-label-md text-on-surface mb-2" for="dinas">Nama Dinas/OPD</label>
<input class="w-full bg-surface border border-outline-variant rounded-lg py-2.5 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors" id="dinas" name="dinas" placeholder="Masukkan nama instansi" type="text">
</div>
<!-- Penanggung Jawab -->
<div class="col-span-1">
<label class="block font-label-md text-label-md text-on-surface mb-2" for="pj">Penanggung Jawab</label>
<input class="w-full bg-surface border border-outline-variant rounded-lg py-2.5 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors" id="pj" name="pj" placeholder="Nama penanggung jawab" type="text">
</div>
<!-- Penerima Server -->
<div class="col-span-1">
<label class="block font-label-md text-label-md text-on-surface mb-2" for="penerima">Penerima Server</label>
<input class="w-full bg-surface border border-outline-variant rounded-lg py-2.5 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors" id="penerima" name="penerima" placeholder="Nama staf penerima" type="text">
</div>
<!-- Lokasi Rack -->
<div class="col-span-1">
<label class="block font-label-md text-label-md text-on-surface mb-2" for="rack">Lokasi Rack Usulan</label>
<input class="w-full bg-surface border border-outline-variant rounded-lg py-2.5 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors" id="rack" name="rack" placeholder="Misal: Rack A, U15 (Opsional)" type="text">
</div>
<!-- Spesifikasi (Full Width) -->
<div class="col-span-1 md:col-span-2">


</div>
</div>
<!-- Actions -->
<div class="mt-8 pt-6 border-t border-outline-variant flex justify-end gap-4">
<button class="px-6 py-2.5 rounded-lg border border-outline text-on-surface-variant font-label-md text-label-md hover:bg-surface-container-high transition-colors focus:ring-2 focus:ring-outline" type="button">
                                Batal
                            </button>
<button class="px-6 py-2.5 rounded-lg bg-primary-container text-on-primary-container font-label-md text-label-md hover:bg-primary hover:text-white transition-colors shadow-sm focus:ring-2 focus:ring-primary focus:ring-offset-2 flex items-center gap-2" type="submit">
<span class="material-symbols-outlined text-sm">save</span>
                                Simpan &amp; Daftarkan
                            </button>
</div>
</form>
</div>
</div>
</main>
</div>


</body></html>