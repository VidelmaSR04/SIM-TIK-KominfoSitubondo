@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-on-surface">Manajemen Pengguna</h1>
        <p class="text-secondary text-sm mt-1">Kelola data pengguna dan hak akses sistem.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="flex items-center gap-2 bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm no-print">
        <span class="material-symbols-outlined text-[18px]">add</span> Tambah Pengguna
    </a>
</div>

@if (session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3 flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">check_circle</span> {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">error</span> {{ session('error') }}
    </div>
@endif

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Stat 1: Total Pengguna -->
    <div class="bg-white rounded-xl p-5 border border-outline-variant shadow-[0_1px_3px_rgba(0,0,0,0.1)] flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-blue-600 text-[28px]" style="font-variation-settings: 'FILL' 1;">group</span>
        </div>
        <div>
            <p class="text-xs font-semibold text-secondary uppercase tracking-wide mb-1">Total Pengguna</p>
            <p class="text-2xl font-bold text-on-surface">{{ $totalUsers }}</p>
        </div>
    </div>

    <!-- Stat 2: Admin -->
    <div class="bg-white rounded-xl p-5 border border-outline-variant shadow-[0_1px_3px_rgba(0,0,0,0.1)] flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-purple-600 text-[28px]" style="font-variation-settings: 'FILL' 1;">admin_panel_settings</span>
        </div>
        <div>
            <p class="text-xs font-semibold text-secondary uppercase tracking-wide mb-1">Administrator</p>
            <p class="text-2xl font-bold text-on-surface">{{ $totalAdmin }}</p>
        </div>
    </div>

    <!-- Stat 3: User OPD -->
    <div class="bg-white rounded-xl p-5 border border-outline-variant shadow-[0_1px_3px_rgba(0,0,0,0.1)] flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-amber-600 text-[28px]" style="font-variation-settings: 'FILL' 1;">apartment</span>
        </div>
        <div>
            <p class="text-xs font-semibold text-secondary uppercase tracking-wide mb-1">User OPD</p>
            <p class="text-2xl font-bold text-on-surface">{{ $totalOpd }}</p>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="bg-white rounded-xl border border-outline-variant shadow-[0_1px_3px_rgba(0,0,0,0.1)] overflow-hidden">
    <!-- Toolbar -->
    <div class="p-4 border-b border-outline-variant flex flex-wrap items-center gap-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-4 flex-wrap w-full">
            <div class="flex items-center gap-2 text-sm text-secondary whitespace-nowrap">
                <span>Show</span>
                <select name="perPage" onchange="this.form.submit()" class="border border-outline-variant rounded-lg bg-white py-1.5 pl-3 pr-8 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <option value="10" {{ request('perPage', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                </select>
                <span>entries</span>
            </div>

            <select name="role" onchange="this.form.submit()" class="border border-outline-variant rounded-lg bg-white py-1.5 pl-3 pr-8 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User OPD</option>
            </select>

            <div class="relative ml-auto">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
                <input name="search" value="{{ request('search') }}" class="pl-9 pr-3 py-2 w-64 border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary" placeholder="Cari nama atau email..." type="text" onkeypress="if(event.keyCode==13) this.form.submit()" />
            </div>
        </form>
    </div>

    <!-- Tabel -->
    <div class="table-container overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[700px]">
            <thead>
                <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide border-y border-outline-variant">
                    <th class="p-4">Nama Pengguna</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Role</th>
                    <th class="p-4">Terdaftar</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-on-surface divide-y divide-outline-variant/60">
                @forelse ($users as $u)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4 font-medium flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-primary-container text-white flex items-center justify-center text-xs font-semibold shrink-0">
                            {{ strtoupper(substr($u->name, 0, 2)) }}
                        </div>
                        {{ $u->name }}
                        @if ($u->id === auth()->id())
                            <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Anda</span>
                        @endif
                    </td>
                    <td class="p-4 text-on-surface-variant">{{ $u->email }}</td>
                    <td class="p-4">
                        @if ($u->role === 'admin')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Admin
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> User OPD
                            </span>
                        @endif
                    </td>
                    <td class="p-4 text-on-surface-variant text-xs">{{ $u->created_at->translatedFormat('d M Y') }}</td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2 text-gray-500">
                            <a href="{{ route('admin.users.edit', $u->id) }}" class="hover:text-blue-600 transition-colors" title="Edit Pengguna">
                                <span class="material-symbols-outlined text-[19px]">edit</span>
                            </a>
                            @if ($u->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus pengguna {{ $u->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="hover:text-red-600 transition-colors" title="Hapus Pengguna">
                                        <span class="material-symbols-outlined text-[19px]">delete</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">Tidak ada data pengguna.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-4 border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-3 text-sm">
        <span class="text-gray-500">
            Menampilkan {{ $users->firstItem() ?? 0 }} hingga {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} entri
        </span>
        <div class="flex gap-1">
            {{ $users->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection
