<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServerPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);

        $query = Server::where('status_kelengkapan', 'lengkap')
            ->whereNotNull('gambar_rack')               // hanya yang punya foto
            ->when($search, function ($query, $search) {
                return $query->where('nama_perangkat', 'like', "%{$search}%")
                    ->orWhere('pemilik_perangkat', 'like', "%{$search}%");
            });

        // If user is not admin, only show their own servers
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        $servers = $query->paginate($perPage);

        return view('server-foto.index', compact('servers'));
    }
}