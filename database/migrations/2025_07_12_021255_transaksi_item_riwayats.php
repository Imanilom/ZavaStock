<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksi_item_riwayats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('transaksi_id');
            $table->enum('jenis_transaksi', ['stok_masuk', 'stok_keluar', 'stok_opname', 'produk_hilang']);
            $table->unsignedBigInteger('produk_id');
            $table->unsignedBigInteger('varian_id')->nullable();
            $table->unsignedBigInteger('detail_id')->nullable();
            $table->integer('kuantitas')->default(0);
            $table->decimal('harga_satuan', 15, 2)->default(0.00);
            $table->decimal('subtotal', 15, 2)->default(0.00);
            $table->unsignedBigInteger('gudang_id')->nullable();
            $table->string('rak', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('transaksi_id')->references('id')->on('transaksi_riwayats');
            $table->foreign('produk_id')->references('id')->on('produks')->onDelete('cascade');
            $table->foreign('varian_id')->references('id')->on('produk_varian')->onDelete('set null');
            $table->foreign('detail_id')->references('id')->on('produk_varian_detail')->onDelete('set null');
            $table->foreign('gudang_id')->references('id')->on('gudangs')->onDelete('set null');

            $table->index(['transaksi_id', 'jenis_transaksi']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi_item_riwayats');
    }
};