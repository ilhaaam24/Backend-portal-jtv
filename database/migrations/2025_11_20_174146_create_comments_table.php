<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
    {
        // Hapus tabel lama biar bersih
        Schema::dropIfExists('comments');

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            
            // Kolom ID Berita (Boleh Null)
            $table->integer('id_berita')->nullable(); 
            
            // --- INI PERBAIKANNYA ---
            // Karena id_penulis adalah BIGINT + UNSIGNED (Tercentang)
            // Maka di sini WAJIB pakai unsignedBigInteger juga
            $table->unsignedBigInteger('user_id'); 

            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('content');
            $table->string('status')->default('active');
            
            $table->timestamps();

            // Indexing agar pencarian cepat
            $table->index('id_berita');
            $table->index('user_id');

            // FOREIGN KEY
            $table->foreign('user_id')
                  ->references('id_penulis')
                  ->on('tb_penulis')
                  ->onDelete('cascade');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};