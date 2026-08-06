@extends('layouts.app')

@section('title', 'Dashboard Server dan Aplikasi')

@section('content')
    <!-- Content -->
    <main class="flex-1 px-container-padding py-8 space-y-8 max-w-[1400px] w-full mx-auto">

        <!-- Page header -->
        <div>
            <h2 class="text-2xl font-bold text-on-surface mb-1.5">Dashboard Server dan Aplikasi</h2>
            <div class="text-sm text-secondary flex items-center gap-2">
                <span>Dashboard</span>
                <span class="text-outline">/</span>
                <span class="text-primary font-medium">Server dan Aplikasi</span>
            </div>
        </div>

        <!-- Summary cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <p class="text-xs font-semibold text-secondary uppercase tracking-wider">All Devices</p>
                        <h3 class="text-3xl font-bold text-on-surface mt-2">{{ number_format($totalDevices ?? 1248) }}</h3>
                    </div>
                    <div class="w-11 h-11 rounded-lg bg-blue-50 text-primary flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined">devices</span>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 text-sm">
                    <span class="material-symbols-outlined text-[16px] text-green-600">trending_up</span>
                    <span class="text-green-600 font-medium">+{{ $percentageGrowth ?? 12 }}%</span>
                    <span class="text-secondary text-xs">dari bulan lalu</span>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <p class="text-xs font-semibold text-secondary uppercase tracking-wider">Application</p>
                        <h3 class="text-3xl font-bold text-on-surface mt-2">{{ number_format($totalApplications ?? 342) }}</h3>
                    </div>
                    <div class="w-11 h-11 rounded-lg bg-green-50 text-green-700 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined">web</span>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 text-sm">
                    <span class="material-symbols-outlined text-[16px] text-green-600">trending_up</span>
                    <span class="text-green-600 font-medium">+{{ $newApps ?? 5 }}</span>
                    <span class="text-secondary text-xs">aplikasi baru</span>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <p class="text-xs font-semibold text-secondary uppercase tracking-wider">VPS</p>
                        <h3 class="text-3xl font-bold text-on-surface mt-2">{{ number_format($totalVps ?? 86) }}</h3>
                    </div>
                    <div class="w-11 h-11 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined">storage</span>
                    </div>
                </div>
                {{-- Progress bar --}}
                <div class="w-full bg-surface-container rounded-full h-1.5 mb-2">
                    <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ $vpsUsage ?? 75 }}%"></div>
                </div>
                <p class="text-xs text-secondary">{{ $vpsUsage ?? 75 }}% Kapasitas Terpakai</p>
            </div>

            <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <p class="text-xs font-semibold text-secondary uppercase tracking-wider">Domain</p>
                        <h3 class="text-3xl font-bold text-on-surface mt-2">{{ number_format($totalDomains ?? 112) }}</h3>
                    </div>
                    <div class="w-11 h-11 rounded-lg bg-red-50 text-red-700 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined">language</span>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 text-sm">
                    <span class="material-symbols-outlined text-[16px] text-red-600">warning</span>
                    <span class="text-red-600 font-medium">{{ $expiringDomains ?? 3 }} Expiring</span>
                    <span class="text-secondary text-xs">dalam 30 hari</span>
                </div>
            </div>
        </div>

        <!-- Chart section (full width) -->
        <div class="bg-white rounded-xl border border-outline-variant shadow-sm p-6 md:p-8">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-lg font-bold text-on-surface">Distribusi RACK Server</h3>
                <button class="text-primary text-sm font-medium hover:underline">Lihat Detail</button>
            </div>

            <div class="flex gap-4">
                <!-- Y-axis labels -->
                <div class="flex flex-col justify-between text-xs text-secondary h-64 py-1 flex-shrink-0 w-8 text-right">
                    <span>100</span><span>75</span><span>50</span><span>25</span><span>0</span>
                </div>

                <!-- Bars -->
                <div class="flex-1">
                    <div class="h-64 flex items-end justify-between gap-3 border-b border-outline-variant pb-0">
                        <div class="flex-1 flex flex-col items-center justify-end h-full group cursor-pointer">
                            <div class="w-full max-w-[72px] bg-primary/20 hover:bg-primary/40 rounded-t-md transition-colors relative" style="height: 80%">
                                <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap">R1: 80</div>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col items-center justify-end h-full group cursor-pointer">
                            <div class="w-full max-w-[72px] bg-primary hover:bg-primary/80 rounded-t-md transition-colors relative" style="height: 95%">
                                <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap">R2: 95</div>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col items-center justify-end h-full group cursor-pointer">
                            <div class="w-full max-w-[72px] bg-primary/60 hover:bg-primary/80 rounded-t-md transition-colors relative" style="height: 60%">
                                <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap">R3: 60</div>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col items-center justify-end h-full group cursor-pointer">
                            <div class="w-full max-w-[72px] bg-primary/40 hover:bg-primary/60 rounded-t-md transition-colors relative" style="height: 45%">
                                <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap">R4: 45</div>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col items-center justify-end h-full group cursor-pointer">
                            <div class="w-full max-w-[72px] bg-primary/80 hover:bg-primary rounded-t-md transition-colors relative" style="height: 75%">
                                <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap">R5: 75</div>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col items-center justify-end h-full group cursor-pointer">
                            <div class="w-full max-w-[72px] bg-primary/30 hover:bg-primary/50 rounded-t-md transition-colors relative" style="height: 30%">
                                <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap">R6: 30</div>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col items-center justify-end h-full group cursor-pointer">
                            <div class="w-full max-w-[72px] bg-primary/50 hover:bg-primary/70 rounded-t-md transition-colors relative" style="height: 55%">
                                <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap">R7: 55</div>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col items-center justify-end h-full group cursor-pointer">
                            <div class="w-full max-w-[72px] bg-primary/90 hover:bg-primary rounded-t-md transition-colors relative" style="height: 85%">
                                <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap">R8: 85</div>
                            </div>
                        </div>
                    </div>

                    <!-- X-axis labels -->
                    <div class="flex justify-between gap-3 pt-3 text-xs text-secondary font-medium text-center">
                        <span class="flex-1">R1</span><span class="flex-1">R2</span><span class="flex-1">R3</span><span class="flex-1">R4</span>
                        <span class="flex-1">R5</span><span class="flex-1">R6</span><span class="flex-1">R7</span><span class="flex-1">R8</span>
                    </div>
                </div>
            </div>

            <!-- Mini stats -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 mt-8 pt-6 border-t border-outline-variant">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-secondary flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]">dns</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-secondary">Rack Mount</p>
                        <p class="font-semibold text-on-surface">{{ number_format($rackMount ?? 324) }} Unit</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-secondary flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]">computer</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-secondary">Tower</p>
                        <p class="font-semibold text-on-surface">{{ number_format($tower ?? 45) }} Unit</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-secondary flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]">domain</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-secondary">Kominfo</p>
                        <p class="font-semibold text-on-surface">{{ number_format($kominfo ?? 210) }} Aset</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-secondary flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]">handshake</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-secondary">Colocation</p>
                        <p class="font-semibold text-on-surface">{{ number_format($colocation ?? 159) }} Aset</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data table -->
        <div class="bg-white rounded-xl border border-outline-variant shadow-sm overflow-hidden">
            <div class="p-5 border-b border-outline-variant flex flex-col sm:flex-row justify-between gap-4 items-center">
                <div class="flex gap-1 bg-surface-container-low p-1 rounded-lg">
                    <button onclick="switchTab('server')" id="tab-btn-server" class="tab-btn px-4 py-2 text-sm font-medium rounded-md bg-white text-primary shadow-sm">Server</button>
                    <button onclick="switchTab('cpanel')" id="tab-btn-cpanel" class="tab-btn px-4 py-2 text-sm font-medium rounded-md text-secondary hover:text-on-surface transition-colors">cPanel</button>
                    <button onclick="switchTab('aplikasi')" id="tab-btn-aplikasi" class="tab-btn px-4 py-2 text-sm font-medium rounded-md text-secondary hover:text-on-surface transition-colors">Aplikasi</button>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <div class="flex items-center gap-2 text-sm text-secondary whitespace-nowrap">
                        <span>Show</span>
                        <select class="custom-select border border-outline-variant rounded-lg bg-white py-1.5 pl-3 pr-8 text-sm focus:ring-1 focus:ring-primary focus:border-primary cursor-pointer">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                        <span>entries</span>
                    </div>
                    <div class="relative flex-1 sm:w-64">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
                        <input class="pl-9 pr-3 py-2 w-full border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary" placeholder="Cari data..." type="text"/>
                    </div>
                </div>
            </div>

            <!-- PANEL: SERVER -->
            <div id="panel-server" class="tab-panel">
                <div class="table-container overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-surface-container-low text-xs font-semibold text-on-surface-variant uppercase tracking-wide border-y border-outline-variant">
                                <th class="p-4 w-24">ID</th>
                                <th class="p-4 w-56">Nama Perangkat</th>
                                <th class="p-4 w-40">IP Server</th>
                                <th class="p-4">IP VPS</th>
                                <th class="p-4 w-36">Status</th>
                                <th class="p-4 w-28 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-on-surface divide-y divide-outline-variant/60">
                            {{-- $servers dikirim dari DashboardController@index sebagai array asosiatif,
                                 BUKAN object/model, jadi dipanggil pakai $server['field'], bukan $server->field. --}}
                            @forelse ($servers ?? [] as $server)
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="p-4 text-secondary font-mono text-xs">{{ $server['id'] ?? 'SRV-001' }}</td>
                                <td class="p-4 font-medium">
                                    <div class="flex items-center gap-2.5">
                                        <span class="material-symbols-outlined text-secondary text-[18px]">dns</span>
                                        {{ $server['nama'] ?? 'Node-DB-Master-01' }}
                                    </div>
                                </td>
                                <td class="p-4 font-mono text-xs text-secondary">{{ $server['ip_server'] ?? '192.168.10.101' }}</td>
                                <td class="p-4 font-mono text-xs text-secondary">{{ $server['ip_vps'] ?? '-' }}</td>
                                <td class="p-4">
                                    @php
                                        $status = $server['status'] ?? 'Aktif';
                                        // Mapping status -> STRING CLASS TAILWIND LENGKAP.
                                        // Tidak boleh diinterpolasi (bg-{{ $x }}-100) karena
                                        // Tailwind hanya mendeteksi nama class yang utuh saat build.
                                        $statusClasses = match ($status) {
                                            'Aktif' => 'bg-green-100 text-green-800',
                                            'Maintenance' => 'bg-amber-100 text-amber-800',
                                            default => 'bg-red-100 text-red-800', // Offline / lainnya
                                        };
                                        $dotClasses = match ($status) {
                                            'Aktif' => 'bg-green-500',
                                            'Maintenance' => 'bg-amber-500',
                                            default => 'bg-red-500',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ $statusClasses }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClasses }}"></span>
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="p-4 text-center relative">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('detailserver', ['id' => $server['id'] ?? 'SRV-001']) }}" title="Lihat Detail" class="text-secondary hover:text-primary hover:bg-blue-50 transition-colors p-1.5 rounded-lg">
                                    <span class="material-symbols-outlined text-[19px]">visibility</span>
                                </a>
                                        <a href="#" title="Edit" class="text-secondary hover:text-amber-600 hover:bg-amber-50 transition-colors p-1.5 rounded-lg">
                                            <span class="material-symbols-outlined text-[19px]">edit</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-secondary">Tidak ada data server</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{--
                    Baris info + paginasi.
                    FIX: method_exists() sebelumnya error karena $servers adalah ARRAY,
                    bukan object -> method_exists(array, ...) melempar TypeError.
                    Sekarang dicek pakai is_object() dulu supaya aman untuk kedua kasus:
                    - array biasa (data dummy sekarang)
                    - Laravel Paginator (kalau nanti controller pakai ->paginate())
                --}}
                <div class="p-4 border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-3 text-sm">
                    @if(isset($servers) && is_object($servers) && method_exists($servers, 'firstItem'))
                        {{-- Aktif otomatis kalau controller nanti pakai Server::paginate() --}}
                        <span class="text-secondary">
                            Menampilkan {{ $servers->firstItem() }} hingga {{ $servers->lastItem() }} dari {{ $servers->total() }} entri
                        </span>
                        <div class="flex gap-1">
                            {{ $servers->links() }}
                        </div>
                    @else
                        {{-- Aktif untuk data dummy array seperti yang dipakai sekarang --}}
                        <span class="text-secondary">Menampilkan {{ isset($servers) ? count($servers) : 0 }} entri</span>
                        <div class="flex gap-1">
                            <button class="px-3 py-1.5 border border-outline-variant rounded-lg text-secondary opacity-50" disabled>Prev</button>
                            <button class="px-3 py-1.5 bg-primary text-white rounded-lg">1</button>
                            <button class="px-3 py-1.5 border border-outline-variant rounded-lg text-secondary hover:bg-surface-container transition-colors">Next</button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- PANEL: CPANEL (tetap statis) -->
            <div id="panel-cpanel" class="tab-panel hidden">
                <div class="table-container overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[900px]">
                        <thead>
                            <tr class="bg-surface-container-low text-xs font-semibold text-on-surface-variant uppercase tracking-wide border-y border-outline-variant">
                                <th class="p-4 w-16">ID</th>
                                <th class="p-4 w-56">Nama cPanel</th>
                                <th class="p-4">Domain</th>
                                <th class="p-4 w-36">IP Local</th>
                                <th class="p-4 w-36">IP Public</th>
                                <th class="p-4 w-28">Status</th>
                                <th class="p-4 w-24 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-on-surface divide-y divide-outline-variant/60">
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="p-4 text-secondary">1</td>
                                <td class="p-4 font-medium text-on-surface">Kecamatan Arjasa</td>
                                <td class="p-4 text-secondary">arjasa.situbondokab.go.id</td>
                                <td class="p-4 font-mono text-xs text-secondary">192.168.99.72</td>
                                <td class="p-4 font-mono text-xs text-secondary">103.165.156.249</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button title="Lihat Detail" class="text-teal-600 hover:text-teal-800 hover:bg-teal-50 transition-colors p-1.5 rounded-lg">
                                            <span class="material-symbols-outlined text-[19px]">search</span>
                                        </button>
                                        <button title="Edit" class="text-teal-600 hover:text-teal-800 hover:bg-teal-50 transition-colors p-1.5 rounded-lg">
                                            <span class="material-symbols-outlined text-[19px]">edit</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="p-4 text-secondary">2</td>
                                <td class="p-4 font-medium text-on-surface">Kecamatan Asembagus</td>
                                <td class="p-4 text-secondary">asembagus.situbondokab.go.id</td>
                                <td class="p-4 font-mono text-xs text-secondary">192.168.99.72</td>
                                <td class="p-4 font-mono text-xs text-secondary">103.165.156.249</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button title="Lihat Detail" class="text-teal-600 hover:text-teal-800 hover:bg-teal-50 transition-colors p-1.5 rounded-lg">
                                            <span class="material-symbols-outlined text-[19px]">search</span>
                                        </button>
                                        <button title="Edit" class="text-teal-600 hover:text-teal-800 hover:bg-teal-50 transition-colors p-1.5 rounded-lg">
                                            <span class="material-symbols-outlined text-[19px]">edit</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="p-4 text-secondary">3</td>
                                <td class="p-4 font-medium text-on-surface">Bakesbangpol</td>
                                <td class="p-4 text-secondary">bakesbangpol.situbondokab.go.id</td>
                                <td class="p-4 font-mono text-xs text-secondary">192.168.99.72</td>
                                <td class="p-4 font-mono text-xs text-secondary">103.76.175.182</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button title="Lihat Detail" class="text-teal-600 hover:text-teal-800 hover:bg-teal-50 transition-colors p-1.5 rounded-lg">
                                            <span class="material-symbols-outlined text-[19px]">search</span>
                                        </button>
                                        <button title="Edit" class="text-teal-600 hover:text-teal-800 hover:bg-teal-50 transition-colors p-1.5 rounded-lg">
                                            <span class="material-symbols-outlined text-[19px]">edit</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="p-4 text-secondary">4</td>
                                <td class="p-4 font-medium text-on-surface">Kecamatan Banyuglugur</td>
                                <td class="p-4 text-secondary">banyuglugur.situbondokab.go.id</td>
                                <td class="p-4 font-mono text-xs text-secondary">192.168.99.72</td>
                                <td class="p-4 font-mono text-xs text-secondary">103.165.156.249</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button title="Lihat Detail" class="text-teal-600 hover:text-teal-800 hover:bg-teal-50 transition-colors p-1.5 rounded-lg">
                                            <span class="material-symbols-outlined text-[19px]">search</span>
                                        </button>
                                        <button title="Edit" class="text-teal-600 hover:text-teal-800 hover:bg-teal-50 transition-colors p-1.5 rounded-lg">
                                            <span class="material-symbols-outlined text-[19px]">edit</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="p-4 text-secondary">5</td>
                                <td class="p-4 font-medium text-on-surface">Kecamatan Banyuputih</td>
                                <td class="p-4 text-secondary">banyuputih.situbondokab.go.id</td>
                                <td class="p-4 font-mono text-xs text-secondary">192.168.99.72</td>
                                <td class="p-4 font-mono text-xs text-secondary">103.165.156.249</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button title="Lihat Detail" class="text-teal-600 hover:text-teal-800 hover:bg-teal-50 transition-colors p-1.5 rounded-lg">
                                            <span class="material-symbols-outlined text-[19px]">search</span>
                                        </button>
                                        <button title="Edit" class="text-teal-600 hover:text-teal-800 hover:bg-teal-50 transition-colors p-1.5 rounded-lg">
                                            <span class="material-symbols-outlined text-[19px]">edit</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-3 text-sm">
                    <span class="text-secondary">Menampilkan 1 hingga 5 dari 166 entri</span>
                    <div class="flex gap-1">
                        <button class="px-3 py-1.5 border border-outline-variant rounded-lg text-secondary opacity-50" disabled>Prev</button>
                        <button class="px-3 py-1.5 bg-primary text-white rounded-lg">1</button>
                        <button class="px-3 py-1.5 border border-outline-variant rounded-lg text-secondary hover:bg-surface-container transition-colors">2</button>
                        <button class="px-3 py-1.5 border border-outline-variant rounded-lg text-secondary hover:bg-surface-container transition-colors">3</button>
                        <span class="px-2 py-1.5 text-secondary">...</span>
                        <button class="px-3 py-1.5 border border-outline-variant rounded-lg text-secondary hover:bg-surface-container transition-colors">17</button>
                        <button class="px-3 py-1.5 border border-outline-variant rounded-lg text-secondary hover:bg-surface-container transition-colors">Next</button>
                    </div>
                </div>
            </div>

            <!-- PANEL: APLIKASI (tetap statis) -->
            <div id="panel-aplikasi" class="tab-panel hidden">
                <div class="table-container overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[900px]">
                        <thead>
                            <tr class="bg-surface-container-low text-xs font-semibold text-on-surface-variant uppercase tracking-wide border-y border-outline-variant">
                                <th class="p-4 w-16">ID</th>
                                <th class="p-4 w-64">Nama Aplikasi</th>
                                <th class="p-4 w-36">IP Local</th>
                                <th class="p-4 w-36">IP Public</th>
                                <th class="p-4 w-48">Penanggung Jawab</th>
                                <th class="p-4 w-36">Status</th>
                                <th class="p-4 w-20 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-on-surface divide-y divide-outline-variant/60">
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="p-4 text-secondary">1</td>
                                <td class="p-4 font-medium text-on-surface">ALADIN (Aplikasi Adminduk Online)</td>
                                <td class="p-4 font-mono text-xs text-secondary">-</td>
                                <td class="p-4 font-mono text-xs text-secondary">103.165.156.229</td>
                                <td class="p-4 text-secondary">Zakiatul Darojati, A.Md.</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Sudah Asesmen
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <button title="Lihat Detail" class="text-teal-600 hover:text-teal-800 hover:bg-teal-50 transition-colors p-1.5 rounded-lg">
                                        <span class="material-symbols-outlined text-[19px]">search</span>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="p-4 text-secondary">2</td>
                                <td class="p-4 font-medium text-on-surface">SKEMA (Survey Kepuasan Masyarakat)</td>
                                <td class="p-4 font-mono text-xs text-secondary">192.168.99.94</td>
                                <td class="p-4 font-mono text-xs text-secondary">103.165.156.249</td>
                                <td class="p-4 text-secondary">Hosnol Fawaid, S.Kom.</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Sudah Asesmen
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <button title="Lihat Detail" class="text-teal-600 hover:text-teal-800 hover:bg-teal-50 transition-colors p-1.5 rounded-lg">
                                        <span class="material-symbols-outlined text-[19px]">search</span>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="p-4 text-secondary">3</td>
                                <td class="p-4 font-medium text-on-surface">Aplikasi Presensi Kabupaten Situbondo (APRESIASI)</td>
                                <td class="p-4 font-mono text-xs text-secondary">-</td>
                                <td class="p-4 font-mono text-xs text-secondary">103.165.156.245</td>
                                <td class="p-4 text-secondary">-</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Sudah Asesmen
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <button title="Lihat Detail" class="text-teal-600 hover:text-teal-800 hover:bg-teal-50 transition-colors p-1.5 rounded-lg">
                                        <span class="material-symbols-outlined text-[19px]">search</span>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="p-4 text-secondary">4</td>
                                <td class="p-4 font-medium text-on-surface">Website DPRD</td>
                                <td class="p-4 font-mono text-xs text-secondary">192.168.99.72</td>
                                <td class="p-4 font-mono text-xs text-secondary">103.165.156.249</td>
                                <td class="p-4 text-secondary">-</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Belum Asesmen
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <button title="Lihat Detail" class="text-teal-600 hover:text-teal-800 hover:bg-teal-50 transition-colors p-1.5 rounded-lg">
                                        <span class="material-symbols-outlined text-[19px]">search</span>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="p-4 text-secondary">5</td>
                                <td class="p-4 font-medium text-on-surface">Website Layanan Tourist Information Center (TIC)</td>
                                <td class="p-4 font-mono text-xs text-secondary">192.168.99.72</td>
                                <td class="p-4 font-mono text-xs text-secondary">103.165.156.249</td>
                                <td class="p-4 text-secondary">-</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Belum Asesmen
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <button title="Lihat Detail" class="text-teal-600 hover:text-teal-800 hover:bg-teal-50 transition-colors p-1.5 rounded-lg">
                                        <span class="material-symbols-outlined text-[19px]">search</span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-3 text-sm">
                    <span class="text-secondary">Menampilkan 1 hingga 5 dari 226 entri</span>
                    <div class="flex gap-1">
                        <button class="px-3 py-1.5 border border-outline-variant rounded-lg text-secondary opacity-50" disabled>Prev</button>
                        <button class="px-3 py-1.5 bg-primary text-white rounded-lg">1</button>
                        <button class="px-3 py-1.5 border border-outline-variant rounded-lg text-secondary hover:bg-surface-container transition-colors">2</button>
                        <button class="px-3 py-1.5 border border-outline-variant rounded-lg text-secondary hover:bg-surface-container transition-colors">3</button>
                        <span class="px-2 py-1.5 text-secondary">...</span>
                        <button class="px-3 py-1.5 border border-outline-variant rounded-lg text-secondary hover:bg-surface-container transition-colors">23</button>
                        <button class="px-3 py-1.5 border border-outline-variant rounded-lg text-secondary hover:bg-surface-container transition-colors">Next</button>
                    </div>
                </div>
            </div>

        </div>

    </main>
@endsection

@push('scripts')
<script>
    function switchTab(name) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
        document.getElementById('panel-' + name).classList.remove('hidden');

        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('bg-white', 'text-primary', 'shadow-sm');
            b.classList.add('text-secondary');
        });
        const activeBtn = document.getElementById('tab-btn-' + name);
        if (activeBtn) {
            activeBtn.classList.remove('text-secondary');
            activeBtn.classList.add('bg-white', 'text-primary', 'shadow-sm');
        }
    }
</script>
@endpush