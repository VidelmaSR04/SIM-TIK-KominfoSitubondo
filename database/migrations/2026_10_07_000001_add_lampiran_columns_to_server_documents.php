<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $kolom = ['lampiran_label', 'lampiran_nomor', 'lampiran_tanggal'];

    public function up(): void
    {
        // Isian kepala halaman lampiran (halaman 2). Kosong = dicetak titik-titik.
        Schema::table('server_documents', function (Blueprint $table) {
            foreach ($this->kolom as $kolom) {
                if (!Schema::hasColumn('server_documents', $kolom)) {
                    $table->string($kolom, 150)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('server_documents', function (Blueprint $table) {
            foreach ($this->kolom as $kolom) {
                if (Schema::hasColumn('server_documents', $kolom)) {
                    $table->dropColumn($kolom);
                }
            }
        });
    }
};