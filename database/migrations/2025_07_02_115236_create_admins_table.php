<?php

// database/migrations/2025_06_17_000001_create_admin_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama', 100);
            $table->string('telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('email', 100)->nullable();
            $table->text('catatan')->nullable();
            $table->string('foto', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin');
    }
};