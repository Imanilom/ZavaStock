<?php
// database/migrations/2025_06_17_000008_create_stok_masuk_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stok_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->unique()->comment('Format: SM/YYYYMMDD/XXX');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->foreignId('varian_id')->nullable()->constrained('produk_varian')->onDelete('cascade');
            $table->foreignId('detail_id')->nullable()->constrained('produk_varian_detail')->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->foreignId('gudang_id')->nullable()->constrained('gudangs')->onDelete('set null');
            $table->integer('kuantitas')->default(0);
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->date('tanggal_masuk');
            $table->date('tanggal_expired')->nullable();
            $table->string('no_batch')->nullable();
            $table->string('rak')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->text('catatan')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->index(['no_transaksi', 'tanggal_masuk', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('stok_masuk');
    }
};