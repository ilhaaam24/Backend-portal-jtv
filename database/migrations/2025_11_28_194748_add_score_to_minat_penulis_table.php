<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tb_minat_penulis', function (Blueprint $table) {
            // Default 0. Score makin tinggi makin sering muncul.
            $table->integer('score')->default(0)->after('id_kategori');
        });
    }

    public function down()
    {
        Schema::table('tb_minat_penulis', function (Blueprint $table) {
            $table->dropColumn('score');
        });
    }
};
