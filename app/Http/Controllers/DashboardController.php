<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        // TODO: ganti angka-angka di bawah ini dengan query asli
        // dari model/database begitu tabelnya sudah siap.
        // Contoh nanti: $totalDevices = Server::count() + Vps::count();

        // ==== Summary Cards ====
        $totalDevices     = 1248;
        $percentageGrowth = 12;

        $totalApplications = 342;
        $newApps            = 5;

        $totalVps  = 86;
        $vpsUsage  = 75; // dalam persen

        $totalDomains    = 112;
        $expiringDomains = 3;

        // ==== Mini stats di bawah chart rack ====
        $rackMount  = 324;
        $tower      = 45;
        $kominfo    = 210;
        $colocation = 159;

        // ==== Data tabel (dipakai partial data-table) ====
        $servers = [
            ['id' => 'SRV-001', 'nama' => 'Node-DB-Master-01', 'ip_server' => '192.168.10.101', 'ip_vps' => '10.0.5.12', 'status' => 'Aktif'],
            ['id' => 'SRV-002', 'nama' => 'Web-Frontend-02', 'ip_server' => '192.168.10.102', 'ip_vps' => '-', 'status' => 'Aktif'],
            ['id' => 'SRV-003', 'nama' => 'Storage-Backup-A', 'ip_server' => '192.168.20.55', 'ip_vps' => '10.0.8.44', 'status' => 'Maintenance'],
            ['id' => 'SRV-004', 'nama' => 'API-Gateway-Core', 'ip_server' => '192.168.10.200', 'ip_vps' => '10.0.5.99', 'status' => 'Offline'],
        ];

        $cpanels = [
            ['id' => 1, 'nama' => 'Kecamatan Arjasa', 'domain' => 'arjasa.situbondokab.go.id', 'ip_local' => '192.168.99.72', 'ip_public' => '103.165.156.249', 'status' => 'Active'],
            ['id' => 2, 'nama' => 'Kecamatan Asembagus', 'domain' => 'asembagus.situbondokab.go.id', 'ip_local' => '192.168.99.72', 'ip_public' => '103.165.156.249', 'status' => 'Active'],
            ['id' => 3, 'nama' => 'Bakesbangpol', 'domain' => 'bakesbangpol.situbondokab.go.id', 'ip_local' => '192.168.99.72', 'ip_public' => '103.76.175.182', 'status' => 'Active'],
            ['id' => 4, 'nama' => 'Kecamatan Banyuglugur', 'domain' => 'banyuglugur.situbondokab.go.id', 'ip_local' => '192.168.99.72', 'ip_public' => '103.165.156.249', 'status' => 'Active'],
            ['id' => 5, 'nama' => 'Kecamatan Banyuputih', 'domain' => 'banyuputih.situbondokab.go.id', 'ip_local' => '192.168.99.72', 'ip_public' => '103.165.156.249', 'status' => 'Active'],
        ];

        $aplikasis = [
            ['id' => 1, 'nama' => 'ALADIN (Aplikasi Adminduk Online)', 'ip_local' => '-', 'ip_public' => '103.165.156.229', 'pic' => 'Zakiatul Darojati, A.Md.', 'status' => 'Sudah Asesmen'],
            ['id' => 2, 'nama' => 'SKEMA (Survey Kepuasan Masyarakat)', 'ip_local' => '192.168.99.94', 'ip_public' => '103.165.156.249', 'pic' => 'Hosnol Fawaid, S.Kom.', 'status' => 'Sudah Asesmen'],
            ['id' => 3, 'nama' => 'Aplikasi Presensi Kabupaten Situbondo (APRESIASI)', 'ip_local' => '-', 'ip_public' => '103.165.156.245', 'pic' => '-', 'status' => 'Sudah Asesmen'],
            ['id' => 4, 'nama' => 'Website DPRD', 'ip_local' => '192.168.99.72', 'ip_public' => '103.165.156.249', 'pic' => '-', 'status' => 'Belum Asesmen'],
            ['id' => 5, 'nama' => 'Website Layanan Tourist Information Center (TIC)', 'ip_local' => '192.168.99.72', 'ip_public' => '103.165.156.249', 'pic' => '-', 'status' => 'Belum Asesmen'],
        ];

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
            'servers',
            'cpanels',
            'aplikasis'
        ));
    }
}