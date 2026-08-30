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
        Schema::create('history_lelang', function (Blueprint $table) {
            $table->id('id_history');

            $table->foreignId('id_lelang')->constrained('tb_lelang' , 'id_lelang')->cascadeOnUpdate();
            $table->foreignId('id_barang')->constrained('tb_barang' , 'id_barang')->cascadeOnUpdate();
            $table->foreignId('id_user')->constrained('tb_masyarakat' , 'id_user')->cascadeOnUpdate();

            $table->integer('penawaran_harga');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_lelangs');
    }
};
