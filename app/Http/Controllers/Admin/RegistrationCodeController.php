<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterData;
use App\Models\RegistrationCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegistrationCodeController extends Controller
{
    public function index(): View
    {
        $codes = RegistrationCode::with('creator:id,name')
            ->latest()
            ->paginate(10);
        $opdOptions = MasterData::forSelect('pemilik_perangkat');

        return view('admin.registration-codes.index', compact('codes', 'opdOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $opdOptions = MasterData::forSelect('pemilik_perangkat');

        $data = $request->validate([
            'label'      => ['required', 'string', Rule::in(array_keys($opdOptions))],
            'max_uses'   => ['nullable', 'integer', 'min:1', 'max:100000'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ], [
            'label.required' => 'OPD wajib dipilih.',
            'label.in' => 'OPD yang dipilih tidak valid.',
        ]);

        $code = RegistrationCode::create([
            'code'       => RegistrationCode::generateUniqueCode(),
            'label'      => $data['label'] ?? null,
            'max_uses'   => $data['max_uses'] ?? null,
            'expires_at' => $data['expires_at'] ?? null,
            'is_active'  => true,
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.registration-codes.index')
            ->with('success', "Kode registrasi {$code->code} berhasil dibuat.");
    }

    public function toggle(RegistrationCode $registrationCode): RedirectResponse
    {
        $registrationCode->update(['is_active' => ! $registrationCode->is_active]);

        return back()->with('success', $registrationCode->is_active
            ? 'Kode registrasi diaktifkan.'
            : 'Kode registrasi dinonaktifkan.');
    }

    /** Hanya kode yang belum pernah dipakai yang boleh dihapus. */
    public function destroy(RegistrationCode $registrationCode): RedirectResponse
    {
        if ($registrationCode->used_count > 0 || $registrationCode->users()->exists()) {
            return back()->with('error', 'Kode yang sudah dipakai tidak dihapus agar jejak pendaftar tetap ada. Nonaktifkan saja.');
        }

        $registrationCode->delete();

        return back()->with('success', 'Kode registrasi dihapus.');
    }
}
