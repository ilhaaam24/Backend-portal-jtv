<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_berita', function (Blueprint $table) {
            // Nambahin kolom nullable setelah editor
            $table->string('jabatan_author')->nullable()->after('editor_berita');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_berita', function (Blueprint $table) {
            $table->dropColumn('jabatan_author');
        });
    }
};
