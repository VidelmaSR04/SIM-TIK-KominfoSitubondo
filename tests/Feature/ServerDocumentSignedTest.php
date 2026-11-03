<?php

namespace Tests\Feature;

use App\Models\Server;
use App\Models\ServerDocument;
use App\Models\User;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ServerDocumentSignedTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Disk privat tempat file bertanda tangan disimpan
        Storage::fake(ServerDocument::SIGNED_DISK);
    }

    // ------------------------------------------------------------------
    // Helper
    // ------------------------------------------------------------------

    /** Disk privat (sudah di-fake di setUp). Bertipe FilesystemAdapter agar assertExists/assertMissing dikenali. */
    private function disk(): FilesystemAdapter
    {
        return Storage::disk(ServerDocument::SIGNED_DISK);
    }

    private function buatServer(?User $pemilik = null): Server
    {
        return Server::forceCreate([
            'nama_perangkat'  => 'Server Uji',
            'jenis_perangkat' => 'Router Khusus',
            'tipe_perangkat'  => 'RACK MOUNT',
            'status'          => 'Aktif',
            'status_locked'   => true,
            'user_id'         => $pemilik?->id,
        ]);
    }

    private function pdf(string $penanda = 'A', string $nama = 'ttd.pdf'): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            $nama,
            "%PDF-1.4\n% {$penanda}\n1 0 obj\n<<>>\nendobj\ntrailer\n<<>>\n%%EOF\n"
        );
    }

    private function dataForm(array $ganti = []): array
    {
        return array_merge([
            'kop_html'       => '<p>Kop</p>',
            'isi_surat'      => '<p>Isi asli</p>',
            'surat_tanggal'  => '2026-11-02',
            'surat_nomor'    => '123/ABC/2026',
            'surat_sifat'    => 'Penting',
            'surat_lampiran' => '1 (satu) berkas',
            'surat_hal'      => 'Rincian server',
            'surat_yth'      => '(Daftar terlampir)',
        ], $ganti);
    }

    private function unggah(User $admin, Server $server, ?UploadedFile $berkas = null)
    {
        return $this->actingAs($admin)->post(
            route('server.dokumen.signed.store', $server),
            ['signed_file' => $berkas ?? $this->pdf()]
        );
    }

    private function dokumenDari(Server $server): ?ServerDocument
    {
        return ServerDocument::where('server_id', $server->id)->first();
    }

    // ------------------------------------------------------------------
    // Admin: unggah, tampilan final, penguncian
    // ------------------------------------------------------------------

    public function test_admin_upload_makes_document_final_and_edit_page_switches_to_final_view(): void
    {
        $admin  = User::factory()->create(['role' => 'admin']);
        $server = $this->buatServer();

        // Sebelum diunggah: formulir edit tampil
        $this->actingAs($admin)->get(route('server.dokumen.edit', $server))
            ->assertOk()
            ->assertSee('id="formDokumen"', false)
            ->assertSee('Upload Dokumen yang Telah Ditandatangani')
            ->assertSee('Belum Ditandatangani');

        $this->unggah($admin, $server)
            ->assertRedirect(route('server.dokumen.edit', $server))
            ->assertSessionHas('success');

        $dokumen = $this->dokumenDari($server);
        $this->assertTrue($dokumen->isSigned());
        $this->disk()->assertExists($dokumen->signed_file);
        $this->assertStringStartsWith(ServerDocument::SIGNED_DIR . '/' . $server->id . '/', $dokumen->signed_file);
        $this->assertSame('ttd.pdf', $dokumen->signed_original_name);
        $this->assertSame('application/pdf', $dokumen->signed_mime);
        $this->assertGreaterThan(0, $dokumen->signed_size);
        $this->assertSame($admin->id, $dokumen->signed_by);
        $this->assertNotNull($dokumen->signed_at);

        // Sesudah diunggah: halaman yang sama berubah menjadi tampilan final
        $this->get(route('server.dokumen.edit', $server))
            ->assertOk()
            ->assertSee('Sudah Ditandatangani / Final')
            ->assertSee('Ganti Dokumen')
            ->assertSee('Hapus Dokumen Bertanda Tangan')
            ->assertSee(route('server.dokumen.signed.preview', $server), false)
            ->assertDontSee('id="formDokumen"', false)
            ->assertDontSee('tinymce.min.js', false);
    }

    public function test_final_document_rejects_every_edit_request_on_the_backend(): void
    {
        $admin  = User::factory()->create(['role' => 'admin']);
        $server = $this->buatServer();

        // Dokumen asli disimpan dulu, lalu ditandatangani
        $this->actingAs($admin)->put(route('server.dokumen.update', $server), $this->dataForm())->assertRedirect();
        $this->unggah($admin, $server);

        // Request langsung ke endpoint edit/simpan/preview-draft ditolak walau datanya valid
        $this->put(route('server.dokumen.update', $server), $this->dataForm(['isi_surat' => '<p>DIUBAH</p>']))
            ->assertStatus(423);
        $this->putJson(route('server.dokumen.update', $server), $this->dataForm(['isi_surat' => '<p>DIUBAH</p>']))
            ->assertStatus(423);
        $this->post(route('server.dokumen.preview-draft', $server), $this->dataForm())
            ->assertStatus(423);

        $this->assertStringNotContainsString('DIUBAH', (string) $this->dokumenDari($server)->isi_surat);
        $this->assertStringContainsString('Isi asli', (string) $this->dokumenDari($server)->isi_surat);
    }

    public function test_unsigned_documents_remain_editable(): void
    {
        $admin  = User::factory()->create(['role' => 'admin']);
        $server = $this->buatServer();

        $this->actingAs($admin)
            ->put(route('server.dokumen.update', $server), $this->dataForm(['surat_nomor' => '001/UJI/2026']))
            ->assertRedirect(route('server.dokumen.edit', $server));

        $this->assertSame('001/UJI/2026', $this->dokumenDari($server)->surat_nomor);
        $this->assertFalse($this->dokumenDari($server)->isSigned());
    }

    // ------------------------------------------------------------------
    // Admin: ganti dan hapus
    // ------------------------------------------------------------------

    public function test_replacing_keeps_document_final_and_removes_the_old_file(): void
    {
        $admin  = User::factory()->create(['role' => 'admin']);
        $server = $this->buatServer();

        $this->unggah($admin, $server, $this->pdf('LAMA', 'lama.pdf'));
        $pathLama = $this->dokumenDari($server)->signed_file;

        $this->unggah($admin, $server, $this->pdf('BARU', 'baru.pdf'))->assertSessionHas('success');

        $dokumen = $this->dokumenDari($server);
        $this->assertTrue($dokumen->isSigned());
        $this->assertNotSame($pathLama, $dokumen->signed_file);
        $this->assertSame('baru.pdf', $dokumen->signed_original_name);
        $this->disk()->assertMissing($pathLama);
        $this->disk()->assertExists($dokumen->signed_file);
        $this->assertCount(1, $this->disk()->allFiles());

        // Preview menampilkan file terbaru, formulir edit tetap terkunci
        $this->get(route('server.dokumen.signed.preview', $server))
            ->assertOk()
            ->assertStreamedContent($this->disk()->get($dokumen->signed_file));
        $this->get(route('server.dokumen.edit', $server))->assertDontSee('id="formDokumen"', false);
        $this->put(route('server.dokumen.update', $server), $this->dataForm())->assertStatus(423);
    }

    public function test_deleting_removes_only_the_signed_file_and_unlocks_editing(): void
    {
        $admin  = User::factory()->create(['role' => 'admin']);
        $server = $this->buatServer();

        $this->actingAs($admin)->put(route('server.dokumen.update', $server), $this->dataForm())->assertRedirect();
        $this->unggah($admin, $server);
        $path = $this->dokumenDari($server)->signed_file;

        $this->delete(route('server.dokumen.signed.destroy', $server))
            ->assertRedirect(route('server.dokumen.edit', $server))
            ->assertSessionHas('success');

        // Hanya file final yang hilang; baris dan isi dokumen asli utuh
        $this->disk()->assertMissing($path);
        $dokumen = $this->dokumenDari($server);
        $this->assertNotNull($dokumen);
        $this->assertFalse($dokumen->isSigned());
        $this->assertNull($dokumen->signed_file);
        $this->assertNull($dokumen->signed_at);
        $this->assertNull($dokumen->signed_by);
        $this->assertSame('123/ABC/2026', $dokumen->surat_nomor);
        $this->assertStringContainsString('Isi asli', (string) $dokumen->isi_surat);

        // Formulir kembali aktif dan bisa menyimpan
        $this->get(route('server.dokumen.edit', $server))
            ->assertOk()
            ->assertSee('id="formDokumen"', false)
            ->assertSee('Upload Dokumen yang Telah Ditandatangani');
        $this->put(route('server.dokumen.update', $server), $this->dataForm(['surat_nomor' => '002/UJI/2026']))
            ->assertRedirect();
        $this->assertSame('002/UJI/2026', $this->dokumenDari($server)->surat_nomor);
    }

    public function test_deleting_when_nothing_is_signed_returns_404(): void
    {
        $admin  = User::factory()->create(['role' => 'admin']);
        $server = $this->buatServer();

        $this->actingAs($admin)->delete(route('server.dokumen.signed.destroy', $server))->assertNotFound();
    }

    // ------------------------------------------------------------------
    // Validasi file
    // ------------------------------------------------------------------

    public function test_invalid_files_are_rejected_and_nothing_is_stored(): void
    {
        $admin  = User::factory()->create(['role' => 'admin']);
        $server = $this->buatServer();

        // Bukan PDF
        $this->unggah($admin, $server, UploadedFile::fake()->createWithContent('ttd.txt', 'bukan pdf'))
            ->assertSessionHasErrors('signed_file');

        // Berekstensi .pdf tetapi isinya bukan PDF
        $this->unggah($admin, $server, UploadedFile::fake()->createWithContent('palsu.pdf', 'ini teks biasa'))
            ->assertSessionHasErrors('signed_file');

        // Terlalu besar
        $this->unggah($admin, $server, UploadedFile::fake()->create('besar.pdf', ServerDocument::SIGNED_MAX_KB + 1, 'application/pdf'))
            ->assertSessionHasErrors('signed_file');

        // Tanpa file
        $this->actingAs($admin)->post(route('server.dokumen.signed.store', $server), [])
            ->assertSessionHasErrors('signed_file');

        $this->assertNull($this->dokumenDari($server));
        $this->assertSame([], $this->disk()->allFiles());
    }

    // ------------------------------------------------------------------
    // Hak akses per role
    // ------------------------------------------------------------------

    public function test_user_and_kabid_cannot_upload_replace_or_delete(): void
    {
        $admin  = User::factory()->create(['role' => 'admin']);
        $user   = User::factory()->create(['role' => 'user']);
        $kabid  = User::factory()->create(['role' => 'kepala_bidang_tik']);
        $server = $this->buatServer($user);

        foreach ([$user, $kabid] as $pengguna) {
            $this->actingAs($pengguna)
                ->post(route('server.dokumen.signed.store', $server), ['signed_file' => $this->pdf()])
                ->assertForbidden();
        }
        $this->assertNull($this->dokumenDari($server));

        $this->unggah($admin, $server);
        $path = $this->dokumenDari($server)->signed_file;

        foreach ([$user, $kabid] as $pengguna) {
            $this->actingAs($pengguna)
                ->post(route('server.dokumen.signed.store', $server), ['signed_file' => $this->pdf('PENGGANTI')])
                ->assertForbidden();
            $this->actingAs($pengguna)
                ->delete(route('server.dokumen.signed.destroy', $server))
                ->assertForbidden();
            $this->actingAs($pengguna)
                ->get(route('server.dokumen.edit', $server))
                ->assertForbidden();
            $this->actingAs($pengguna)
                ->put(route('server.dokumen.update', $server), $this->dataForm())
                ->assertForbidden();
        }

        // File final tidak berubah sama sekali
        $this->assertSame($path, $this->dokumenDari($server)->signed_file);
        $this->disk()->assertExists($path);
    }

    public function test_admin_owner_and_kabid_all_receive_the_same_signed_file(): void
    {
        $admin  = User::factory()->create(['role' => 'admin']);
        $user   = User::factory()->create(['role' => 'user']);
        $kabid  = User::factory()->create(['role' => 'kepala_bidang_tik']);
        $server = $this->buatServer($user);

        $this->unggah($admin, $server, $this->pdf('FINAL'));
        $isi = $this->disk()->get($this->dokumenDari($server)->signed_file);

        foreach ([$admin, $user, $kabid] as $pengguna) {
            // Rute yang sudah ada (dipakai daftar dokumen dan dashboard user) kini menyajikan file final
            $this->actingAs($pengguna)->get(route('server.dokumen.preview', $server))
                ->assertOk()
                ->assertHeader('Content-Type', 'application/pdf')
                ->assertStreamedContent($isi);
            $this->actingAs($pengguna)->get(route('server.dokumen.download', $server))
                ->assertOk()
                ->assertStreamedContent($isi);

            // Rute khusus dokumen bertanda tangan
            $this->actingAs($pengguna)->get(route('server.dokumen.signed.preview', $server))
                ->assertOk()
                ->assertStreamedContent($isi);
            $this->actingAs($pengguna)->get(route('server.dokumen.signed.download', $server))
                ->assertOk()
                ->assertStreamedContent($isi);
        }
    }

    public function test_user_who_does_not_own_the_server_cannot_open_the_signed_file(): void
    {
        $admin  = User::factory()->create(['role' => 'admin']);
        $pemilik = User::factory()->create(['role' => 'user']);
        $lain   = User::factory()->create(['role' => 'user']);
        $server = $this->buatServer($pemilik);

        $this->unggah($admin, $server);

        $this->actingAs($lain)->get(route('server.dokumen.signed.preview', $server))->assertForbidden();
        $this->actingAs($lain)->get(route('server.dokumen.signed.download', $server))->assertForbidden();
        $this->actingAs($lain)->get(route('server.dokumen.preview', $server))->assertForbidden();
    }

    public function test_guests_cannot_reach_signed_files_or_upload_endpoints(): void
    {
        $admin  = User::factory()->create(['role' => 'admin']);
        $server = $this->buatServer();
        $this->unggah($admin, $server);

        $this->app['auth']->forgetGuards();   // tamu: tanpa sesi login

        $this->get(route('server.dokumen.signed.preview', $server))->assertRedirect(route('login'));
        $this->get(route('server.dokumen.signed.download', $server))->assertRedirect(route('login'));
        $this->post(route('server.dokumen.signed.store', $server), ['signed_file' => $this->pdf()])->assertRedirect(route('login'));
        $this->delete(route('server.dokumen.signed.destroy', $server))->assertRedirect(route('login'));

        // File tersimpan di disk privat, bukan di disk publik
        $path = $this->dokumenDari($server)->signed_file;
        $this->assertFalse(Storage::disk('public')->exists($path));
    }

    public function test_signed_preview_is_not_available_for_unsigned_documents(): void
    {
        $user   = User::factory()->create(['role' => 'user']);
        $server = $this->buatServer($user);

        $this->actingAs($user)->get(route('server.dokumen.signed.preview', $server))->assertNotFound();
        $this->actingAs($user)->get(route('server.dokumen.signed.download', $server))->assertNotFound();
    }

    // ------------------------------------------------------------------
    // Konsistensi status database dan file
    // ------------------------------------------------------------------

    public function test_missing_file_never_silently_unlocks_the_document(): void
    {
        $admin  = User::factory()->create(['role' => 'admin']);
        $server = $this->buatServer();

        $this->unggah($admin, $server);
        $dokumen = $this->dokumenDari($server);
        $this->disk()->delete($dokumen->signed_file);   // file hilang dari penyimpanan

        // Status tetap final (kunci tidak terbuka), pengguna diberi tahu, dan tidak ada PDF pengganti
        $this->assertTrue($dokumen->fresh()->isSigned());
        $this->assertFalse($dokumen->fresh()->signedFileExists());

        $this->get(route('server.dokumen.edit', $server))
            ->assertOk()
            ->assertSee('tidak ditemukan di penyimpanan')
            ->assertDontSee('id="formDokumen"', false);
        $this->get(route('server.dokumen.preview', $server))->assertNotFound();
        $this->put(route('server.dokumen.update', $server), $this->dataForm())->assertStatus(423);

        // Admin tetap bisa memulihkan dengan mengunggah ulang
        $this->unggah($admin, $server, $this->pdf('ULANG'));
        $this->assertTrue($this->dokumenDari($server)->signedFileExists());
    }
}