@extends('layouts.app')

@section('content')
<div x-data="{
    showPhotoModal: false,
    photo: {},
    downloadFoto: function(event) {
        const button = event.target;
        const row = button.closest('tr');
        if (!row) return;

        const data = row.dataset;
        const kepemilikan = data.kepemilikan || '-';
        const kode = data.kode || '-';
        const gambar = data.gambar || '';
        const tanggal = data.tanggal || '-';
        const rack = data.rack || '-';

        // Ensure Times New Roman is loaded if needed
        this.loadFontIfNeeded();

        // Create canvas
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        // Set dimensions (matching PhotoCardService proportions)
        const width = 600;
        const height = 900;
        canvas.width = width;
        canvas.height = height;

        // Draw background gradient (simplified - solid color for now, can enhance)
        ctx.fillStyle = '#f5f8fc'; // gradientStart from PhotoCardService
        ctx.fillRect(0, 0, width, height);

        // Load and draw image if available
        if (gambar) {
            const img = new Image();
            img.crossOrigin = 'anonymous'; // Handle CORS
            img.onload = () => {
                // Define header and footer reserved spaces (based on drawText measurements)
                const headerHeight = 120; // approximate header space from drawText
                const footerHeight = 70;  // approximate footer space from drawText
                const hGap = 20; // horizontal gap
                const vGap = 20; // vertical gap

                // Calculate available space for image
                let availableWidth = width - (hGap * 2);
                let availableHeight = height - headerHeight - footerHeight - (vGap * 2);

                // Apply max constraints from PhotoCardService
                const maxWidth = 540; // photoMaxWidth from PhotoCardService
                const maxHeight = 650; // photoMaxHeight from PhotoCardService
                availableWidth = Math.min(availableWidth, maxWidth);
                availableHeight = Math.min(availableHeight, maxHeight);

                // Get natural image dimensions
                const naturalWidth = img.naturalWidth;
                const naturalHeight = img.naturalHeight;

                // Determine orientation
                const isLandscape = naturalWidth > naturalHeight;

                // Calculate scaling factor to fit within available space while maintaining aspect ratio
                let scaleWidth = availableWidth / naturalWidth;
                let scaleHeight = availableHeight / naturalHeight;
                let scale = Math.min(scaleWidth, scaleHeight);

                // Calculate final image dimensions
                const imgWidth = Math.floor(naturalWidth * scale);
                const imgHeight = Math.floor(naturalHeight * scale);

                // Calculate positioning to center image in available space
                const startX = hGap + (availableWidth - imgWidth) / 2;
                const startY = headerHeight + vGap + (availableHeight - imgHeight) / 2;

                // Draw image centered in available space
                ctx.drawImage(img, startX, startY, imgWidth, imgHeight);
                this.drawText(ctx, kepemilikan, kode, tanggal, rack, width, height);
                this.triggerDownload(canvas, 'foto-rack-' + (kode === '-' ? 'unknown' : kode) + '.png');
            };
            img.onerror = () => {
                console.error('Failed to load image:', gambar);
                // If image fails to load, continue with text only
                this.drawText(ctx, kepemilikan, kode, tanggal, rack, width, height);
                this.triggerDownload(canvas, 'foto-rack-' + (kode === '-' ? 'unknown' : kode) + '.png');
            };
            img.src = gambar;
        } else {
            // No image, just draw text
            this.drawText(ctx, kepemilikan, kode, tanggal, rack, width, height);
            this.triggerDownload(canvas, 'foto-rack-' + (kode === '-' ? 'unknown' : kode) + '.png');
        }
    },
    loadFontIfNeeded: function() {
        // Check if Times New Roman is available, if not try to load it
        // Note: Times New Roman is usually a system font, but we check just in case
        if (document.fonts) {
            return document.fonts.ready.then(() => {
                // Fonts are loaded
            }).catch(() => {
                // If font loading fails, continue anyway - might still work if font is available
            });
        }
        // If document.fonts not available, continue anyway
        return Promise.resolve();
    },
    drawText: function(ctx, kepemilikan, kode, tanggal, rack, width, height) {
        // We'll draw three lines: first nama_opd, then kode_perangkat below it
        const headerCenterX = width / 2;
        const headerY = 30 + 40; // padding + baseline for first line

        // Nama OPD/kepemilikan (judul besar, bold) - ~36px
        const fontSizeNama = 36;
        ctx.font = `bold ${fontSizeNama}px 'Times New Roman'`;
        const textWidthNama = ctx.measureText(kepemilikan).width;
        const xNama = headerCenterX - (textWidthNama / 2);
        ctx.fillStyle = '#000000';
        ctx.fillText(kepemilikan, xNama, headerY);

        // Kode Perangkat (medium weight) - ~22px
        const fontSizeKode = 22;
        ctx.font = `500 ${fontSizeKode}px 'Times New Roman'`; // 500 is medium weight
        const kodeY = headerY + 40; // space between lines
        const textWidthKode = ctx.measureText(kode).width;
        const xKode = headerCenterX - (textWidthKode / 2);
        ctx.fillText(kode, xKode, kodeY);

        // Footer: Tanggal (left) and Nomor Rack (right)
        const footerY = height - 30 - 20;
        const leftX = 30;
        const rightX = width - 30;
        const fontSizeFooter = 18;
        ctx.font = `${fontSizeFooter}px 'Times New Roman'`;

        // Tanggal (left)
        const textWidthTanggal = ctx.measureText(tanggal).width;
        const xTanggal = leftX;
        ctx.fillText(tanggal, xTanggal, footerY);

        // Nomor Rack (right)
        const textWidthRack = ctx.measureText(rack).width;
        const xRack = rightX - textWidthRack;
        ctx.fillText(rack, xRack, footerY);
    },
    triggerDownload: function(canvas, filename) {
        canvas.toBlob((blob) => {
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }, 'image/png');
    }
}" class="flex flex-col">
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
                    <th class="p-4">Nomor</th>
                    <th class="p-4">Kode Perangkat</th>
                    <th class="p-4">QR</th>
                    <th class="p-4">Kepemilikan</th>
                    <th class="p-4">Tanggal</th>
                    <th class="p-4 text-center">Detail Dokumen</th>
                    <th class="p-4 text-center">Detail Photo</th>
                </tr>
            </thead>
            <tbody class="text-sm text-on-surface divide-y divide-outline-variant/60">
                @forelse ($servers as $server)
                <tr class="hover:bg-gray-50 transition-colors"
    data-kepemilikan="{{ $server->nama_opd }}"
    data-kode="{{ $server->kode_perangkat ?? $server->id }}"
    data-gambar="{{ Storage::url($server->gambar_rack) }}"
    data-tanggal="{{ $server->tanggal_from_kode ? $server->tanggal_from_kode->format('d M Y') : '-' }}"
    data-rack="{{ $server->nomor_rack ?? '-' }}">
                    <td class="p-4 text-gray-500 font-mono text-xs">{{ (($servers->currentPage() - 1) * $servers->perPage()) + $loop->iteration }}</td>
                    <td class="p-4 text-gray-500 font-mono text-xs">{{ $server->kode_perangkat }}</td>
                    <td class="p-4 text-center">
                        <img src="{{ route('qr.show', $server->id) }}" alt="QR Code" class="w-12 h-12">
                    </td>
                    <td class="p-4">{{ $server->nama_opd }}</td>
                    <td class="p-4 text-gray-500 font-mono text-xs">{{ $server->tanggal_from_kode ? $server->tanggal_from_kode->format('d M Y') : '-' }}</td>
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
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2 text-gray-500">
                            <!-- Preview button -->
                            <button type="button" @click="photo = {
                                kepemilikan: @js($server->nama_opd),
                                kode: @js($server->kode_perangkat),
                                gambar: @js(Storage::url($server->gambar_rack)),
                                tanggal: @js($server->tanggal_from_kode ? $server->tanggal_from_kode->format('d M Y') : '-'),
                                rack: @js($server->nomor_rack ?? '-')
                            }; showPhotoModal = true" class="hover:text-blue-600 transition-colors" title="Lihat Foto">
                                <span class="material-symbols-outlined text-[19px]">visibility</span>
                            </button>
                            <!-- Download button -->
                            <button type="button" @click="downloadFoto($event)" class="hover:text-green-600 transition-colors" title="Unduh Foto">
                                <span class="material-symbols-outlined text-[19px]">download</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-4 text-center text-gray-500">Tidak ada dokumen server.</td>
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
    <!-- Modal Preview Foto -->
    <div x-show="showPhotoModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-white/70" @keydown.escape.window="showPhotoModal = false; photo = {}" @click.self="showPhotoModal = false; photo = {}">
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
            <button type="button" @click="showPhotoModal = false; photo = {}" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center text-sm z-50" style="background-color: rgba(255,255,255,0.8); backdrop-filter: blur(2px); border: 1px solid rgba(0,0,0,0.1);">
                ×
            </button>
            <div class="flex flex-col items-center p-4 border-b border-outline-variant">
                <span x-text="photo.kepemilikan" class="mb-1 text-center" style="font: bold 36px 'Times New Roman'; color: #000000;"></span>
                <span x-text="photo.kode" class="text-center" style="font: 500 22px 'Times New Roman'; color: #000000;"></span>
            </div>
            <div class="p-4 flex items-center justify-center bg-gray-50">
                <template x-if="photo.gambar">
                        <img :src="photo.gambar" x-on:error="this.$el.src = '/storage/placeholder.png'" class="max-h-[400px] w-auto object-contain rounded-lg">
                    </template>
                    <template x-else>
                        <div class="flex h-[400px] w-full items-center justify-center bg-gray-200 text-gray-500 text-sm">
                            Foto tidak tersedia
                        </div>
                    </template>
            </div>
            <div class="flex items-center justify-between p-4 border-t border-outline-variant">
                <span x-text="photo.tanggal" style="font: 18px 'Times New Roman'; color: #000000;"></span>
                <span x-text="photo.rack" style="font: 18px 'Times New Roman'; color: #000000;"></span>
            </div>
        </div>
    </div>
</div>
@endsection
