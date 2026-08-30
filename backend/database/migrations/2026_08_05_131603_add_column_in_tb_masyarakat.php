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
        Schema::table('tb_masyarakat', function (Blueprint $table) {
            $table->string('nama_lengkap', 255);
            $table->string('telp', 25);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_masyarakat', function (Blueprint $table) {
            //
        });
    }
};
