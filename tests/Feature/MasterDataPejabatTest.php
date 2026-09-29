<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use App\Models\MasterData;
use Illuminate\Http\UploadedFile;
use App\Models\User;

class MasterDataPejabatTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create and log in an admin user
        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
        $this->actingAs($this->admin);
    }

    /** @test */
    public function test_add_pejabat_without_pdf()
    {
        $response = $this->post(route('master-data.store'), [
            'kategori' => MasterData::KATEGORI_PEJABAT,
            'value' => MasterData::JABATAN_KEPALA_DINAS,
            'label' => 'Drs. Sugiyono, M.Pd.I',
            'nip' => '19680312 199403 1 001',
            'pangkat' => 'Pembina Utama Muda (IV/c)',
            'urutan' => 1,
            'is_aktif' => true,
        ]);

        $response->assertRedirect(route('master-data.index', ['kategori' => MasterData::KATEGORI_PEJABAT']));
        $response->assertSessionHas('success', 'Data berhasil ditambahkan.');

        $this->assertDatabaseHas('master_data', [
            'kategori' => MasterData::KATEGORI_PEJABAT,
            'value' => MasterData::JABATAN_KEPALA_DINAS,
            'label' => 'Drs. Sugiyono, M.Pd.I',
            'nip' => '19680312 199403 1 001',
            'pangkat' => 'Pembina Utama Muda (IV/c)',
            'urutan' => 1,
            'is_aktif' => true,
            'dokumen_pdf' => null,
        ]);
    }

    /** @test */
    public function test_update_pejabat()
    {
        // Create initial pejabat
        $pejabat = MasterData::create([
            'kategori' => MasterData::KATEGORI_PEJABAT,
            'value' => MasterData::JABATAN_KEPALA_DINAS,
            'label' => 'Drs. Sugiyono, M.Pd.I',
            'nip' => '19680312 199403 1 001',
            'pangkat' => 'Pembina Utama Muda (IV/c)',
            'urutan' => 1,
            'is_aktif' => true,
        ]);

        $response = $this->put(route('master-data.update', $pejabat), [
            'kategori' => MasterData::KATEGORI_PEJABAT,
            'value' => MasterData::JABATAN_KEPALA_DINAS,
            'label' => 'Drs. Sugiyono, M.Pd.I (updated)',
            'nip' => '19680312 199403 1 002',
            'pangkat' => 'Penata Tingkat I (III/d)',
            'urutan' => 2,
            'is_aktif' => false,
        ]);

        $response->assertRedirect(route('master-data.index', ['kategori' => MasterData::KATEGORI_PEJABAT']));
        $response->assertSessionHas('success', 'Data berhasil diperbarui.');

        $pejabat->refresh();
        $this->assertEquals('Drs. Sugiyono, M.Pd.I (updated)', $pejabat->label);
        $this->assertEquals('19680312 199403 1 002', $pejabat->nip);
        $this->assertEquals('Penata Tingkat I (III/d)', $pejabat->pangkat);
        $this->assertEquals(2, $pejabat->urutan);
        $this->assertFalse($pejabat->is_aktif);
        $this->assertNull($pejabat->dokumen_pdf);
    }

    /** @test */
    public function test_delete_pejabat()
    {
        $pejabat = MasterData::create([
            'kategori' => MasterData::KATEGORI_PEJABAT,
            'value' => MasterData::JABATAN_KEPALA_DINAS,
            'label' => 'Drs. Sugiyono, M.Pd.I',
            'nip' => '19680312 199403 1 001',
            'pangkat' => 'Pembina Utama Muda (IV/c)',
            'urutan' => 1,
            'is_aktif' => true,
        ]);

        $response = $this->delete(route('master-data.destroy', $pejabat));

        $response->assertRedirect(route('master-data.index', ['kategori' => MasterData::KATEGORI_PEJABAT']));
        $response->assertSessionHas('success', 'Data berhasil dihapus.');

        $this->assertNull(MasterData::find($pejabat->id));
    }

    /** @test */
    public function test_dokumen_route_not_found()
    {
        // Since dokumen route removed, accessing should give 404
        $response = $this->actingAs($this->admin)->get('/master-data/1/dokumen');
        $response->assertStatus(404);
    }
}