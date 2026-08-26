<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            // Status kelengkapan data: pending (baru dari user) -> dilengkapi -> lengkap (QR aktif)
            $table->enum('status_kelengkapan', ['pending', 'dilengkapi', 'lengkap'])
                ->default('pending')
                ->after('id');

            // Siapa user yang mendaftarkan (nullable, kalau server dibuat langsung oleh admin tanpa lewat form user)
            $table->foreignId('user_id')->nullable()->after('status_kelengkapan')
                ->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['status_kelengkapan', 'user_id']);
        });
    }
};
