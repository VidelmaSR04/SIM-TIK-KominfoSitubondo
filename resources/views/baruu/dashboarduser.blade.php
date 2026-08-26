<!DOCTYPE html><html class="light" lang="en" style="width: 1280px; height: 1024px; overflow: hidden; position: relative;"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Dashboard Pengguna - SIM TIK</title>
<!-- Google Fonts: Inter -->
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;display=swap" rel="stylesheet">
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Tailwind Theme Configuration -->
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-container-high": "#e6e8ea",
                        "on-surface-variant": "#434655",
                        "on-error": "#ffffff",
                        "tertiary-fixed-dim": "#ffb95f",
                        "secondary": "#565e74",
                        "on-secondary-fixed": "#131b2e",
                        "on-tertiary-container": "#ffeedd",
                        "on-primary-container": "#eeefff",
                        "surface-bright": "#f7f9fb",
                        "surface-dim": "#d8dadc",
                        "secondary-fixed": "#dae2fd",
                        "on-surface": "#191c1e",
                        "on-background": "#191c1e",
                        "primary": "#004ac6",
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
                        "base": "4px",
                        "gutter": "16px",
                        "container-padding": "24px",
                        "topbar-height": "64px",
                        "sidebar-width": "260px"
                    },
                    "fontFamily": {
                        "body-lg": ["Inter"],
                        "data-tabular": ["Inter"],
                        "body-md": ["Inter"],
                        "headline-lg": ["Inter"],
                        "label-md": ["Inter"],
                        "display": ["Inter"],
                        "headline-md": ["Inter"]
                    },
                    "fontSize": {
                        "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "data-tabular": ["13px", { "lineHeight": "18px", "fontWeight": "400" }],
                        "body-md": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                        "headline-lg": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "label-md": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600" }],
                        "display": ["36px", { "lineHeight": "44px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
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
<body class="bg-background text-on-surface font-body-md antialiased overflow-x-hidden">
<!-- SideNavBar (Shared Component) -->
<nav class="bg-[#0F172A] border-r border-outline-variant shadow-sm flex flex-col h-screen fixed left-0 top-0 z-40 w-[260px]">
<!-- Brand Header -->
<div class="h-topbar-height flex items-center px-6 border-b border-[#1E293B]">
<div class="w-8 h-8 rounded bg-primary-container flex items-center justify-center mr-3 shrink-0">
<span class="material-symbols-outlined text-on-primary-container" data-weight="fill" style="font-size: 20px;">dns</span>
</div>
<div>
<h1 class="font-headline-md text-headline-md font-bold text-white leading-tight">SIM TIK</h1>
<p class="font-label-md text-label-md text-white/60 font-normal">Asset Management</p>
</div>
</div>
<!-- Navigation Links -->
<div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
<!-- Active Tab: Dashboard -->
<a class="flex items-center px-3 py-2.5 rounded-lg border-l-4 border-primary bg-secondary-container/10 text-white font-bold hover:bg-secondary-container/5 transition-colors active:scale-[0.98] transition-transform group" href="#">
<span class="material-symbols-outlined mr-3 text-primary" data-weight="fill">dashboard</span>
<span class="font-body-md text-body-md">Dashboard</span>
</a>
<!-- Inactive Tabs -->
<a class="flex items-center px-3 py-2.5 rounded-lg border-l-4 border-transparent text-white/70 hover:text-white hover:bg-secondary-container/5 transition-colors active:scale-[0.98] transition-transform group" href="#">
<span class="material-symbols-outlined mr-3 group-hover:text-white transition-colors">dns</span>
<span class="font-body-md text-body-md">Server</span>
</a>




</div>
<!-- User Quick Actions / Footer Area -->
<div class="p-4 border-t border-[#1E293B]">
<a class="flex items-center text-white/70 hover:text-white font-body-md text-body-md transition-colors" href="#">
<span class="material-symbols-outlined mr-3">help</span>
                Bantuan &amp; Dukungan
            </a>
</div>
</nav>
<!-- TopNavBar (Shared Component) -->
<header class="bg-surface-container-lowest dark:bg-surface-dim border-b border-outline-variant shadow-sm fixed top-0 right-0 w-[calc(100%-260px)] h-topbar-height flex justify-between items-center px-container-padding ml-[260px] z-30">
<!-- Search Bar (on_left) -->
<div class="flex-1 flex items-center max-w-md">
<div class="relative w-full group">
<div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-on-surface-variant">
<span class="material-symbols-outlined text-[20px]">search</span>
</div>
<input class="bg-surface-container-low text-on-surface text-body-md rounded-lg block w-full pl-10 p-2 border border-transparent focus-within:ring-2 focus-within:ring-primary focus:border-transparent transition-all outline-none" placeholder="Cari perangkat, IP, atau nama pengguna..." type="text">
</div>
</div>
<!-- Trailing Actions & Profile -->
<div class="flex items-center space-x-4">
<!-- Notifications -->
<button class="relative p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-all focus:outline-none focus:ring-2 focus:ring-primary">
<span class="material-symbols-outlined" data-icon="notifications">notifications</span>
<span class="absolute top-1.5 right-1.5 w-2 h-2 bg-error rounded-full ring-2 ring-surface-container-lowest"></span>
</button>
<!-- Divider -->
<div class="h-6 w-px bg-outline-variant mx-2"></div>
<!-- Profile Dropdown Trigger -->
<button class="flex items-center space-x-3 hover:bg-surface-container-low p-1.5 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-primary">
<div class="w-8 h-8 rounded-full overflow-hidden bg-primary-fixed border border-outline-variant shrink-0">
<img alt="Administrator Profile" class="w-full h-full object-cover" data-alt="A professional corporate headshot of a senior IT administrator. The individual has a confident, approachable expression, wearing a neat business casual dark blue shirt. The background is a highly blurred, bright modern data center with soft white and blue LED ambient lighting, fitting perfectly into a clean enterprise software aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuD5_tEMQho6TuBV4pw-8-BtLH-AmWbZdycqBY3_KJK1iT3Ogwt6p6XTaqA5RpMIu4INYusyTdo-_J-LbME_qguD6E2EoHxKwdSEH5iDChCdF39wzTknO96PCRq6Mf21Xvrdrp65r07T-p1domHGDQu0vy-1yzbCQOGl2pWguv5AkbhRb9IJzG5iE0JXAiDiSHtYOiUqnwCk3wxIsnw5CnbKJvmwsFbmYMxBbviBj7AqhTwS7bMLZbgM">
</div>
<div class="text-left hidden md:block">
<p class="font-body-md text-body-md font-bold text-on-surface leading-tight">Admin Jaringan</p>
<p class="font-label-md text-label-md text-on-surface-variant font-normal leading-tight">Diskominfo</p>
</div>
<span class="material-symbols-outlined text-on-surface-variant text-[20px]">expand_more</span>
</button>
</div>
</header>
<!-- Main Content Area -->
<main class="ml-[260px] pt-topbar-height min-h-screen">
<div class="p-container-padding max-w-7xl mx-auto space-y-gutter">
<!-- Page Header Section -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4">
<div>
<h2 class="font-headline-lg text-headline-lg text-on-surface">Dashboard Pengguna</h2>
<p class="font-body-md text-body-md text-on-surface-variant mt-1">Daftar perangkat TIK yang Anda daftarkan.</p>
</div>
<div class="mt-4 sm:mt-0">
<button class="bg-primary-container text-on-primary-container font-body-md text-body-md font-bold py-2.5 px-4 rounded-lg shadow-sm hover:bg-primary-container/90 transition-colors flex items-center focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background">
<span class="material-symbols-outlined mr-2 text-[20px]">add</span>
                        Tambah Perangkat
                    </button>
</div>
</div>
<!-- Main Table Card -->
<div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
<!-- Card Header -->
<div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
<h3 class="font-headline-md text-headline-md text-on-surface">Perangkat Saya</h3>
<button class="text-on-surface-variant hover:text-primary transition-colors p-1 rounded hover:bg-surface-container-low">
<span class="material-symbols-outlined text-[20px]">filter_list</span>
</button>
</div>
<!-- Table Container -->
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low border-b border-outline-variant font-label-md text-label-md text-on-surface-variant">
<th class="px-6 py-3 font-semibold">Nama Perangkat</th>
<th class="px-6 py-3 font-semibold">Jenis Perangkat</th>
<th class="px-6 py-3 font-semibold">Dinas/OPD</th>
<th class="px-6 py-3 font-semibold">Lokasi</th>
<th class="px-6 py-3 font-semibold text-center">Status Kelengkapan</th>
<th class="px-6 py-3 font-semibold text-right">Aksi</th>
</tr>
</thead>
<tbody class="font-data-tabular text-data-tabular text-on-surface divide-y divide-outline-variant">
<!-- Row 1 -->
<tr class="hover:bg-surface-container-low/50 transition-colors group">
<td class="px-6 py-4 whitespace-nowrap">
<div class="font-semibold text-primary">Server Kepegawaian</div>
<div class="text-on-surface-variant text-[11px] mt-0.5">SN: SV-BKPSDM-001</div>
</td>
<td class="px-6 py-4 whitespace-nowrap text-on-surface-variant">Server Fisik</td>
<td class="px-6 py-4 whitespace-nowrap text-on-surface-variant">Dinas BKPSDM</td>
<td class="px-6 py-4 whitespace-nowrap text-on-surface-variant">
<div class="flex items-center">
<span class="material-symbols-outlined text-[16px] mr-1.5 text-on-surface-variant/70">dns</span>
                                        Rack A, U12
                                    </div>
</td>
<td class="px-6 py-4 whitespace-nowrap text-center">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-label-md text-label-md bg-tertiary-fixed text-on-tertiary-fixed border border-tertiary-fixed-dim/30">
                                        Pending
                                    </span>
</td>
<td class="px-6 py-4 whitespace-nowrap text-right">
<div class="flex items-center justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-1.5 text-on-surface-variant hover:text-primary hover:bg-primary-fixed rounded transition-colors" title="Lihat Detail">
<span class="material-symbols-outlined text-[18px]">visibility</span>
</button>

</div>
</td>
</tr>
<!-- Row 2 -->
<tr class="hover:bg-surface-container-low/50 transition-colors group bg-surface-container-lowest">
<td class="px-6 py-4 whitespace-nowrap">
<div class="font-semibold text-primary">Switch Core 1</div>
<div class="text-on-surface-variant text-[11px] mt-0.5">SN: NW-DKI-042</div>
</td>
<td class="px-6 py-4 whitespace-nowrap text-on-surface-variant">Network Switch</td>
<td class="px-6 py-4 whitespace-nowrap text-on-surface-variant">Diskominfo</td>
<td class="px-6 py-4 whitespace-nowrap text-on-surface-variant">
<div class="flex items-center">
<span class="material-symbols-outlined text-[16px] mr-1.5 text-on-surface-variant/70">dns</span>
                                        Rack B, U05
                                    </div>
</td>
<td class="px-6 py-4 whitespace-nowrap text-center">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-label-md text-label-md bg-secondary-fixed text-on-secondary-fixed border border-secondary-fixed-dim/30">
                                        Lengkap
                                    </span>
</td>
<td class="px-6 py-4 whitespace-nowrap text-right">
<div class="flex items-center justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-1.5 text-on-surface-variant hover:text-primary hover:bg-primary-fixed rounded transition-colors" title="Lihat Detail">
<span class="material-symbols-outlined text-[18px]">visibility</span>
</button>
<button class="p-1.5 text-on-surface-variant hover:text-primary hover:bg-primary-fixed rounded transition-colors" title="Edit">

</button>
</div>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-surface-container-low/50 transition-colors group">
<td class="px-6 py-4 whitespace-nowrap">
<div class="font-semibold text-primary">Storage NAS Alpha</div>
<div class="text-on-surface-variant text-[11px] mt-0.5">SN: ST-BAP-009</div>
</td>
<td class="px-6 py-4 whitespace-nowrap text-on-surface-variant">Penyimpanan</td>
<td class="px-6 py-4 whitespace-nowrap text-on-surface-variant">BAPPEDA</td>
<td class="px-6 py-4 whitespace-nowrap text-on-surface-variant">
<div class="flex items-center">
<span class="material-symbols-outlined text-[16px] mr-1.5 text-on-surface-variant/70">dns</span>
                                        Rack A, U22
                                    </div>
</td>
<td class="px-6 py-4 whitespace-nowrap text-center">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-label-md text-label-md bg-secondary-fixed text-on-secondary-fixed border border-secondary-fixed-dim/30">
                                        Lengkap
                                    </span>
</td>
<td class="px-6 py-4 whitespace-nowrap text-right">
<div class="flex items-center justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-1.5 text-on-surface-variant hover:text-primary hover:bg-primary-fixed rounded transition-colors" title="Lihat Detail">
<span class="material-symbols-outlined text-[18px]">visibility</span>
</button>

</div>
</td>
</tr>
</tbody>
</table>
</div>
<!-- Pagination Footer -->
<div class="px-6 py-4 border-t border-outline-variant bg-surface-container-lowest flex items-center justify-between sm:px-6">
<div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
<div>
<p class="font-body-md text-body-md text-on-surface-variant">
                                Menampilkan <span class="font-semibold text-on-surface">1</span> sampai <span class="font-semibold text-on-surface">3</span> dari <span class="font-semibold text-on-surface">12</span> hasil
                            </p>
</div>
<div>
<nav aria-label="Pagination" class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
<a class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-outline-variant bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container-low transition-colors" href="#">
<span class="sr-only">Previous</span>
<span class="material-symbols-outlined text-[20px]">chevron_left</span>
</a>
<!-- Current: "z-10 bg-primary-fixed border-primary text-primary", Default: "bg-surface-container-lowest border-outline-variant text-on-surface-variant hover:bg-surface-container-low" -->
<a aria-current="page" class="z-10 bg-primary-fixed border-primary text-primary relative inline-flex items-center px-4 py-2 border font-body-md text-body-md font-semibold" href="#">
                                    1
                                </a>
<a class="bg-surface-container-lowest border-outline-variant text-on-surface-variant hover:bg-surface-container-low relative inline-flex items-center px-4 py-2 border font-body-md text-body-md transition-colors" href="#">
                                    2
                                </a>
<a class="bg-surface-container-lowest border-outline-variant text-on-surface-variant hover:bg-surface-container-low relative inline-flex items-center px-4 py-2 border font-body-md text-body-md transition-colors" href="#">
                                    3
                                </a>
<span class="relative inline-flex items-center px-4 py-2 border border-outline-variant bg-surface-container-lowest text-on-surface-variant font-body-md text-body-md">
                                    ...
                                </span>
<a class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-outline-variant bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container-low transition-colors" href="#">
<span class="sr-only">Next</span>
<span class="material-symbols-outlined text-[20px]">chevron_right</span>
</a>
</nav>
</div>
</div>
</div>
</div>
</div>
</main>


</body></html>
