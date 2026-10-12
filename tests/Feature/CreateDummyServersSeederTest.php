<?php

namespace Tests\Feature;

use App\Models\Server;
use App\Models\User;
use Database\Seeders\CreateDummyServersSeeder;
use Database\Seeders\MasterDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateDummyServersSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_fifty_complete_servers_per_ownership_and_is_repeatable(): void
    {
        User::factory()->create([
            'name' => 'Admin Sistem',
            'email' => 'admin@situbondo.go.id',
            'role' => 'admin',
        ]);
        User::factory()->create([
            'name' => 'User OPD',
            'email' => 'user@situbondo.go.id',
            'role' => 'user',
        ]);

        $this->seed(MasterDataSeeder::class);
        $this->seed(CreateDummyServersSeeder::class);

        $this->assertSame(50, Server::where('status_kepemilikan', 'Kominfo')->count());
        $this->assertSame(50, Server::where('status_kepemilikan', 'Colocation')->count());
        $this->assertSame(100, Server::where('serial_number', 'like', 'DUMMY-%')->count());
        $this->assertSame(100, Server::where('status_kelengkapan', 'lengkap')->count());
        $this->assertSame(100, Server::whereNotNull('kode_perangkat')->count());

        $this->seed(CreateDummyServersSeeder::class);
        $this->assertSame(100, Server::where('serial_number', 'like', 'DUMMY-%')->count());
    }
}
