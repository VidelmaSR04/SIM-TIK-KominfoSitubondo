<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perangkat');
            $table->enum('jenis_perangkat', ['router', 'switch', 'server'])->nullable();
            $table->string('serial_number')->nullable();
            $table->string('merk_perangkat')->nullable();
            $table->string('type')->nullable();
            $table->enum('kondisi_tipe', ['Standard', 'High Performance'])->nullable();
            $table->enum('kondisi_status', ['Baru', 'Bekas'])->nullable();
            $table->text('spesifikasi')->nullable();
            $table->enum('tipe_perangkat', ['RACK MOUNT', 'TOWER', 'BLADE'])->nullable();
            $table->enum('status_kepemilikan', ['Kominfo', 'Colocation'])->nullable();
            $table->string('pemilik_perangkat')->nullable();
            $table->string('ip_server')->nullable();
            $table->string('ip_vps')->nullable();
            $table->enum('status', ['Aktif', 'Non-Aktif', 'Maintenance'])->default('Aktif');
            $table->string('ukuran_hdd')->nullable();
            $table->string('ukuran_ram')->nullable();
            $table->string('nomor_rack')->nullable();
            $table->integer('jumlah_core')->nullable();
            $table->string('peruntukan')->nullable();
            $table->string('nama_pengirim')->nullable();
            $table->string('nama_penerima')->nullable();
            $table->timestamp('jam_pengisian')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('servers');
    }
};