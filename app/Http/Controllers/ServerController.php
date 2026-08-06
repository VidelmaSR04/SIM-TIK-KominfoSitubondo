<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
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
            'gambar_rack' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
        Server::create($data);

        return redirect()->route('server.index')->with('success', 'Server berhasil ditambahkan.');
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
            'gambar_rack' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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

        // Upload gambar jika ada
        if ($request->hasFile('gambar_rack')) {
            // Hapus gambar lama jika ada
            if ($server->gambar_rack && Storage::disk('public')->exists($server->gambar_rack)) {
                Storage::disk('public')->delete($server->gambar_rack);
            }

            $file = $request->file('gambar_rack');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('rack_images', $filename, 'public');
            $data['gambar_rack'] = $path;
        }

        $server->update($data);

        return redirect()->route('server.index')->with('success', 'Server berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $server = Server::findOrFail($id);
        $server->delete();

        return redirect()->route('server.index')->with('success', 'Server berhasil dihapus.');
    }


    public function exportPdf($id)
    {
        $server = Server::findOrFail($id);
        $pdf = Pdf::loadView('pdf.detailserver', compact('server'));
        return $pdf->download('detail-server-' . $server->id . '.pdf');
    }
}
