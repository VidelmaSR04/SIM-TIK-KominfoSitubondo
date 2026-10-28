<?php

namespace Tests\Feature;

use App\Models\Server;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class ServerIndexFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_server_index_supports_search_and_document_date_filters(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        Server::forceCreate([
            'nama_perangkat' => 'Rack Server Cocok',
            'jenis_perangkat' => 'Server',
            'kode_perangkat' => 'SR260515001',
        ]);
        Server::forceCreate([
            'nama_perangkat' => 'Rack Server Tanggal Lain',
            'jenis_perangkat' => 'Server',
            'kode_perangkat' => 'SR260616002',
        ]);

        $this->get(route('server.index', [
            'search' => 'Rack Server',
            'tanggal' => 15,
            'bulan' => 5,
            'tahun' => 2026,
        ]))
            ->assertOk()
            ->assertSee('Rack Server Cocok')
            ->assertDontSee('Rack Server Tanggal Lain');
    }

    public function test_admin_login_always_redirects_to_rack_dashboard(): void
    {
        Config::set('services.recaptcha.enabled', false);

        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => 'password',
        ]);

        $this->withSession(['url.intended' => route('server.index')])
            ->post(route('login'), [
                'email' => $admin->email,
                'password' => 'password',
            ])
            ->assertRedirect(route('admin.manajemen-server', absolute: false));
    }

    public function test_manajemen_server_menu_opens_the_server_table_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->get(route('admin.manajemen-server'))
            ->assertOk()
            ->assertSee('href="' . route('server.index') . '"', false);

        $this->get(route('server.index'))
            ->assertOk()
            ->assertSee('Perangkat & Server', false);
    }
}
