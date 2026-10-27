@extends('layouts.app')

@section('title', 'Tambah Perangkat Baru - SIM TIK')

@push('styles')
<style>
    .field {
        width: 100%;
        background: #fff;
        border: 1px solid #cbd5e1;
        border-radius: 0.5rem;
        padding: 0.625rem 0.875rem;
        font-size: 14px;
        color: #0f172a;
        transition: border-color .15s, box-shadow .15s;
    }
    .field::placeholder { color: #94a3b8; }
    .field:focus { outline: none; border-color: #004ac6; box-shadow: 0 0 0 3px rgba(37, 99, 235, .2); }
    .field[readonly] { background: #f1f5f9; color: #475569; cursor: not-allowed; }
    .field:disabled { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; }
    .field.is-invalid { border-color: #ba1a1a; }
    .lbl { display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 0.375rem; }
    .opd-options { max-height: 15rem; overflow-y: auto; }
</style>
@endpush

@section('content')
@php
    $opd = old('opd') ?? '';
    $statusKepemilikan = ($opd === 'Kominfo') ? 'Kominfo' : 'Colocation';
    $dateString = old('tanggal_input') ?? now()->toDateString();
    $defaultKode = \App\Models\Server::generateKodePerangkat($statusKepemilikan, $dateString);
@endphp

<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-secondary mb-2">
        <a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a>
        <span>/</span>
        <span class="text-primary font-medium">Tambah Perangkat</span>
    </div>
    <h1 class="text-2xl font-bold text-on-surface">Tambah Perangkat Baru</h1>
    <p class="text-sm text-secondary mt-1">Daftarkan perangkat TIK untuk digunakan di lingkungan OPD.</p>
</div>

{{-- Banner info --}}
<div class="mb-6 flex items-start gap-3 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3">
    <span class="material-symbols-outlined text-primary mt-0.5">info</span>
    <p class="text-sm text-blue-900">Data akan berstatus <strong>PENDING</strong> hingga dilengkapi oleh admin.</p>
</div>

{{-- Ringkasan error validasi --}}
@if ($errors->any())
    <div class="mb-6 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3">
        <span class="material-symbols-outlined text-red-600 mt-0.5">error</span>
        <div class="text-sm text-red-900">
            <p class="font-semibold mb-1">Periksa kembali data yang diisi:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- Card formulir --}}
<div class="bg-white border border-outline-variant rounded-xl shadow-sm overflow-hidden">
    <form id="deviceForm" action="{{ route('inputdatauser.store') }}" method="POST">
        @csrf

        <div class="p-6 sm:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                {{-- Baris 1 - Kode Perangkat --}}
                <div class="md:col-span-2">
                    <label class="lbl" for="kode_perangkat">Kode Perangkat <span class="text-red-600">*</span></label>
                    <input type="text" id="kode_perangkat" name="kode_perangkat" readonly
                           class="field @error('kode_perangkat') is-invalid @enderror"
                           value="{{ old('kode_perangkat', $defaultKode) }}"
                           placeholder="Kode perangkat akan dibuat otomatis mengikuti format sistem">
                    <p class="mt-1.5 text-xs text-secondary">Kode perangkat akan dibuat otomatis setelah submit berdasarkan tanggal input dan pilihan OPD.</p>
                    @error('kode_perangkat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Baris 2 kiri - Nama Pengirim --}}
                <div>
                    <label class="lbl" for="nama_pengirim">Nama Pengirim <span class="text-red-600">*</span></label>
                    <input type="text" id="nama_pengirim" name="nama_pengirim"
                           class="field @error('nama_pengirim') is-invalid @enderror"
                           value="{{ old('nama_pengirim') }}" placeholder="Masukkan nama pengirim"
                           required>
                    @error('nama_pengirim') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Baris 2 kanan - Nama Penerima --}}
                <div>
                    <label class="lbl" for="nama_penerima">Nama Penerima (diisi admin)</label>
                    <input type="text" id="nama_penerima" class="field" value=""
                           placeholder="Akan diisi oleh admin" disabled>
                </div>

                {{-- Baris 3 - Nama Dinas/OPD --}}
                <div class="md:col-span-2">
                    <label class="lbl" for="opd">Nama Dinas/OPD <span class="text-red-600">*</span></label>
                    <div class="relative" id="opdDropdown">
                        <input type="hidden" id="opd" name="opd" value="{{ old('opd') }}">
                        <button type="button" id="opdTrigger"
                                class="field flex items-center justify-between text-left @error('opd') is-invalid @enderror"
                                aria-haspopup="listbox" aria-expanded="false" aria-controls="opdMenu">
                            <span id="opdSelectedLabel" class="{{ old('opd') ? 'text-slate-900' : 'text-slate-400' }}">
                                {{ old('opd') ? ($opdList[old('opd')] ?? old('opd')) : 'Pilih Dinas/OPD' }}
                            </span>
                            <span class="material-symbols-outlined text-[18px] text-secondary">expand_more</span>
                        </button>
                        <div id="opdMenu" class="hidden absolute left-0 right-0 top-full z-30 mt-1 overflow-hidden rounded-lg border border-outline-variant bg-white shadow-lg">
                            <div class="border-b border-outline-variant bg-white p-2">
                                <input type="search" id="opdSearch" class="field" placeholder="Cari Dinas/OPD..." autocomplete="off">
                            </div>
                            <div id="opdOptions" class="opd-options py-1" role="listbox" aria-label="Pilihan Dinas/OPD">
                                @foreach($opdList as $value => $label)
                                    <button type="button" class="opd-option w-full px-3 py-2 text-left text-sm text-slate-700 hover:bg-blue-50 focus:bg-blue-50 focus:outline-none"
                                            data-value="{{ $value }}" data-label="{{ $label }}" role="option">
                                        {{ $label }}
                                    </button>
                                @endforeach
                                <p id="opdNoResults" class="hidden px-3 py-2 text-sm text-secondary">OPD tidak ditemukan.</p>
                            </div>
                        </div>
                    </div>
                    @error('opd') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    <p id="opdRequiredMessage" class="hidden mt-1 text-xs text-red-600">Nama Dinas/OPD wajib dipilih.</p>
                </div>

                {{-- Baris 4 - Nama Perangkat --}}
                <div class="md:col-span-2">
                    <label class="lbl" for="nama_perangkat">Nama Perangkat <span class="text-red-600">*</span></label>
                    <input type="text" id="nama_perangkat" name="nama_perangkat"
                           class="field @error('nama_perangkat') is-invalid @enderror"
                           value="{{ old('nama_perangkat') }}" placeholder="Masukkan nama perangkat" required>
                    @error('nama_perangkat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Baris 5 - Jenis Perangkat --}}
                <div class="md:col-span-2">
                    <label class="lbl" for="jenis_perangkat">Jenis Perangkat <span class="text-red-600">*</span></label>
                    <div id="jenis_wrapper" class="space-y-3">
                        <select id="jenis_perangkat" name="jenis_perangkat"
                                class="field @error('jenis_perangkat') is-invalid @enderror" required>
                            <option value="" disabled selected>Pilih jenis perangkat</option>
                            @foreach($jenisList as $value => $label)
                                <option value="{{ $value }}" {{ old('jenis_perangkat') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                            <option value="lainnya" {{ old('jenis_perangkat') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>

                        <div id="jenis-lainnya_wrapper" class="hidden">
                            <label class="lbl" for="jenis_lainnya">Jenis Perangkat (Lainnya) <span class="text-red-600">*</span></label>
                            <input type="text" id="jenis_lainnya" name="jenis_lainnya" class="field"
                                   value="{{ old('jenis_lainnya') }}" placeholder="Masukkan jenis perangkat lain">
                        </div>
                    </div>
                    @error('jenis_perangkat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    @error('jenis_lainnya') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Baris 6 - Merk Perangkat --}}
                <div class="md:col-span-2">
                    <label class="lbl" for="merk_perangkat">Merk Perangkat <span class="text-red-600">*</span></label>
                    <div id="merk_wrapper" class="space-y-3">
                        <select id="merk_perangkat" name="merk_perangkat"
                                class="field @error('merk_perangkat') is-invalid @enderror" required>
                            <option value="" disabled selected>Pilih merk perangkat</option>
                            @foreach($merkList as $value => $label)
                                <option value="{{ $value }}" {{ old('merk_perangkat') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                            <option value="lainnya" {{ old('merk_perangkat') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>

                        <div id="merk-lainnya_wrapper" class="hidden">
                            <label class="lbl" for="merk_lainnya">Merk Perangkat (Lainnya) <span class="text-red-600">*</span></label>
                            <input type="text" id="merk_lainnya" name="merk_lainnya" class="field"
                                   value="{{ old('merk_lainnya') }}" placeholder="Masukkan merk perangkat lain">
                        </div>
                    </div>
                    @error('merk_perangkat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    @error('merk_lainnya') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Baris 7 - Tanggal Input --}}
                <div class="md:col-span-2">
                    <label class="lbl" for="tanggal_input">Tanggal Input <span class="text-red-600">*</span></label>
                    <input type="date" id="tanggal_input" name="tanggal_input"
                           class="field @error('tanggal_input') is-invalid @enderror"
                           value="{{ old('tanggal_input', now()->toDateString()) }}" required>
                    @error('tanggal_input') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Aksi --}}
        <div class="px-6 sm:px-8 py-4 border-t border-outline-variant bg-surface-container-low flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
            <a href="{{ route('user.dashboarduser') }}"
               class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg border border-outline-variant bg-white text-sm font-medium text-secondary hover:bg-surface-container-low focus:outline-none transition-colors">
                Batal
            </a>
            <button type="submit" id="submitBtn"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-primary text-sm font-medium text-white shadow-sm hover:bg-primary-container focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-70 disabled:cursor-not-allowed transition-colors">
                <span id="submitIcon" class="material-symbols-outlined text-[18px]">save</span>
                <svg id="submitSpinner" class="hidden animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle>
                    <path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4" stroke-linecap="round" class="opacity-75"></path>
                </svg>
                <span id="submitLabel">Simpan &amp; Daftarkan</span>
            </button>
        </div>
    </form>
</div>

{{-- Kembali ke Dashboard --}}
<div class="mt-5 text-left">
    <a href="{{ route('user.dashboarduser') }}"
       class="inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:underline">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
        Kembali ke Dashboard
    </a>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ---- Field "Lainnya" (jenis & merk) ----
        const jenisSelect = document.getElementById('jenis_perangkat');
        const jenisLainnyaWrapper = document.getElementById('jenis-lainnya_wrapper');
        const merkSelect = document.getElementById('merk_perangkat');
        const merkLainnyaWrapper = document.getElementById('merk-lainnya_wrapper');

        function toggleLainnya(select, wrapper) {
            if (!select || !wrapper) return;
            const input = wrapper.querySelector('input');
            if (select.value === 'lainnya') {
                wrapper.classList.remove('hidden');
                if (input) input.required = true;
            } else {
                wrapper.classList.add('hidden');
                if (input) {
                    input.required = false;
                    input.value = '';
                }
            }
        }
        toggleLainnya(jenisSelect, jenisLainnyaWrapper);
        toggleLainnya(merkSelect, merkLainnyaWrapper);
        if (jenisSelect) jenisSelect.addEventListener('change', () => toggleLainnya(jenisSelect, jenisLainnyaWrapper));
        if (merkSelect) merkSelect.addEventListener('change', () => toggleLainnya(merkSelect, merkLainnyaWrapper));

        // ---- Preview kode perangkat ----
        const opdSelect = document.getElementById('opd');
        const opdDropdown = document.getElementById('opdDropdown');
        const opdTrigger = document.getElementById('opdTrigger');
        const opdSelectedLabel = document.getElementById('opdSelectedLabel');
        const opdMenu = document.getElementById('opdMenu');
        const opdSearch = document.getElementById('opdSearch');
        const opdOptions = Array.from(document.querySelectorAll('.opd-option'));
        const opdNoResults = document.getElementById('opdNoResults');
        const opdRequiredMessage = document.getElementById('opdRequiredMessage');
        const tanggalInput = document.getElementById('tanggal_input');
        const kodePerangkat = document.getElementById('kode_perangkat');

        function closeOpdMenu() {
            if (!opdMenu || !opdTrigger) return;
            opdMenu.classList.add('hidden');
            opdTrigger.setAttribute('aria-expanded', 'false');
        }

        function openOpdMenu() {
            if (!opdMenu || !opdTrigger || !opdSearch) return;
            opdMenu.classList.remove('hidden');
            opdTrigger.setAttribute('aria-expanded', 'true');
            opdSearch.value = '';
            filterOpdOptions();
            opdSearch.focus();
        }

        function filterOpdOptions() {
            const searchTerm = opdSearch.value.trim().toLocaleLowerCase('id');
            let visibleCount = 0;
            opdOptions.forEach(function (option) {
                const matches = option.dataset.label.toLocaleLowerCase('id').includes(searchTerm);
                option.classList.toggle('hidden', !matches);
                if (matches) visibleCount++;
            });
            opdNoResults.classList.toggle('hidden', visibleCount > 0);
        }

        if (opdTrigger) {
            opdTrigger.addEventListener('click', function () {
                if (opdMenu.classList.contains('hidden')) {
                    openOpdMenu();
                } else {
                    closeOpdMenu();
                }
            });
        }
        if (opdSearch) opdSearch.addEventListener('input', filterOpdOptions);
        opdOptions.forEach(function (option) {
            option.addEventListener('click', function () {
                opdSelect.value = option.dataset.value;
                opdSelectedLabel.textContent = option.dataset.label;
                opdSelectedLabel.classList.remove('text-slate-400');
                opdSelectedLabel.classList.add('text-slate-900');
                opdTrigger.classList.remove('is-invalid');
                opdRequiredMessage.classList.add('hidden');
                closeOpdMenu();
                opdSelect.dispatchEvent(new Event('input', { bubbles: true }));
                opdSelect.dispatchEvent(new Event('change', { bubbles: true }));
            });
        });
        document.addEventListener('click', function (event) {
            if (opdDropdown && !opdDropdown.contains(event.target)) closeOpdMenu();
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && opdMenu && !opdMenu.classList.contains('hidden')) {
                closeOpdMenu();
                opdTrigger.focus();
            }
        });

        function fallbackKode(prefix, dateValue) {
            let datePart;
            if (dateValue) {
                datePart = dateValue.replace(/-/g, '').slice(2);
            } else {
                const t = new Date();
                datePart = String(t.getFullYear()).slice(-2)
                    + String(t.getMonth() + 1).padStart(2, '0')
                    + String(t.getDate()).padStart(2, '0');
            }
            return prefix + datePart + 'A';
        }

        let kodeRequestId = 0;
        async function updateKodePerangkatPreview() {
            if (!opdSelect || !tanggalInput || !kodePerangkat) return;
            const statusKepemilikan = opdSelect.value === 'Kominfo' ? 'Kominfo' : 'Colocation';
            const prefix = statusKepemilikan === 'Kominfo' ? 'KO' : 'CO';
            const dateParam = tanggalInput.value || '';
            const myId = ++kodeRequestId;
            try {
                const response = await fetch('/server/next-code?status_kepemilikan=' + encodeURIComponent(statusKepemilikan)
                    + (dateParam ? '&date=' + encodeURIComponent(dateParam) : ''));
                const data = await response.json();
                if (myId !== kodeRequestId) return;
                kodePerangkat.value = data.kode_perangkat ? data.kode_perangkat : fallbackKode(prefix, dateParam);
            } catch (e) {
                console.error(e);
                if (myId !== kodeRequestId) return;
                kodePerangkat.value = fallbackKode(prefix, dateParam);
            }
        }
        updateKodePerangkatPreview();
        if (opdSelect) opdSelect.addEventListener('change', updateKodePerangkatPreview);
        if (opdSelect) opdSelect.addEventListener('input', updateKodePerangkatPreview);
        if (tanggalInput) tanggalInput.addEventListener('change', updateKodePerangkatPreview);

        // ---- Cegah submit ganda + indikator loading ----
        const form = document.getElementById('deviceForm');
        const btn = document.getElementById('submitBtn');
        if (form && btn) {
            form.addEventListener('submit', function (event) {
                if (!opdSelect.value) {
                    event.preventDefault();
                    opdTrigger.classList.add('is-invalid');
                    opdRequiredMessage.classList.remove('hidden');
                    opdTrigger.focus();
                    return;
                }
                if (btn.dataset.busy === '1') return;
                btn.dataset.busy = '1';
                setTimeout(function () {
                    btn.disabled = true;
                    const icon = document.getElementById('submitIcon');
                    const spinner = document.getElementById('submitSpinner');
                    const label = document.getElementById('submitLabel');
                    if (icon) icon.classList.add('hidden');
                    if (spinner) spinner.classList.remove('hidden');
                    if (label) label.textContent = 'Menyimpan...';
                }, 0);
            });
            form.addEventListener('submit', function (e) {
                if (btn.dataset.busy === '1' && btn.disabled) e.preventDefault();
            }, true);
            window.addEventListener('pageshow', function (e) {
                if (e.persisted) {
                    btn.disabled = false;
                    btn.dataset.busy = '0';
                    const icon = document.getElementById('submitIcon');
                    const spinner = document.getElementById('submitSpinner');
                    const label = document.getElementById('submitLabel');
                    if (icon) icon.classList.remove('hidden');
                    if (spinner) spinner.classList.add('hidden');
                    if (label) label.textContent = 'Simpan & Daftarkan';
                }
            });
        }
    });
</script>
@endpush
