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
                        <thead class="bg-surface-container">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant tracking-wide w-20">Urutan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant tracking-wide">Value</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant tracking-wide">Label</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant tracking-wide w-32">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-on-surface-variant tracking-wide w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-surface-container-lowest divide-y divide-outline-variant">
                            @if ($items->isEmpty())
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <span class="material-symbols-outlined text-3xl text-on-surface-variant/50 block mb-2">inbox</span>
                                        <p class="text-on-surface-variant text-sm">Belum ada data untuk kategori "{{ $kategoriAktifLabel }}".</p>
                                        <p class="text-on-surface-variant text-xs mt-1">Klik "Tambah Data" untuk menambahkan entri pertama.</p>
                                    </td>
                                </tr>
                            @else
                                @foreach ($items as $item)
                                    <tr class="hover:bg-primary/5 transition-colors duration-150">
                                        <td class="px-6 py-4 text-on-surface-variant">{{ $item->urutan }}</td>
                                        <td class="px-6 py-4 text-on-surface">{{ $item->value }}</td>
                                        <td class="px-6 py-4 text-on-surface">{{ $item->label ?? $item->value }}</td>
                                        <td class="px-6 py-4">
                                            <form action="{{ route('master-data.toggleAktif', $item) }}" method="POST" class="inline-flex">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium transition-all duration-150 hover:scale-[1.03]
                                                               {{ $item->is_aktif ? 'bg-primary/10 text-primary' : 'bg-surface-container text-on-surface-variant' }}">
                                                    <span class="h-1.5 w-1.5 rounded-full {{ $item->is_aktif ? 'bg-primary' : 'bg-on-surface-variant/50' }}"></span>
                                                    {{ $item->is_aktif ? 'Aktif' : 'Tidak Aktif' }}
                                                </button>
                                            </form>
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
                                                        data-edit-is-aktif="{{ $item->is_aktif ? '1' : '0' }}">
                                                    <span class="material-symbols-outlined text-lg">edit</span>
                                                </button>
                                                <form action="{{ route('master-data.destroy', $item) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            title="Hapus"
                                                            class="p-2 rounded-lg hover:bg-red-500/10 text-on-surface-variant hover:text-red-500 transition-colors duration-150"
                                                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                        <span class="material-symbols-outlined text-lg">delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
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
            <div class="relative bg-surface-container-lowest p-6 shadow-lg rounded-lg">
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
                <form id="master-data-form" class="space-y-5" action="" method="POST">
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

                    <!-- Value -->
                    <div>
                        <label for="form-value" class="mb-2 block text-sm font-medium text-on-surface-variant">Value</label>
                        <input type="text" id="form-value" name="value" class="block w-full rounded-lg border border-outline-variant bg-surface-bright px-4 py-3 text-sm font-medium text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-primary/20 focus:ring-2 focus:outline-none @error('value') border-red-500 @enderror" required>
                        @error('value')
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

                    <!-- Urutan -->
                    <div>
                        <label for="form-urutan" class="mb-2 block text-sm font-medium text-on-surface-variant">Urutan</label>
                        <input type="number" id="form-urutan" name="urutan" class="block w-full rounded-lg border border-outline-variant bg-surface-bright px-4 py-3 text-sm font-medium text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-primary/20 focus:ring-2 focus:outline-none @error('urutan') border-red-500 @enderror">
                        @error('urutan')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="flex items-center">
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
                    formValue.value = value;
                    formLabel.value = label;
                    formUrutan.value = urutan;
                    formIsAktif.checked = isAktif;

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
                formValue.value = '';
                formLabel.value = '';
                formUrutan.value = '';
                formIsAktif.checked = true;

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
