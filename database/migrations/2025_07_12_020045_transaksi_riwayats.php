<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksi_riwayats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->enum('jenis_transaksi', ['stok_masuk', 'stok_keluar', 'stok_opname', 'produk_hilang']);
            $table->unsignedBigInteger('transaksi_id');
            $table->string('kode_transaksi', 100);
            $table->dateTime('tanggal_transaksi');
            $table->integer('total_item')->default(0);
            $table->decimal('total_nilai', 15, 2)->default(0.00);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['jenis_transaksi', 'transaksi_id']);
            $table->index('tanggal_transaksi');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi_riwayats');
    }
};