<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('server_aplikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained()->onDelete('cascade');
            $table->foreignId('aplikasi_id')->constrained()->onDelete('cascade');
            $table->string('ip_local')->nullable();
            $table->string('ip_public')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();

            // Optional: unique constraint to prevent duplicate pairs
            $table->unique(['server_id', 'aplikasi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('server_aplikasi');
    }
};