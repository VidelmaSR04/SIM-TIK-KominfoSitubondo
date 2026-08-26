<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Tambahkan ini
use Barryvdh\DomPDF\Facade\Pdf;

class ServerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('perPage', 10);

        $servers = Server::when($search, function ($query, $search) {
            return $query->where('nama_perangkat', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%")
                ->orWhere('ip_server', 'like', "%{$search}%");
        })->paginate($perPage);

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
            'jenis_perangkat'     => 'nullable|in:router,switch,server',
            'serial_number'       => 'nullable|string|max:255',
            'merk_perangkat'      => 'nullable|string|max:255',
            'type'                => 'nullable|string|max:255',
            'kondisi_tipe'        => 'nullable|in:Standard,High Performance',
            'kondisi_status'      => 'nullable|in:Baru,Bekas',
            'spesifikasi'         => 'nullable|string',
            'tipe_perangkat'      => 'nullable|in:RACK MOUNT,TOWER,BLADE',
            'status_kepemilikan'  => 'nullable|in:Kominfo,OPD Lain',
            'pemilik_perangkat'   => 'nullable|string|max:255',
            'ip_server'           => 'nullable|ip',
            'ip_vps'              => 'nullable|ip',
            'status'              => 'nullable|in:Aktif,Non-Aktif,Maintenance',
            'ukuran_hdd'          => 'nullable|string|max:50',
            'ukuran_ram'          => 'nullable|string|max:50',
            'nomor_rack'          => 'nullable|string|max:50',
            'gambar_rack'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'jumlah_core'         => 'nullable|integer|min:1',
            'peruntukan'          => 'nullable|string|max:255',
            'nama_pengirim'       => 'nullable|string|max:255',
            'nama_penerima'       => 'nullable|string|max:255',
            'jam_pengisian'       => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except('_token');

        // Cek otomatis: lengkap kalau semua field wajib sudah terisi, kalau belum jadi 'dilengkapi'
        $data['status_kelengkapan'] = Server::hitungStatusKelengkapan($data);

        // ============= UPLOAD GAMBAR =============
        if ($request->hasFile('gambar_rack')) {
            $file = $request->file('gambar_rack');

            // Validasi tambahan untuk file
            if ($file->isValid()) {
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $path = $file->storeAs('rack_images', $filename, 'public');
                $data['gambar_rack'] = $path;

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

        Server::create($data);

        return redirect()->route('server.index')->with('success', 'Server berhasil ditambahkan. [DEBUG status_kelengkapan: ' . $data['status_kelengkapan'] . ']');
    }

    public function show($id)
    {
        $server = Server::findOrFail($id);
        return view('detailserver', compact('server'));
    }

    public function edit($id)
    {
        $server = Server::findOrFail($id);
        return view('inputdata', compact('server'));
    }

public function update(Request $request, $id)
{
    $server = Server::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'nama_perangkat'      => 'required|string|max:255',
        'jenis_perangkat'     => 'nullable|in:router,switch,server',
        'serial_number'       => 'nullable|string|max:255',
        'merk_perangkat'      => 'nullable|string|max:255',
        'type'                => 'nullable|string|max:255',
        'kondisi_tipe'        => 'nullable|in:Standard,High Performance',
        'kondisi_status'      => 'nullable|in:Baru,Bekas',
        'spesifikasi'         => 'nullable|string',
        'tipe_perangkat'      => 'nullable|in:RACK MOUNT,TOWER,BLADE',
        'status_kepemilikan'  => 'nullable|in:Kominfo,OPD Lain',
        'pemilik_perangkat'   => 'nullable|string|max:255',
        'ip_server'           => 'nullable|ip',
        'ip_vps'              => 'nullable|ip',
        'status'              => 'nullable|in:Aktif,Non-Aktif,Maintenance',
        'ukuran_hdd'          => 'nullable|string|max:50',
        'ukuran_ram'          => 'nullable|string|max:50',
        'nomor_rack'          => 'nullable|string|max:50',
        'gambar_rack'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'jumlah_core'         => 'nullable|integer|min:1',
        'peruntukan'          => 'nullable|string|max:255',
        'nama_pengirim'       => 'nullable|string|max:255',
        'nama_penerima'       => 'nullable|string|max:255',
        'jam_pengisian'       => 'nullable|date',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $data = $request->except('_token', '_method');

    // Cek otomatis: lengkap kalau semua field wajib sudah terisi, kalau belum jadi 'dilengkapi'
    $data['status_kelengkapan'] = Server::hitungStatusKelengkapan($data);

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
            $data['gambar_rack'] = $path;

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
        $data['gambar_rack'] = null;
    }
    // =========================================

    $server->update($data);

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

    public function exportPdf($id)
    {
        $server = Server::findOrFail($id);
        $pdf = Pdf::loadView('pdf.detailserver', compact('server'));
        return $pdf->download('detail-server-' . $server->id . '.pdf');
    }
}
