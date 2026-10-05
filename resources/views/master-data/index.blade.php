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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant tracking-wide">Pangkat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant tracking-wide">QR TTD</th>
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
                                            <td class="px-6 py-4">
                                                @if ($item->qrcode_path)
                                                    <img src="{{ $item->qrcodeUrl() }}" alt="QR {{ $item->label }}"
                                                         style="height:44px;width:44px;object-fit:contain;"
                                                         class="rounded border border-outline-variant bg-white">
                                                @else
                                                    <span class="text-xs text-on-surface-variant">Belum ada</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-end gap-1">
                                                    <button type="button"
                                                            title="Edit"
                                                            class="p-2 rounded-lg hover:bg-primary/10 text-on-surface-variant hover:text-primary transition-colors duration-150"
                                                            data-edit-url="{{ route('master-data.update', $item) }}"
                                                            data-edit-id="{{ $item->id }}"
                                                            data-edit-kategori="{{ $item->kategori }}"
                                                            data-edit-value="{{ $item->value }}"
                                                            data-edit-label="{{ $item->label ?? '' }}"
                                                            data-edit-urutan="{{ $item->urutan }}"
                                                            data-edit-is-aktif="{{ $item->is_aktif ? '1' : '0' }}"
                                                            data-edit-pangkat="{{ $item->pangkat }}"
                                                            data-edit-nip="{{ $item->nip }}"
                                                            data-edit-jabatan-ttd="{{ $item->jabatan_ttd }}"
                                                            data-edit-qrcode-url="{{ $item->qrcodeUrl() }}">
                                                        <span class="material-symbols-outlined text-lg">edit</span>
                                                    </button>
                                                    <form action="{{ route('master-data.destroy', $item) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                title="Hapus"
                                                                class="p-2 rounded-lg hover:bg-red-500/10 text-on-surface-variant hover:text-red-500 transition-colors duration-150
                                                                onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                            <span class="material-symbols-outlined text-lg">delete</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        @else
                                            <td class="px-6 py-4 text-on-surface-variant">{{ $loop->iteration }}</td> <!-- Urutan -->
                                            <td class="px-6 py-4 text-on-surface">{{ $item->value }}</td> <!-- Value -->
                                            <td class="px-6 py-4 text-on-surface">{{ $item->label ?? $item->value }}</td> <!-- Label -->
                                            <td class="flex items-center justify-center px-6 py-4">
                                                <span class="px-3 py-1 rounded-full {{ $item->is_aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $item->is_aktif ? 'Aktif' : 'Tidak Aktif' }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-end gap-1">
                                                    <button type="button"
                                                            title="Edit"
                                                            class="p-2 rounded-lg hover:bg-primary/10 text-on-surface-variant hover:text-primary transition-colors duration-150"
                                                            data-edit-url="{{ route('master-data.update', $item) }}"
                                                            data-edit-id="{{ $item->id }}"
                                                            data-edit-kategori="{{ $item->kategori }}"
                                                            data-edit-value="{{ $item->value }}"
                                                            data-edit-label="{{ $item->label ?? '' }}"
                                                            data-edit-urutan="{{ $item->urutan }}"
                                                            data-edit-is-aktif="{{ $item->is_aktif ? '1' : '0' }}"
                                                            data-edit-pangkat=""
                                                            data-edit-nip=""
                                                            data-edit-jabatan-ttd=""
                                                            data-edit-qrcode-url="">
                                                        <span class="material-symbols-outlined text-lg">edit</span>
                                                    </button>
                                                    <form action="{{ route('master-data.destroy', $item) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                title="Hapus"
                                                                class="p-2 rounded-lg hover:bg-red-500/10 text-on-surface-variant hover:text-red-500 transition-colors duration-150
                                                                onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                            <span class="material-symbols-outlined text-lg">delete</span>
                                                        </button>
                                                    </form>
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

    <!-- Add/Edit Modal -->
    <div id="master-data-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/40" aria-hidden="true">
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
                        <label for="form-pangkat" class="mb-2 block text-sm font-medium text-on-surface-variant">Pangkat</label>
                        <select id="form-pangkat" name="pangkat" class="block w-full rounded-lg border border-outline-variant bg-surface-bright px-4 py-3 text-sm font-medium text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-primary/20 focus:ring-2 focus:outline-none @error('pangkat') border-red-500 @enderror">
                            <option value="">&mdash; Pilih pangkat &mdash;</option>
                            @foreach (\App\Models\MasterData::PANGKAT_PNS as $pangkatOpsi)
                                <option value="{{ $pangkatOpsi }}">{{ $pangkatOpsi }}</option>
                            @endforeach
                        </select>
                        @error('pangkat')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-on-surface-variant">Di PDF yang dicetak hanya nama pangkatnya, misalnya &quot;Pembina Utama Muda&quot;.</p>
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

                    <!-- QR Code tanda tangan (only for pejabat) -->
                    <div id="qrcode-field">
                        <label for="form-qrcode" class="mb-2 block text-sm font-medium text-on-surface-variant">QR Code Tanda Tangan (gambar)</label>
                        <div id="qrcode-preview-wrap" style="display:none;" class="mb-3 items-center gap-3">
                            <img id="qrcode-preview" src="" alt="QR Code"
                                 style="height:96px;width:96px;object-fit:contain;"
                                 class="rounded border border-outline-variant bg-white p-1">
                            <label class="flex items-center gap-2 text-sm text-on-surface-variant">
                                <input type="checkbox" id="form-hapus-qrcode" name="hapus_qrcode" value="1"
                                       class="h-4 w-4 text-primary focus:ring-primary border-outline-variant rounded">
                                Hapus QR
                            </label>
                        </div>
                        <input type="file" id="form-qrcode" name="qrcode" accept="image/png,image/jpeg"
                               class="block w-full text-sm text-on-surface-variant">
                        @error('qrcode')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-on-surface-variant">PNG atau JPG, maksimal 2 MB. Dicetak di atas nama pejabat pada PDF.</p>
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
                    <div class="flex items-center" id="is-aktif-field">
                        <input type="hidden" name="is_aktif" value="0">
                        <input type="checkbox" id="form-is-aktif" name="is_aktif" value="1" class="h-4 w-4 text-primary focus:ring-primary border-outline-variant rounded">
                        <label for="form-is-aktif" class="ml-3 block text-sm font-medium text-on-surface">Aktif</label>
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
@endsection

@push('scripts')
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
            const formIsAktif = document.getElementById('form-is-aktif');
            const formPangkat = document.getElementById('form-pangkat');
            const formNip = document.getElementById('form-nip');
            const formJabatanTtd = document.getElementById('form-jabatan-ttd');
            const formQr = document.getElementById('form-qrcode');
            const formHapusQr = document.getElementById('form-hapus-qrcode');
            const qrPreview = document.getElementById('qrcode-preview');
            const qrPreviewWrap = document.getElementById('qrcode-preview-wrap');

            // Tampilkan / sembunyikan pratinjau QR Code di modal
            function tampilkanQr(url) {
                qrPreviewWrap.style.display = url ? 'flex' : 'none';
                qrPreview.src = url || '';
                formHapusQr.checked = false;
            }

            // Pratinjau langsung saat file QR baru dipilih
            formQr.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    qrPreview.src = URL.createObjectURL(this.files[0]);
                    qrPreviewWrap.style.display = 'flex';
                    formHapusQr.checked = false;
                }
            });
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
                    formIsAktif.checked = isAktif;
                    const pangkat = this.getAttribute('data-edit-pangkat');
                    formPangkat.value = pangkat;
                    const nip = this.getAttribute('data-edit-nip');
                    formNip.value = nip;
                    formJabatanTtd.value = this.getAttribute('data-edit-jabatan-ttd') || '';
                    formQr.value = '';
                    tampilkanQr(this.getAttribute('data-edit-qrcode-url') || '');

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
                formIsAktif.checked = true;
                formNip.value = '';
                formPangkat.value = '';
                formJabatanTtd.value = '';
                formQr.value = '';
                tampilkanQr('');

                modal.classList.remove('hidden');
                modal.setAttribute('aria-hidden', 'false');
            });

            // Close modal
            function closeModal() {
                modal.classList.add('hidden');
                modal.setAttribute('aria-hidden', 'true');
                form.reset();
                tampilkanQr('');
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
                document.getElementById('qrcode-field').style.display = isPejabat ? 'block' : 'none';
                document.getElementById('is-aktif-field').style.display = 'block';

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
@endpush