<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Satu baris saja: kop bawaan untuk dokumen baru.
        Schema::create('document_settings', function (Blueprint $table) {
            $table->id();
            $table->string('kop_baris_1');
            $table->string('kop_baris_2');
            $table->text('kop_alamat')->nullable();
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_settings');
    }
};