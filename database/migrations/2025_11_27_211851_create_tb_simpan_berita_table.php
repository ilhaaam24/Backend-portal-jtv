<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_simpan_berita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('id_berita'); // Asumsi ini adalah kolom ID berita Anda
            $table->timestamps();

            // Kunci unik gabungan untuk mencegah duplikasi
            $table->unique(['id_user', 'id_berita']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_simpan_berita');
    }
};
