<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the enum definition for role column
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','user','kepala_bidang_tik') NOT NULL DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the enum definition back to original
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','user') NOT NULL DEFAULT 'user'");
    }
};