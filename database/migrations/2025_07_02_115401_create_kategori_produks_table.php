<?php

// database/migrations/2025_06_17_000004_create_kategori_produk_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kategori_produks', function (Blueprint $table) {
            $table->id();
            $table->string('id_kategori', 10)->unique();
            $table->string('nama_kategori', 50);
            $table->text('deskripsi')->nullable();
            $table->enum('jenis_kategori', ['Makanan', 'Minuman', 'Alat']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_produk');
    }
};