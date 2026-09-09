<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ServerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);

        $query = Server::when($search, function ($query, $search) {
            return $query->where('nama_perangkat', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%")
                ->orWhere('ip_server', 'like', "%{$search}%");
        });

        // If user is not admin, only show their own servers
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        $servers = $query->paginate($perPage);

        return view('server', compact('servers'));
    }

    public function create()
    {
        return view('inputdata');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_perangkat'      => 'required|string|max:255',
            'jenis_perangkat'     => 'required|in:router,switch,server',
            'serial_number'       => 'required|string|max:255',
            'merk_perangkat'      => 'required|string|max:255',
            'type'                => 'required|string|max:255',
            'kondisi_tipe'        => 'required|in:Standard,High Performance',
            'kondisi_status'      => 'required|in:Baru,Bekas',
            'spesifikasi'         => 'required|string',
            'tipe_perangkat'      => 'required|in:RACK MOUNT,TOWER,BLADE',
            'status_kepemilikan'  => 'required|in:Kominfo,Colocation',
            'pemilik_perangkat'   => 'required_if:status_kepemilikan,Colocation|nullable|string|max:255',
            'ip_server'           => 'required|ip',
            'ip_vps'              => 'nullable|ip',
            'status'              => 'required|in:Aktif,Non-Aktif,Maintenance,automatic',
            'ukuran_hdd'          => 'required|string|max:50',
            'ukuran_ram'          => 'required|string|max:50',
            'nomor_rack'          => 'required|string|max:50',
            'gambar_rack'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'jumlah_core'         => 'required|integer|min:1',
            'peruntukan'          => 'required|string|max:255',
            'nama_pengirim'       => 'required|string|max:255',
            'nama_penerima'       => 'required|string|max:255',
            'jam_pengisian'       => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except('_token');
        $data['user_id'] = Auth::user()->id;

        // Calculate status_kelengkapan FIRST
        $data['status_kelengkapan'] = Server::hitungStatusKelengkapan($data);

        // Determine status_locked based on admin's manual status choice from form
        // We need to check the original status value BEFORE removing it from $data
        $formStatus = $data['status'] ?? null;
        // Remove 'status' from data if it's 'automatic' to prevent SQL error
        // 'automatic' is a pseudo-value used only to set status_locked = false
        if (isset($data['status']) && $data['status'] === 'automatic') {
            unset($data['status']);
        }

        // Create server instance (we'll set status_locked explicitly after creation)
        $server = Server::create($data);

        // Generate and set kode_perangkat
        $server->kode_perangkat = Server::generateKodePerangkat(
            $data['status_kepemilikan'],
            $server->created_at ? $server->created_at->toDateString() : null
        );
        $server->save();

        // Determine status_locked based on admin's manual status choice from form
        // If admin selects 'Non-Aktif' or 'Maintenance' -> lock status (status_locked = true)
        // If admin selects 'Aktif', 'Pending', or 'automatic' -> unlock status (status_locked = false)
        // This allows admin to override automatic status synchronization or return to automatic mode
        if (isset($formStatus)) {
            if ($formStatus === 'Non-Aktif' || $formStatus === 'Maintenance') {
                $server->status_locked = true;
            } else {
                // 'Aktif', 'Pending', or 'automatic' -> unlock for automatic synchronization
                $server->status_locked = false;
            }
            // Save the server with the correct status_locked value
            $server->save();
        }

        // ============= UPLOAD GAMBAR =============
        if ($request->hasFile('gambar_rack')) {
            $file = $request->file('gambar_rack');

            // Validasi tambahan untuk file
            if ($file->isValid()) {
                // Hapus gambar lama jika ada (though for new server, likely none)
                if ($server->gambar_rack && Storage::disk('public')->exists($server->gambar_rack)) {
                    Storage::disk('public')->delete($server->gambar_rack);
                    Log::info('Gambar lama dihapus: ' . $server->gambar_rack);
                }

                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $path = $file->storeAs('rack_images', $filename, 'public');
                $server->gambar_rack = $path;

                // Log untuk debugging
                Log::info('Gambar berhasil diupload:', [
                    'filename' => $filename,
                    'path' => $path,
                    'size' => $file->getSize()
                ]);
            } else {
                return redirect()->back()->with('error', 'File gambar tidak valid.')->withInput();
            }
        }
        // =========================================

        // Sync status berdasarkan status_kelengkapan (jika belum dikunci manual oleh admin)
        $server->syncStatusFromKelengkapan();
        $server->save();

        return redirect()->route('detailserver', ['id' => $server->id])->with('success', 'Server berhasil ditambahkan.');
    }

    public function show($id)
    {
        $server = Server::findOrFail($id);

        // If user is not admin, verify ownership
        if (!Auth::check() || (Auth::user()->role !== 'admin' && $server->user_id !== Auth::id())) {
            abort(403, 'Unauthorized action.');
        }

        return view('detailserver', compact('server'));
    }

    public function edit($id)
    {
        $server = Server::findOrFail($id);

        // If user is not admin, verify ownership
        if (!Auth::check() || (Auth::user()->role !== 'admin' && $server->user_id !== Auth::id())) {
            abort(403, 'Unauthorized action.');
        }

        return view('inputdata', compact('server'));
    }

    public function update(Request $request, $id)
    {
        $server = Server::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_perangkat'      => 'required|string|max:255',
            'jenis_perangkat'     => 'required|in:router,switch,server',
            'serial_number'       => 'required|string|max:255',
            'merk_perangkat'      => 'required|string|max:255',
            'type'                => 'required|string|max:255',
            'kondisi_tipe'        => 'required|in:Standard,High Performance',
            'kondisi_status'      => 'required|in:Baru,Bekas',
            'spesifikasi'         => 'required|string',
            'tipe_perangkat'      => 'required|in:RACK MOUNT,TOWER,BLADE',
            'status_kepemilikan'  => 'required|in:Kominfo,Colocation',
            'pemilik_perangkat'   => 'required_if:status_kepemilikan,Colocation|nullable|string|max:255',
            'ip_server'           => 'required|ip',
            'ip_vps'              => 'nullable|ip',
            'status'              => 'required|in:Aktif,Non-Aktif,Maintenance,automatic',
            'ukuran_hdd'          => 'required|string|max:50',
            'ukuran_ram'          => 'required|string|max:50',
            'nomor_rack'          => 'required|string|max:50',
            'gambar_rack'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'jumlah_core'         => 'required|integer|min:1',
            'peruntukan'          => 'required|string|max:255',
            'nama_pengirim'       => 'required|string|max:255',
            'nama_penerima'       => 'required|string|max:255',
            'jam_pengisian'       => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except('_token', '_method');

        // Calculate status_kelengkapan FIRST
        $data['status_kelengkapan'] = Server::hitungStatusKelengkapan($data);

        // Determine status_locked based on admin's manual status choice from form
        // We need to check the original status value BEFORE removing it from $data
        $formStatus = $data['status'] ?? null;
        // Remove 'status' from data if it's 'automatic' to prevent SQL error
        // 'automatic' is a pseudo-value used only to set status_locked = false
        if (isset($data['status']) && $data['status'] === 'automatic') {
            unset($data['status']);
        }

        // Update server with base data (excluding status_locked for mass assignment)
        $server->update($data);

        // Determine status_locked based on admin's manual status choice from form
        // If admin selects 'Non-Aktif' or 'Maintenance' -> lock status (status_locked = true)
        // If admin selects 'Aktif', 'Pending', or 'automatic' -> unlock status (status_locked = false)
        // This allows admin to override automatic status synchronization or return to automatic mode
        if (isset($formStatus)) {
            if ($formStatus === 'Non-Aktif' || $formStatus === 'Maintenance') {
                $server->status_locked = true;
            } else {
                // 'Aktif', 'Pending', or 'automatic' -> unlock for automatic synchronization
                $server->status_locked = false;
            }
            // Save the server with the correct status_locked value
            $server->save();
        }

        // ============= UPLOAD GAMBAR =============
        $hasNewFile = $request->hasFile('gambar_rack');
        $removeImage = $request->has('remove_image') && $request->remove_image == '1';

        if ($hasNewFile) {
            $file = $request->file('gambar_rack');

            if ($file->isValid()) {
                // Hapus gambar lama jika ada
                if ($server->gambar_rack && Storage::disk('public')->exists($server->gambar_rack)) {
                    Storage::disk('public')->delete($server->gambar_rack);
                    Log::info('Gambar lama dihapus: ' . $server->gambar_rack);
                }

                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $path = $file->storeAs('rack_images', $filename, 'public');
                $server->gambar_rack = $path;

                Log::info('Gambar baru diupload: ' . $path);

                // Reset remove_image karena ada file baru
                $removeImage = false;
            } else {
                return redirect()->back()->with('error', 'File gambar tidak valid.')->withInput();
            }
        }

        // Hapus gambar jika diminta DAN tidak ada file baru
        if ($removeImage) {
            if ($server->gambar_rack && Storage::disk('public')->exists($server->gambar_rack)) {
                Storage::disk('public')->delete($server->gambar_rack);
                Log::info('Gambar dihapus via request: ' . $server->gambar_rack);
            }
            $server->gambar_rack = null;
        }
        // =========================================

        // Sync status berdasarkan status_kelengkapan (jika belum dikunci manual)
        $server->syncStatusFromKelengkapan();
        $server->save();

        return redirect()->route('server.index')->with('success', 'Server berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $server = Server::findOrFail($id);

        // ============= HAPUS GAMBAR =============
        if ($server->gambar_rack && Storage::disk('public')->exists($server->gambar_rack)) {
            Storage::disk('public')->delete($server->gambar_rack);
            Log::info('Gambar dihapus saat delete server: ' . $server->gambar_rack);
        }
        // =========================================

        $server->delete();

        return redirect()->route('server.index')->with('success', 'Server berhasil dihapus.');
    }

    // ============= METHOD UNTUK HAPUS GAMBAR =============
    public function removeImage($id)
    {
        try {
            $server = Server::findOrFail($id);

            if ($server->gambar_rack && Storage::disk('public')->exists($server->gambar_rack)) {
                Storage::disk('public')->delete($server->gambar_rack);
                $server->gambar_rack = null;
                $server->save();

                Log::info('Gambar berhasil dihapus via AJAX: ' . $server->id);

                return response()->json([
                    'success' => true,
                    'message' => 'Gambar berhasil dihapus'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gambar tidak ditemukan'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error hapus gambar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    // =====================================================

    /**
     * Form khusus admin untuk melengkapi data teknis server yang
     * didaftarkan user (masih menggunakan form "inputdata" yang sama
     * dengan create/edit, supaya tidak ada view ganda yang bentrok).
     */
    public function lengkapi($id)
    {
        $server = Server::findOrFail($id);

        return view('inputdata', compact('server'));
    }

    /**
     * Simpan hasil "melengkapi data" oleh admin.
     * Logic-nya sama persis dengan update() (validasi penuh + hitung ulang
     * status_kelengkapan), jadi cukup delegasikan ke situ supaya tidak ada
     * duplikasi/​potensi bentrok logic.
     */
    public function updateLengkapi(Request $request, $id)
    {
        return $this->update($request, $id);
    }

    public function exportPdf($id)
    {
        $server = Server::findOrFail($id);
        $pdf = Pdf::loadView('pdf.detailserver', compact('server'));
        return $pdf->download('detail-server-' . $server->id . '.pdf');
    }

    /**
     * Unlock status and sync with data completeness - for admin one-click fix
     */
    public function unlockAndSync(Request $request, $id)
    {
        $server = Server::findOrFail($id);

        // Unlock the status
        $server->status_locked = false;

        // Sync status based on data completeness
        $server->syncStatusFromKelengkapan();

        // Save the changes
        $server->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Status berhasil dibuka dan disinkronisasi dengan data kelengkapan.');
    }
}
