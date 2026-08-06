<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cpanels', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('domain');
            $table->string('ip_local');
            $table->string('ip_public');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cpanels');
    }
};