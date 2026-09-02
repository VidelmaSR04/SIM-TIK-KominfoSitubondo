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
        // First update existing data from 'OPD Lain' to 'Colocation'
        DB::statement("UPDATE servers SET status_kepemilikan = 'Colocation' WHERE status_kepemilikan = 'OPD Lain'");

        // Then modify the enum definition
        DB::statement("ALTER TABLE servers MODIFY status_kepemilikan ENUM('Kominfo', 'Colocation') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First update existing data from 'Colocation' back to 'OPD Lain'
        DB::statement("UPDATE servers SET status_kepemilikan = 'OPD Lain' WHERE status_kepemilikan = 'Colocation'");

        // Then revert the enum definition
        DB::statement("ALTER TABLE servers MODIFY status_kepemilikan ENUM('Kominfo', 'OPD Lain') NOT NULL");
    }
};
