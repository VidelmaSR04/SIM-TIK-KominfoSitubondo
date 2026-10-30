@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-on-surface">Persetujuan Pengguna</h1>
    <p class="text-secondary text-sm mt-1">Setujui atau tolak pendaftar yang memakai kode registrasi.</p>
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

<div class="flex gap-2 mb-4">
    <a href="{{ route('admin.approvals.index', ['status' => 'pending']) }}"
       class="px-4 py-2 rounded-lg text-sm font-medium {{ $status === 'pending' ? 'bg-primary text-white' : 'bg-white border border-outline-variant text-on-surface' }}">
        Menunggu ({{ $counts['pending'] }})
    </a>
    <a href="{{ route('admin.approvals.index', ['status' => 'rejected']) }}"
       class="px-4 py-2 rounded-lg text-sm font-medium {{ $status === 'rejected' ? 'bg-primary text-white' : 'bg-white border border-outline-variant text-on-surface' }}">
        Ditolak ({{ $counts['rejected'] }})
    </a>
</div>

<div class="bg-white rounded-xl border border-outline-variant shadow-[0_1px_3px_rgba(0,0,0,0.1)] overflow-x-auto">
    <table class="w-full text-sm text-left">
        <thead class="bg-surface-container-low text-xs uppercase text-secondary">
            <tr>
                <th class="px-4 py-3">Nama</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Kode / OPD</th>
                <th class="px-4 py-3">Mendaftar</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant">
        @forelse ($users as $u)
            <tr>
                <td class="px-4 py-3 font-medium">{{ $u->name }}</td>
                <td class="px-4 py-3">{{ $u->email }}</td>
                <td class="px-4 py-3">
                    @if ($u->registrationCode)
                        <span class="font-mono">{{ $u->registrationCode->code }}</span>
                        @if ($u->registrationCode->label)<span class="text-secondary"> · {{ $u->registrationCode->label }}</span>@endif
                    @else - @endif
                    @if ($u->status === 'rejected' && $u->rejection_reason)
                        <div class="text-xs text-red-600 mt-1">Alasan: {{ $u->rejection_reason }}</div>
                    @endif
                </td>
                <td class="px-4 py-3">{{ $u->created_at?->format('d M Y H:i') }}</td>
                <td class="px-4 py-3">
                    <div class="flex justify-end gap-2 items-start">
                        <form method="POST" action="{{ route('admin.approvals.approve', $u) }}">
                            @csrf @method('PATCH')
                            <button class="px-3 py-1 text-xs rounded-lg bg-green-600 text-white hover:bg-green-700">Setujui</button>
                        </form>
                        @if ($status === 'pending')
                        <form method="POST" action="{{ route('admin.approvals.reject', $u) }}" class="flex gap-2"
                              onsubmit="return confirm('Tolak pendaftaran ini?')">
                            @csrf @method('PATCH')
                            <input name="rejection_reason" type="text" maxlength="255" placeholder="Alasan (opsional)"
                                   class="px-2 py-1 text-xs border border-outline-variant rounded-lg w-40">
                            <button class="px-3 py-1 text-xs rounded-lg border border-red-200 text-red-600 hover:bg-red-50">Tolak</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-secondary">Tidak ada data.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $users->links() }}</div>
@endsection
