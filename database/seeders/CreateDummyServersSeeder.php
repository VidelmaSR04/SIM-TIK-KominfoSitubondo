<?php

namespace Database\Seeders;

use App\Models\MasterData;
use App\Models\Server;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use RuntimeException;

class CreateDummyServersSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@situbondo.go.id')->first();
        $user = User::where('email', 'user@situbondo.go.id')->first();

        if (!$admin || !$user) {
            throw new RuntimeException('Jalankan CreateDummyUsersSeeder terlebih dahulu untuk membuat user admin dan user OPD.');
        }

        $opdList = MasterData::where('kategori', 'pemilik_perangkat')
            ->aktif()
            ->orderBy('urutan')
            ->pluck('value')
            ->all();

        if ($opdList === []) {
            throw new RuntimeException('Data Master kategori Pemilik Perangkat belum tersedia. Jalankan MasterDataSeeder terlebih dahulu.');
        }

        $jenisPerangkat = [
            ['jenis' => 'Server', 'merk' => 'Dell', 'tipe' => 'Rack Server', 'bentuk' => 'Rack Mount'],
            ['jenis' => 'Router', 'merk' => 'Cisco', 'tipe' => 'Enterprise Router', 'bentuk' => 'Rack Mount'],
            ['jenis' => 'Switch', 'merk' => 'HPE', 'tipe' => 'Managed Switch', 'bentuk' => 'Rack Mount'],
            ['jenis' => 'Server', 'merk' => 'Lenovo', 'tipe' => 'Tower Server', 'bentuk' => 'Tower'],
            ['jenis' => 'Server', 'merk' => 'Supermicro', 'tipe' => 'Blade Server', 'bentuk' => 'Blade'],
        ];
        $ram = ['8 GB', '16 GB', '32 GB', '64 GB', '128 GB'];
        $storage = ['500 GB', '1 TB', '2 TB', '4 TB', '8 TB'];
        $now = Carbon::now();

        foreach (['Kominfo', 'Colocation'] as $ownership) {
            for ($index = 1; $index <= 50; $index++) {
                $number = str_pad((string) $index, 3, '0', STR_PAD_LEFT);
                $spec = $jenisPerangkat[($index - 1) % count($jenisPerangkat)];
                $opd = $ownership === 'Colocation'
                    ? $opdList[($index - 1) % count($opdList)]
                    : null;
                $date = $now->copy()->subDays($index - 1);
                $serial = 'DUMMY-' . ($ownership === 'Kominfo' ? 'KO' : 'OPD') . '-' . $number;

                $attributes = [
                    'user_id' => $ownership === 'Kominfo' ? $admin->id : $user->id,
                    'nama_perangkat' => $ownership === 'Kominfo'
                        ? "Perangkat Dummy Kominfo {$number}"
                        : "Perangkat Dummy OPD {$number} - {$opd}",
                    'jenis_perangkat' => $spec['jenis'],
                    'merk_perangkat' => $spec['merk'],
                    'type' => $spec['tipe'],
                    'tipe_perangkat' => $spec['bentuk'],
                    'serial_number' => $serial,
                    'ip_server' => ($ownership === 'Kominfo' ? '10.50.0.' : '10.51.0.') . $index,
                    'ip_vps' => ($ownership === 'Kominfo' ? '172.20.0.' : '172.21.0.') . $index,
                    'status_kepemilikan' => $ownership,
                    'pemilik_perangkat' => $opd,
                    'kondisi_tipe' => $index % 4 === 0 ? 'High Performance' : 'Standard',
                    'kondisi_status' => $index % 5 === 0 ? 'Bekas' : 'Baru',
                    'spesifikasi' => "{$spec['merk']} {$spec['tipe']}, {$ram[($index - 1) % count($ram)]} RAM",
                    'ukuran_ram' => $ram[($index - 1) % count($ram)],
                    'ukuran_hdd' => $storage[($index - 1) % count($storage)],
                    'nomor_rack' => 'R' . ((int) ceil($index / 10)),
                    'jumlah_core' => [4, 8, 16, 24, 32][($index - 1) % 5],
                    'peruntukan' => $ownership === 'Kominfo' ? 'Layanan Pusat Data' : "Layanan Operasional {$opd}",
                    'nama_pengirim' => $ownership === 'Kominfo' ? $admin->name : $user->name,
                    'nama_penerima' => $ownership === 'Kominfo' ? $admin->name : $user->name,
                    'tanggal_input' => $date->toDateString(),
                    'jam_pengisian' => $date,
                    'status_kelengkapan' => 'lengkap',
                    'status_locked' => false,
                    'status' => 'Aktif',
                ];

                $server = Server::where('serial_number', $serial)->first();
                $attributes['kode_perangkat'] = $server?->getRawOriginal('kode_perangkat')
                    ?: Server::generateKodePerangkat($ownership, $date->toDateString());

                Server::updateOrCreate(
                    ['serial_number' => $serial],
                    $attributes
                );
            }
        }

        $this->command?->info('Dummy perangkat siap: 50 Kominfo dan 50 OPD (total 100).');
    }
}
