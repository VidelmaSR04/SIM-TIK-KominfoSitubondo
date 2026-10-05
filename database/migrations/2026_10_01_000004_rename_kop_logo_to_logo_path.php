<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('document_settings', 'kop_logo')) {
            Schema::table('document_settings', function (Blueprint $table) {
                $table->renameColumn('kop_logo', 'logo_path');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('document_settings', 'logo_path')) {
            Schema::table('document_settings', function (Blueprint $table) {
                $table->renameColumn('logo_path', 'kop_logo');
            });
        }
    }
};