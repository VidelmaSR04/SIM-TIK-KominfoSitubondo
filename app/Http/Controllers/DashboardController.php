<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\Cpanel;
use App\Models\Aplikasi;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ==== Summary Cards ====
        $totalDevices = Server::count();
        $totalApplications = Aplikasi::count();
        $totalVps = Server::where('tipe_perangkat', 'VPS')->count(); // sesuaikan jika kolom berbeda
        $totalDomains = Cpanel::count();

        // Hitung pertumbuhan (contoh: server baru bulan ini vs bulan lalu)
        $currentMonth = Server::whereMonth('created_at', now()->month)->count();
        $lastMonth = Server::whereMonth('created_at', now()->subMonth()->month)->count();
        $percentageGrowth = $lastMonth > 0 ? round((($currentMonth - $lastMonth) / $lastMonth) * 100) : 0;

        // Aplikasi baru dalam 30 hari terakhir
        $newApps = Aplikasi::where('created_at', '>=', now()->subDays(30))->count();

        // VPS Usage (contoh: rata-rata persentase penggunaan, jika ada kolom usage)
        // Untuk sementara statis 0 atau hitung dari data lain
        $vpsUsage = 0; // bisa dikembangkan nanti

        // Expiring domains (contoh: cpanel dengan tanggal kadaluarsa, jika ada)
        $expiringDomains = 0; // bisa dikembangkan nanti

        // ==== Grafik RACK ====
        // Kelompokkan berdasarkan nomor_rack, ambil total server per rack
        $rackRaw = Server::select('nomor_rack', DB::raw('count(*) as total'))
                         ->whereNotNull('nomor_rack')
                         ->groupBy('nomor_rack')
                         ->get()
                         ->keyBy('nomor_rack');

        // Definisikan label rack yang diinginkan (R1 - R8)
        $rackLabels = ['R1', 'R2', 'R3', 'R4', 'R5', 'R6', 'R7', 'R8'];
        $rackData = [];
        $maxValue = 0;

        foreach ($rackLabels as $label) {
            // Cari data berdasarkan label, jika tidak ada maka 0
            $value = $rackRaw->get($label)?->total ?? 0;
            $rackData[$label] = $value;
            if ($value > $maxValue) $maxValue = $value;
        }

        // Pastikan maxValue minimal 1 agar skala grafik tidak 0
        $maxValue = max($maxValue, 1);

        // ==== Mini stats di bawah chart rack ====
        $rackMount = Server::where('tipe_perangkat', 'RACK MOUNT')->count();
        $tower     = Server::where('tipe_perangkat', 'TOWER')->count();
        $kominfo   = Server::where('status_kepemilikan', 'Kominfo')->count();
        $colocation= Server::where('status_kepemilikan', 'OPD Lain')->count();

        // ==== Data tabel (dipakai partial data-table) ====
        // Gunakan paginate untuk masing-masing tabel, agar bisa ditampilkan dengan pagination
        $servers = Server::latest()->paginate(10);
        $cpanels = Cpanel::latest()->paginate(10);
        $aplikasis = Aplikasi::latest()->paginate(10);

        return view('dashboard', compact(
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