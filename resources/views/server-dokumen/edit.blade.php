@extends('layouts.app')

@section('title', 'Edit Dokumen - ' . $server->nama_perangkat)

@push('styles')
<style>
    /*
     * Halaman Edit Dokumen = ruang kerja setinggi layar.
     * - Halaman utama tidak ikut scroll; panel form (kiri) dan preview (kanan) punya scroll sendiri.
     * - Dua panel selalu 50 : 50 dan tinggi mengikuti layar (satuan viewport + grid tanpa breakpoint),
     *   jadi saat zoom in/out di browser susunannya tidak berubah/ber-reflow ke satu kolom.
     */
    body:has(#editDokumenRoot) { height: 100vh; overflow: hidden; }
    body:has(#editDokumenRoot) footer { display: none; }
    body:has(#editDokumenRoot) main {
        display: flex;
        flex-direction: column;
        min-height: 0;
        max-width: none;            /* lebar penuh, bukan dibatasi 1400px */
        overflow: hidden;
        padding-top: 1.25rem;
        padding-bottom: 1.25rem;
    }

    .edit-dok { flex: 1 1 0; min-height: 0; display: flex; flex-direction: column; }
    .edit-dok__head { flex: none; }
    .edit-dok__alert { flex: none; margin-top: .75rem; }

    .edit-dok__split {
        flex: 1 1 0;
        min-height: 0;
        margin-top: 1rem;
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);   /* lebar form = lebar preview */
        gap: 1.5rem;
    }
    .edit-dok__pane { min-width: 0; min-height: 0; }

    /* Panel kiri: form, scroll sendiri */
    .edit-dok__form {
        overflow-y: auto;
        overscroll-behavior: contain;
        scrollbar-gutter: stable;
        padding-right: .5rem;
    }
    /* Tombol Simpan tetap terlihat di dasar panel form */
    .edit-dok__actions {
        position: sticky;
        bottom: 0;
        z-index: 5;
        padding: .75rem 0;
        background: #f7f9fb;
        border-top: 1px solid #dde0e8;
    }

    /* Panel kanan: preview mengisi sisa tinggi */
    .edit-dok__preview { display: flex; flex-direction: column; gap: .75rem; }
    .edit-dok__preview iframe { flex: 1 1 0; min-height: 0; width: 100%; }
</style>
@endpush

@section('content')
<div id="editDokumenRoot" class="edit-dok">

    <div class="edit-dok__head">
        <a href="{{ route('server.dokumen.index') }}" class="text-sm text-secondary hover:text-primary inline-flex items-center gap-1">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali ke Manajemen Dokumen
        </a>
        <h1 class="text-2xl font-bold text-on-surface mt-2">Edit Dokumen Server</h1>
        <p class="text-secondary text-sm mt-1">{{ $server->kode_perangkat }} &middot; {{ $server->nama_perangkat }}</p>
    </div>

    @if (session('success'))
        <div class="edit-dok__alert rounded-lg border border-green-200 bg-green-50 text-green-800 text-sm px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="edit-dok__alert rounded-lg border border-red-200 bg-red-50 text-red-800 text-sm px-4 py-3">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="edit-dok__split">

        {{-- ================= FORM (scroll sendiri) ================= --}}
        <form id="formDokumen" method="POST" action="{{ route('server.dokumen.update', $server) }}" class="edit-dok__pane edit-dok__form space-y-6">
            @csrf
            @method('PUT')

            {{-- KOP --}}
            <section class="bg-white rounded-xl border border-outline-variant shadow-sm p-5 space-y-3">
                <h2 class="font-semibold text-on-surface">Kop Surat</h2>
                <textarea id="kop_html" name="kop_html">{{ old('kop_html', $dokumen->kop_html) }}</textarea>
                <p class="text-xs text-gray-500">Logo dipasang otomatis di kiri. Garis bawah kop selalu ditambahkan.</p>

                <label class="flex items-center gap-2 text-sm text-secondary">
                    <input type="checkbox" name="jadikan_bawaan" value="1" class="rounded border-outline-variant text-primary focus:ring-primary">
                    Jadikan kop ini sebagai bawaan untuk dokumen baru
                </label>
            </section>

            {{-- SURAT PENGANTAR --}}
            <section class="bg-white rounded-xl border border-outline-variant shadow-sm p-5 space-y-3">
                <h2 class="font-semibold text-on-surface">Surat Pengantar (Halaman 1)</h2>
                <textarea id="isi_surat" name="isi_surat">{{ old('isi_surat', $dokumen->isi_surat) }}</textarea>
            </section>

            {{-- LAMPIRAN --}}
            <section class="bg-white rounded-xl border border-outline-variant shadow-sm p-5 space-y-4">
                <h2 class="font-semibold text-on-surface">Lampiran (Halaman 2)</h2>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-secondary" for="lampiran_label">Lampiran</label>
                    <input type="text" id="lampiran_label" name="lampiran_label" maxlength="100" placeholder="Contoh: Lampiran I"
                           value="{{ old('lampiran_label', $dokumen->lampiran_label) }}"
                           class="w-full border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <p class="text-xs text-gray-500">Kosongkan: di PDF tercetak titik-titik untuk diisi manual.</p>
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-secondary" for="lampiran_nomor">Surat Nomor</label>
                    <input type="text" id="lampiran_nomor" name="lampiran_nomor" maxlength="150" placeholder="Contoh: 500.12.6/88/431.313/2026"
                           value="{{ old('lampiran_nomor', $dokumen->lampiran_nomor) }}"
                           class="w-full border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <p class="text-xs text-gray-500">Kosongkan: di PDF tercetak titik-titik untuk diisi manual.</p>
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-secondary" for="lampiran_tanggal">Tanggal</label>
                    <input type="text" id="lampiran_tanggal" name="lampiran_tanggal" maxlength="100" placeholder="Contoh: 24 September 2026"
                           value="{{ old('lampiran_tanggal', $dokumen->lampiran_tanggal) }}"
                           class="w-full border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <p class="text-xs text-gray-500">Kosongkan: di PDF tercetak titik-titik untuk diisi manual.</p>
                </div>
            </section>

            {{-- TANDA TANGAN --}}
            <section class="bg-white rounded-xl border border-outline-variant shadow-sm p-5 space-y-5">
                <h2 class="font-semibold text-on-surface">Tanda Tangan (Halaman 2)</h2>

                @foreach (['kiri' => 'Tanda tangan kiri', 'kanan' => 'Tanda tangan kanan'] as $sisi => $label)
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-secondary" for="ttd_{{ $sisi }}_master_id">{{ $label }}</label>

                        <select id="ttd_{{ $sisi }}_master_id" name="ttd_{{ $sisi }}_master_id" data-judul="ttd_{{ $sisi }}_judul"
                                class="select-pejabat w-full border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                            <option value="">&mdash; Kosongkan &mdash;</option>
                            @foreach ($pejabatList as $p)
                                <option value="{{ $p->id }}" data-jabatan="{{ $p->jabatanTtd() }}" data-qr="{{ filled($p->qrcode_path) ? '1' : '0' }}"
                                    @selected((string) old("ttd_{$sisi}_master_id", $dokumen->{"ttd_{$sisi}_master_id"}) === (string) $p->id)>
                                    {{ $p->label }} &mdash; {{ $p->value }}
                                </option>
                            @endforeach
                        </select>
                        <p id="ttd_{{ $sisi }}_qr_info" class="text-xs"></p>

                        <label class="block text-xs text-gray-500" for="ttd_{{ $sisi }}_judul">Teks di atas tanda tangan</label>
                        <textarea id="ttd_{{ $sisi }}_judul" name="ttd_{{ $sisi }}_judul" rows="3" maxlength="500"
                                  class="w-full border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary">{{ old("ttd_{$sisi}_judul", $dokumen->{"ttd_{$sisi}_judul"}) }}</textarea>
                    </div>
                @endforeach

                <p class="text-xs text-gray-500">Nama, pangkat, NIP, dan QR Code diambil otomatis dari Master Data (kategori Pejabat).</p>
            </section>

            <div class="edit-dok__actions flex items-center gap-3">
                <button type="submit" class="inline-flex items-center gap-2 bg-primary text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-primary-container transition-colors">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan
                </button>
                <a href="{{ route('server.dokumen.index') }}" class="text-sm text-secondary hover:text-on-surface">Batal</a>
            </div>
        </form>

        {{-- ================= PREVIEW PDF (tetap di tempat) ================= --}}
        <div class="edit-dok__pane edit-dok__preview">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-on-surface">Preview PDF</h2>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="perbaruiPreview()" class="inline-flex items-center gap-1 border border-outline-variant bg-white text-sm px-3 py-2 rounded-lg hover:bg-gray-50">
                        <span class="material-symbols-outlined text-[18px]">refresh</span>
                        Perbarui preview
                    </button>
                    <a href="{{ route('server.dokumen.download', $server) }}" class="inline-flex items-center gap-1 border border-outline-variant bg-white text-sm px-3 py-2 rounded-lg hover:bg-gray-50" title="Mengunduh versi yang sudah disimpan">
                        <span class="material-symbols-outlined text-[18px]">download</span>
                        Unduh PDF
                    </a>
                </div>
            </div>
            <iframe name="previewFrame" id="previewFrame" class="rounded-lg border border-outline-variant bg-gray-100"></iframe>
            <p class="text-xs text-gray-500">Preview memakai isi form saat ini (belum perlu disimpan). Tombol Unduh PDF memakai versi yang sudah disimpan.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.4/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    const PREVIEW_URL = @js(route('server.dokumen.preview-draft', $server));

    function perbaruiPreview() {
        if (window.tinymce) { tinymce.triggerSave(); }

        const form   = document.getElementById('formDokumen');
        const method = form.querySelector('input[name="_method"]');
        const asli   = { action: form.action, target: form.target };

        form.action     = PREVIEW_URL;
        form.target     = 'previewFrame';
        method.disabled = true;      // route preview memakai POST
        form.submit();

        form.action     = asli.action;
        form.target     = asli.target;
        method.disabled = false;
    }

    const pengaturanDasar = {
        menubar: false,
        statusbar: false,
        branding: false,
        promotion: false,
        entity_encoding: 'raw',
        plugins: 'lists table',
        font_family_formats: 'Times New Roman=times new roman,times,serif; Arial=arial,helvetica,sans-serif',
        font_size_formats: '10pt 11pt 12pt 14pt 16pt',
        line_height_formats: '1 1.15 1.3 1.5 2',
    };

    // ---------- Spasi sebelum / sesudah paragraf (seperti menu Line Spacing di Word) ----------
    const SPASI_PARAGRAF = '12pt';                         // nilai yang ditambahkan, sama seperti Word
    const BLOK_SPASI     = ['P', 'H1', 'H2', 'H3', 'H4', 'DIV', 'LI'];

    const IKON_SPASI_SEBELUM = '<svg width="24" height="24" viewBox="0 0 24 24"><path d="M11 2h2v6.2l2.6-2.6L17 7l-5 5-5-5 1.4-1.4L11 8.2z"/><path d="M5 14h14v1.5H5zM5 17.5h14V19H5zM5 21h14v1.5H5z"/></svg>';
    const IKON_SPASI_SESUDAH = '<svg width="24" height="24" viewBox="0 0 24 24"><path d="M5 2h14v1.5H5zM5 5.5h14V7H5zM5 9h14v1.5H5z"/><path d="M11 13h2v5.2l2.6-2.6L17 17l-5 5-5-5 1.4-1.4 2.6 2.6z"/></svg>';

    function blokTerpilih(editor) {
        return editor.selection.getSelectedBlocks().filter(function (b) {
            return BLOK_SPASI.indexOf(b.nodeName) !== -1;
        });
    }

    // true bila paragraf punya spasi (dari style inline maupun CSS editor) di sisi tsb
    function adaSpasi(editor, blok, sisi) {
        return parseFloat(editor.dom.getStyle(blok, 'margin-' + sisi, true)) > 0;
    }

    function tambahTombolSpasi(editor) {
        editor.ui.registry.addIcon('spasi-sebelum', IKON_SPASI_SEBELUM);
        editor.ui.registry.addIcon('spasi-sesudah', IKON_SPASI_SESUDAH);

        function daftarkan(nama, ikon, sisi, tooltip) {
            editor.ui.registry.addToggleButton(nama, {
                icon: ikon,
                tooltip: tooltip,
                onAction: function () {
                    const blok = blokTerpilih(editor);
                    if (!blok.length) { return; }

                    // Semua paragraf terpilih sudah punya spasi -> hapus (jadi 0); selain itu -> tambahkan
                    const semuaAda = blok.every(function (b) { return adaSpasi(editor, b, sisi); });

                    editor.undoManager.transact(function () {
                        blok.forEach(function (b) {
                            editor.dom.setStyle(b, 'margin-' + sisi, semuaAda ? '0' : SPASI_PARAGRAF);
                        });
                    });
                    editor.nodeChanged();
                },
                onSetup: function (api) {
                    const perbarui = function () {
                        const blok = blokTerpilih(editor);
                        api.setActive(blok.length > 0 && blok.every(function (b) { return adaSpasi(editor, b, sisi); }));
                    };
                    editor.on('NodeChange', perbarui);
                    return function () { editor.off('NodeChange', perbarui); };
                }
            });
        }

        daftarkan('spasisebelum', 'spasi-sebelum', 'top',    'Tambah / hapus spasi sebelum paragraf (12pt)');
        daftarkan('spasisesudah', 'spasi-sesudah', 'bottom', 'Tambah / hapus spasi sesudah paragraf (12pt)');
    }

    // Editor kop surat
    tinymce.init({
        ...pengaturanDasar,
        selector: '#kop_html',
        height: 300,
        toolbar: 'undo redo | fontfamily fontsize lineheight spasisebelum spasisesudah | bold italic underline | alignleft aligncenter alignright | outdent indent | removeformat',
        setup: tambahTombolSpasi,
        content_style: `
            body { font-family: Arial, Helvetica, sans-serif; font-size: 10pt; line-height: 1.15;
                   max-width: 16.59cm; margin: 12px auto; padding: 0 6px; }
            p { margin: 0; }
        `,
    });

    // Editor surat pengantar (halaman 1)
    tinymce.init({
        ...pengaturanDasar,
        selector: '#isi_surat',
        height: 560,
        toolbar: 'undo redo | fontfamily fontsize lineheight spasisebelum spasisesudah | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table | removeformat',
        content_style: `
            body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5;
                   max-width: 16.59cm; margin: 12px auto; padding: 0 6px; }
            p { margin: 0 0 12pt 0; }
            ul, ol { margin: 0 0 12pt 0; padding-left: 1.25cm; }
            table { border-collapse: collapse; }
            td, th { padding: 1pt 2pt; vertical-align: top; }
        `,
        setup: function (editor) {
            tambahTombolSpasi(editor);
            editor.on('init', perbaruiPreview);
        }
    });

    // Info ketersediaan QR Code untuk pejabat yang dipilih
    function infoQr(sel) {
        const el  = document.getElementById(sel.id.replace('_master_id', '_qr_info'));
        const opt = sel.selectedOptions[0];
        if (!el) { return; }

        if (!sel.value || !opt) {
            el.textContent = '';
            return;
        }

        if (opt.dataset.qr === '1') {
            el.textContent = 'QR Code tanda tangan pejabat ini akan dicetak di PDF.';
            el.className   = 'text-xs text-green-800';
        } else {
            el.textContent = 'Pejabat ini belum punya QR Code (unggah di Master Data > Pejabat). PDF memberi ruang kosong untuk tanda tangan.';
            el.className   = 'text-xs text-red-500';
        }
    }

    // Saat pejabat diganti, isi teks di atas tanda tangan mengikuti jabatannya
    // (awalan "Mengetahui," dipertahankan bila sudah ada).
    document.querySelectorAll('.select-pejabat').forEach(function (sel) {
        infoQr(sel);
        sel.addEventListener('change', function () {
            const judul   = document.getElementById(sel.dataset.judul);
            const jabatan = sel.selectedOptions[0] ? (sel.selectedOptions[0].dataset.jabatan || '') : '';
            const baris   = judul.value.split('\n');
            const awalan  = /^mengetahui,?$/i.test((baris[0] || '').trim()) ? baris[0].trim() + '\n' : '';
            judul.value   = jabatan ? awalan + jabatan : '';
            infoQr(sel);
        });
    });
</script>
@endpush