<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan kolom kode_perangkat ke tabel servers
        Schema::table('servers', function (Blueprint $table) {
            $table->string('kode_perangkat', 20)
                  ->unique()
                  ->nullable()
                  ->comment('Kode unik perangkat berbasis kepemilikan, tanggal, dan urutan harian')
                  ->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn(['kode_perangkat']);
        });
    }
};
