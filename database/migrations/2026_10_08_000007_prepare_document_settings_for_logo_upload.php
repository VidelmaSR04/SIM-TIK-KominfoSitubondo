<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pengaturan kop & logo bawaan (satu baris). Kolom di bawah dipastikan ada dan boleh kosong,
        // supaya simpan logo / kop bawaan berhasil walau baris pengaturan belum pernah dibuat.
        Schema::table('document_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('document_settings', 'kop_html')) {
                $table->longText('kop_html')->nullable();
            }
            if (!Schema::hasColumn('document_settings', 'logo_path')) {
                $table->string('logo_path')->nullable();
            }
        });

        foreach (['kop_baris_1', 'kop_baris_2'] as $kolom) {
            if (Schema::hasColumn('document_settings', $kolom)) {
                Schema::table('document_settings', function (Blueprint $table) use ($kolom) {
                    $table->string($kolom)->nullable()->change();
                });
            }
        }
    }

    public function down(): void
    {
        // Sengaja kosong: perubahan ini hanya melonggarkan/menambah kolom dan aman dibiarkan.
    }
};