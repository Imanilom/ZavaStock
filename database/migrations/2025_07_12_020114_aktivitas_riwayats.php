<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('aktivitas_riwayats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('tipe_aktivitas', ['login', 'logout', 'create', 'update', 'delete', 'approve', 'reject', 'system']);
            $table->string('subjek_tipe', 100)->nullable();
            $table->unsignedBigInteger('subjek_id')->nullable();
            $table->text('deskripsi');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index('tipe_aktivitas');
            $table->index(['subjek_tipe', 'subjek_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('aktivitas_riwayats');
    }
};