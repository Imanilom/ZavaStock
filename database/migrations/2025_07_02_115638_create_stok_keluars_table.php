<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stok_keluars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('produk_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('varian_id')->nullable()->constrained('produk_varian')->onDelete('set null');
            $table->foreignId('detail_id')->nullable()->constrained('produk_varian_detail')->onDelete('set null');
            $table->foreignId('gudang_id')->constrained('gudangs')->onDelete('cascade');
            $table->string('rak')->nullable(); // misalnya rak dari mana
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->integer('kuantitas');
            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stok_keluars');
    }
};