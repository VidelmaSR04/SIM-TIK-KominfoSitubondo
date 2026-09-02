<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ServerDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);

        $query = Server::where('status_kelengkapan', 'lengkap')
            ->when($search, function ($query, $search) {
                return $query->where('nama_perangkat', 'like', "%{$search}%")
                    ->orWhere('pemilik_perangkat', 'like', "%{$search}%");
            });

        // If user is not admin, only show their own servers
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        $servers = $query->paginate($perPage);

        return view('server-dokumen.index', compact('servers'));
    }

    /**
     * Show PDF inline for preview
     */
    public function streamPdf(Server $server)
    {
        // If user is not admin, verify ownership
        if (!Auth::check() || (Auth::user()->role !== 'admin' && $server->user_id !== Auth::id())) {
            abort(403, 'Unauthorized action.');
        }

        $pdf = Pdf::loadView('pdf.detailserver', compact('server'));
        return $pdf->stream('detail-server-' . $server->id . '.pdf');
    }

    /**
     * Download PDF
     */
    public function download(Server $server)
    {
        // If user is not admin, verify ownership
        if (!Auth::check() || (Auth::user()->role !== 'admin' && $server->user_id !== Auth::id())) {
            abort(403, 'Unauthorized action.');
        }

        $pdf = Pdf::loadView('pdf.detailserver', compact('server'));
        return $pdf->download('detail-server-' . $server->id . '.pdf');
    }
}