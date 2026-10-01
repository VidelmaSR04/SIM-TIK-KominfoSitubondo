<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('server_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->unique()->constrained('servers')->cascadeOnDelete();

            // Kop (salinan saat disimpan, supaya dokumen lama tidak berubah sendiri)
            $table->string('kop_baris_1');
            $table->string('kop_baris_2');
            $table->text('kop_alamat')->nullable();

            // Isi surat pengantar (halaman 1), HTML hasil editor yang sudah dibersihkan
            $table->longText('isi_surat')->nullable();

            // Tanda tangan kiri & kanan (nama/pangkat/NIP disalin dari master data)
            foreach (['kiri', 'kanan'] as $sisi) {
                $table->foreignId("ttd_{$sisi}_master_id")->nullable()
                    ->constrained('master_data')->nullOnDelete();
                $table->text("ttd_{$sisi}_judul")->nullable();
                $table->string("ttd_{$sisi}_nama")->nullable();
                $table->string("ttd_{$sisi}_pangkat")->nullable();
                $table->string("ttd_{$sisi}_nip", 50)->nullable();
            }

            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('server_documents');
    }
};