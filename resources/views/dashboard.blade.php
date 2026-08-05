<!DOCTYPE html>

<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>SIM TIK - Dashboard Server dan Aplikasi</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
                      "data-tabular": ["Inter"],
                      "headline-lg": ["Inter"],
                      "headline-md": ["Inter"],
                      "label-md": ["Inter"],
                      "body-lg": ["Inter"],
                      "display": ["Inter"],
                      "body-md": ["Inter"]
              },
              "fontSize": {
                      "data-tabular": ["13px", {"lineHeight": "18px", "fontWeight": "400"}],
                      "headline-lg": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                      "headline-md": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                      "label-md": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                      "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                      "display": ["36px", {"lineHeight": "44px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                      "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}]
              }
            }
          }
        }
    </script>
<style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 1; }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        /* Custom scrollbar for tables */
        .table-container::-webkit-scrollbar { height: 8px; }
        .table-container::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .table-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .table-container::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-background text-on-background font-body-md min-h-screen flex">
<!-- SideNavBar -->
<aside class="fixed left-0 top-0 h-full w-sidebar-width bg-[#0F172A] shadow-sm flex flex-col z-20 hidden md:flex">
<div class="p-6 flex items-center gap-4">
<img class="w-12 h-12 rounded-full object-cover border-2 border-slate-700" data-alt="A small, professional circular avatar profile picture for an IT admin in a light mode enterprise application. Clean corporate lighting, subtle grey background, high resolution rendering showing a confident tech professional in a modern office environment." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDUWc-wkowPv8dVH3e4J0uJizrF7Oxn05UIk5vtAd6fJ-TNS4TPWXN11Yipi9vlX0WvEB_D-i2I9R4_1rqO0CfCAw-ewrS1AXPNY22ArfhQFI2DV4JCcoaoKmY5Anse129d1FvTY2JdrLQleZvJb7P7BiS5B0SnQs_J1lQpBockk70zHau_XR0MtiFEZ_FKAehZ8_t-5yvWkm50awQvZA6d2bBBIrDKDl2uPh2fcK4MG3wM1XBA7LUQ"/>
<div>
<h1 class="font-headline-lg text-headline-lg font-bold text-white">SIM TIK</h1>
<p class="font-body-md text-body-md text-slate-400">Admin Data Center</p>
</div>
</div>
<nav class="flex-1 overflow-y-auto mt-4 space-y-1 px-3">
<a class="flex items-center gap-3 px-4 py-3 border-l-4 border-primary bg-primary/10 text-primary font-semibold rounded-r-lg transition-all duration-200 ease-in-out" href="#">
<span class="material-symbols-outlined text-xl">dns</span>
<span>Server &amp; Aplikasi</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 border-l-4 border-transparent text-slate-300 hover:text-white hover:bg-slate-800 transition-colors rounded-r-lg" href="#">
<span class="material-symbols-outlined text-xl">settings_applications</span>
<span>cPanel</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 border-l-4 border-transparent text-slate-300 hover:text-white hover:bg-slate-800 transition-colors rounded-r-lg" href="#">
<span class="material-symbols-outlined text-xl">apps</span>
<span>Aplikasi</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 border-l-4 border-transparent text-slate-300 hover:text-white hover:bg-slate-800 transition-colors rounded-r-lg" href="#">
<span class="material-symbols-outlined text-xl">swap_horiz</span>
<span>SPLP</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 border-l-4 border-transparent text-slate-300 hover:text-white hover:bg-slate-800 transition-colors rounded-r-lg" href="#">
<span class="material-symbols-outlined text-xl">assignment</span>
<span>Laporan Tugas</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 border-l-4 border-transparent text-slate-300 hover:text-white hover:bg-slate-800 transition-colors rounded-r-lg" href="#">
<span class="material-symbols-outlined text-xl">monitor_heart</span>
<span>Laporan NOC</span>
</a>
</nav>
</aside>
<!-- Main Content Wrapper -->
<div class="flex-1 flex flex-col md:ml-sidebar-width min-w-0">
<!-- TopNavBar -->
<header class="bg-surface dark:bg-surface-container docked full-width top-0 h-topbar-height border-b border-outline-variant shadow-sm flex justify-between items-center px-container-padding sticky z-10">
<div class="flex items-center gap-4">
<!-- Mobile Menu Button (hidden on desktop) -->
<button class="md:hidden text-on-surface-variant hover:bg-surface-container-low p-2 rounded-full transition-colors">
<span class="material-symbols-outlined">menu</span>
</button>
<div class="relative hidden sm:block">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
<input class="pl-10 pr-4 py-2 border border-outline-variant rounded-lg font-body-md text-body-md focus:ring-2 focus:ring-primary focus:border-primary bg-white text-on-surface w-64" placeholder="Pencarian..." type="text"/>
</div>
</div>
<div class="flex items-center gap-2">
<button class="text-on-surface-variant hover:bg-surface-container-low p-2 rounded-full transition-colors relative">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-1 right-1 w-2.5 h-2.5 bg-error rounded-full"></span>
</button>
<button class="text-on-surface-variant hover:bg-surface-container-low p-2 rounded-full transition-colors">
<span class="material-symbols-outlined">qr_code_scanner</span>
</button>
<button class="text-on-surface-variant hover:bg-surface-container-low p-2 rounded-full transition-colors">
<span class="material-symbols-outlined">account_circle</span>
</button>
</div>
</header>
<!-- Main Content Area -->
<main class="flex-1 p-container-padding space-y-6 overflow-x-hidden">
<!-- Page Header -->
<div>
<h2 class="font-headline-lg text-headline-lg text-on-surface mb-1">Dashboard Server dan Aplikasi</h2>
<div class="font-body-md text-body-md text-secondary flex items-center gap-2">
<span class="material-symbols-outlined text-[16px]">home</span>
<span class="text-outline">/</span>
<span class="text-primary font-medium">Dashboard</span>
</div>
</div>
<!-- Summary Cards (Bento style) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
<!-- Card 1 -->
<div class="bg-white rounded-xl border border-outline-variant p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
<div class="absolute right-0 top-0 w-24 h-24 bg-primary/5 rounded-bl-full -z-10 group-hover:bg-primary/10 transition-colors"></div>
<div class="flex justify-between items-start mb-4">
<div>
<p class="font-label-md text-label-md text-secondary uppercase tracking-wider">All Devices</p>
<h3 class="font-display text-display text-on-surface mt-1">1,248</h3>
</div>
<div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary-container flex items-center justify-center">
<span class="material-symbols-outlined">devices</span>
</div>
</div>
<div class="flex items-center gap-1 text-sm">
<span class="material-symbols-outlined text-[16px] text-green-600">trending_up</span>
<span class="text-green-600 font-medium">+12%</span>
<span class="text-secondary text-xs">dari bulan lalu</span>
</div>
</div>
<!-- Card 2 -->
<div class="bg-white rounded-xl border border-outline-variant p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
<div class="absolute right-0 top-0 w-24 h-24 bg-green-50 rounded-bl-full -z-10 group-hover:bg-green-100 transition-colors"></div>
<div class="flex justify-between items-start mb-4">
<div>
<p class="font-label-md text-label-md text-secondary uppercase tracking-wider">Application</p>
<h3 class="font-display text-display text-on-surface mt-1">342</h3>
</div>
<div class="w-10 h-10 rounded-lg bg-green-100 text-green-700 flex items-center justify-center">
<span class="material-symbols-outlined">web</span>
</div>
</div>
<div class="flex items-center gap-1 text-sm">
<span class="material-symbols-outlined text-[16px] text-green-600">trending_up</span>
<span class="text-green-600 font-medium">+5</span>
<span class="text-secondary text-xs">aplikasi baru</span>
</div>
</div>
<!-- Card 3 -->
<div class="bg-white rounded-xl border border-outline-variant p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
<div class="absolute right-0 top-0 w-24 h-24 bg-yellow-50 rounded-bl-full -z-10 group-hover:bg-yellow-100 transition-colors"></div>
<div class="flex justify-between items-start mb-4">
<div>
<p class="font-label-md text-label-md text-secondary uppercase tracking-wider">VPS</p>
<h3 class="font-display text-display text-on-surface mt-1">86</h3>
</div>
<div class="w-10 h-10 rounded-lg bg-yellow-100 text-yellow-700 flex items-center justify-center">
<span class="material-symbols-outlined">storage</span>
</div>
</div>
<div class="w-full bg-surface-container-high rounded-full h-1.5 mt-4">
<div class="bg-yellow-500 h-1.5 rounded-full" style="width: 75%"></div>
</div>
<p class="text-xs text-secondary mt-2">75% Kapasitas Terpakai</p>
</div>
<!-- Card 4 -->
<div class="bg-white rounded-xl border border-outline-variant p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
<div class="absolute right-0 top-0 w-24 h-24 bg-red-50 rounded-bl-full -z-10 group-hover:bg-red-100 transition-colors"></div>
<div class="flex justify-between items-start mb-4">
<div>
<p class="font-label-md text-label-md text-secondary uppercase tracking-wider">Domain</p>
<h3 class="font-display text-display text-on-surface mt-1">112</h3>
</div>
<div class="w-10 h-10 rounded-lg bg-red-100 text-red-700 flex items-center justify-center">
<span class="material-symbols-outlined">language</span>
</div>
</div>
<div class="flex items-center gap-1 text-sm mt-3">
<span class="material-symbols-outlined text-[16px] text-red-600">warning</span>
<span class="text-red-600 font-medium">3 Expiring</span>
<span class="text-secondary text-xs">dalam 30 hari</span>
</div>
</div>
</div>
<!-- Info Section (2 Columns) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
<!-- Left: Chart Area -->
<div class="lg:col-span-2 bg-white rounded-xl border border-outline-variant shadow-sm p-6">
<div class="flex justify-between items-center mb-6">
<h3 class="font-headline-md text-headline-md text-on-surface">Distribusi RACK Server</h3>
<button class="text-primary text-sm font-medium hover:underline">Lihat Detail</button>
</div>
<!-- Simulated Bar Chart -->
<div class="h-64 flex items-end justify-between gap-2 pb-4 border-b border-outline-variant relative">
<!-- Y-axis labels -->
<div class="absolute left-0 top-0 h-full flex flex-col justify-between text-xs text-secondary py-4 pr-2">
<span>100</span><span>75</span><span>50</span><span>25</span><span>0</span>
</div>
<!-- Bars -->
<div class="flex-1 flex items-end justify-around pl-8 h-[200px]">
<div class="w-full max-w-[40px] bg-primary/20 hover:bg-primary/40 rounded-t-sm relative group cursor-pointer" style="height: 80%">
<div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-inverse-surface text-inverse-on-surface text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity">R1: 80</div>
</div>
<div class="w-full max-w-[40px] bg-primary rounded-t-sm relative group cursor-pointer" style="height: 95%">
<div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-inverse-surface text-inverse-on-surface text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity">R2: 95</div>
</div>
<div class="w-full max-w-[40px] bg-primary/60 rounded-t-sm relative group cursor-pointer" style="height: 60%">
<div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-inverse-surface text-inverse-on-surface text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity">R3: 60</div>
</div>
<div class="w-full max-w-[40px] bg-primary/40 rounded-t-sm relative group cursor-pointer" style="height: 45%">
<div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-inverse-surface text-inverse-on-surface text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity">R4: 45</div>
</div>
<div class="w-full max-w-[40px] bg-primary/80 rounded-t-sm relative group cursor-pointer" style="height: 75%">
<div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-inverse-surface text-inverse-on-surface text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity">R5: 75</div>
</div>
<div class="w-full max-w-[40px] bg-primary/30 rounded-t-sm relative group cursor-pointer" style="height: 30%">
<div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-inverse-surface text-inverse-on-surface text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity">R6: 30</div>
</div>
<div class="w-full max-w-[40px] bg-primary/50 rounded-t-sm relative group cursor-pointer" style="height: 55%">
<div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-inverse-surface text-inverse-on-surface text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity">R7: 55</div>
</div>
<div class="w-full max-w-[40px] bg-primary/90 rounded-t-sm relative group cursor-pointer" style="height: 85%">
<div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-inverse-surface text-inverse-on-surface text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity">R8: 85</div>
</div>
</div>
</div>
<!-- X-axis labels -->
<div class="flex justify-around pl-8 pt-2 text-xs text-secondary font-medium">
<span>R1</span><span>R2</span><span>R3</span><span>R4</span><span>R5</span><span>R6</span><span>R7</span><span>R8</span>
</div>
<!-- Mini Stats below chart -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8 pt-4 border-t border-outline-variant/50">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded bg-surface-container flex items-center justify-center text-secondary">
<span class="material-symbols-outlined text-[18px]">dns</span>
</div>
<div>
<p class="text-xs text-secondary">Rack Mount</p>
<p class="font-semibold text-on-surface">324 Unit</p>
</div>
</div>
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded bg-surface-container flex items-center justify-center text-secondary">
<span class="material-symbols-outlined text-[18px]">computer</span>
</div>
<div>
<p class="text-xs text-secondary">Tower</p>
<p class="font-semibold text-on-surface">45 Unit</p>
</div>
</div>
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded bg-surface-container flex items-center justify-center text-secondary">
<span class="material-symbols-outlined text-[18px]">domain</span>
</div>
<div>
<p class="text-xs text-secondary">Kominfo</p>
<p class="font-semibold text-on-surface">210 Aset</p>
</div>
</div>
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded bg-surface-container flex items-center justify-center text-secondary">
<span class="material-symbols-outlined text-[18px]">handshake</span>
</div>
<div>
<p class="text-xs text-secondary">Colocation</p>
<p class="font-semibold text-on-surface">159 Aset</p>
</div>
</div>
</div>
</div>
</div>
<!-- Data Tables Section -->
<div class="bg-white rounded-xl border border-outline-variant shadow-sm overflow-hidden flex flex-col">
<!-- Tabs and Controls -->
<div class="p-4 border-b border-outline-variant flex flex-col sm:flex-row justify-between gap-4 items-center bg-surface-bright">
<div class="flex space-x-1 bg-surface-container-low p-1 rounded-lg self-start sm:self-auto">
<button class="px-4 py-2 text-sm font-medium rounded-md bg-white text-primary shadow-sm">Server</button>
<button class="px-4 py-2 text-sm font-medium rounded-md text-secondary hover:text-on-surface hover:bg-white/50 transition-all">cPanel</button>
<button class="px-4 py-2 text-sm font-medium rounded-md text-secondary hover:text-on-surface hover:bg-white/50 transition-all">Aplikasi</button>
</div>
<div class="flex items-center gap-3 w-full sm:w-auto">
<div class="flex items-center gap-2 text-sm text-secondary whitespace-nowrap">
<span>Show</span>
<select class="border-outline-variant rounded bg-white py-1 px-2 text-sm focus:ring-primary focus:border-primary">
<option>10</option>
<option>25</option>
<option>50</option>
</select>
<span>entries</span>
</div>
<div class="relative w-full sm:w-64">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
<input class="pl-9 pr-3 py-1.5 w-full border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary" placeholder="Cari data..." type="text"/>
</div>
</div>
</div>
<!-- Table Content (Server Table Active) -->
<div class="table-container overflow-x-auto">
<table class="w-full text-left border-collapse min-w-[800px]">
<thead>
<tr class="bg-[#F1F5F9] font-label-md text-label-md text-on-surface-variant border-y border-outline-variant">
<th class="p-4 w-16">ID</th>
<th class="p-4">Nama Perangkat</th>
<th class="p-4">IP Server</th>
<th class="p-4">IP VPS</th>
<th class="p-4 w-32">Status</th>
<th class="p-4 w-24 text-center">Action</th>
</tr>
</thead>
<tbody class="font-data-tabular text-data-tabular text-on-surface divide-y divide-outline-variant/50">
<tr class="hover:bg-surface-container-lowest transition-colors group">
<td class="p-4 text-secondary">SRV-001</td>
<td class="p-4 font-medium flex items-center gap-2">
<span class="material-symbols-outlined text-secondary text-[18px]">dns</span>
                                    Node-DB-Master-01
                                </td>
<td class="p-4 font-mono text-xs">192.168.10.101</td>
<td class="p-4 font-mono text-xs">10.0.5.12</td>
<td class="p-4">
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
<span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                    </span>
</td>
<td class="p-4 text-center">
<button class="text-secondary hover:text-primary transition-colors p-1 rounded hover:bg-surface-container"><span class="material-symbols-outlined text-[20px]">more_vert</span></button>
</td>
</tr>
<tr class="hover:bg-surface-container-lowest transition-colors group">
<td class="p-4 text-secondary">SRV-002</td>
<td class="p-4 font-medium flex items-center gap-2">
<span class="material-symbols-outlined text-secondary text-[18px]">dns</span>
                                    Web-Frontend-02
                                </td>
<td class="p-4 font-mono text-xs">192.168.10.102</td>
<td class="p-4 font-mono text-xs">-</td>
<td class="p-4">
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
<span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                    </span>
</td>
<td class="p-4 text-center">
<button class="text-secondary hover:text-primary transition-colors p-1 rounded hover:bg-surface-container"><span class="material-symbols-outlined text-[20px]">more_vert</span></button>
</td>
</tr>
<tr class="hover:bg-surface-container-lowest transition-colors group">
<td class="p-4 text-secondary">SRV-003</td>
<td class="p-4 font-medium flex items-center gap-2">
<span class="material-symbols-outlined text-secondary text-[18px]">dns</span>
                                    Storage-Backup-A
                                </td>
<td class="p-4 font-mono text-xs">192.168.20.55</td>
<td class="p-4 font-mono text-xs">10.0.8.44</td>
<td class="p-4">
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
<span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Maintenance
                                    </span>
</td>
<td class="p-4 text-center">
<button class="text-secondary hover:text-primary transition-colors p-1 rounded hover:bg-surface-container"><span class="material-symbols-outlined text-[20px]">more_vert</span></button>
</td>
</tr>
<tr class="hover:bg-surface-container-lowest transition-colors group">
<td class="p-4 text-secondary">SRV-004</td>
<td class="p-4 font-medium flex items-center gap-2">
<span class="material-symbols-outlined text-secondary text-[18px]">dns</span>
                                    API-Gateway-Core
                                </td>
<td class="p-4 font-mono text-xs">192.168.10.200</td>
<td class="p-4 font-mono text-xs">10.0.5.99</td>
<td class="p-4">
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
<span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Offline
                                    </span>
</td>
<td class="p-4 text-center">
<button class="text-secondary hover:text-primary transition-colors p-1 rounded hover:bg-surface-container"><span class="material-symbols-outlined text-[20px]">more_vert</span></button>
</td>
</tr>
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="p-4 border-t border-outline-variant flex items-center justify-between text-sm">
<span class="text-secondary">Menampilkan 1 hingga 4 dari 324 entri</span>
<div class="flex gap-1">
<button class="px-3 py-1 border border-outline-variant rounded text-secondary hover:bg-surface-container disabled:opacity-50" disabled="">Prev</button>
<button class="px-3 py-1 bg-primary text-white rounded">1</button>
<button class="px-3 py-1 border border-outline-variant rounded text-secondary hover:bg-surface-container">2</button>
<button class="px-3 py-1 border border-outline-variant rounded text-secondary hover:bg-surface-container">3</button>
<span class="px-2 py-1 text-secondary">...</span>
<button class="px-3 py-1 border border-outline-variant rounded text-secondary hover:bg-surface-container">Next</button>
</div>
</div>
</div>
</main>
<!-- Footer -->
<footer class="bg-surface-container-lowest full-width py-4 border-t border-outline-variant flex justify-center items-center mt-auto">
<p class="font-body-md text-body-md text-secondary">Copyright © 2026 Dinas Komunikasi dan Informatika Kab. Situbondo. All rights reserved.</p>
</footer>
</div>
</body></html>
