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
        Schema::create('tb_lelang', function (Blueprint $table) {
            $table->id('id_lelang');

            $table->dateTime('tgl_lelang')->nullable();
            $table->integer('harga_akhir')->nullable();
            $table->enum('status', ['dibuka', 'ditutup'])->default('ditutup');

            $table->foreignId('id_user')->nullable()->constrained('tb_masyarakat', 'id_user')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('id_barang')->nullable()->constrained('tb_barang', 'id_barang')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('id_petugas')->nullable()->constrained('tb_petugas', 'id_petugas')->cascadeOnUpdate()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lelangs');
    }
};
