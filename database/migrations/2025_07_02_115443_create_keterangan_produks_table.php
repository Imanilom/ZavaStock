<?php

// database/migrations/2025_06_17_000005_create_keterangan_produk_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('keterangan_produk', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 100);
            $table->text('deskripsi')->nullable();
            $table->boolean('butuh_verifikasi')->default(true);
            $table->timestamps();
        });

        // Insert default reasons
        DB::table('keterangan_produk')->insert([
            ['kode' => 'SHRINKAGE', 'nama' => 'Shrinkage', 'deskripsi' => 'Kehilangan normal karena penyusutan stok', 'butuh_verifikasi' => false],
            ['kode' => 'THEFT', 'nama' => 'Pencurian', 'deskripsi' => 'Barang hilang karena tindak pencurian', 'butuh_verifikasi' => true],
            ['kode' => 'DAMAGED', 'nama' => 'Kerusakan', 'deskripsi' => 'Barang rusak dan tidak bisa dijual', 'butuh_verifikasi' => true],
            ['kode' => 'EXPIRED', 'nama' => 'Kadaluarsa', 'deskripsi' => 'Barang kadaluarsa sebelum terjual', 'butuh_verifikasi' => false],
            ['kode' => 'MISCOUNT', 'nama' => 'Kesalahan Hitung', 'deskripsi' => 'Selisih hasil stock opname', 'butuh_verifikasi' => true],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('keterangan_produk');
    }
};