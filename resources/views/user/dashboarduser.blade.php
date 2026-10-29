@extends('layouts.app')

@section('title', 'Dashboard Pengguna - SIM TIK')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-on-surface">Dashboard Pengguna</h1>
        <p class="text-secondary text-sm mt-1">Daftar perangkat TIK yang Anda daftarkan.</p>
    </div>
    <a href="{{ route('inputdatauser.create') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-container text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm self-start sm:self-auto">
        <span class="material-symbols-outlined text-[18px]">add</span> Tambah Data
    </a>
</div>

@if(session('success'))
    <div class="mb-6 rounded-lg border-l-4 border-green-500 bg-green-50 px-4 py-3 flex items-start space-x-3">
        <span class="material-symbols-outlined text-green-600">check_circle</span>
        <div class="text-sm text-green-900 font-medium">{{ session('success') }}</div>
    </div>
@endif

<div class="bg-white rounded-xl border border-outline-variant shadow-sm overflow-hidden">
    <!-- Toolbar -->
    <div class="p-4 border-b border-outline-variant flex flex-wrap items-center justify-between gap-4">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
            <input id="deviceSearchInput" class="pl-9 pr-3 py-2 w-64 sm:w-80 border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary" placeholder="Cari perangkat, IP, kode, penerima..." type="text" />
        </div>

        <div class="relative" id="statusFilterWrapper">
            <button id="statusFilterTrigger" type="button" class="flex items-center gap-2 px-3 py-2 border border-outline-variant rounded-lg text-sm text-secondary hover:text-on-surface hover:bg-surface-container-low transition-colors">
                <span class="material-symbols-outlined text-[18px]">filter_list</span>
                <span id="activeFilterLabel">Semua Status</span>
                <span class="material-symbols-outlined text-[16px]">expand_more</span>
            </button>
            <div id="statusFilterMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-outline-variant rounded-lg shadow-lg py-1 z-20">
                <button type="button" data-status-filter="all" class="status-filter-option w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-container-low transition-colors font-medium">Semua Status</button>
                <button type="button" data-status-filter="Aktif" class="status-filter-option w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-container-low transition-colors">Aktif</button>
                <button type="button" data-status-filter="Non-Aktif" class="status-filter-option w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-container-low transition-colors">Non-Aktif</button>
                <button type="button" data-status-filter="Maintenance" class="status-filter-option w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-container-low transition-colors">Maintenance</button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low border-b border-outline-variant text-xs font-semibold text-secondary uppercase tracking-wider">
                    <th class="px-6 py-3.5 text-center">No</th>
                    <th class="px-6 py-3.5">Kode Perangkat</th>
                    <th class="px-6 py-3.5">Jenis</th>
                    <th class="px-6 py-3.5">Nama OPD</th>
                    <th class="px-6 py-3.5">Nama Penerima</th>
                    <th class="px-6 py-3.5">Nama Perangkat</th>
                    <th class="px-6 py-3.5 text-center">Status</th>
                    <th class="px-6 py-3.5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="deviceTableBody" class="divide-y divide-outline-variant text-sm">
                @forelse($devices as $device)
                <tr class="device-row hover:bg-surface-container-low/50 transition-colors"
                    data-search="{{ strtolower(($device->nama_perangkat ?? '').' '.($device->jenis_perangkat ?? '').' '.($device->pemilik_perangkat ?? '').' '.($device->nama_penerima ?? '').' '.($device->kode_perangkat ?? '')) }}"
                    data-status="{{ $device->status }}">
                    <td class="px-6 py-4 text-center text-secondary">{{ ($devices->currentPage() - 1) * $devices->perPage() + $loop->iteration }}</td>
                    <td class="px-6 py-4 font-medium text-on-surface whitespace-nowrap">{{ $device->kode_perangkat }}</td>
                    <td class="px-6 py-4 text-secondary whitespace-nowrap">{{ ucfirst($device->jenis_perangkat) }}</td>
                    <td class="px-6 py-4 text-secondary">{{ $device->pemilik_perangkat }}</td>
                    <td class="px-6 py-4 text-secondary">{{ $device->nama_penerima ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="font-semibold text-primary">{{ $device->nama_perangkat }}</span>
                    </td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        @php
                            $statusMap = [
                                'Aktif' => 'bg-green-100 text-green-800',
                                'Pending' => 'bg-yellow-100 text-yellow-800',
                                'Non-Aktif' => 'bg-red-100 text-red-800',
                                'Maintenance' => 'bg-yellow-100 text-yellow-800',
                            ];
                            $badgeColor = $statusMap[$device->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                            {{ $device->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        <div class="flex justify-center items-center gap-2">
                            @if($device->is_lengkap)
                                <a href="{{ route('server.dokumen.preview', $device->id) }}" target="_blank" rel="noopener" class="text-secondary hover:text-primary transition-colors p-1" title="Lihat dokumen">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </a>
                                <a href="{{ route('server.dokumen.download', $device->id) }}" class="text-secondary hover:text-primary transition-colors p-1" title="Unduh dokumen">
                                    <span class="material-symbols-outlined text-[20px]">download</span>
                                </a>
                            @else
                                <span class="text-secondary/40 cursor-not-allowed p-1" title="Dokumen tersedia setelah data dilengkapi admin">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </span>
                                <span class="text-secondary/40 cursor-not-allowed p-1" title="Dokumen tersedia setelah data dilengkapi admin">
                                    <span class="material-symbols-outlined text-[20px]">download</span>
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-secondary">
                        Belum ada perangkat yang Anda daftarkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($devices->hasPages())
    <div class="p-4 border-t border-outline-variant bg-white">
        {{ $devices->links('vendor.pagination.sim-tik') }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusFilterTrigger = document.getElementById('statusFilterTrigger');
        const statusFilterMenu = document.getElementById('statusFilterMenu');
        const activeFilterLabel = document.getElementById('activeFilterLabel');
        const statusOptions = document.querySelectorAll('.status-filter-option');
        const searchInput = document.getElementById('deviceSearchInput');
        let activeStatus = 'all';

        if (statusFilterTrigger && statusFilterMenu) {
            statusFilterTrigger.addEventListener('click', function (e) {
                e.stopPropagation();
                statusFilterMenu.classList.toggle('hidden');
            });

            statusOptions.forEach(function (option) {
                option.addEventListener('click', function () {
                    activeStatus = option.dataset.statusFilter;
                    activeFilterLabel.textContent = option.textContent.trim();
                    statusFilterMenu.classList.add('hidden');
                    applyFilters();
                });
            });

            document.addEventListener('click', function () {
                statusFilterMenu.classList.add('hidden');
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }

        function applyFilters() {
            const keyword = (searchInput ? searchInput.value : '').trim().toLowerCase();
            const rows = document.querySelectorAll('#deviceTableBody .device-row');

            rows.forEach(function (row) {
                const matchesSearch = keyword === '' || row.dataset.search.includes(keyword);
                const matchesStatus = activeStatus === 'all' || row.dataset.status === activeStatus;
                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
        }
    });
</script>
@endpush
