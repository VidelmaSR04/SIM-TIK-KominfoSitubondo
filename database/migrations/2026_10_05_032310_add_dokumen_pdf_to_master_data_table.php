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
        Schema::table('master_data', function (Blueprint $table) {
            if (!Schema::hasColumn('master_data', 'dokumen_pdf')) {
                $table->string('dokumen_pdf')->nullable()->after('pangkat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_data', function (Blueprint $table) {
            if (Schema::hasColumn('master_data', 'dokumen_pdf')) {
                $table->dropColumn('dokumen_pdf');
            }
        });
    }
};
