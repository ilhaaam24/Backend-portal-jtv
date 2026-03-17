<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('tb_minat_penulis', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('id_penulis');
        $table->unsignedBigInteger('id_kategori');
        $table->timestamps();

        // Optional: Foreign Key biar aman (kalau tabel user/kategori dihapus, minatnya ilang)
        // $table->foreign('id_pengguna')->references('id_pengguna')->on('tb_pengguna')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_minat_penulis');
    }
};
