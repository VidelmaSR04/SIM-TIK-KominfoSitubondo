<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Server;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class CreateDummyServersSeeder extends Seeder
{
    public function run(): void
    {
        // Get users for assignment
        $admin = User::where('email', 'admin@situbondo.go.id')->first();
        $user = User::where('email', 'user@situbondo.go.id')->first();
        $kepala = User::where('email', 'kepalatik@situbondo.go.id')->first();

        if (!$admin || !$user) {
            $this->command->info('Required users not found. Please run CreateDummyUsersSeeder first.');
            return;
        }

        // Data for Kominfo servers (5)
        $kominfoData = [
            [
                'nama_perangkat' => 'Server Kominfo 01',
                'jenis_perangkat' => 'server',
                'merk_perangkat' => 'Dell',
                'serial_number' => 'SN001KF',
                'ip_server' => '192.168.10.1',
                'ip_vps' => '',
                'status_kepemilikan' => 'Kominfo',
                'pemilik_perangkat' => null,
                'nama_pengirim' => 'Admin Sistem',
                'nama_penerima' => null,
                'tanggal_input' => '2026-09-01',
                'ukuran_ram' => '32 GB',
                'ukuran_hdd' => '2 TB',
                'jumlah_core' => '16',
                'kondisi_tipe' => 'Standard',
                'kondisi_status' => 'Baru',
                'spesifikasi' => 'Xeon Silver 4214, 128GB RAM',
                'tipe_perangkat' => 'RACK MOUNT',
                'peruntukan' => 'Pusat Data',
                'type' => 'Rack Server',
            ],
            [
                'nama_perangkat' => 'Server Kominfo 02',
                'jenis_perangkat' => 'server',
                'merk_perangkat' => 'HP',
                'serial_number' => 'SN002KF',
                'ip_server' => '192.168.10.2',
                'ip_vps' => '',
                'status_kepemilikan' => 'Kominfo',
                'pemilik_perangkat' => null,
                'nama_pengirim' => 'Admin Sistem',
                'nama_penerima' => null,
                'tanggal_input' => '2026-09-02',
                'ukuran_ram' => '64 GB',
                'ukuran_hdd' => '4 TB',
                'jumlah_core' => '24',
                'kondisi_tipe' => 'High Performance',
                'kondisi_status' => 'Baru',
                'spesifikasi' => 'Xeon Gold 6230, 256GB RAM',
                'tipe_perangkat' => 'RACK MOUNT',
                'peruntukan' => 'Cadangan Data',
                'type' => 'Rack Server',
            ],
            [
                'nama_perangkat' => 'Server Kominfo 03',
                'jenis_perangkat' => 'server',
                'merk_perangkat' => 'Lenovo',
                'serial_number' => 'SN003KF',
                'ip_server' => '192.168.10.3',
                'ip_vps' => '',
                'status_kepemilikan' => 'Kominfo',
                'pemilik_perangkat' => null,
                'nama_pengirim' => 'Admin Sistem',
                'nama_penerima' => null,
                'tanggal_input' => '2026-09-03',
                'ukuran_ram' => '16 GB',
                'ukuran_hdd' => '1 TB',
                'jumlah_core' => '8',
                'kondisi_tipe' => 'Standard',
                'kondisi_status' => 'Bekas',
                'spesifikasi' => 'Xeon E5-2620, 64GB RAM',
                'tipe_perangkat' => 'TOWER',
                'peruntukan' => 'Development',
                'type' => 'Tower Server',
            ],
            [
                'nama_perangkat' => 'Server Kominfo 04',
                'jenis_perangkat' => 'server',
                'merk_perangkat' => 'Supermicro',
                'serial_number' => 'SN004KF',
                'ip_server' => '192.168.10.4',
                'ip_vps' => '',
                'status_kepemilikan' => 'Kominfo',
                'pemilik_perangkat' => null,
                'nama_pengirim' => 'Admin Sistem',
                'nama_penerima' => null,
                'tanggal_input' => '2026-09-04',
                'ukuran_ram' => '128 GB',
                'ukuran_hdd' => '8 TB',
                'jumlah_core' => '32',
                'kondisi_tipe' => 'High Performance',
                'kondisi_status' => 'Baru',
                'spesifikasi' => 'Dual Xeon Platinum, 512GB RAM',
                'tipe_perangkat' => 'RACK MOUNT',
                'peruntukan' => 'Komputasi Tinggi',
                'type' => 'Blade Server',
            ],
            [
                'nama_perangkat' => 'Server Kominfo 05',
                'jenis_perangkat' => 'server',
                'merk_perangkat' => 'Cisco',
                'serial_number' => 'SN005KF',
                'ip_server' => '192.168.10.5',
                'ip_vps' => '',
                'status_kepemilikan' => 'Kominfo',
                'pemilik_perangkat' => null,
                'nama_pengirim' => 'Admin Sistem',
                'nama_penerima' => null,
                'tanggal_input' => '2026-09-05',
                'ukuran_ram' => '8 GB',
                'ukuran_hdd' => '500 GB',
                'jumlah_core' => '4',
                'kondisi_tipe' => 'Standard',
                'kondisi_status' => 'Baru',
                'spesifikasi' => 'Small form factor',
                'tipe_perangkat' => 'RACK MOUNT',
                'peruntukan' => 'Edge Computing',
                'type' => 'Rack Server',
            ],
        ];

        // Data for Colocation/OPD servers (5)
        $opdList = [
            'Dinas Kesehatan',
            'Dinas Pendidikan',
            'Dinas Pekerjaan Umum',
            'Dinas Perhubungan',
            'Dinas Sosial',
        ];

        $opdData = [];
        foreach ($opdList as $index => $opd) {
            $opdData[] = [
                'nama_perangkat' => "Server $opd " . ($index + 1),
                'jenis_perangkat' => 'server',
                'merk_perangkat' => 'Dell',
                'serial_number' => "SN00" . ($index + 6) . "OP",
                'ip_server' => "192.168.20." . ($index + 1),
                'ip_vps' => '',
                'status_kepemilikan' => 'Colocation',
                'pemilik_perangkat' => $opd,
                'nama_pengirim' => 'User Biasa',
                'nama_penerima' => 'User Biasa',
                'tanggal_input' => '2026-09-' . str_pad($index + 6, 2, '0', STR_PAD_LEFT),
                'ukuran_ram' => '16 GB',
                'ukuran_hdd' => '1 TB',
                'jumlah_core' => '8',
                'kondisi_tipe' => 'Standard',
                'kondisi_status' => 'Baru',
                'spesifikasi' => 'Standard office server',
                'tipe_perangkat' => 'RACK MOUNT',
                'peruntukan' => 'Operasional ' . $opd,
                'type' => 'Rack Server',
            ];
        }

        // Combine all data
        $allData = array_merge($kominfoData, $opdData);

        foreach ($allData as $data) {
            // Determine user_id based on status_kepemilikan
            $userId = ($data['status_kepemilikan'] === 'Kominfo') ? $admin->id : $user->id;

            // Generate kode_perangkat
            $kode = Server::generateKodePerangkat(
                $data['status_kepemilikan'],
                $data['tanggal_input']
            );

            // Create server
            Server::create([
                'user_id' => $userId,
                'nama_perangkat' => $data['nama_perangkat'],
                'jenis_perangkat' => $data['jenis_perangkat'],
                'merk_perangkat' => $data['merk_perangkat'],
                'serial_number' => $data['serial_number'],
                'ip_server' => $data['ip_server'],
                'ip_vps' => $data['ip_vps'],
                'status_kepemilikan' => $data['status_kepemilikan'],
                'pemilik_perangkat' => $data['pemilik_perangkat'],
                'nama_pengirim' => $data['nama_pengirim'],
                'nama_penerima' => $data['nama_penerima'],
                'tanggal_input' => $data['tanggal_input'],
                'ukuran_ram' => $data['ukuran_ram'],
                'ukuran_hdd' => $data['ukuran_hdd'],
                'jumlah_core' => $data['jumlah_core'],
                'kondisi_tipe' => $data['kondisi_tipe'],
                'kondisi_status' => $data['kondisi_status'],
                'spesifikasi' => $data['spesifikasi'],
                'tipe_perangkat' => $data['tipe_perangkat'],
                'peruntukan' => $data['peruntukan'],
                'type' => $data['type'],
                'gambar_rack' => null,
                'status_kelengkapan' => 'lengkap',
                'status_locked' => false,
                'kode_perangkat' => $kode,
                // status will be synced via observer or we set manually
                'status' => 'Aktif', // since lengkap and not locked
            ]);
        }

        $this->command->info('Created 10 dummy servers (5 Kominfo, 5 OPD).');
    }
}