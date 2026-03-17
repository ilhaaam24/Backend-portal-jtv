<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_simpan_berita', function (Blueprint $table) {
            // Drop foreign key yang salah
            $table->dropForeign('tb_simpan_berita_id_user_foreign');

            // Tambahkan foreign key yang benar ke tabel tb_penulis
            $table->foreign('id_user')
                  ->references('id_penulis')
                  ->on('tb_penulis')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tb_simpan_berita', function (Blueprint $table) {
            // Rollback: hapus foreign key baru
            $table->dropForeign(['id_user']);

            // Kembalikan foreign key lama
            $table->foreign('id_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
