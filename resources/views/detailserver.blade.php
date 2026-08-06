@extends('layouts.app')

@section('content')
    @php
        if (!isset($server)) {
            return redirect()->route('server.index')->with('error', 'Server tidak ditemukan.');
        }

        $statusColor = match ($server->status) {
            'Aktif' => 'bg-emerald-100 text-emerald-800',
            'Maintenance' => 'bg-amber-100 text-amber-800',
            default => 'bg-red-100 text-red-800',
        };

        $qrUrl = route('detailserver', $server->id);
        $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrUrl);
    @endphp

    <div class="flex justify-between items-center mb-6">
        <div>
            <nav aria-label="Breadcrumb" class="flex text-sm text-on-surface-variant mb-2 font-label-md">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li><a class="hover:text-primary transition-colors" href="{{ route('server.index') }}">Server dan
                            Aplikasi</a></li>
                    <li><span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span><a
                            href="{{ route('server.index') }}">Server</a></li>
                    <li aria-current="page"><span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span><span
                            class="text-on-surface font-semibold">Detail Server</span></li>
                </ol>
            </nav>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Detail Server</h2>
        </div>
        <a href="{{ route('server.index') }}"
            class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 border border-outline-variant px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali
        </a>
    </div>

    <!-- GRID UTAMA: 2 baris -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- BARIS 1: Tentang Server (kiri) + QR Code (kanan) -->
        <!-- Kolom kiri (2/3) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-outline-variant shadow-sm overflow-hidden h-full">
                <div class="bg-primary text-white px-6 py-4 font-headline-md text-headline-md flex items-center gap-2">
                    <span class="material-symbols-outlined">dns</span> Tentang Server
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                    <div>
                        <p class="text-xs text-secondary font-semibold uppercase tracking-wider">Nama Server</p>
                        <p class="font-semibold text-on-surface">{{ $server->nama_perangkat }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-secondary font-semibold uppercase tracking-wider">Tipe Perangkat</p>
                        <p class="font-semibold text-on-surface">{{ $server->tipe_perangkat ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-secondary font-semibold uppercase tracking-wider">Status Kepemilikan</p>
                        <p class="font-semibold text-on-surface">{{ $server->status_kepemilikan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-secondary font-semibold uppercase tracking-wider">IP Server</p>
                        <p class="font-semibold text-on-surface font-mono">{{ $server->ip_server ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-secondary font-semibold uppercase tracking-wider">IP VPS</p>
                        <p class="font-semibold text-on-surface font-mono">{{ $server->ip_vps ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-secondary font-semibold uppercase tracking-wider">Status</p>
                        <span
                            class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">{{ $server->status }}</span>
                    </div>
                    <div>
                        <p class="text-xs text-secondary font-semibold uppercase tracking-wider">Peruntukan</p>
                        <p class="font-semibold text-on-surface">{{ $server->peruntukan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-secondary font-semibold uppercase tracking-wider">Tanggal Pengisian</p>
                        <p class="font-semibold text-on-surface">
                            {{ $server->jam_pengisian ? $server->jam_pengisian->format('d M Y, H:i') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-secondary font-semibold uppercase tracking-wider">Pengirim / Penerima</p>
                        <p class="font-semibold text-on-surface">{{ $server->nama_pengirim ?? '-' }} /
                            {{ $server->nama_penerima ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-secondary font-semibold uppercase tracking-wider">Pemilik</p>
                        <p class="font-semibold text-on-surface">{{ $server->pemilik_perangkat ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom kanan (1/3) untuk QR Code -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-outline-variant shadow-sm overflow-hidden h-full flex flex-col">
                <div class="bg-primary text-white px-6 py-4 font-headline-md text-headline-md flex items-center gap-2">
                    <span class="material-symbols-outlined">qr_code_scanner</span> QR Code
                </div>
                <div class="p-6 flex flex-col items-center justify-center flex-1">
                    <img id="qrImage" src="{{ $qrImageUrl }}" alt="QR Code"
                        class="w-40 h-40 rounded-lg border border-outline-variant" crossorigin="anonymous">
                    <p class="mt-4 text-sm text-center text-secondary">Scan untuk melihat detail server ini</p>
                    <div class="flex flex-wrap gap-3 mt-3 justify-center">
                        <button onclick="downloadQRCode()"
                            class="flex items-center gap-1 bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-container transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[18px]">download</span> Download QR
                        </button>
                        <a href="{{ route('detailserver', $server->id) }}" target="_blank"
                            class="flex items-center gap-1 bg-surface-container-low text-on-surface-variant px-4 py-2 rounded-lg text-sm font-medium hover:bg-surface-container transition-colors border border-outline-variant">
                            <span class="material-symbols-outlined text-[18px]">link</span> Salin Link
                        </a>
                        <a href="{{ route('server.pdf', $server->id) }}" target="_blank"
                            class="flex items-center gap-1 bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-container transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[18px]">print</span> Cetak PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- BARIS 2: Spesifikasi Server (kiri) + Informasi Tambahan (kanan) -->
        <!-- Kolom kiri (2/3) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-outline-variant shadow-sm overflow-hidden h-full">
                <div class="bg-primary text-white px-6 py-4 font-headline-md text-headline-md flex items-center gap-2">
                    <span class="material-symbols-outlined">settings</span> Spesifikasi Server
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                        <div class="text-center p-3 bg-surface-container-low rounded-lg">
                            <span class="material-symbols-outlined text-primary">memory</span>
                            <p class="text-xs text-secondary">RAM</p>
                            <p class="font-bold">{{ $server->ukuran_ram ?? '-' }}</p>
                        </div>
                        <div class="text-center p-3 bg-surface-container-low rounded-lg">
                            <span class="material-symbols-outlined text-primary">hard_drive</span>
                            <p class="text-xs text-secondary">HDD</p>
                            <p class="font-bold">{{ $server->ukuran_hdd ?? '-' }}</p>
                        </div>
                        <div class="text-center p-3 bg-surface-container-low rounded-lg">
                            <span class="material-symbols-outlined text-primary">developer_board</span>
                            <p class="text-xs text-secondary">Core</p>
                            <p class="font-bold">{{ $server->jumlah_core ?? '-' }}</p>
                        </div>
                        <div class="text-center p-3 bg-surface-container-low rounded-lg">
                            <span class="material-symbols-outlined text-primary">kitchen</span>
                            <p class="text-xs text-secondary">RACK</p>
                            <p class="font-bold">{{ $server->nomor_rack ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm border-t border-outline-variant pt-4">
                        <div><span class="text-secondary">Merk:</span> <span
                                class="font-semibold">{{ $server->merk_perangkat ?? '-' }}</span></div>
                        <div><span class="text-secondary">Kondisi:</span> <span
                                class="font-semibold">{{ $server->kondisi_tipe ?? '-' }} /
                                {{ $server->kondisi_status ?? '-' }}</span></div>
                        <div><span class="text-secondary">Spesifikasi:</span> <span
                                class="font-semibold">{{ $server->spesifikasi ?? '-' }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom kanan (1/3) untuk Informasi Tambahan -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-outline-variant shadow-sm overflow-hidden h-full flex flex-col">
                <div class="bg-primary text-white px-6 py-4 font-headline-md text-headline-md flex items-center gap-2">
                    <span class="material-symbols-outlined">info</span> Informasi Tambahan
                </div>
                <div class="p-6 space-y-2 text-sm flex-1">
                    <div><span class="text-secondary">Dibuat:</span> {{ $server->created_at->format('d M Y, H:i') }}</div>
                    <div><span class="text-secondary">Terakhir Update:</span>
                        {{ $server->updated_at->format('d M Y, H:i') }}</div>
                    <div><span class="text-secondary">ID Server:</span> <span
                            class="font-mono">{{ $server->id }}</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL APLIKASI (Full Width) -->
    <div class="mt-6 bg-white rounded-xl border border-outline-variant shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-bright">
            <h3 class="font-headline-md text-headline-md text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined">apps</span> Server using for
            </h3>
            <div class="flex gap-2">
                <button
                    class="p-1.5 text-on-surface-variant hover:bg-surface-container-low rounded transition-colors border border-outline-variant bg-surface-container-lowest">
                    <span class="material-symbols-outlined text-[20px]">download</span>
                </button>
                <button
                    class="p-1.5 text-on-surface-variant hover:bg-surface-container-low rounded transition-colors border border-outline-variant bg-surface-container-lowest">
                    <span class="material-symbols-outlined text-[20px]">more_vert</span>
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F1F5F9] border-b border-outline-variant">
                        <th class="px-6 py-3 text-xs font-semibold text-secondary uppercase">IP Local</th>
                        <th class="px-6 py-3 text-xs font-semibold text-secondary uppercase">IP Public</th>
                        <th class="px-6 py-3 text-xs font-semibold text-secondary uppercase">Nama Aplikasi</th>
                        <th class="px-6 py-3 text-xs font-semibold text-secondary uppercase">URL</th>
                        <th class="px-6 py-3 text-xs font-semibold text-secondary uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($server->aplikasis) && $server->aplikasis->count() > 0)
                        @foreach ($server->aplikasis as $app)
                            <tr class="border-b border-outline-variant hover:bg-surface-container-low transition">
                                <td class="px-6 py-4 font-mono text-sm">{{ $app->pivot->ip_local ?? '-' }}</td>
                                <td class="px-6 py-4 font-mono text-sm">{{ $app->pivot->ip_public ?? '-' }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $app->nama }}</td>
                                <td class="px-6 py-4 text-primary hover:underline"><a
                                        href="#">{{ $app->pivot->url ?? '-' }}</a></td>
                                <td class="px-6 py-4 text-center">
                                    <button
                                        class="p-1.5 bg-primary/10 text-primary hover:bg-primary hover:text-white rounded transition">
                                        <span class="material-symbols-outlined text-[18px]">east</span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-secondary">Belum ada aplikasi terpasang.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div
            class="px-6 py-3 border-t border-outline-variant flex flex-col sm:flex-row justify-between items-center gap-3 text-sm text-secondary">
            <span>Showing {{ $server->aplikasis->count() ?? 0 }} entries</span>
            <div class="flex gap-1">
                <button class="px-3 py-1 border rounded disabled:opacity-50" disabled>Prev</button>
                <button class="px-3 py-1 bg-primary text-white rounded">1</button>
                <button class="px-3 py-1 border rounded hover:bg-surface-container-low">Next</button>
            </div>
        </div>
    </div>

    <script>
        function downloadQRCode() {
            const img = document.getElementById('qrImage');
            const url = img.src;

            fetch(url)
                .then(response => response.blob())
                .then(blob => {
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'qrcode-server-{{ $server->id }}.png';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    URL.revokeObjectURL(link.href);
                })
                .catch(() => {
                    window.open(url, '_blank');
                });
        }
    </script>
@endsection
