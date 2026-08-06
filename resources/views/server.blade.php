@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-on-surface">Perangkat & Server</h1>
        <p class="text-secondary text-sm mt-1">Manajemen data perangkat dan server aktif.</p>
    </div>
    <a href="{{ route('server.create') }}" class="flex items-center gap-2 bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm no-print">
        <span class="material-symbols-outlined text-[18px]">add</span> Tambah Data
    </a>
</div>

<div class="bg-white rounded-xl border border-outline-variant shadow-sm overflow-hidden">
    <!-- Toolbar -->
    <div class="p-4 border-b border-outline-variant flex flex-wrap items-center gap-4">
        <form action="{{ route('server.index') }}" method="GET" class="flex items-center gap-4 flex-wrap">
            <div class="flex items-center gap-2 text-sm text-secondary whitespace-nowrap">
                <span>Show</span>
                <select name="perPage" onchange="this.form.submit()" class="border border-outline-variant rounded-lg bg-white py-1.5 pl-3 pr-8 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                </select>
                <span>entries</span>
            </div>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
                <input name="search" value="{{ request('search') }}" class="pl-9 pr-3 py-2 w-64 border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary" placeholder="Cari data..." type="text" onkeypress="if(event.keyCode==13) this.form.submit()" />
            </div>
        </form>
    </div>

    <!-- Tabel -->
    <div class="table-container overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead>
                <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide border-y border-outline-variant">
                    <th class="p-4">ID</th>
                    <th class="p-4">NAMA PERANGKAT</th>
                    <th class="p-4">IP SERVER</th>
                    <th class="p-4">IP VPS</th>
                    <th class="p-4">STATUS</th>
                    <th class="p-4 text-center">AKSI</th>
                </tr>
            </thead>
            <tbody class="text-sm text-on-surface divide-y divide-outline-variant/60">
                @forelse ($servers as $s)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4 text-gray-500 font-mono text-xs">{{ $s->id }}</td>
                    <td class="p-4 font-medium flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-gray-400 text-[18px]">dns</span> {{ $s->nama_perangkat }}
                    </td>
                    <td class="p-4 font-mono text-xs text-gray-500">{{ $s->ip_server ?? '-' }}</td>
                    <td class="p-4 font-mono text-xs text-gray-500">{{ $s->ip_vps ?? '-' }}</td>
                    <td class="p-4">
                        @php
                            $status = $s->status;
                            $c = match($status) {
                                'Aktif' => 'bg-green-100 text-green-700',
                                'Maintenance' => 'bg-amber-100 text-amber-700',
                                default => 'bg-red-100 text-red-700'
                            };
                            $d = match($status) {
                                'Aktif' => 'bg-green-500',
                                'Maintenance' => 'bg-amber-500',
                                default => 'bg-red-500'
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ $c }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $d }}"></span> {{ $status }}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2 text-gray-500">
                            <a href="{{ route('detailserver', $s->id) }}" class="hover:text-blue-600 transition-colors" title="Lihat Detail Server">
                                <span class="material-symbols-outlined text-[19px]">visibility</span>
                            </a>
                            <a href="{{ route('server.edit', $s->id) }}" class="hover:text-blue-600 transition-colors" title="Edit Data">
                                <span class="material-symbols-outlined text-[19px]">edit</span>
                            </a>
                            <form action="{{ route('server.destroy', $s->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus server ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="hover:text-red-600 transition-colors" title="Hapus Data">
                                    <span class="material-symbols-outlined text-[19px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500">Tidak ada data server.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-4 border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-3 text-sm">
        <span class="text-gray-500">
            Menampilkan {{ $servers->firstItem() ?? 0 }} hingga {{ $servers->lastItem() ?? 0 }} dari {{ $servers->total() }} entri
        </span>
        <div class="flex gap-1">
            {{ $servers->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection