<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan kolom status_locked ke tabel servers
        Schema::table('servers', function (Blueprint $table) {
            $table->boolean('status_locked')
                  ->default(false)
                  ->comment('True jika admin telah memilih status manual (Non-Aktif/Maintenance), false jika status masih mengikuti status_kelengkapan otomatis')
                  ->after('status');
        });

        // Baru jalankan update SETELAH kolom benar-benar ada
        DB::table('servers')->update(['status_locked' => true]);

        // Update enum status di tabel servers untuk menambahkan nilai 'Pending'
        // Menggunakan raw SQL karena doctrine/dbal tidak terinstall
        DB::statement("ALTER TABLE servers MODIFY COLUMN status ENUM('Aktif', 'Non-Aktif', 'Maintenance', 'Pending') DEFAULT 'Aktif'");
    }

    public function down(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn(['status_locked']);
        });

        // Kembalikan enum status seperti semula (menghapus 'Pending')
        DB::statement("ALTER TABLE servers MODIFY COLUMN status ENUM('Aktif', 'Non-Aktif', 'Maintenance') DEFAULT 'Aktif'");
    }
};
