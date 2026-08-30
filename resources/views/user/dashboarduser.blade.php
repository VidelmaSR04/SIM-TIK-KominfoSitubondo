<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Dashboard Pengguna - SIM TIK</title>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
                        "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"
                    },
                    "spacing": {
                        "base": "4px", "gutter": "16px", "container-padding": "24px",
                        "topbar-height": "64px", "sidebar-width": "260px"
                    },
                    "fontFamily": {
                        "body-lg": ["Inter"], "data-tabular": ["Inter"], "body-md": ["Inter"],
                        "headline-lg": ["Inter"], "label-md": ["Inter"], "display": ["Inter"], "headline-md": ["Inter"]
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
<nav class="bg-[#0F172A] border-r border-outline-variant shadow-sm flex flex-col h-screen fixed left-0 top-0 z-40 w-[260px]">
<div class="h-topbar-height flex items-center px-6 border-b border-[#1E293B]">
<div class="w-8 h-8 rounded bg-primary-container flex items-center justify-center mr-3 shrink-0">
<span class="material-symbols-outlined text-on-primary-container" data-weight="fill" style="font-size: 20px;">dns</span>
</div>
<div>
<h1 class="font-headline-md text-headline-md font-bold text-white leading-tight">SIM TIK</h1>
<p class="font-label-md text-label-md text-white/60 font-normal">Asset Management</p>
</div>
</div>
<div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
<a class="flex items-center px-3 py-2.5 rounded-lg border-l-4 border-primary bg-secondary-container/10 text-white font-bold hover:bg-secondary-container/5 transition-colors active:scale-[0.98] transition-transform group" href="{{ route('user.dashboarduser') }}">
<span class="material-symbols-outlined mr-3 text-primary" data-weight="fill">dashboard</span>
<span class="font-body-md text-body-md">Dashboard</span>
</a>
</div>
<div class="p-4 border-t border-[#1E293B]">
<a class="flex items-center text-white/70 hover:text-white font-body-md text-body-md transition-colors" href="#">
<span class="material-symbols-outlined mr-3">help</span>
                Bantuan &amp; Dukungan
            </a>
</div>
</nav>
<header class="bg-surface-container-lowest dark:bg-surface-dim border-b border-outline-variant shadow-sm fixed top-0 right-0 w-[calc(100%-260px)] h-topbar-height flex justify-between items-center px-container-padding ml-[260px] z-30">
<div class="flex-1 flex items-center max-w-md">
<div class="relative w-full group">
<div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-on-surface-variant">
<span class="material-symbols-outlined text-[20px]">search</span>
</div>
<input id="deviceSearchInput" class="bg-surface-container-low text-on-surface text-body-md rounded-lg block w-full pl-10 p-2 border border-transparent focus-within:ring-2 focus-within:ring-primary focus:border-transparent transition-all outline-none" placeholder="Cari perangkat, IP, atau nama pengguna..." type="text">
</div>
</div>
<div class="flex items-center space-x-4">
<button class="relative p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-all focus:outline-none focus:ring-2 focus:ring-primary">
<span class="material-symbols-outlined" data-icon="notifications">notifications</span>
<span class="absolute top-1.5 right-1.5 w-2 h-2 bg-error rounded-full ring-2 ring-surface-container-lowest"></span>
</button>
<div class="h-6 w-px bg-outline-variant mx-2"></div>
<div class="relative" id="profileDropdownWrapper">
<button id="profileDropdownTrigger" class="flex items-center space-x-3 hover:bg-surface-container-low p-1.5 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-primary" type="button">
<div class="w-8 h-8 rounded-full bg-primary-fixed border border-outline-variant shrink-0 flex items-center justify-center font-semibold text-sm text-primary">
{{ auth()->check() ? strtoupper(substr(auth()->user()->name ?? 'Pengguna', 0, 2)) : 'PG' }}
</div>
<div class="text-left hidden md:block">
<p class="font-body-md text-body-md font-bold text-on-surface leading-tight">{{ auth()->user()->name ?? 'Pengguna' }}</p>
</div>
<span class="material-symbols-outlined text-on-surface-variant text-[20px]">expand_more</span>
</button>
<div id="profileDropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-surface-container-lowest border border-outline-variant rounded-lg shadow-lg py-2 z-50">
<a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface transition-colors font-body-md text-body-md">
<span class="material-symbols-outlined text-[20px]">person</span>
Profil Saya
</a>
<div class="my-1 border-t border-outline-variant"></div>
<form method="POST" action="{{ route('logout') }}">
@csrf
<button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-error hover:bg-surface-container-low transition-colors font-body-md text-body-md text-left">
<span class="material-symbols-outlined text-[20px]">logout</span>
Keluar
</button>
</form>
</div>
</div>
</div>
</header>
<main class="ml-[260px] pt-topbar-height min-h-screen">
<div class="p-container-padding max-w-7xl mx-auto space-y-gutter">

@if(session('success'))
<div class="bg-secondary-fixed text-on-secondary-fixed px-4 py-3 rounded-lg mb-4">
    {{ session('success') }}
</div>
@endif

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4">
<div>
<h2 class="font-headline-lg text-headline-lg text-on-surface">Dashboard Pengguna</h2>
<p class="font-body-md text-body-md text-on-surface-variant mt-1">Daftar perangkat TIK yang Anda daftarkan.</p>
</div>
<div class="mt-4 sm:mt-0">
<a href="{{ route('inputdatauser.create') }}" class="bg-primary-container text-on-primary-container font-body-md text-body-md font-bold py-2.5 px-4 rounded-lg shadow-sm hover:bg-primary-container/90 transition-colors flex items-center focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background">
<span class="material-symbols-outlined mr-2 text-[20px]">add</span>
                        Tambah Perangkat
                    </a>
</div>
</div>

<div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
<div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
<h3 class="font-headline-md text-headline-md text-on-surface">Perangkat Saya</h3>
<div class="relative" id="statusFilterWrapper">
<button id="statusFilterTrigger" type="button" class="text-on-surface-variant hover:text-primary transition-colors p-1 rounded hover:bg-surface-container-low">
<span class="material-symbols-outlined text-[20px]">filter_list</span>
</button>
<div id="statusFilterMenu" class="hidden absolute right-0 mt-2 w-44 bg-surface-container-lowest border border-outline-variant rounded-lg shadow-lg py-2 z-50">
<button type="button" data-status-filter="all" class="status-filter-option w-full text-left px-4 py-2 text-on-surface hover:bg-surface-container-low transition-colors font-body-md text-body-md">Semua Status</button>
<button type="button" data-status-filter="pending" class="status-filter-option w-full text-left px-4 py-2 text-on-surface hover:bg-surface-container-low transition-colors font-body-md text-body-md">Pending</button>
<button type="button" data-status-filter="lengkap" class="status-filter-option w-full text-left px-4 py-2 text-on-surface hover:bg-surface-container-low transition-colors font-body-md text-body-md">Lengkap</button>
</div>
</div>
</div>

<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low border-b border-outline-variant font-label-md text-label-md text-on-surface-variant">
<th class="px-6 py-3 font-semibold">Nama Perangkat</th>
<th class="px-6 py-3 font-semibold">Jenis Perangkat</th>
<th class="px-6 py-3 font-semibold">Dinas/OPD</th>
<th class="px-6 py-3 font-semibold">Lokasi</th>
<th class="px-6 py-3 font-semibold text-center">Status Kelengkapan</th>
</tr>
</thead>
<tbody id="deviceTableBody" class="font-data-tabular text-data-tabular text-on-surface divide-y divide-outline-variant">
@forelse($devices as $device)
<tr class="device-row hover:bg-surface-container-low/50 transition-colors group"
    data-search="{{ strtolower($device->nama_perangkat.' '.$device->jenis_perangkat.' '.$device->pemilik_perangkat.' '.$device->nomor_rack) }}"
    data-status="{{ $device->status_kelengkapan }}">
<td class="px-6 py-4 whitespace-nowrap">
<div class="font-semibold text-primary">{{ $device->nama_perangkat }}</div>
<div class="text-on-surface-variant text-[11px] mt-0.5">SN: {{ $device->serial_number ?? '-' }}</div>
</td>
<td class="px-6 py-4 whitespace-nowrap text-on-surface-variant">{{ ucfirst($device->jenis_perangkat) }}</td>
<td class="px-6 py-4 whitespace-nowrap text-on-surface-variant">{{ $device->pemilik_perangkat ?? '-' }}</td>
<td class="px-6 py-4 whitespace-nowrap text-on-surface-variant">
<div class="flex items-center">
<span class="material-symbols-outlined text-[16px] mr-1.5 text-on-surface-variant/70">dns</span>
                                        {{ $device->nomor_rack ?? '-' }}
                                    </div>
</td>
<td class="px-6 py-4 whitespace-nowrap text-center">
@if($device->status_kelengkapan === 'lengkap')
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-label-md text-label-md bg-secondary-fixed text-on-secondary-fixed border border-secondary-fixed-dim/30">
                                        Lengkap
                                    </span>
@else
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-label-md text-label-md bg-tertiary-fixed text-on-tertiary-fixed border border-tertiary-fixed-dim/30">
                                        {{ ucfirst($device->status_kelengkapan) }}
                                    </span>
@endif
</td>
</tr>
@empty
<tr>
<td colspan="5" class="px-6 py-10 text-center text-on-surface-variant">
    Belum ada perangkat yang Anda daftarkan.
</td>
</tr>
@endforelse
</tbody>
</table>
</div>

@if($devices->total() > 0)
<div class="px-6 py-4 border-t border-outline-variant bg-surface-container-lowest flex items-center justify-between sm:px-6">
<div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
<div>
<p class="font-body-md text-body-md text-on-surface-variant">
                                Menampilkan <span class="font-semibold text-on-surface">{{ $devices->firstItem() }}</span> sampai <span class="font-semibold text-on-surface">{{ $devices->lastItem() }}</span> dari <span class="font-semibold text-on-surface">{{ $devices->total() }}</span> hasil
                            </p>
</div>
<div>
    {{ $devices->links() }}
</div>
</div>
</div>
@endif

</div>
</div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const profileTrigger = document.getElementById('profileDropdownTrigger');
        const profileMenu = document.getElementById('profileDropdownMenu');

        profileTrigger.addEventListener('click', function (e) {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
            statusFilterMenu.classList.add('hidden');
        });

        const statusFilterTrigger = document.getElementById('statusFilterTrigger');
        const statusFilterMenu = document.getElementById('statusFilterMenu');
        const statusOptions = document.querySelectorAll('.status-filter-option');
        let activeStatus = 'all';

        statusFilterTrigger.addEventListener('click', function (e) {
            e.stopPropagation();
            statusFilterMenu.classList.toggle('hidden');
            profileMenu.classList.add('hidden');
        });

        statusOptions.forEach(function (option) {
            option.addEventListener('click', function () {
                activeStatus = option.dataset.statusFilter;
                statusFilterMenu.classList.add('hidden');
                applyFilters();
            });
        });

        document.addEventListener('click', function () {
            profileMenu.classList.add('hidden');
            statusFilterMenu.classList.add('hidden');
        });

        const searchInput = document.getElementById('deviceSearchInput');
        searchInput.addEventListener('input', applyFilters);

        function applyFilters() {
            const keyword = searchInput.value.trim().toLowerCase();
            const rows = document.querySelectorAll('#deviceTableBody .device-row');

            rows.forEach(function (row) {
                const matchesSearch = keyword === '' || row.dataset.search.includes(keyword);
                const matchesStatus = activeStatus === 'all' || row.dataset.status === activeStatus;
                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
        }
    });
</script>
</body></html>
