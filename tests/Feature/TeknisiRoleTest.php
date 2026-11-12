<?php

namespace Tests\Feature;

use App\Models\MasterData;
use App\Models\Server;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeknisiRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        // Pastikan database yang dipakai test adalah sim_tik_test
        $dbName = config('database.connections.mysql.database');
        if ($dbName !== 'sim_tik_test') {
            $this->markTestSkipped("Testing harus menggunakan database sim_tik_test, saat ini: {$dbName}");
        }
    }

    public function test_teknisi_can_access_dashboard_and_server_index(): void
    {
        $teknisi = User::factory()->create(['role' => 'teknisi']);

        $response = $this->actingAs($teknisi)->get(route('manajemen-server'));
        $response->assertOk();

        $responseServer = $this->actingAs($teknisi)->get(route('server.index'));
        $responseServer->assertOk();
    }

    public function test_teknisi_can_create_and_store_server_kominfo_and_colocation(): void
    {
        $teknisi = User::factory()->create(['role' => 'teknisi']);

        // Master data yang dibutuhkan
        MasterData::create(['kategori' => 'jenis_perangkat', 'value' => 'Blade Server', 'is_aktif' => true]);
        MasterData::create(['kategori' => 'merk_perangkat', 'value' => 'Dell', 'is_aktif' => true]);
        MasterData::create(['kategori' => 'tipe_perangkat', 'value' => 'RACK MOUNT', 'is_aktif' => true]);
        MasterData::create(['kategori' => 'status_kepemilikan', 'value' => 'Kominfo', 'is_aktif' => true]);
        MasterData::create(['kategori' => 'status_kepemilikan', 'value' => 'Colocation', 'is_aktif' => true]);
        MasterData::create(['kategori' => 'pemilik_perangkat', 'value' => 'Dinas Kesehatan', 'is_aktif' => true]);
        MasterData::create(['kategori' => 'nomor_rack', 'value' => 'R1', 'is_aktif' => true]);
        MasterData::create(['kategori' => 'kondisi_tipe', 'value' => 'Standard', 'is_aktif' => true]);
        MasterData::create(['kategori' => 'kondisi_status', 'value' => 'Baru', 'is_aktif' => true]);

        // 1. Store Kominfo Server
        $responseKominfo = $this->actingAs($teknisi)->post(route('server.store'), [
            'nama_perangkat' => 'Server Kominfo Teknisi',
            'jenis_perangkat' => 'Blade Server',
            'serial_number' => 'SN-KOM-01',
            'merk_perangkat' => 'Dell',
            'type' => 'PowerEdge',
            'kondisi_tipe' => 'Standard',
            'kondisi_status' => 'Baru',
            'spesifikasi' => 'RAM 64GB',
            'tipe_perangkat' => 'RACK MOUNT',
            'status_kepemilikan' => 'Kominfo',
            'ip_server' => '10.0.1.10',
            'ip_vps' => '',
            'status' => 'Aktif',
            'ukuran_hdd' => '512 GB',
            'ukuran_ram' => '16 GB',
            'nomor_rack' => 'R1',
            'jumlah_core' => 8,
            'peruntukan' => 'Web Portal',
            'jam_pengisian' => '2026-11-12 10:00:00',
        ]);
        $responseKominfo->assertRedirect(route('detailserver', ['id' => 1]));

        $serverKominfo = Server::where('nama_perangkat', 'Server Kominfo Teknisi')->first();
        $this->assertNotNull($serverKominfo);
        $this->assertStringStartsWith('KO', $serverKominfo->kode_perangkat);

        // 2. Store Colocation Server
        $responseColocation = $this->actingAs($teknisi)->post(route('server.store'), [
            'nama_perangkat' => 'Server Dinkes Teknisi',
            'jenis_perangkat' => 'Blade Server',
            'serial_number' => 'SN-CO-01',
            'merk_perangkat' => 'Dell',
            'type' => 'PowerEdge',
            'kondisi_tipe' => 'Standard',
            'kondisi_status' => 'Baru',
            'spesifikasi' => 'RAM 32GB',
            'tipe_perangkat' => 'RACK MOUNT',
            'status_kepemilikan' => 'Colocation',
            'pemilik_perangkat' => 'Dinas Kesehatan',
            'nama_pengirim' => 'Budi Dinkes',
            'nama_penerima' => 'Teknisi Jaga',
            'ip_server' => '10.0.2.10',
            'ip_vps' => '',
            'status' => 'Aktif',
            'ukuran_hdd' => '256 GB',
            'ukuran_ram' => '8 GB',
            'nomor_rack' => 'R1',
            'jumlah_core' => 4,
            'peruntukan' => 'Sistem Antrean Dinkes',
            'jam_pengisian' => '2026-11-12 10:00:00',
        ]);
        $responseColocation->assertRedirect(route('detailserver', ['id' => 2]));

        $serverColocation = Server::where('nama_perangkat', 'Server Dinkes Teknisi')->first();
        $this->assertNotNull($serverColocation);
        $this->assertStringStartsWith('CO', $serverColocation->kode_perangkat);
        $this->assertEquals('Dinas Kesehatan', $serverColocation->pemilik_perangkat);
    }

    public function test_teknisi_can_edit_update_destroy_servers_owned_by_other_users(): void
    {
        $otherUser = User::factory()->create(['role' => 'user']);
        $teknisi = User::factory()->create(['role' => 'teknisi']);

        $server = Server::create([
            'user_id' => $otherUser->id,
            'nama_perangkat' => 'Server Milik OPD Lain',
            'jenis_perangkat' => 'Server',
            'serial_number' => 'SN-OPD-99',
            'merk_perangkat' => 'HP',
            'type' => 'ProLiant',
            'kondisi_tipe' => 'Standard',
            'kondisi_status' => 'Baru',
            'spesifikasi' => 'Spec',
            'tipe_perangkat' => 'RACK MOUNT',
            'status_kepemilikan' => 'Kominfo',
            'ip_server' => '10.10.10.10',
            'status' => 'Non-Aktif', // Non-aktif agar bisa di-destroy
            'status_locked' => true,
            'ukuran_hdd' => '256 GB',
            'ukuran_ram' => '8 GB',
            'nomor_rack' => 'R1',
            'jumlah_core' => 4,
            'peruntukan' => 'Testing',
            'jam_pengisian' => '2026-11-12',
        ]);

        // Teknisi can view detail
        $this->actingAs($teknisi)->get(route('detailserver', $server->id))->assertOk();

        // Teknisi can edit
        $this->actingAs($teknisi)->get(route('server.edit', $server->id))->assertOk();

        // Teknisi can lengkapi
        $this->actingAs($teknisi)->get(route('server.lengkapi', $server->id))->assertOk();

        // Teknisi can destroy non-aktif server
        $this->actingAs($teknisi)->delete(route('server.destroy', $server->id))
            ->assertRedirect(route('server.index'));

        $this->assertDatabaseMissing('servers', ['id' => $server->id]);
    }

    public function test_teknisi_is_forbidden_from_admin_only_routes(): void
    {
        $teknisi = User::factory()->create(['role' => 'teknisi']);

        // Forbidden routes (403)
        $this->actingAs($teknisi)->get('/admin/manajemen-server')->assertForbidden();
        $this->actingAs($teknisi)->get(route('server.dokumen.index'))->assertForbidden();
        $this->actingAs($teknisi)->get(route('master-data.index'))->assertForbidden();
        $this->actingAs($teknisi)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($teknisi)->get(route('admin.approvals.index'))->assertForbidden();
        $this->actingAs($teknisi)->get(route('admin.registration-codes.index'))->assertForbidden();
    }

    public function test_teknisi_login_redirect_and_sidebar_visibility(): void
    {
        \Illuminate\Support\Facades\Config::set('services.recaptcha.enabled', false);

        $teknisi = User::factory()->create([
            'email' => 'teknisi.test@situbondo.go.id',
            'password' => bcrypt('password123'),
            'role' => 'teknisi',
            'status' => 'active',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'teknisi.test@situbondo.go.id',
            'password' => 'password123',
        ]);
        $response->assertRedirect(route('manajemen-server', absolute: false));

        $view = $this->actingAs($teknisi)->get(route('manajemen-server'));
        $view->assertSee('Teknisi Server');
        $view->assertSee(route('server.index'));
        $view->assertDontSee('Manajemen Dokumen');
        $view->assertDontSee('Master Data');
        $view->assertDontSee('User Management');
        $view->assertDontSee('Persetujuan User');
        $view->assertDontSee('Kode Registrasi');
    }
}
