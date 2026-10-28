<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\Cpanel;
use App\Models\Aplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ManajemenServerController extends Controller
{
    public function index(Request $request)
    {
        // Determine if user is admin or kepala_bidang_tik
        $isAdmin = Auth::check() && in_array(Auth::user()->role, ['admin', 'kepala_bidang_tik']);

        // ==== Summary Cards ====
        $totalDevices = $isAdmin ? Server::count() : Server::where('user_id', Auth::id())->count();
        $totalApplications = Aplikasi::count();
        $totalVps = $isAdmin ? Server::where('tipe_perangkat', 'VPS')->count() : Server::where('user_id', Auth::id())->where('tipe_perangkat', 'VPS')->count();
        $totalDomains = Cpanel::count();

        // Hitung pertumbuhan (contoh: server baru bulan ini vs bulan lalu)
        $currentMonth = $isAdmin ? Server::whereMonth('created_at', now()->month)->count() : Server::where('user_id', Auth::id())->whereMonth('created_at', now()->month)->count();
        $lastMonth = $isAdmin ? Server::whereMonth('created_at', now()->subMonth()->month)->count() : Server::where('user_id', Auth::id())->whereMonth('created_at', now()->subMonth()->month)->count();
        $percentageGrowth = $lastMonth > 0 ? round((($currentMonth - $lastMonth) / $lastMonth) * 100) : 0;

        // Aplikasi baru dalam 30 hari terakhir
        $newApps = Aplikasi::where('created_at', '>=', now()->subDays(30))->count();

        $vpsUsage = 0; // bisa dikembangkan nanti
        $expiringDomains = 0; // bisa dikembangkan nanti

        // ==== Grafik RACK ====
        $rackRaw = $isAdmin ? Server::select('nomor_rack', DB::raw('count(*) as total'))
                         ->whereNotNull('nomor_rack')
                         ->groupBy('nomor_rack')
                         ->get()
                         ->keyBy('nomor_rack') :
                         Server::select('nomor_rack', DB::raw('count(*) as total'))
                         ->whereNotNull('nomor_rack')
                         ->where('user_id', Auth::id())
                         ->groupBy('nomor_rack')
                         ->get()
                         ->keyBy('nomor_rack');

        $rackLabels = ['R1', 'R2', 'R3', 'R4', 'R5', 'R6', 'R7', 'R8'];
        $rackData = [];
        $maxValue = 0;

        foreach ($rackLabels as $label) {
            $value = $rackRaw->get($label)?->total ?? 0;
            $rackData[$label] = $value;
            if ($value > $maxValue) $maxValue = $value;
        }

        $maxValue = max($maxValue, 1);

        // ==== Mini stats di bawah chart rack ====
        $rackMount = $isAdmin ? Server::where('tipe_perangkat', 'RACK MOUNT')->count() : Server::where('user_id', Auth::id())->where('tipe_perangkat', 'RACK MOUNT')->count();
        $tower     = $isAdmin ? Server::where('tipe_perangkat', 'TOWER')->count() : Server::where('user_id', Auth::id())->where('tipe_perangkat', 'TOWER')->count();
        $kominfo   = $isAdmin ? Server::where('status_kepemilikan', 'Kominfo')->count() : Server::where('user_id', Auth::id())->where('status_kepemilikan', 'Kominfo')->count();
        $colocation= $isAdmin ? Server::where('status_kepemilikan', 'Colocation')->count() : Server::where('user_id', Auth::id())->where('status_kepemilikan', 'Colocation')->count();

        // ==== Data tabel ====
        $perPage = in_array((int) $request->input('perPage'), [10, 25, 50], true)
            ? (int) $request->input('perPage')
            : 10;

        $servers = Server::visibleTo(Auth::user())
            ->filter($request->input('search'))
            ->orderByRaw("COALESCE(SUBSTRING(kode_perangkat, 3), '') desc")
            ->orderByDesc('id');
        $servers = $servers->paginate($perPage, ['*'], 'server_page')->withQueryString();
        $cpanels = Cpanel::latest()->paginate(10, ['*'], 'cpanel_page')->withQueryString();
        $aplikasis = Aplikasi::latest()->paginate(10, ['*'], 'aplikasi_page')->withQueryString();

        return view('manajemen-server', compact(
            'totalDevices',
            'percentageGrowth',
            'totalApplications',
            'newApps',
            'totalVps',
            'vpsUsage',
            'totalDomains',
            'expiringDomains',
            'rackMount',
            'tower',
            'kominfo',
            'colocation',
            'rackData',
            'maxValue',
            'servers',
            'cpanels',
            'aplikasis'
        ));
    }
}