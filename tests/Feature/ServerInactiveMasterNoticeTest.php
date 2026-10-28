<?php

namespace Tests\Feature;

use App\Models\MasterData;
use App\Models\Server;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServerInactiveMasterNoticeTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_form_warns_about_inactive_master_without_changing_server_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $jenisMaster = MasterData::create([
            'kategori' => 'jenis_perangkat',
            'value' => 'Router Khusus',
            'label' => 'Router Khusus',
            'is_aktif' => true,
        ]);
        $tipeMaster = MasterData::create([
            'kategori' => 'tipe_perangkat',
            'value' => 'BLADE',
            'label' => 'BLADE',
            'is_aktif' => true,
        ]);

        $server = Server::forceCreate([
            'nama_perangkat' => 'Server Uji',
            'jenis_perangkat' => 'Router Khusus',
            'tipe_perangkat' => 'RACK MOUNT',
            'status' => 'Aktif',
            'status_locked' => true,
        ]);

        $jenisMaster->update(['is_aktif' => false]);
        $tipeMaster->update(['is_aktif' => false]);

        $this->get(route('server.edit', $server))
            ->assertOk()
            ->assertSee('Master Data Non-Aktif')
            ->assertSee('Jenis perangkat: &quot;Router Khusus&quot;', false)
            ->assertSee('Router Khusus (Non-Aktif)');

        $server->refresh();
        $this->assertSame('Aktif', $server->status);

        $this->followingRedirects()
            ->from(route('server.edit', $server))
            ->put(route('server.update', $server), [
                'nama_perangkat' => 'Nama yang tidak boleh tersimpan',
                'jenis_perangkat' => 'Router Khusus',
                'serial_number' => 'SN-001',
                'merk_perangkat' => 'Merk Uji',
                'type' => 'Tipe Uji',
                'kondisi_tipe' => 'Standard',
                'kondisi_status' => 'Baru',
                'spesifikasi' => 'Spesifikasi uji',
                'tipe_perangkat' => 'RACK MOUNT',
                'status_kepemilikan' => 'Kominfo',
                'ip_server' => '192.168.1.10',
                'ip_vps' => '',
                'status' => 'Aktif',
                'ukuran_hdd' => '256 GB',
                'ukuran_ram' => '8 GB',
                'nomor_rack' => 'R1',
                'jumlah_core' => 4,
                'peruntukan' => 'Uji',
                'nama_pengirim' => '',
                'nama_penerima' => '',
                'jam_pengisian' => now()->format('Y-m-d H:i:s'),
            ])
            ->assertOk()
            ->assertSee('Master Data Non-Aktif')
            ->assertSee('Jenis perangkat: &quot;Router Khusus&quot;', false);

        $this->assertSame('Aktif', $server->fresh()->status);
        $this->assertSame('Server Uji', $server->fresh()->nama_perangkat);
    }
}
