@extends('layouts.app')

@section('content')
    @php
        $statusColor = match ($server->status) {
            'Aktif' => 'bg-emerald-100 text-emerald-800',
            'Maintenance' => 'bg-amber-100 text-amber-800',
            default => 'bg-red-100 text-red-800',
        };

        $qrUrl = route('detailserver', $server->id);
        $qrImageUrl = route('qr.show', $server->id);
    @endphp<div class="flex justify-between items-center mb-6">
    <div>
        <nav aria-label="Breadcrumb" class="flex text-sm text-on-surface-variant mb-2 font-label-md">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">

                {{-- Item 1: Server dan Aplikasi --}}
                <li>
                    <a class="hover:text-primary transition-colors" href="{{ route('server.index') }}">
                        Server dan Aplikasi
                    </a>
                </li>

                {{-- Item 2: Server ← INI YANG BUG, tag <a tidak lengkap --}}
                <li>
                    <span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span>
                    <a class="hover:text-primary transition-colors" href="{{ route('server.index') }}">
                        Server
                    </a>
                </li>

                {{-- Item 3: Detail Server (halaman aktif) --}}
                <li aria-current="page">
                    <span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span>
                    <span class="text-on-surface font-semibold">Detail Server</span>
                </li>

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
                        class="w-40 h-40 rounded-lg border border-outline-variant">
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

        <!-- KOLOM KANAN: INFORMASI TAMBAHAN + GAMBAR RACK -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-outline-variant shadow-sm overflow-hidden h-full flex flex-col">
                <div class="bg-primary text-white px-6 py-4 font-headline-md text-headline-md flex items-center gap-2">
                    <span class="material-symbols-outlined">info</span> Informasi Tambahan
                </div>
                <div class="p-6 space-y-4 text-sm flex-1">
                    <!-- Gambar Rack -->
                    @if($server->gambar_rack)
                        <div>
                            <p class="text-xs text-secondary font-semibold uppercase tracking-wider mb-2">Gambar Rack</p>
                            <div class="relative group">
                                <img src="{{ Storage::url($server->gambar_rack) }}"
                                     alt="Gambar Rack Server"
                                     class="w-full rounded-lg border border-outline-variant cursor-pointer hover:opacity-90 transition-opacity"
                                     onclick="openImageModal('{{ Storage::url($server->gambar_rack) }}')">
                                <button onclick="openImageModal('{{ Storage::url($server->gambar_rack) }}')"
                                        class="absolute inset-0 flex items-center justify-center bg-black/0 hover:bg-black/30 transition-all rounded-lg">
                                    <span class="material-symbols-outlined text-white opacity-0 group-hover:opacity-100 transition-opacity text-4xl">zoom_in</span>
                                </button>
                            </div>
                        </div>
                        <div class="border-t border-outline-variant pt-3"></div>
                    @else
                        <div>
                            <p class="text-xs text-secondary font-semibold uppercase tracking-wider mb-2">Gambar Rack</p>
                            <div class="bg-surface-container-low rounded-lg border border-dashed border-outline-variant p-6 text-center">
                                <span class="material-symbols-outlined text-4xl text-secondary">image_not_supported</span>
                                <p class="text-xs text-secondary mt-2">Tidak ada gambar</p>
                            </div>
                        </div>
                        <div class="border-t border-outline-variant pt-3"></div>
                    @endif

                    <!-- Informasi lainnya -->
                    <div><span class="text-secondary">Dibuat:</span> {{ $server->created_at->format('d M Y, H:i') }}</div>
                    <div><span class="text-secondary">Terakhir Update:</span>
                        {{ $server->updated_at->format('d M Y, H:i') }}</div>
                    <div><span class="text-secondary">ID Server:</span> <span
                            class="font-mono">{{ $server->id }}</span></div>
                    <div><span class="text-secondary">RACK:</span> <span
                            class="font-semibold">{{ $server->nomor_rack ?? '-' }}</span></div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal Preview Gambar -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black/70 flex items-center justify-center p-4" onclick="closeImageModal()">
        <div class="relative max-w-4xl w-full" onclick="event.stopPropagation()">
            <button onclick="closeImageModal()"
                    class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors">
                <span class="material-symbols-outlined text-3xl">close</span>
            </button>
            <img id="modalImage" src="" alt="Gambar Rack" class="w-full rounded-lg shadow-2xl">
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-black/60 text-white text-sm px-4 py-2 rounded-lg">
                Klik di luar gambar untuk menutup
            </div>
        </div>
    </div>

    <script>
        function downloadQRCode() {
            const url = "{{ route('qr.download', $server->id) }}";
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

        // Modal untuk preview gambar
        function openImageModal(imageUrl) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageUrl;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Tutup modal dengan tombol ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
@endsection