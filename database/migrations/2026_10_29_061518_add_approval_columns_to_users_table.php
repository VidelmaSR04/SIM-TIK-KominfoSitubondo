<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'status')) {
                // DEFAULT 'active' => semua akun lama otomatis aktif
                $table->string('status', 20)->default('active')->after('role')->index();
            }
            if (! Schema::hasColumn('users', 'registration_code_id')) {
                $table->foreignId('registration_code_id')->nullable()->after('status')
                    ->constrained('registration_codes')->nullOnDelete();
            }
            if (! Schema::hasColumn('users', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->after('registration_code_id')
                    ->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('users', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
            if (! Schema::hasColumn('users', 'rejection_reason')) {
                $table->string('rejection_reason', 255)->nullable()->after('approved_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'approved_by')) {
                $table->dropConstrainedForeignId('approved_by');
            }
            if (Schema::hasColumn('users', 'registration_code_id')) {
                $table->dropConstrainedForeignId('registration_code_id');
            }
            foreach (['rejection_reason', 'approved_at'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
            if (Schema::hasColumn('users', 'status')) {
                $table->dropIndex(['status']);
                $table->dropColumn('status');
            }
        });
    }
};
