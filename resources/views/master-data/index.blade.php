@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <h1 class="text-2xl font-bold text-on-surface">Data Master</h1>
            <button type="button"
                    id="tambahDataBtn"
                    class="bg-primary-container hover:bg-primary/90 text-on-primary-container font-medium px-5 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                Tambah Data
                <span class="material-symbols-outlined">add</span>
            </button>
        </div>
    </div>

    <!-- Tabs for categories -->
    <div class="mb-6">
        <div class="overflow-x-auto rounded-lg border border-outline-variant bg-surface-container-lowest">
            <ul class="flex border-b border-outline-variant bg-surface-container-low">
                @foreach (App\Models\MasterData::KATEGORI as $kategoriKey => $kategoriLabel)
                    <li>
                        <a href="{{ route('master-data.index', ['kategori' => $kategoriKey]) }}"
                           class="px-6 py-4 text-lg font-medium flex-1 text-center transition-all duration-200
                                  border-b-2 border-transparent hover:text-primary hover:bg-primary/5
                                  text-on-surface-variant {{ request()->query('kategori') == $kategoriKey ? 'text-primary border-primary bg-primary/5' : '' }} relative">
                            {{ $kategoriLabel }}
                            @if (request()->query('kategori') == $kategoriKey)
                                <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-primary"></span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
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

    <!-- Table of data -->
    <div class="rounded-lg border border-outline-variant overflow-hidden shadow-sm">
        <table class="min-w-full divide-y divide-outline-variant">
            <thead class="bg-surface-container">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant uppercase tracking-wider">Urutan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant uppercase tracking-wider">Value</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant uppercase tracking-wider">Label</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-on-surface-variant uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-surface-container-lowest divide-y divide-outline-variant">
                @if ($items->isEmpty())
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-on-surface-variant">
                            Tidak ada data untuk kategori ini.
                        </td>
                    </tr>
                @else
                    @foreach ($items as $item)
                        <tr class="hover:bg-primary/5 transition-colors duration-150">
                            <td class="px-6 py-4 text-center text-on-surface font-medium">{{ $item->urutan }}</td>
                            <td class="px-6 py-4 text-on-surface font-mono">{{ $item->value }}</td>
                            <td class="px-6 py-4 text-on-surface">{{ $item->label ?? $item->value }}</td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('master-data.toggleAktif', $item) }}" method="POST" class="inline-flex items-center">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-full text-xs font-medium transition-all duration-150
                                                   {{ $item->is_aktif ? 'bg-primary/10 text-primary hover:bg-primary/20' : 'bg-surface-container hover:bg-primary/5 text-on-surface-variant' }}
                                                   hover:scale-[1.05]">
                                        {{ $item->is_aktif ? 'Aktif' : 'Tidak Aktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <!-- Edit Button -->
                                <button type="button"
                                        class="p-2 rounded-hover bg-surface-container hover:bg-primary/5 text-on-surface-variant transition-colors duration-150 flex items-center justify-center hover:scale-[1.05]"
                                        data-edit-url="{{ route('master-data.update', $item) }}"
                                        data-edit-id="{{ $item->id }}"
                                        data-edit-kategori="{{ $item->kategori }}"
                                        data-edit-value="{{ $item->value }}"
                                        data-edit-label="{{ $item->label ?? '' }}"
                                        data-edit-urutan="{{ $item->urutan }}"
                                        data-edit-is-aktif="{{ $item->is_aktif ? '1' : '0' }}">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <!-- Delete Button -->
                                <form action="{{ route('master-data.destroy', $item) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 rounded-hover bg-surface-container hover:bg-red-50/5 text-red-500 hover:text-red-600 transition-colors duration-150 flex items-center justify-center hover:scale-[1.05]"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <!-- Add/Edit Modal -->
    <div id="master-data-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden aria-hidden="true">
        <div class="relative w-full max-w-md max-h-[90vh] overflow-hidden">
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
                            @foreach (App\Models\MasterData::KATEGORI as $k => $label)
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
                        <input type="checkbox" id="form-is-aktif" name="is_aktif" value="1" class="h-4 w-4 text-primary focus:ring-primary border-border-outline-variant rounded">
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

            // Open modal for adding new data
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

            // Open modal for adding new data (from the main button)
            document.getElementById('tambahDataBtn').addEventListener('click', function () {
                modalTitle.textContent = 'Tambah Data Master';
                form.action = "{{ route('master-data.store') }}";
                formMethod.value = 'POST';
                formId.value = '';
                formKategori.value = "{{ request()->query('kategori', key(App\Models\MasterData::KATEGORI)) }}";
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
                // Reset form
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

            // Prevent form submission on enter in textarea/input (optional)
            form.addEventListener('submit', function (e) {
                // You can add client-side validation here if needed
            });
        });
    </script>
@endpush