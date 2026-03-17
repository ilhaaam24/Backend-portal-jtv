<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cek dulu biar gak error "Table already exists" kalau tidak sengaja ke-run 2x
        if (!Schema::hasTable('comment_likes')) {
            
            Schema::create('comment_likes', function (Blueprint $table) {
                $table->id();

                // 1. Relasi ke Penulis (User Frontend)
                // Kita gunakan 'id_penulis' sesuai request Anda
                $table->unsignedBigInteger('id_penulis');

                // 2. Relasi ke Komentar
                $table->unsignedBigInteger('comment_id');

                $table->timestamps();

                // 3. CONSTRAINT PENTING:
                // Agar 1 Penulis tidak bisa like berkali-kali di komentar yang sama
                $table->unique(['id_penulis', 'comment_id']);

                // 4. FOREIGN KEYS (KUNCI TAMU)
                // Ini memastikan data valid. 
                // Jika Penulis dihapus -> Like buatannya ikut terhapus (cascade)
                // Jika Komentar dihapus -> Like di komentar itu ikut terhapus (cascade)
                
                // PENTING: Pastikan Primary Key di tb_penulis namanya benar-benar 'id_penulis'.
                // Jika di tb_penulis primary key-nya cuma 'id', ganti ->references('id_penulis') menjadi ->references('id')
                $table->foreign('id_penulis')
                      ->references('id_penulis') // Nama kolom Primary Key di tb_penulis
                      ->on('tb_penulis')         // Nama tabel User Frontend Anda
                      ->onDelete('cascade');

                $table->foreign('comment_id')
                      ->references('id')
                      ->on('comments')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_likes');
    }
};