<?php

namespace Tests\Feature\Admin;

use App\Models\MasterData;
use App\Models\RegistrationCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class RegistrationCodeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create(['role' => 'admin']));
    }

    public function test_registration_code_form_lists_active_opds(): void
    {
        MasterData::create([
            'kategori' => 'pemilik_perangkat',
            'value' => 'Dinas Kesehatan',
            'label' => 'Dinas Kesehatan',
            'urutan' => 1,
            'is_aktif' => true,
        ]);
        Cache::forget('master_data.select.pemilik_perangkat');

        $this->get(route('admin.registration-codes.index'))
            ->assertOk()
            ->assertSee('>OPD</label>', false)
            ->assertSee('value="Dinas Kesehatan"', false)
            ->assertSee('Dinas Kesehatan');
    }

    public function test_registration_code_requires_an_active_master_opd(): void
    {
        MasterData::create([
            'kategori' => 'pemilik_perangkat',
            'value' => 'Dinas Kesehatan',
            'label' => 'Dinas Kesehatan',
            'urutan' => 1,
            'is_aktif' => true,
        ]);
        Cache::forget('master_data.select.pemilik_perangkat');

        $this->post(route('admin.registration-codes.store'), [
            'label' => 'OPD Tidak Terdaftar',
        ])->assertSessionHasErrors('label');

        $this->assertDatabaseCount('registration_codes', 0);

        $this->post(route('admin.registration-codes.store'), [
            'label' => 'Dinas Kesehatan',
        ])->assertRedirect(route('admin.registration-codes.index'));

        $this->assertDatabaseHas('registration_codes', [
            'label' => 'Dinas Kesehatan',
        ]);
    }
}
