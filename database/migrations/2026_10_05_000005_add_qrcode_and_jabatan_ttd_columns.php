<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Master Data (kategori pejabat):
        //  - jabatan_ttd : teks jabatan di atas tanda tangan, satu baris = satu baris di PDF
        //  - qrcode_path : path gambar QR Code tanda tangan (disk 'public', folder qrcode_ttd/)
        Schema::table('master_data', function (Blueprint $table) {
            if (!Schema::hasColumn('master_data', 'jabatan_ttd')) {
                $table->text('jabatan_ttd')->nullable();
            }
            if (!Schema::hasColumn('master_data', 'qrcode_path')) {
                $table->string('qrcode_path')->nullable();
            }
        });

        // Salinan (snapshot) path QR saat dokumen disimpan, sama seperti nama/pangkat/NIP
        Schema::table('server_documents', function (Blueprint $table) {
            foreach (['ttd_kiri_qrcode', 'ttd_kanan_qrcode'] as $kolom) {
                if (!Schema::hasColumn('server_documents', $kolom)) {
                    $table->string($kolom)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('server_documents', function (Blueprint $table) {
            foreach (['ttd_kiri_qrcode', 'ttd_kanan_qrcode'] as $kolom) {
                if (Schema::hasColumn('server_documents', $kolom)) {
                    $table->dropColumn($kolom);
                }
            }
        });

        Schema::table('master_data', function (Blueprint $table) {
            foreach (['jabatan_ttd', 'qrcode_path'] as $kolom) {
                if (Schema::hasColumn('master_data', $kolom)) {
                    $table->dropColumn($kolom);
                }
            }
        });
    }
};
