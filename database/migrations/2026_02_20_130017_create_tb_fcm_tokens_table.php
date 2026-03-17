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
        Schema::create('tb_fcm_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_penulis')->nullable(); // null = guest
            $table->string('token')->unique();
            $table->string('device_type', 20)->default('android'); // android / ios
            $table->timestamps();

            $table->index('id_penulis');
            $table->index('token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_fcm_tokens');
    }
};
