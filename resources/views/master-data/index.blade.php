@extends('layouts.app')

@section('content')
    @php
        $kategoriList = App\Models\MasterData::KATEGORI;
        $kategoriAktifKey = request()->query('kategori', array_key_first($kategoriList));
        $kategoriAktifLabel = $kategoriList[$kategoriAktifKey] ?? reset($kategoriList);
    @endphp

    <div class="mb-6">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold text-on-surface">Data Master</h1>
                <p class="text-sm text-on-surface-variant mt-1">Kelola nilai referensi yang dipakai di seluruh sistem.</p>
            </div>
            <button type="button"
                    id="tambahDataBtn"
                    class="bg-primary-container hover:bg-primary/90 text-on-primary-container font-medium px-5 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                Tambah Data
                <span class="material-symbols-outlined">add</span>
            </button>
        </div>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="mb-6 rounded-lg border-l-4 border-primary/50 bg-primary/5 px-4 py-3 flex items-start space-x-3">
            <span class="material-symbols-outlined text-primary">check_circle</span>
            <div class="text-on-surface">{{ session('success') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-lg border-l-4 border-red-500/50 bg-red-50/5 px-4 py-3 flex items-start space-x-3">
            <span class="material-symbols-outlined text-red-500">error</span>
            <div>
                <ul class="list-disc list-inset text-on-surface-variant space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="flex flex-col md:flex-row gap-6 items-start">

        <!-- Category nav: mobile = horizontal pills -->
        <div class="md:hidden w-full overflow-x-auto -mx-1 px-1">
            <div class="flex gap-2 pb-1 whitespace-nowrap">
                @foreach ($kategoriList as $kategoriKey => $kategoriLabel)
                    <a href="{{ route('master-data.index', ['kategori' => $kategoriKey]) }}"
                       class="shrink-0 px-4 py-2 rounded-full text-sm font-medium border transition-colors duration-150
                              {{ $kategoriKey == $kategoriAktifKey
                                    ? 'bg-primary-container border-primary-container text-on-primary-container'
                                    : 'bg-surface-container-lowest border-outline-variant text-on-surface-variant hover:bg-primary/5' }}">
                        {{ $kategoriLabel }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Category nav: desktop = sidebar list -->
        <aside class="hidden md:block w-64 shrink-0">
            <div class="rounded-lg border border-outline-variant bg-surface-container-lowest overflow-hidden sticky top-4">
                <div class="px-4 py-3 border-b border-outline-variant">
                    <span class="text-xs font-medium text-on-surface-variant">Kategori</span>
                </div>
                <nav class="max-h-[70vh] overflow-y-auto">
                    @foreach ($kategoriList as $kategoriKey => $kategoriLabel)
                        @php $isActive = $kategoriKey == $kategoriAktifKey; @endphp
                        <a href="{{ route('master-data.index', ['kategori' => $kategoriKey]) }}"
                           class="flex items-center justify-between gap-2 px-4 py-3 text-sm border-l-2 transition-colors duration-150
                                  {{ $isActive
                                        ? 'border-primary bg-primary/5 text-primary font-medium'
                                        : 'border-transparent text-on-surface-variant hover:bg-primary/5 hover:text-on-surface' }}">
                            <span>{{ $kategoriLabel }}</span>
                            @if ($isActive)
                                <span class="material-symbols-outlined text-base">chevron_right</span>
                            @endif
                        </a>
                    @endforeach
                </nav>
            </div>
        </aside>

        <!-- Table -->
        <div class="flex-1 w-full min-w-0">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-medium text-on-surface-variant">
                    Menampilkan: <span class="text-on-surface font-semibold">{{ $kategoriAktifLabel }}</span>
                </h2>
                <span class="text-xs text-on-surface-variant">{{ $items->count() }} data</span>
            </div>

            <div class="rounded-lg border border-outline-variant overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-[640px] w-full divide-y divide-outline-variant">
                        @php $isPejabat = $kategoriAktifKey === 'pejabat'; @endphp
                        <thead class="bg-surface-container">
                            <tr>
                                @if($isPejabat)
                                    <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant tracking-wide w-20">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant tracking-wide">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant tracking-wide">Jabatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant tracking-wide">NIP</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant tracking-wide">Pangkat / Golongan</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-on-surface-variant tracking-wide">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-on-surface-variant tracking-wide w-28">Aksi</th>
                                @else
                                    <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant tracking-wide">Urutan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant tracking-wide">Value</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant tracking-wide">Label</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-on-surface-variant tracking-wide">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-on-surface-variant tracking-wide w-28">Aksi</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody class="bg-surface-container-lowest divide-y divide-outline-variant">
                            @if ($items->isEmpty())
                                <tr>
                                    @php $isPejabat = $kategoriAktifKey === 'pejabat'; @endphp
                                    <td colspan="{{ $isPejabat ? 7 : 5 }}" class="px-6 py-16 text-center">
                                        <span class="material-symbols-outlined text-3xl text-on-surface-variant/50 block mb-2">inbox</span>
                                        <p class="text-on-surface-variant text-sm">Belum ada data untuk kategori "{{ $kategoriAktifLabel }}".</p>
                                        <p class="text-on-surface-variant text-xs mt-1">Klik "Tambah Data" untuk menambahkan entri pertama.</p>
                                    </td>
                                </tr>
                            @else
                                @foreach ($items as $item)
                                    <tr class="hover:bg-primary/5 transition-colors duration-150">
                                        @php $isPejabat = $kategoriAktifKey === 'pejabat'; @endphp
                                        @if($isPejabat)
                                            <td class="px-6 py-4 text-on-surface-variant">{{ $loop->iteration }}</td>
                                            <td class="px-6 py-4 text-on-surface">{{ $item->label }}</td> <!-- Nama -->
                                            <td class="px-6 py-4 text-on-surface">{{ $item->value }}</td> <!-- Jabatan -->
                                            <td class="px-6 py-4 text-on-surface">{{ $item->nip ?? '' }}</td>
                                            <td class="px-6 py-4 text-on-surface">{{ $item->pangkat ?? '' }}</td>
                                            <td class="px-6 py-4 text-center">@include('master-data._status-toggle', ['item' => $item])</td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-end gap-1">
                                                    <button type="button"
                                                            title="Edit"
                                                            class="p-2 rounded-lg hover:bg-primary/10 text-on-surface-variant hover:text-primary transition-colors duration-150"
                                                            data-edit-url="{{ route('master-data.update', $item) }}"
                                                            data-edit-id="{{ $item->id }}"
                                                            data-cek-url="{{ route('master-data.pemakaian', $item) }}"
                                                            data-edit-kategori="{{ $item->kategori }}"
                                                            data-edit-value="{{ $item->value }}"
                                                            data-edit-label="{{ $item->label ?? '' }}"
                                                            data-edit-urutan="{{ $item->urutan }}"
                                                            data-edit-is-aktif="{{ $item->is_aktif ? '1' : '0' }}"
                                                            data-edit-pangkat="{{ $item->pangkat }}"
                                                            data-edit-nip="{{ $item->nip }}"
                                                            data-edit-jabatan-ttd="{{ $item->jabatan_ttd }}">
                                                        <span class="material-symbols-outlined text-lg">edit</span>
                                                    </button>
                                                </div>
                                            </td>
                                        @else
                                            <td class="px-6 py-4 text-on-surface-variant">{{ $loop->iteration }}</td> <!-- Urutan -->
                                            <td class="px-6 py-4 text-on-surface">{{ $item->value }}</td> <!-- Value -->
                                            <td class="px-6 py-4 text-on-surface">{{ $item->label ?? $item->value }}</td> <!-- Label -->
                                            <td class="px-6 py-4 text-center">@include('master-data._status-toggle', ['item' => $item])</td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-end gap-1">
                                                    <button type="button"
                                                            title="Edit"
                                                            class="p-2 rounded-lg hover:bg-primary/10 text-on-surface-variant hover:text-primary transition-colors duration-150"
                                                            data-edit-url="{{ route('master-data.update', $item) }}"
                                                            data-edit-id="{{ $item->id }}"
                                                            data-cek-url="{{ route('master-data.pemakaian', $item) }}"
                                                            data-edit-kategori="{{ $item->kategori }}"
                                                            data-edit-value="{{ $item->value }}"
                                                            data-edit-label="{{ $item->label ?? '' }}"
                                                            data-edit-urutan="{{ $item->urutan }}"
                                                            data-edit-is-aktif="{{ $item->is_aktif ? '1' : '0' }}"
                                                            data-edit-pangkat=""
                                                            data-edit-nip=""
                                                            data-edit-jabatan-ttd="">
                                                        <span class="material-symbols-outlined text-lg">edit</span>
                                                    </button>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Modal diletakkan di akhir <body> agar overlay menutupi seluruh layar (header & sidebar) --}}
    <!-- Add/Edit Modal -->
    <div id="master-data-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden bg-black/40" aria-hidden="true">
        <div class="relative w-full max-w-md max-h-[90vh] overflow-hidden mx-4">
            <div class="relative bg-surface-container-lowest p-6 shadow-lg rounded-lg flex flex-col h-full">
                <!-- Modal Header -->
                <div class="flex justify-between items-start pb-4 mb-4 border-b border-outline-variant">
                    <h2 class="text-xl font-bold text-on-surface" id="modal-title">
                        Tambah Data Master
                    </h2>
                    <button type="button" class="modal-close btn-icon hover:bg-primary/10 rounded-lg p-1.5" id="modal-close-btn">
                        <span class="material-symbols-outlined text-on-surface-variant hover:text-on-surface">close</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="master-data-form" class="space-y-5 flex-1 overflow-y-auto" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <input type="hidden" name="id" id="form-id">

                    <!-- Kategori -->
                    <div>
                        <label for="form-kategori" class="mb-2 block text-sm font-medium text-on-surface-variant">Kategori</label>
                        <select id="form-kategori" name="kategori" class="block w-full rounded-lg border border-outline-variant bg-surface-bright px-4 py-3 text-sm font-medium text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-primary/20 focus:ring-2 focus:outline-none @error('kategori') border-red-500 @enderror" required>
                            @foreach ($kategoriList as $k => $label)
                                <option value="{{ $k }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Label -->
                    <div>
                        <label for="form-label" class="mb-2 block text-sm font-medium text-on-surface-variant">Label (opsional)</label>
                        <input type="text" id="form-label" name="label" class="block w-full rounded-lg border border-outline-variant bg-surface-bright px-4 py-3 text-sm font-medium text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-primary/20 focus:ring-2 focus:outline-none @error('label') border-red-500 @enderror">
                        @error('label')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Value -->
                    <div>
                        <label for="form-value" class="mb-2 block text-sm font-medium text-on-surface-variant">Value</label>
                        <input type="text" id="form-value" name="value" class="block w-full rounded-lg border border-outline-variant bg-surface-bright px-4 py-3 text-sm font-medium text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-primary/20 focus:ring-2 focus:outline-none @error('value') border-red-500 @enderror" required>
                        @error('value')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIP (only for pejabat) -->
                    <div id="nip-field">
                        <label for="form-nip" class="mb-2 block text-sm font-medium text-on-surface-variant">NIP</label>
                        <input type="text" id="form-nip" name="nip" class="block w-full rounded-lg border border-outline-variant bg-surface-bright px-4 py-3 text-sm font-medium text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-primary/20 focus:ring-2 focus:outline-none @error('nip') border-red-500 @enderror">
                        @error('nip')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pangkat (only for pejabat) -->
                    <div id="pangkat-field">
                        <label for="form-pangkat" class="mb-2 block text-sm font-medium text-on-surface-variant">Pangkat / Golongan</label>
                        <select id="form-pangkat" name="pangkat" class="block w-full rounded-lg border border-outline-variant bg-surface-bright px-4 py-3 text-sm font-medium text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-primary/20 focus:ring-2 focus:outline-none @error('pangkat') border-red-500 @enderror">
                            <option value="">&mdash; Pilih pangkat &mdash;</option>
                            @foreach (\App\Models\MasterData::PANGKAT_PNS as $pangkatOpsi)
                                <option value="{{ $pangkatOpsi }}">{{ $pangkatOpsi }}</option>
                            @endforeach
                        </select>
                        @error('pangkat')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-on-surface-variant">Pangkat dan golongan akan dicetak di bawah nama pejabat.</p>
                    </div>

                    <!-- Jabatan pada tanda tangan (only for pejabat) -->
                    <div id="jabatan-ttd-field">
                        <label for="form-jabatan-ttd" class="mb-2 block text-sm font-medium text-on-surface-variant">Jabatan di Tanda Tangan (opsional)</label>
                        <textarea id="form-jabatan-ttd" name="jabatan_ttd" rows="3" maxlength="500"
                                  placeholder="KEPALA DINAS&#10;KOMUNIKASI DAN INFORMATIKA&#10;KABUPATEN SITUBONDO"
                                  class="block w-full rounded-lg border border-outline-variant bg-surface-bright px-4 py-3 text-sm font-medium text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-primary/20 focus:ring-2 focus:outline-none @error('jabatan_ttd') border-red-500 @enderror"></textarea>
                        @error('jabatan_ttd')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-on-surface-variant">Satu baris di sini = satu baris di PDF. Kosongkan untuk memakai Jabatan di atas (huruf besar).</p>
                    </div>

                                        <!-- Urutan -->
                    <div id="urutan-field">
                        <label for="form-urutan" class="mb-2 block text-sm font-medium text-on-surface-variant">Urutan</label>
                        <input type="number" id="form-urutan" name="urutan" class="block w-full rounded-lg border border-outline-variant bg-surface-bright px-4 py-3 text-sm font-medium text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-primary/20 focus:ring-2 focus:outline-none @error('urutan') border-red-500 @enderror">
                        @error('urutan')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div id="is-aktif-field">
                        <label for="form-is-aktif" class="mb-2 block text-sm font-medium text-on-surface-variant">Status</label>
                        <select id="form-is-aktif" name="is_aktif" class="block w-full rounded-lg border border-outline-variant bg-surface-bright px-4 py-3 text-sm font-medium text-on-surface focus:border-primary focus:ring-primary/20 focus:ring-2 focus:outline-none @error('is_aktif') border-red-500 @enderror" required>
                            <option value="1" selected>Aktif</option>
                            <option value="0">Non-Aktif</option>
                        </select>
                        @error('is_aktif')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-on-surface-variant">Saat diubah ke Non-Aktif, sistem akan mengecek apakah masih dipakai perangkat Kominfo atau Colocation.</p>
                    </div>
                </form>

                <!-- Modal Footer -->
                <div class="flex justify-end pt-4 mt-6 space-x-3 border-t border-outline-variant">
                    <button type="button" class="modal-close btn px-5 py-2 rounded-lg text-sm font-medium text-on-surface-variant hover:bg-surface-container transition-colors duration-150" id="modal-cancel-btn">
                        Batal
                    </button>
                    <button type="submit" form="master-data-form" class="btn px-5 py-2 rounded-lg text-sm font-medium text-on-primary-container bg-primary-container hover:bg-primary/90 transition-colors duration-150" id="modal-submit-btn">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Popup ubah status (cek pemakaian) -->
    <form id="status-toggle-form" method="POST" action="" class="hidden">
        @csrf
        @method('PATCH')
    </form>

    <div id="status-modal" class="fixed inset-0 z-[110] flex items-center justify-center hidden bg-black/40" aria-hidden="true">
        <div class="relative w-full max-w-md max-h-[90vh] overflow-y-auto mx-4 bg-surface-container-lowest p-6 shadow-lg rounded-lg">
            <div class="flex items-start gap-3 mb-4">
                <span id="status-modal-icon" class="material-symbols-outlined text-3xl text-amber-500">help</span>
                <h2 id="status-modal-title" class="text-lg font-bold text-on-surface pt-1">Ubah status</h2>
            </div>
            <div id="status-modal-body" class="space-y-3 text-sm text-on-surface-variant"></div>
            <div class="flex justify-end gap-3 pt-5 mt-5 border-t border-outline-variant">
                <button type="button" id="status-modal-cancel"
                        class="px-5 py-2 rounded-lg text-sm font-medium text-on-surface-variant hover:bg-surface-container transition-colors duration-150">
                    Batal
                </button>
                <button type="button" id="status-modal-ok"
                        class="px-5 py-2 rounded-lg text-sm font-medium text-white bg-primary hover:bg-primary/90 transition-colors duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                    Lanjutkan
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('master-data-modal');
            const modalTitle = document.getElementById('modal-title');
            const modalCloseBtn = document.getElementById('modal-close-btn');
            const modalCancelBtn = document.getElementById('modal-cancel-btn');
            const form = document.getElementById('master-data-form');
            const formMethod = document.getElementById('form-method');
            const formId = document.getElementById('form-id');
            const formKategori = document.getElementById('form-kategori');
            const formValue = document.getElementById('form-value');
            const formLabel = document.getElementById('form-label');
            const formUrutan = document.getElementById('form-urutan');
            const formPangkat = document.getElementById('form-pangkat');
            const formNip = document.getElementById('form-nip');
            const formIsAktif = document.getElementById('form-is-aktif');
            const formJabatanTtd = document.getElementById('form-jabatan-ttd');
            const modalSubmitBtn = document.getElementById('modal-submit-btn');

            // Open modal for editing
            document.querySelectorAll('[data-edit-url]').forEach(button => {
                button.addEventListener('click', function () {
                    const kategori = this.getAttribute('data-edit-kategori');
                    const id = this.getAttribute('data-edit-id');
                    const value = this.getAttribute('data-edit-value');
                    const label = this.getAttribute('data-edit-label');
                    const urutan = this.getAttribute('data-edit-urutan');
                    const isAktif = this.getAttribute('data-edit-is-aktif') === '1';

                    modalTitle.textContent = 'Edit Data Master';
                    form.action = this.getAttribute('data-edit-url');
                    formMethod.value = 'PUT';
                    formId.value = id;
                    formKategori.value = kategori;
                    togglePejabatFields();

                    formValue.value = value;
                    formLabel.value = label;
                    formUrutan.value = urutan;
                    const pangkat = this.getAttribute('data-edit-pangkat');
                    formPangkat.value = pangkat;
                    const nip = this.getAttribute('data-edit-nip');
                    formNip.value = nip;
                    formJabatanTtd.value = this.getAttribute('data-edit-jabatan-ttd') || '';
                    formIsAktif.value = isAktif ? '1' : '0';
                    // Simpan info untuk pengecekan pemakaian saat status diubah ke Non-Aktif
                    form.dataset.cekUrl = this.getAttribute('data-cek-url') || '';
                    form.dataset.statusAwal = isAktif ? '1' : '0';
                    form.dataset.nama = label || value;
                    modal.classList.remove('hidden');
                    modal.setAttribute('aria-hidden', 'false');
                });
            });

            // Open modal for adding new data
            document.getElementById('tambahDataBtn').addEventListener('click', function () {
                modalTitle.textContent = 'Tambah Data Master';
                form.action = "{{ route('master-data.store') }}";
                formMethod.value = 'POST';
                formId.value = '';
                formKategori.value = "{{ $kategoriAktifKey }}";
                togglePejabatFields();
                // Hide current file info when adding

                formValue.value = '';
                formLabel.value = '';
                formUrutan.value = '';
                formNip.value = '';
                formPangkat.value = '';
                formJabatanTtd.value = '';
                formIsAktif.value = '1';
                form.dataset.cekUrl = '';
                form.dataset.statusAwal = '';
                form.dataset.nama = '';
                modal.classList.remove('hidden');
                modal.setAttribute('aria-hidden', 'false');
            });

            // Close modal
            function closeModal() {
                modal.classList.add('hidden');
                modal.setAttribute('aria-hidden', 'true');
                form.reset();
                formMethod.value = 'POST';
                formId.value = '';
                modalTitle.textContent = 'Tambah Data Master';
            }

            modalCloseBtn.addEventListener('click', closeModal);
            modalCancelBtn.addEventListener('click', closeModal);

            // Toggle pejabat fields based on category
            function togglePejabatFields() {
                const kategori = formKategori.value;
                const isPejabat = kategori === 'pejabat';

                // Show/hide NIP and PDF fields
                document.getElementById('nip-field').style.display = isPejabat ? 'block' : 'none';
                                document.getElementById('pangkat-field').style.display = isPejabat ? 'block' : 'none';
                document.getElementById('urutan-field').style.display = isPejabat ? 'none' : 'block';
                document.getElementById('jabatan-ttd-field').style.display = isPejabat ? 'block' : 'none';

                // Change labels for Value and Label if pejabat
                const valueLabel = document.querySelector('label[for="form-value"]');
                const labelLabel = document.querySelector('label[for="form-label"]');
                if (isPejabat) {
                    valueLabel.textContent = 'Jabatan';
                    labelLabel.textContent = 'Nama Lengkap';
                } else {
                    valueLabel.textContent = 'Value';
                    labelLabel.textContent = 'Label (opsional)';
                }
            }

            // Event listener for category change
            formKategori.addEventListener('change', togglePejabatFields);

            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusModal = document.getElementById('status-modal');
            const statusTitle = document.getElementById('status-modal-title');
            const statusIcon  = document.getElementById('status-modal-icon');
            const statusBody  = document.getElementById('status-modal-body');
            const btnOk       = document.getElementById('status-modal-ok');
            const btnCancel   = document.getElementById('status-modal-cancel');
            const toggleForm  = document.getElementById('status-toggle-form');
            const dataForm    = document.getElementById('master-data-form');

            let onConfirm = null;   // aksi yang dijalankan saat tombol utama ditekan
            let requestId = 0;

            // Bangun elemen dengan textContent (aman dari XSS, nama server berasal dari input user)
            function el(tag, cls, text) {
                const e = document.createElement(tag);
                if (cls) e.className = cls;
                if (text !== undefined) e.textContent = text;
                return e;
            }

            function setIcon(name, colorClass) {
                statusIcon.textContent = name;
                statusIcon.className = 'material-symbols-outlined text-3xl ' + colorClass;
            }

            function setOk(label, enabled, danger) {
                btnOk.textContent = label;
                btnOk.disabled = !enabled;
                btnOk.className = 'px-5 py-2 rounded-lg text-sm font-medium text-white transition-colors duration-150 disabled:opacity-50 disabled:cursor-not-allowed '
                    + (danger ? 'bg-red-600 hover:bg-red-700' : 'bg-primary hover:bg-primary/90');
            }

            function openStatusModal() {
                statusModal.classList.remove('hidden');
                statusModal.setAttribute('aria-hidden', 'false');
            }

            function closeStatusModal() {
                statusModal.classList.add('hidden');
                statusModal.setAttribute('aria-hidden', 'true');
                onConfirm = null;
                requestId++; // abaikan respons fetch yang masih berjalan
            }

            function statCard(label, jumlah) {
                const box = el('div', 'flex-1 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-center');
                box.append(el('div', 'text-2xl font-bold text-amber-700', String(jumlah)));
                box.append(el('div', 'text-xs font-medium text-amber-800', label));
                return box;
            }

            function renderPemakaian(nama, d) {
                statusBody.replaceChildren();

                if (!d.dipakai) {
                    setIcon('check_circle', 'text-green-600');
                    statusTitle.textContent = 'Nonaktifkan data master?';
                    statusBody.append(el('p', '', '"' + nama + '" belum dipakai oleh perangkat mana pun, jadi aman untuk dinonaktifkan.'));
                    setOk('Nonaktifkan', true, false);
                    return;
                }

                setIcon('warning', 'text-amber-500');
                statusTitle.textContent = 'Data master masih dipakai';
                statusBody.append(el('p', '', '"' + nama + '" masih dipakai oleh ' + d.total + ' perangkat:'));

                const stats = el('div', 'flex gap-3');
                stats.append(statCard('Kominfo', d.kominfo));
                stats.append(statCard('Colocation', d.colocation));
                statusBody.append(stats);

                const list = el('ul', 'rounded-lg border border-outline-variant divide-y divide-outline-variant max-h-48 overflow-y-auto');
                d.daftar.forEach(function (s) {
                    const li = el('li', 'px-3 py-2 flex items-center justify-between gap-3');
                    const kiri = el('div', 'min-w-0');
                    kiri.append(el('div', 'text-on-surface font-medium truncate', s.nama));
                    kiri.append(el('div', 'text-xs font-mono', s.kode));
                    li.append(kiri);
                    li.append(el('span', 'shrink-0 text-xs px-2 py-0.5 rounded-full bg-surface-container text-on-surface-variant', s.kepemilikan));
                    list.append(li);
                });
                statusBody.append(list);

                if (d.total > d.daftar.length) {
                    statusBody.append(el('p', 'text-xs', '... dan ' + (d.total - d.daftar.length) + ' perangkat lainnya.'));
                }

                statusBody.append(el('p', 'text-xs',
                    'Data perangkat yang sudah ada tidak berubah, tetapi nilai ini tidak akan muncul lagi sebagai pilihan saat input perangkat baru.'));
                setOk('Tetap Nonaktifkan', true, true);
            }

            // Popup nonaktifkan: cek pemakaian dulu, lalu jalankan `lanjut` jika user setuju
            async function mintaKonfirmasiNonaktif(nama, cekUrl, lanjut) {
                const thisRequest = ++requestId;
                onConfirm = lanjut;
                statusBody.replaceChildren();

                setIcon('hourglass_top', 'text-secondary');
                statusTitle.textContent = 'Memeriksa pemakaian...';
                statusBody.append(el('p', '', 'Mohon tunggu, sistem sedang mengecek perangkat Kominfo dan Colocation.'));
                setOk('Nonaktifkan', false, false);
                openStatusModal();

                try {
                    const res = await fetch(cekUrl, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin'
                    });
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    const data = await res.json();
                    if (thisRequest !== requestId) return;
                    renderPemakaian(nama, data);
                } catch (err) {
                    if (thisRequest !== requestId) return;
                    setIcon('error', 'text-red-500');
                    statusTitle.textContent = 'Gagal memeriksa pemakaian';
                    statusBody.replaceChildren(el('p', '', 'Status tidak diubah karena pemakaian data master tidak bisa diperiksa. Coba lagi beberapa saat.'));
                    setOk('Nonaktifkan', false, false);
                }
            }

            // Popup aktifkan kembali (tanpa cek pemakaian)
            function mintaKonfirmasiAktif(nama, lanjut) {
                requestId++;
                onConfirm = lanjut;
                statusBody.replaceChildren(el('p', '', '"' + nama + '" akan muncul kembali sebagai pilihan saat input perangkat.'));
                setIcon('toggle_on', 'text-green-600');
                statusTitle.textContent = 'Aktifkan data master?';
                setOk('Aktifkan', true, false);
                openStatusModal();
            }

            // 1) Klik badge status di tabel
            document.querySelectorAll('.js-toggle-status').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const nama = btn.dataset.nama;
                    const kirim = function () {
                        toggleForm.action = btn.dataset.toggleUrl;
                        toggleForm.submit();
                    };
                    if (btn.dataset.aktif === '1') {
                        mintaKonfirmasiNonaktif(nama, btn.dataset.cekUrl, kirim);
                    } else {
                        mintaKonfirmasiAktif(nama, kirim);
                    }
                });
            });

            // 2) Simpan form edit dengan status diubah dari Aktif ke Non-Aktif
            dataForm.addEventListener('submit', function (e) {
                const sedangEdit = document.getElementById('form-method').value === 'PUT';
                const statusBaru = document.getElementById('form-is-aktif').value;
                const dariAktifKeNon = dataForm.dataset.statusAwal === '1' && statusBaru === '0';

                if (!sedangEdit || !dariAktifKeNon || !dataForm.dataset.cekUrl) return;

                e.preventDefault();
                mintaKonfirmasiNonaktif(dataForm.dataset.nama || 'Data ini', dataForm.dataset.cekUrl, function () {
                    dataForm.submit(); // submit() tidak memicu event submit lagi
                });
            });

            btnOk.addEventListener('click', function () {
                if (typeof onConfirm !== 'function' || btnOk.disabled) return;
                btnOk.disabled = true;
                onConfirm();
            });

            btnCancel.addEventListener('click', closeStatusModal);
            statusModal.addEventListener('click', function (e) {
                if (e.target === statusModal) closeStatusModal();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !statusModal.classList.contains('hidden')) closeStatusModal();
            });
        });
    </script>
@endpush
