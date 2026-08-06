@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-on-surface">Manajemen Aplikasi</h1>
        <p class="text-secondary text-sm mt-1">Daftar aplikasi dan sistem informasi terkait.</p>
    </div>
    <button class="flex items-center gap-2 bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm no-print">
        <span class="material-symbols-outlined text-[18px]">add</span> Tambah Data
    </button>
</div>

<div class="bg-white rounded-xl border border-outline-variant shadow-sm overflow-hidden">
    <!-- Toolbar -->
    <div class="p-4 border-b border-outline-variant flex flex-col sm:flex-row justify-end items-center gap-4">
        <div class="flex items-center gap-2 text-sm text-secondary whitespace-nowrap">
            <span>Show</span>
            <select class="border border-outline-variant rounded-lg bg-white py-1.5 pl-3 pr-8 text-sm cursor-pointer"><option>10</option></select>
            <span>entries</span>
        </div>
        <div class="relative w-64 sm:w-auto sm:flex-1">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
            <input class="pl-9 pr-3 py-2 w-full border border-outline-variant rounded-lg text-sm" placeholder="Cari data..." type="text"/>
        </div>
    </div>

    <!-- Tabel -->
    <div class="table-container overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[900px]">
            <thead>
                <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide border-y border-outline-variant">
                    <th class="p-4 w-16">ID</th>
                    <th class="p-4 w-64">NAMA APLIKASI</th>
                    <th class="p-4 w-36">IP LOCAL</th>
                    <th class="p-4 w-36">IP PUBLIC</th>
                    <th class="p-4 w-48">PENANGGUNG JAWAB</th>
                    <th class="p-4 w-36">STATUS</th>
                    <th class="p-4 w-20 text-center">AKSI</th>
                </tr>
            </thead>
            <tbody class="text-sm text-on-surface divide-y divide-outline-variant/60">
                @php
                $aplikasis = [
                    ['id' => 1, 'nama' => 'ALADIN (Aplikasi Adminduk Online)', 'local' => '-', 'public' => '103.165.156.229', 'pj' => 'Zakiatul Darojati, A.Md.', 'status' => 'Sudah Asesmen'],
                    ['id' => 2, 'nama' => 'SKEMA (Survey Kepuasan Masyarakat)', 'local' => '192.168.99.94', 'public' => '103.165.156.249', 'pj' => 'Hosnol Fawaid, S.Kom.', 'status' => 'Sudah Asesmen'],
                    ['id' => 3, 'nama' => 'Aplikasi Presensi Kabupaten Situbondo (APRESIASI)', 'local' => '-', 'public' => '103.165.156.245', 'pj' => '-', 'status' => 'Sudah Asesmen'],
                    ['id' => 4, 'nama' => 'Website DPRD', 'local' => '192.168.99.72', 'public' => '103.165.156.249', 'pj' => '-', 'status' => 'Belum Asesmen'],
                    ['id' => 5, 'nama' => 'Website Layanan Tourist Information Center (TIC)', 'local' => '192.168.99.72', 'public' => '103.165.156.249', 'pj' => '-', 'status' => 'Belum Asesmen'],
                ];
                @endphp
                @foreach ($aplikasis as $a)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4 text-gray-500">{{ $a['id'] }}</td>
                    <td class="p-4 font-medium text-on-surface">{{ $a['nama'] }}</td>
                    <td class="p-4 font-mono text-xs text-gray-500">{{ $a['local'] }}</td>
                    <td class="p-4 font-mono text-xs text-gray-500">{{ $a['public'] }}</td>
                    <td class="p-4 text-gray-500">{{ $a['pj'] }}</td>
                    <td class="p-4">
                        @php
                        $aStatusColor = $a['status'] == 'Sudah Asesmen' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600';
                        $aDotColor = $a['status'] == 'Sudah Asesmen' ? 'bg-green-500' : 'bg-gray-400';
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ $aStatusColor }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $aDotColor }}"></span> {{ $a['status'] }}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <button class="text-teal-600 hover:text-teal-800 transition-colors p-1.5 rounded-lg">
                            <span class="material-symbols-outlined text-[19px]">search</span>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-4 border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-3 text-sm">
        <span class="text-gray-500">Menampilkan 1 hingga 5 dari 226 entri</span>
        <div class="flex gap-1">
            <button class="px-3 py-1.5 border border-outline-variant rounded-lg text-gray-400 opacity-50 cursor-not-allowed">Prev</button>
            <button class="px-3 py-1.5 bg-primary text-white rounded-lg shadow-sm">1</button>
            <button class="px-3 py-1.5 border border-outline-variant rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">2</button>
            <button class="px-3 py-1.5 border border-outline-variant rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">3</button>
            <span class="px-2 py-1.5 text-gray-500">...</span>
            <button class="px-3 py-1.5 border border-outline-variant rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">23</button>
            <button class="px-3 py-1.5 border border-outline-variant rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">Next</button>
        </div>
    </div>
</div>
@endsection