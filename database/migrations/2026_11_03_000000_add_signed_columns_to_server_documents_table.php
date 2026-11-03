<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Dokumen bertanda tangan (final) disimpan di baris server_documents yang sama
     * dengan dokumen aslinya. Status "sudah ditandatangani" tidak punya kolom sendiri:
     * dokumen dianggap final selama signed_file terisi, sehingga status tidak bisa
     * berbeda dengan keberadaan file.
     */
    public function up(): void
    {
        Schema::table('server_documents', function (Blueprint $table) {
            if (!Schema::hasColumn('server_documents', 'signed_file')) {
                $table->string('signed_file')->nullable();            // path di disk 'local' (privat)
            }
            if (!Schema::hasColumn('server_documents', 'signed_original_name')) {
                $table->string('signed_original_name')->nullable();   // nama file asli dari perangkat admin
            }
            if (!Schema::hasColumn('server_documents', 'signed_mime')) {
                $table->string('signed_mime', 100)->nullable();
            }
            if (!Schema::hasColumn('server_documents', 'signed_size')) {
                $table->unsignedBigInteger('signed_size')->nullable(); // ukuran dalam byte
            }
            if (!Schema::hasColumn('server_documents', 'signed_at')) {
                $table->timestamp('signed_at')->nullable();
            }
            if (!Schema::hasColumn('server_documents', 'signed_by')) {
                $table->foreignId('signed_by')->nullable()->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('server_documents', function (Blueprint $table) {
            if (Schema::hasColumn('server_documents', 'signed_by')) {
                $table->dropConstrainedForeignId('signed_by');
            }
        });

        Schema::table('server_documents', function (Blueprint $table) {
            foreach (['signed_file', 'signed_original_name', 'signed_mime', 'signed_size', 'signed_at'] as $kolom) {
                if (Schema::hasColumn('server_documents', $kolom)) {
                    $table->dropColumn($kolom);
                }
            }
        });
    }
};