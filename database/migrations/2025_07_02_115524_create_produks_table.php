<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabel produk utama
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('sku', 100)->unique();
            $table->string('nama_produk', 255);
            $table->text('deskripsi')->nullable();
            $table->string('kategori', 100);
            $table->string('bahan', 100)->nullable();
            $table->enum('status', ['AKTIF', 'NONAKTIF'])->default('AKTIF');
            $table->string('foto', 255)->nullable();
            $table->timestamps();
        });

        // Tabel varian produk (ukuran)
        Schema::create('produk_varian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->string('varian', 100); // Misal: S, M, L, XL
            $table->bigInteger('harga_beli')->default(0);
            $table->bigInteger('harga_jual')->default(0);
            $table->string('diskon', 20)->nullable();
            $table->string('satuan', 20)->nullable();
            $table->string('lokasi', 100)->nullable();
            $table->string('batch', 100)->nullable();
            $table->string('panjang', 20)->nullable();
            $table->string('lebar', 20)->nullable();
            $table->string('tinggi', 20)->nullable();
            $table->string('berat', 20)->nullable();
            $table->timestamps();
        });

        // Tabel stok untuk setiap varian
        Schema::create('produk_varian_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('varian_id')->constrained('produk_varian')->onDelete('cascade');
            $table->string('kode_detail', 100)->unique();
            $table->string('detail', 100); // misal: warna, motif, kode batch unik
            $table->integer('stok')->default(0);
            $table->string('foto', 255)->nullable();
            $table->timestamps();
        });

        // Tabel supplier produk (jika banyak supplier per produk)
        Schema::create('produk_supplier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produk_supplier');
        Schema::dropIfExists('produk_warna');
        Schema::dropIfExists('produk_varian');
        Schema::dropIfExists('produks');
    }
};