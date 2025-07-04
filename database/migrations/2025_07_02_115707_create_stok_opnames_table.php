<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stok_opnames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('produk_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('varian_id')->nullable()->constrained('produk_varian')->onDelete('set null');
            $table->foreignId('detail_id')->nullable()->constrained('produk_varian_detail')->onDelete('set null');
            $table->foreignId('gudang_id')->constrained()->onDelete('cascade');
            $table->string('rak')->nullable(); // Lokasi rak di gudang
            $table->integer('stok_sistem')->default(0);
            $table->integer('stok_fisik')->default(0);
            $table->integer('selisih')->default(0);
            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stok_opnames');
    }
};