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
        // Cek dulu: Kalau kolom 'is_replied' BELUM ada di tabel 'curhatans', baru gas bikin.
        if (!Schema::hasColumn('curhatans', 'is_replied')) {
            Schema::table('curhatans', function (Blueprint $table) {
                // Menambah kolom boolean, default-nya false (belum dibalas)
                $table->boolean('is_replied')->default(false)->after('message');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('curhatans', function (Blueprint $table) {
            // Cek dulu: Kalau kolomnya ada, baru dihapus (biar aman pas rollback)
            if (Schema::hasColumn('curhatans', 'is_replied')) {
                $table->dropColumn('is_replied');
            }
        });
    }
};
