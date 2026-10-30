@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-on-surface">Kode Registrasi</h1>
    <p class="text-secondary text-sm mt-1">Buat kode yang dibagikan kepada calon pengguna. Pendaftar tetap perlu disetujui admin.</p>
</div>

@if (session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3 flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">check_circle</span> {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">error</span> {{ session('error') }}
    </div>
@endif

<form method="POST" action="{{ route('admin.registration-codes.store') }}"
      class="bg-white rounded-xl border border-outline-variant shadow-[0_1px_3px_rgba(0,0,0,0.1)] p-5 mb-8 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
    @csrf
    <div>
        <label class="block text-xs font-semibold text-secondary uppercase tracking-wide mb-1" for="label">OPD</label>
        <select id="label" name="label" required
                class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">Pilih OPD</option>
            @foreach ($opdOptions as $value => $opdLabel)
                <option value="{{ $value }}" @selected(old('label') === $value)>{{ $opdLabel }}</option>
            @endforeach
        </select>
        @error('label')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-xs font-semibold text-secondary uppercase tracking-wide mb-1" for="max_uses">Maks. pemakaian</label>
        <input id="max_uses" name="max_uses" type="number" min="1" value="{{ old('max_uses') }}" placeholder="Kosong = tanpa batas"
               class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        @error('max_uses')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-xs font-semibold text-secondary uppercase tracking-wide mb-1" for="expires_at">Kedaluwarsa</label>
        <input id="expires_at" name="expires_at" type="datetime-local" value="{{ old('expires_at') }}"
               class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        @error('expires_at')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <button type="submit" class="flex items-center justify-center gap-2 bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
        <span class="material-symbols-outlined text-[18px]">add</span> Buat Kode
    </button>
</form>

<div class="bg-white rounded-xl border border-outline-variant shadow-[0_1px_3px_rgba(0,0,0,0.1)] overflow-x-auto">
    <table class="w-full text-sm text-left">
        <thead class="bg-surface-container-low text-xs uppercase text-secondary">
            <tr>
                <th class="px-4 py-3">Kode</th>
                <th class="px-4 py-3">OPD</th>
                <th class="px-4 py-3">Pemakaian</th>
                <th class="px-4 py-3">Kedaluwarsa</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant">
        @forelse ($codes as $c)
            <tr>
                <td class="px-4 py-3 font-mono font-semibold tracking-wider">{{ $c->code }}</td>
                <td class="px-4 py-3">{{ $c->label ?: '-' }}</td>
                <td class="px-4 py-3">{{ $c->used_count }} / {{ $c->max_uses ?? '∞' }}</td>
                <td class="px-4 py-3">{{ $c->expires_at ? $c->expires_at->format('d M Y H:i') : '-' }}</td>
                <td class="px-4 py-3">
                    @if ($c->isUsable())
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-200 text-gray-700">{{ $c->unusableReason() }}</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex justify-end gap-2">
                        <form method="POST" action="{{ route('admin.registration-codes.toggle', $c) }}">
                            @csrf @method('PATCH')
                            <button class="px-3 py-1 text-xs rounded-lg border border-outline-variant hover:bg-surface-container-low">
                                {{ $c->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        @if ($c->used_count === 0)
                        <form method="POST" action="{{ route('admin.registration-codes.destroy', $c) }}" onsubmit="return confirm('Hapus kode ini?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 text-xs rounded-lg border border-red-200 text-red-600 hover:bg-red-50">Hapus</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-secondary">Belum ada kode registrasi.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $codes->links() }}</div>
@endsection
