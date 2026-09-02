@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-on-surface">Manajemen Dokumen Server</h1>
        <p class="text-secondary text-sm mt-1">Daftar rincian server dalam format PDF yang dapat diunduh atau ditinjau.</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-outline-variant shadow-sm overflow-hidden">
    <!-- Toolbar -->
    <div class="p-4 border-b border-outline-variant flex flex-wrap items-center gap-4">
        <form action="{{ route('server.dokumen.index') }}" method="GET" class="flex items-center gap-4 flex-wrap">
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
                <input name="search" value="{{ request('search') }}" class="pl-9 pr-3 py-2 w-64 border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary" placeholder="Cari nama perangkat atau pemilik..." type="text" onkeypress="if(event.keyCode==13) this.form.submit()" />
            </div>
        </form>
    </div>

    <!-- Tabel -->
    <div class="table-container overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead>
                <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide border-y border-outline-variant">
                    <th class="p-4">ID</th>
                    <th class="p-4">QR</th>
                    <th class="p-4">Nama OPD</th>
                    <th class="p-4">Tanggal</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-on-surface divide-y divide-outline-variant/60">
                @forelse ($servers as $server)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4 text-gray-500 font-mono text-xs">{{ $server->id }}</td>
                    <td class="p-4 text-center">
                        <img src="{{ route('qr.show', $server->id) }}" alt="QR Code" class="w-12 h-12">
                    </td>
                    <td class="p-4">{{ $server->nama_opd }}</td>
                    <td class="p-4 text-gray-500 font-mono text-xs">{{ $server->created_at->format('d M Y') }}</td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2 text-gray-500">
                            <!-- Preview button -->
                            <a href="{{ route('server.dokumen.preview', $server->id) }}" class="hover:text-blue-600 transition-colors" title="Preview PDF" target="_blank">
                                <span class="material-symbols-outlined text-[19px]">visibility</span>
                            </a>
                            <!-- Download button -->
                            <a href="{{ route('server.dokumen.download', $server->id) }}" class="hover:text-green-600 transition-colors" title="Unduh PDF">
                                <span class="material-symbols-outlined text-[19px]">download</span>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">Tidak ada dokumen server.</td>
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