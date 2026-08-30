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
        Schema::create('tb_petugas', function (Blueprint $table) {
            $table->id('id_petugas');

            $table->string('nama_petugas', 255);
            $table->string('username', 25);
            $table->string('password', 255);
            $table->string('telp', 25);

            $table->foreignId('id_level')->constrained('tb_level', 'id_level')->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas');
    }
};
