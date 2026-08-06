@extends('layouts.app')

@section('content')
@php
// Mengambil ID dari URL (misal: SRV-001, SRV-002)
$id = request()->route('id');

// Data Dummy Lengkap untuk Semua Server
$serverData = [
    'SRV-001' => [
        'nama' => 'Node-DB-Master-01',
        'tipe' => 'Rack Mount',
        'kepemilikan' => 'Dinas Kominfo',
        'ip_server' => '192.168.10.101',
        'status' => 'Active',
        'peruntukan' => 'Database Production',
        'waktu' => '12 Jan 2026, 10:00',
        'pengirim_penerima' => 'Budi / Andi',
        'ram' => '16 GB',
        'hdd' => '1 TB',
        'core' => '8 Core',
        'rack' => 'Rack 2',
        'merk' => 'DELL PowerEdge',
        'kondisi' => 'Standard / Baru',
        'spesifikasi' => 'SSD NVMe added',
        'table_data' => [
            ['local' => '192.168.10.10', 'public' => '103.11.22.33', 'nama_app' => 'Sistem Keuangan', 'url' => 'keuangan.situbondokab.go.id'],
            ['local' => '-', 'public' => '-', 'nama_app' => 'Portal Berita', 'url' => 'berita.situbondokab.go.id']
        ]
    ],
    'SRV-002' => [
        'nama' => 'Web-Frontend-02',
        'tipe' => 'Tower',
        'kepemilikan' => 'Dinas Kominfo',
        'ip_server' => '192.168.10.102',
        'status' => 'Active',
        'peruntukan' => 'Web Hosting',
        'waktu' => '15 Feb 2026, 09:30',
        'pengirim_penerima' => 'Joko / Siti',
        'ram' => '8 GB',
        'hdd' => '512 GB',
        'core' => '4 Core',
        'rack' => '-',
        'merk' => 'HP ProLiant',
        'kondisi' => 'Standard / Bekas',
        'spesifikasi' => '-',
        'table_data' => [
            ['local' => '192.168.10.12', 'public' => '103.11.22.45', 'nama_app' => 'E-Office', 'url' => 'eoffice.situbondokab.go.id']
        ]
    ],
    'SRV-003' => [
        'nama' => 'Storage-Backup-A',
        'tipe' => 'Rack Mount',
        'kepemilikan' => 'Dinas PUPR',
        'ip_server' => '192.168.20.55',
        'status' => 'Maintenance',
        'peruntukan' => 'Backup Server',
        'waktu' => '20 Mar 2026, 14:00',
        'pengirim_penerima' => 'Anton / Rina',
        'ram' => '32 GB',
        'hdd' => '4 TB',
        'core' => '8 Core',
        'rack' => 'Rack 1',
        'merk' => 'DELL PowerEdge',
        'kondisi' => 'Standard / Baru',
        'spesifikasi' => 'RAID 10',
        'table_data' => [
            ['local' => '192.168.20.1', 'public' => '-', 'nama_app' => 'Backup Data Center', 'url' => '-']
        ]
    ],
    'SRV-004' => [
        'nama' => 'API-Gateway-Core',
        'tipe' => 'Rack Mount',
        'kepemilikan' => 'Dinas Kominfo',
        'ip_server' => '192.168.10.200',
        'status' => 'Offline',
        'peruntukan' => 'API Services',
        'waktu' => '-',
        'pengirim_penerima' => '- / -',
        'ram' => '16 GB',
        'hdd' => '256 GB',
        'core' => '4 Core',
        'rack' => 'Rack 2',
        'merk' => 'Cisco UCS',
        'kondisi' => 'Standard / Baru',
        'spesifikasi' => '-',
        'table_data' => []
    ]
];

// Jika ID tidak ditemukan (misal diketik manual di URL), tampilkan data SRV-001
$server = $serverData[$id] ?? $serverData['SRV-001'];

// Helper untuk warna badge status
$statusColor = match($server['status']) {
    'Active' => 'bg-emerald-100 text-emerald-800',
    'Maintenance' => 'bg-amber-100 text-amber-800',
    default => 'bg-red-100 text-red-800'
};
@endphp

<!-- Header & Breadcrumb -->
<div class="flex justify-between items-end mb-6">
  <div>
    <nav aria-label="Breadcrumb" class="flex text-sm text-on-surface-variant mb-2 font-label-md">
      <ol class="inline-flex items-center space-x-1 md:space-x-2">
        <li class="inline-flex items-center">
          <a class="hover:text-primary transition-colors" href="{{ route('server') }}">Server dan Aplikasi</a>
        </li>
        <li>
          <div class="flex items-center">
            <span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span>
            <a class="hover:text-primary transition-colors" href="{{ route('server') }}">Server</a>
          </div>
        </li>
        <li aria-current="page">
          <div class="flex items-center">
            <span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span>
            <span class="text-on-surface font-semibold">Detail Server</span>
          </div>
        </li>
      </ol>
    </nav>
    <h2 class="font-headline-lg text-headline-lg text-on-surface">Detail Server</h2>
  </div>
</div>

<!-- Bento Grid Layout -->
<div class="grid grid-cols-12 gap-gutter mb-6">
  <!-- Left Column: Tentang Server -->
  <div class="col-span-12 lg:col-span-8 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-[0_1px_3px_rgba(0,0,0,0.1)] overflow-hidden flex flex-col md:flex-row">
    <div class="flex-1 flex flex-col">
      <div class="bg-primary text-on-primary px-6 py-4 font-headline-md text-headline-md border-b border-primary/20">Tentang Server</div>
      <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8 flex-1">
        <div class="flex items-start gap-3">
          <div class="p-2 bg-primary/10 rounded-lg text-primary mt-1"><span class="material-symbols-outlined text-[20px]">dns</span></div>
          <div><p class="font-label-md text-label-md text-on-surface-variant mb-1">Nama Server</p><p class="font-body-md text-body-md font-semibold text-on-surface">{{ $server['nama'] }}</p></div>
        </div>
        <div class="flex items-start gap-3">
          <div class="p-2 bg-primary/10 rounded-lg text-primary mt-1"><span class="material-symbols-outlined text-[20px]">inventory_2</span></div>
          <div><p class="font-label-md text-label-md text-on-surface-variant mb-1">Tipe Perangkat</p><p class="font-body-md text-body-md font-semibold text-on-surface">{{ $server['tipe'] }}</p></div>
        </div>
        <div class="flex items-start gap-3">
          <div class="p-2 bg-primary/10 rounded-lg text-primary mt-1"><span class="material-symbols-outlined text-[20px]">account_balance</span></div>
          <div><p class="font-label-md text-label-md text-on-surface-variant mb-1">Status Kepemilikan</p><p class="font-body-md text-body-md font-semibold text-on-surface">{{ $server['kepemilikan'] }}</p></div>
        </div>
        <div class="flex items-start gap-3">
          <div class="p-2 bg-primary/10 rounded-lg text-primary mt-1"><span class="material-symbols-outlined text-[20px]">router</span></div>
          <div><p class="font-label-md text-label-md text-on-surface-variant mb-1">IP Server</p><p class="font-body-md text-body-md font-semibold text-on-surface">{{ $server['ip_server'] }}</p></div>
        </div>
        <div class="flex items-start gap-3">
          <div class="p-2 bg-primary/10 rounded-lg text-primary mt-1"><span class="material-symbols-outlined text-[20px]">info</span></div>
          <div><p class="font-label-md text-label-md text-on-surface-variant mb-1">Status Server</p>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
              {{ $server['status'] }}
            </span>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="p-2 bg-primary/10 rounded-lg text-primary mt-1"><span class="material-symbols-outlined text-[20px]">target</span></div>
          <div><p class="font-label-md text-label-md text-on-surface-variant mb-1">Peruntukan Server</p><p class="font-body-md text-body-md font-semibold text-on-surface">{{ $server['peruntukan'] }}</p></div>
        </div>
        <div class="flex items-start gap-3">
          <div class="p-2 bg-primary/10 rounded-lg text-primary mt-1"><span class="material-symbols-outlined text-[20px]">schedule</span></div>
          <div><p class="font-label-md text-label-md text-on-surface-variant mb-1">Waktu Pengisian</p><p class="font-body-md text-body-md font-semibold text-on-surface">{{ $server['waktu'] }}</p></div>
        </div>
        <div class="flex items-start gap-3">
          <div class="p-2 bg-primary/10 rounded-lg text-primary mt-1"><span class="material-symbols-outlined text-[20px]">swap_horiz</span></div>
          <div><p class="font-label-md text-label-md text-on-surface-variant mb-1">Pengirim/Penerima</p><p class="font-body-md text-body-md font-semibold text-on-surface">{{ $server['pengirim_penerima'] }}</p></div>
        </div>
      </div>
    </div>
    <!-- QR Code Section -->
    <div class="w-full md:w-64 bg-surface-container-low border-t md:border-t-0 md:border-l border-outline-variant p-6 flex flex-col items-center justify-center">
      <button class="flex items-center gap-2 bg-white text-on-surface-variant border border-outline-variant px-4 py-2 rounded-lg font-label-md hover:bg-surface-container-low transition-colors shadow-sm mb-4 w-full justify-center">
        <span class="material-symbols-outlined text-[18px]">download</span> Unduh Kode QR
      </button>
      <img class="w-40 h-40 object-contain bg-white p-2 rounded-lg shadow-sm border border-outline-variant mb-4" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBo5cL_-IX66qkUJYb_9ky2aVgpiOm8aq_RzyB2v1PjV5Cp8if2AEbXwPmRJHab066gPscOMTDicSTHQFyVNkGASI7MrvRnYnc5uyHIG327WezRZWCyhxVwvHv2s1qhDYEjjQaN9NQ0Q2wm81hfRDjuTYUmiZLpPWDBgjuGp-CvVApcoBmY-yheui9n8ZEjemuMitV0k4Eu-qdcIamc0bg9SNW-vNeK_XY3NZtbEsFKgfdrfhNJvq0Y">
      <p class="text-center font-label-md text-on-surface-variant">Scan untuk info server real-time</p>
    </div>
  </div>
  <!-- Right Column: Stats & Specs -->
  <div class="col-span-12 lg:col-span-4 flex flex-col gap-gutter">
    <div class="grid grid-cols-2 gap-gutter">
      <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant shadow-sm flex flex-col items-center justify-center text-center">
        <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 mb-2"><span class="material-symbols-outlined">memory</span></div>
        <p class="font-label-md text-on-surface-variant">RAM</p><p class="font-headline-md text-on-surface mt-1">{{ $server['ram'] }}</p>
      </div>
      <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant shadow-sm flex flex-col items-center justify-center text-center">
        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 mb-2"><span class="material-symbols-outlined">hard_drive</span></div>
        <p class="font-label-md text-on-surface-variant">HDD</p><p class="font-headline-md text-on-surface mt-1">{{ $server['hdd'] }}</p>
      </div>
      <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant shadow-sm flex flex-col items-center justify-center text-center">
        <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 mb-2"><span class="material-symbols-outlined">developer_board</span></div>
        <p class="font-label-md text-on-surface-variant">Core</p><p class="font-headline-md text-on-surface mt-1">{{ $server['core'] }}</p>
      </div>
      <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant shadow-sm flex flex-col items-center justify-center text-center">
        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 mb-2"><span class="material-symbols-outlined">kitchen</span></div>
        <p class="font-label-md text-on-surface-variant">RACK</p><p class="font-headline-md text-on-surface mt-1">{{ $server['rack'] }}</p>
      </div>
    </div>
    <div class="bg-surface-container-low rounded-xl p-4 border border-outline-variant shadow-sm flex flex-col gap-3">
      <div class="flex justify-between items-center bg-surface-container-lowest p-3 rounded border border-outline-variant/50">
        <span class="font-label-md text-on-surface-variant">Merk</span><span class="font-body-md font-semibold text-on-surface">{{ $server['merk'] }}</span>
      </div>
      <div class="flex justify-between items-center bg-surface-container-lowest p-3 rounded border border-outline-variant/50">
        <span class="font-label-md text-on-surface-variant">Kondisi Server</span><span class="font-body-md font-semibold text-on-surface">{{ $server['kondisi'] }}</span>
      </div>
      <div class="flex justify-between items-center bg-surface-container-lowest p-3 rounded border border-outline-variant/50">
        <span class="font-label-md text-on-surface-variant">Spesifikasi Upgrade</span><span class="font-body-md font-semibold text-on-surface">{{ $server['spesifikasi'] }}</span>
      </div>
    </div>
  </div>
</div>

<!-- Bottom Section: Table -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-[0_1px_3px_rgba(0,0,0,0.1)] overflow-hidden">
  <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-bright">
    <h3 class="font-headline-md text-headline-md text-on-surface">Server using for</h3>
    <div class="flex gap-2">
      <button class="p-1.5 text-on-surface-variant hover:bg-surface-container-low rounded transition-colors border border-outline-variant bg-surface-container-lowest"><span class="material-symbols-outlined text-[20px]">download</span></button>
      <button class="p-1.5 text-on-surface-variant hover:bg-surface-container-low rounded transition-colors border border-outline-variant bg-surface-container-lowest"><span class="material-symbols-outlined text-[20px]">more_vert</span></button>
    </div>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-[#F1F5F9] border-b border-outline-variant">
          <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant">IP Local</th>
          <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant">IP Public</th>
          <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant">Nama Aplikasi</th>
          <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant">URL</th>
          <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="font-data-tabular text-data-tabular">
        @forelse ($server['table_data'] as $row)
        <tr class="bg-surface-container-lowest border-b border-outline-variant hover:bg-surface-container-low transition-colors">
          <td class="px-6 py-4 text-on-surface font-semibold">{{ $row['local'] }}</td>
          <td class="px-6 py-4 text-on-surface">{{ $row['public'] }}</td>
          <td class="px-6 py-4 text-on-surface">{{ $row['nama_app'] }}</td>
          <td class="px-6 py-4 text-primary hover:underline cursor-pointer">{{ $row['url'] }}</td>
          <td class="px-6 py-4 text-center">
            <button class="p-1.5 bg-primary/10 text-primary hover:bg-primary hover:text-on-primary rounded transition-colors inline-flex items-center justify-center" title="Pindah"><span class="material-symbols-outlined text-[18px]">east</span></button>
          </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="px-6 py-4 text-center text-secondary">Tidak ada aplikasi yang terpasang pada server ini.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="px-6 py-4 border-t border-outline-variant flex flex-col md:flex-row justify-between items-center gap-4 bg-surface-bright">
    <div class="text-body-md text-on-surface-variant">Showing <span class="font-semibold text-on-surface">1</span> to <span class="font-semibold text-on-surface">{{ count($server['table_data']) }}</span> of <span class="font-semibold text-on-surface">{{ count($server['table_data']) }}</span> entries</div>
    <nav class="flex items-center gap-1">
      <button class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded transition-colors disabled:opacity-50" disabled=""><span class="material-symbols-outlined text-[20px]">chevron_left</span></button>
      <button class="w-8 h-8 flex items-center justify-center rounded bg-primary text-on-primary font-semibold text-body-md shadow-sm">1</button>
      <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-surface-container-low text-on-surface-variant font-medium text-body-md transition-colors">2</button>
      <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-surface-container-low text-on-surface-variant font-medium text-body-md transition-colors">3</button>
      <span class="px-1 text-on-surface-variant">...</span>
      <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-surface-container-low text-on-surface-variant font-medium text-body-md transition-colors">6</button>
      <button class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded transition-colors"><span class="material-symbols-outlined text-[20px]">chevron_right</span></button>
    </nav>
  </div>
</div>
@endsection