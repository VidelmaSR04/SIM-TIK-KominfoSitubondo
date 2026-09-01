<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\Cpanel;
use App\Models\Aplikasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ManajemenServerController extends Controller
{
    public function index()
    {
        // Determine if user is admin
        $isAdmin = Auth::check() && Auth::user()->isAdmin();

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
        $colocation= $isAdmin ? Server::where('status_kepemilikan', 'OPD Lain')->count() : Server::where('user_id', Auth::id())->where('status_kepemilikan', 'OPD Lain')->count();

        // ==== Data tabel ====
        $servers = $isAdmin ? Server::latest()->paginate(10) : Server::where('user_id', Auth::id())->latest()->paginate(10);
        $cpanels = Cpanel::latest()->paginate(10);
        $aplikasis = Aplikasi::latest()->paginate(10);

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