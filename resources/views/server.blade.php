@extends('layouts.app')

@section('content')
<?php
$servers = [
    ['id' => 'SRV-001', 'nama' => 'Node-DB-Master-01', 'ip_server' => '192.168.10.101', 'ip_vps' => '10.0.5.12', 'status' => 'Aktif'],
    ['id' => 'SRV-002', 'nama' => 'Web-Frontend-02', 'ip_server' => '192.168.10.102', 'ip_vps' => '-', 'status' => 'Aktif'],
    ['id' => 'SRV-003', 'nama' => 'Storage-Backup-A', 'ip_server' => '192.168.20.55', 'ip_vps' => '10.0.8.44', 'status' => 'Maintenance'],
    ['id' => 'SRV-004', 'nama' => 'API-Gateway-Core', 'ip_server' => '192.168.10.200', 'ip_vps' => '10.0.5.99', 'status' => 'Offline'],
];
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-on-surface">Perangkat & Server</h1>
        <p class="text-secondary text-sm mt-1">Manajemen data perangkat dan server aktif.</p>
    </div>
    <a href="{{ route('inputdata') }}" class="flex items-center gap-2 bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm no-print">
    <span class="material-symbols-outlined text-[18px]">add</span> Tambah Data
</a>
</div>

<div class="bg-white rounded-xl border border-outline-variant shadow-sm overflow-hidden">
    <div class="p-4 border-b border-outline-variant flex justify-end items-center gap-4">
        <div class="flex items-center gap-2 text-sm text-secondary whitespace-nowrap">
            <span>Show</span>
            <select class="border border-outline-variant rounded-lg bg-white py-1.5 pl-3 pr-8 text-sm"><option>10</option></select>
            <span>entries</span>
        </div>
        <div class="relative w-64">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
            <input class="pl-9 pr-3 py-2 w-full border border-outline-variant rounded-lg text-sm" placeholder="Cari data..." type="text"/>
        </div>
    </div>
    <div class="table-container overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead><tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide border-y border-outline-variant"><th class="p-4">ID</th><th class="p-4">NAMA PERANGKAT</th><th class="p-4">IP SERVER</th><th class="p-4">IP VPS</th><th class="p-4">STATUS</th><th class="p-4 text-center">AKSI</th></tr></thead>
            <tbody class="text-sm text-on-surface divide-y divide-outline-variant/60">
                @foreach ($servers as $s)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4 text-gray-500 font-mono text-xs">{{ $s['id'] }}</td>
                    <td class="p-4 font-medium flex items-center gap-2.5"><span class="material-symbols-outlined text-gray-400 text-[18px]">dns</span> {{ $s['nama'] }}</td>
                    <td class="p-4 font-mono text-xs text-gray-500">{{ $s['ip_server'] }}</td>
                    <td class="p-4 font-mono text-xs text-gray-500">{{ $s['ip_vps'] }}</td>
                    <td class="p-4">
                        @php $c = match($s['status']) { 'Aktif' => 'bg-green-100 text-green-700', 'Maintenance' => 'bg-amber-100 text-amber-700', default => 'bg-red-100 text-red-700' }; $d = match($s['status']) { 'Aktif' => 'bg-green-500', 'Maintenance' => 'bg-amber-500', default => 'bg-red-500' }; @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ $c }}"><span class="w-1.5 h-1.5 rounded-full {{ $d }}"></span> {{ $s['status'] }}</span>
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2 text-gray-500">
                            <!-- IKON MATA DIUBAH MENJADI LINK DETAIL SERVER -->
                            <a href="{{ route('detailserver', ['id' => $s['id']]) }}" class="hover:text-blue-600 transition-colors" title="Lihat Detail Server">
                                <span class="material-symbols-outlined text-[19px]">visibility</span>
                            </a>
                            <button class="hover:text-blue-600 transition-colors" title="Edit Data">
                                <span class="material-symbols-outlined text-[19px]">edit</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-outline-variant flex justify-between text-sm">
        <span class="text-gray-500">Menampilkan 4 entri</span>
        <div class="flex gap-1"><button class="px-3 py-1.5 border border-outline-variant rounded-lg text-gray-400 opacity-50" disabled>Prev</button><button class="px-3 py-1.5 bg-primary text-white rounded-lg">1</button><button class="px-3 py-1.5 border border-outline-variant rounded-lg text-gray-500 hover:bg-gray-50">Next</button></div>
    </div>
</div>

<!-- MODAL TAMBAH DATA -->
<div id="createModal" class="fixed inset-0 bg-black/50 z-[60] hidden overflow-y-auto p-6 flex items-center justify-center no-print">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl overflow-hidden my-auto relative">
        <div class="bg-[#2aa9bd] text-white px-6 py-4 flex justify-between items-center">
            <h3 class="font-semibold text-lg flex items-center gap-2"><span class="material-symbols-outlined">library_books</span> Form Biodata</h3>
            <button onclick="closeModal()" class="text-white/80 hover:text-white"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form class="p-6 space-y-4" onsubmit="event.preventDefault(); alert('Data berhasil disimpan (Simulasi Frontend)!'); closeModal();">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="col-span-2"><label class="block text-sm font-bold mb-1">Nama Perangkat</label><div class="relative"><span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">dns</span><input type="text" class="w-full pl-10 pr-3 py-2 border border-outline-variant rounded-lg text-sm"></div></div>
                <div><label class="block text-sm font-bold mb-1">Jenis Perangkat</label><select class="w-full py-2 px-3 border border-outline-variant rounded-lg text-sm"><option>-- Pilih Perangkat --</option><option>Server</option></select></div>
                <div><label class="block text-sm font-bold mb-1">Serial Number</label><div class="relative"><span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">tag</span><input type="text" class="w-full pl-10 pr-3 py-2 border border-outline-variant rounded-lg text-sm"></div></div>
                <div><label class="block text-sm font-bold mb-1">Merk Perangkat</label><select class="w-full py-2 px-3 border border-outline-variant rounded-lg text-sm"><option>MIKROTIK</option></select></div>
                <div><label class="block text-sm font-bold mb-1">TYPE</label><input type="text" class="w-full py-2 px-3 border border-outline-variant rounded-lg text-sm"></div>
                <div><label class="block text-sm font-bold mb-1">Tipe Perangkat</label><select class="w-full py-2 px-3 border border-outline-variant rounded-lg text-sm"><option>RACK MOUNT</option></select></div>
                <div><label class="block text-sm font-bold mb-1">Status Kepemilikan</label><select class="w-full py-2 px-3 border border-outline-variant rounded-lg text-sm"><option>Kominfo</option></select></div>
                <div><label class="block text-sm font-bold mb-1">IP Peerangkat</label><div class="relative"><span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">language</span><input type="text" class="w-full pl-10 pr-3 py-2 border border-outline-variant rounded-lg text-sm"></div></div>
                <div><label class="block text-sm font-bold mb-1">Status Perangkat</label><select class="w-full py-2 px-3 border border-outline-variant rounded-lg text-sm"><option>Aktif</option></select></div>
                <div><label class="block text-sm font-bold mb-1">Ukuran HDD</label><select class="w-full py-2 px-3 border border-outline-variant rounded-lg text-sm"><option>300 GB</option></select></div>
                <div><label class="block text-sm font-bold mb-1">Ukuran RAM</label><select class="w-full py-2 px-3 border border-outline-variant rounded-lg text-sm"><option>4 GB</option></select></div>
                <div><label class="block text-sm font-bold mb-1">Nomor RACK</label><select class="w-full py-2 px-3 border border-outline-variant rounded-lg text-sm"><option>-- Pilih Rack --</option></select></div>
                <div><label class="block text-sm font-bold mb-1">Jumlah Core</label><select class="w-full py-2 px-3 border border-outline-variant rounded-lg text-sm"><option>4</option></select></div>
                <div class="col-span-2"><label class="block text-sm font-bold mb-1">Peruntukan Perangkat</label><div class="relative"><span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">list_alt</span><input type="text" class="w-full pl-10 pr-3 py-2 border border-outline-variant rounded-lg text-sm"></div></div>
                <div class="col-span-2 pt-2 border-t border-gray-100"><button type="submit" class="bg-[#2aa9bd] hover:bg-[#2390a2] text-white px-6 py-2 rounded-lg text-sm font-medium">Simpan</button></div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openModal() { document.getElementById('createModal').classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    function closeModal() { document.getElementById('createModal').classList.add('hidden'); document.body.style.overflow = 'auto'; }
    document.getElementById('createModal').addEventListener('click', function(e) { if (e.target === this) closeModal(); });
</script>
@endpush
@endsection