<?php

// database/migrations/2025_06_17_000007_create_produk_hilang_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('produk_hilang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->foreignId('varian_id')->nullable()->constrained('produk_varian')->onDelete('cascade');
            $table->foreignId('detail_id')->nullable()->constrained('produk_varian_detail')->onDelete('cascade');
            $table->integer('jumlah_hilang')->default(0);
            $table->date('tanggal_kejadian');
            $table->date('tanggal_lapor')->useCurrent();
            $table->foreignId('keterangan_id')->constrained('keterangan_produk')->onDelete('cascade');
            $table->text('catatan_tambahan')->nullable();
            $table->string('status', 20)->default('DRAFT'); // DRAFT, REPORTED, VERIFIED, REJECTED
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->index(['produk_id', 'status']);
            $table->index('tanggal_kejadian');
        });
    }

    public function down()
    {
        Schema::dropIfExists('produk_hilang');
    }
};