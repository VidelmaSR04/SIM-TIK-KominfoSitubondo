<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','user','kepala_bidang_tik','teknisi') NOT NULL DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $count = DB::table('users')->where('role', 'teknisi')->count();
        if ($count > 0) {
            throw new \RuntimeException("Tidak dapat me-rollback migrasi: masih terdapat {$count} user dengan role 'teknisi'. Ubah atau hapus role user tersebut terlebih dahulu sebelum rollback.");
        }

        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','user','kepala_bidang_tik') NOT NULL DEFAULT 'user'");
    }
};
