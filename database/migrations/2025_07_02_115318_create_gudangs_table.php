<?php
// database/migrations/2025_06_17_000003_create_gudang_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gudangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('alamat');
            $table->string('telepon');
            $table->string('email')->nullable();
            $table->enum('jenis', ['utama', 'cabang', 'retur', 'lainnya'])->default('utama');
            $table->boolean('aktif')->default(true);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('gudang_rak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gudang_id')->constrained('gudangs')->onDelete('cascade');
            $table->string('kode_rak')->comment('Format: A1, B2, etc');
            $table->string('nama_rak');
            $table->text('deskripsi')->nullable();
            $table->integer('kapasitas')->nullable()->comment('Dalam jumlah item');
            $table->timestamps();
            
            $table->unique(['gudang_id', 'kode_rak']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('gudang_rak');
        Schema::dropIfExists('gudangs');
    }
};