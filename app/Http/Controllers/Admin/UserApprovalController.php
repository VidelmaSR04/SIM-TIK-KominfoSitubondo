<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserApprovalController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->input('status', User::STATUS_PENDING);
        if (! in_array($status, [User::STATUS_PENDING, User::STATUS_REJECTED], true)) {
            $status = User::STATUS_PENDING;
        }

        $users = User::with('registrationCode:id,code,label')
            ->where('status', $status)
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        $counts = [
            User::STATUS_PENDING  => User::where('status', User::STATUS_PENDING)->count(),
            User::STATUS_REJECTED => User::where('status', User::STATUS_REJECTED)->count(),
        ];

        return view('admin.approvals.index', compact('users', 'status', 'counts'));
    }

    public function approve(Request $request, User $user): RedirectResponse
    {
        if ($user->isActive()) {
            return back()->with('error', 'Akun ini sudah aktif.');
        }

        // Hanya kolom status/persetujuan; role & password tidak disentuh.
        $user->forceFill([
            'status'           => User::STATUS_ACTIVE,
            'approved_by'      => $request->user()->id,
            'approved_at'      => now(),
            'rejection_reason' => null,
        ])->save();

        return back()->with('success', "Akun {$user->name} disetujui dan sekarang bisa login.");
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak bisa menolak akun Anda sendiri.');
        }

        if ($user->role !== 'user') {
            return back()->with('error', 'Hanya akun berperan "user" yang bisa ditolak lewat halaman ini.');
        }

        $data = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:255'],
        ]);

        // Data tidak dihapus; hanya ditandai rejected (bisa disetujui ulang).
        $user->forceFill([
            'status'           => User::STATUS_REJECTED,
            'approved_by'      => $request->user()->id,
            'approved_at'      => now(),
            'rejection_reason' => $data['rejection_reason'] ?? null,
        ])->save();

        return back()->with('success', "Pendaftaran {$user->name} ditolak.");
    }
}
