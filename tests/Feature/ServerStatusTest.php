<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServerStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_input_device_sets_status_kepemilikan_colocation_and_shows_correct_opd()
    {
        // login as user
        $user = User::factory()->create([
            'role' => 'user',
        ]);
        $this->actingAs($user);

        // Simulate submitting the user form (InputDataUserController)
        $response = $this->post(route('user.inputdatauser.store'), [
            'jenis' => 'server',
            'merk' => 'TestMerk',
            'dinas' => 'Dinas Kesehatan',
            'nama_pengirim' => 'Test Pengirim',
            'nama_penerima' => 'Test Penerima',
            'rack' => 'R01',
        ]);

        $response->assertRedirect(route('user.dashboarduser'));

        // Find the created server
        $server = Server::where('user_id', $user->id)->first();

        $this->assertNotNull($server);
        $this->assertEquals('Colocation', $server->status_kepemilikan);
        $this->assertEquals('Dinas Kesehatan', $server->pemilik_perangkat);
        $this->assertEquals('Dinas Kesehatan', $server->nama_opd);

        // Cleanup (handled by RefreshDatabase)
    }

    /** @test */
    public function editing_server_with_pending_status_and_unlocked_does_not_reset_to_non_aktif_when_all_fields_filled()
    {
        // login as admin (or any user with permission)
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin);

        // Create a server with status Pending, status_locked = false, missing required fields
        $server = Server::create([
            'user_id' => $admin->id,
            'nama_perangkat' => 'Test Server',
            'jenis_perangkat' => 'server',
            'merk_perangkat' => 'Test Merk',
            // missing required fields intentionally
            'status_kepemilikan' => 'Kominfo',
            'pemilik_perangkat' => 'Kominfo',
            'nama_pengirim' => 'Test',
            'nama_penerima' => 'Test',
            'status' => 'Pending',
            'status_locked' => false,
        ]);

        $this->assertEquals('Pending', $server->status);
        $this->assertFalse($server->status_locked);
        $this->assertEquals('dilengkapi', $server->status_kelengkapan); // because no required fields filled
        $this->assertGreaterThan(0, count($server->getMissingRequiredFields()));

        // Simulate editing the server: fill all required fields but do NOT change status dropdown.
        // In the blade, when status_locked=false, the dropdown sends 'automatic' as selected.
        $response = $this->put(route('server.update', $server->id), [
            'nama_perangkat' => $server->nama_perangkat,
            'jenis_perangkat' => $server->jenis_perangkat,
            'merk_perangkat' => $server->merk_perangkat,
            'serial_number' => 'SN123456',
            'ip_server' => '192.168.1.100',
            'nomor_rack' => 'R01',
            'ukuran_ram' => '8 GB',
            'ukuran_hdd' => '256 GB',
            'jumlah_core' => '4',
            'kondisi_tipe' => 'Standard',
            'kondisi_status' => 'Baru',
            'spesifikasi' => 'Test Spec',
            'tipe_perangkat' => 'RACK MOUNT',
            'status_kepemilikan' => $server->status_kepemilikan,
            'pemilik_perangkat' => $server->pemilik_perangkat,
            'nama_pengirim' => $server->nama_pengirim,
            'nama_penerima' => $server->nama_penerima,
            'status' => 'automatic', // this is what the form sends when not touched
        ]);

        $response->assertRedirect(route('server.index'));

        // Refresh server
        $server->refresh();

        // After filling all required fields, status_kelengkapan should be 'lengkap'
        $this->assertEquals('lengkap', $server->status_kelengkapan);
        $this->assertEquals(0, count($server->getMissingRequiredFields()));

        // Because status_locked is false (we sent automatic), syncStatus should have set status to 'Aktif'
        $this->assertEquals('Aktif', $server->status);
        $this->assertFalse($server->status_locked);

        // Cleanup
    }
}
