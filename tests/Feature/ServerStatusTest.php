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
        $response = $this->post(route('inputdatauser.store'), [
            'nama_perangkat' => 'Test Perangkat',
            'jenis_perangkat' => 'server',
            'merk_perangkat' => 'TestMerk',
            'opd' => 'Dinas Kesehatan',
            'nama_pengirim' => 'Test Pengirim',
            'nama_penerima' => 'Tidak boleh diisi user',
            'tanggal_input' => '2026-10-05',
        ]);

        $response->assertRedirect(route('user.dashboarduser'));

        // Find the created server
        $server = Server::where('user_id', $user->id)->first();

        $this->assertNotNull($server);
        $this->assertEquals('Colocation', $server->status_kepemilikan);
        $this->assertEquals('Dinas Kesehatan', $server->pemilik_perangkat);
        $this->assertEquals('Dinas Kesehatan', $server->nama_opd);
        $this->assertNull($server->nama_penerima);
        $this->assertEquals('Pending', $server->status);
    }

    /** @test */
    public function user_must_provide_a_sender_name_and_the_opd_must_be_a_known_option()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->post(route('inputdatauser.store'), [
            'nama_perangkat' => 'Test Perangkat',
            'jenis_perangkat' => 'server',
            'merk_perangkat' => 'TestMerk',
            'opd' => 'OPD Tidak Terdaftar',
            'tanggal_input' => '2026-10-05',
        ]);

        $response->assertSessionHasErrors(['nama_pengirim', 'opd']);
        $this->assertDatabaseCount('servers', 0);

        // Cleanup (handled by RefreshDatabase)
    }

    /** @test */
    public function user_form_supports_opd_search_and_keeps_recipient_admin_only()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->get(route('inputdatauser.create'));

        $response->assertOk()
            ->assertSee('id="opdTrigger"', false)
            ->assertSee('id="opdSearch" class="field" placeholder="Cari Dinas/OPD..."', false)
            ->assertSee('class="opd-options py-1"', false)
            ->assertSee('id="nama_penerima" class="field" value=""', false)
            ->assertSee('placeholder="Akan diisi oleh admin" disabled', false);
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
        // Explicitly set status_kelengkapan to 'pending' since it's not being set correctly from default
        $owner = User::factory()->create(['role' => 'user']);
        $server = Server::create([
            'user_id' => $owner->id,
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
            'status_kelengkapan' => 'pending', // Explicitly set since default not working
        ]);

        // Debug: Check what was actually saved
        $this->assertEquals('Pending', $server->status);
        $this->assertFalse($server->status_locked, 'Status locked should be false but is: ' . var_export($server->status_locked, true));

        // Debug status_kelengkapan
        $this->assertEquals('pending', $server->status_kelengkapan, 'Expected pending but got: ' . $server->status_kelengkapan);

        $this->assertGreaterThan(0, count($server->getMissingRequiredFields()));

        // Simulate editing the server: fill all required fields but do NOT change status dropdown.
        // In the blade, when status_locked=false, the dropdown sends 'automatic' as selected.
        $response = $this->put(route('server.update', $server->id), [
            'nama_perangkat' => $server->nama_perangkat,
            'jenis_perangkat' => $server->jenis_perangkat,
            'merk_perangkat' => $server->merk_perangkat,
            'serial_number' => 'SN123456',
            'ip_server' => '192.168.1.100',
            'ip_vps' => '', // ip_vps is nullable, so we can send empty string
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
            // Add the missing required fields that were causing validation errors
            'type' => 'Test Type',
            'peruntukan' => 'Test Peruntukan',
            'jam_pengisian' => '2026-10-05 08:00:00',
        ]);

        $response->assertRedirect(route('server.index'));

        // Refresh server
        $server->refresh();

        // Debug what we actually got after update
        // dd([
        //     'status' => $server->status,
        //     'status_locked' => $server->status_locked,
        //     'status_kelengkapan' => $server->status_kelengkapan,
        // ]);

        // After filling all required fields, status_kelengkapan should be 'lengkap'
        $this->assertEquals('lengkap', $server->status_kelengkapan);
        $this->assertEquals(0, count($server->getMissingRequiredFields()));

        // Because status_locked is false (we sent automatic), syncStatus should have set status to 'Aktif'
        $this->assertEquals('Aktif', $server->status);
        // Convert to boolean for proper comparison (0 is false in PHP but not identical to false)
        $this->assertFalse((bool)$server->status_locked);

        $this->actingAs($owner)
            ->get(route('user.dashboarduser'))
            ->assertOk()
            ->assertSee(route('server.dokumen.download', $server->id));

        // Cleanup
    }
}