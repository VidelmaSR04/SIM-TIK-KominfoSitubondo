<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aplikasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('ip_local')->nullable();
            $table->string('ip_public')->nullable();
            $table->string('pic')->nullable();
            $table->enum('status', ['Sudah Asesmen', 'Belum Asesmen'])->default('Belum Asesmen');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aplikasis');
    }
};