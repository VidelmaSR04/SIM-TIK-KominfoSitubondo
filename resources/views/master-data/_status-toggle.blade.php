{{-- Badge status yang bisa diklik. Dipakai di tabel master data (pejabat & non-pejabat). --}}
<button type="button"
        class="js-toggle-status inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium transition-colors
               {{ $item->is_aktif ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}"
        title="Klik untuk mengubah status"
        data-nama="{{ $item->label ?? $item->value }}"
        data-aktif="{{ $item->is_aktif ? '1' : '0' }}"
        data-cek-url="{{ route('master-data.pemakaian', $item) }}"
        data-toggle-url="{{ route('master-data.toggleAktif', $item) }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $item->is_aktif ? 'bg-green-500' : 'bg-red-500' }}"></span>
    {{ $item->is_aktif ? 'Aktif' : 'Non-Aktif' }}
</button>
