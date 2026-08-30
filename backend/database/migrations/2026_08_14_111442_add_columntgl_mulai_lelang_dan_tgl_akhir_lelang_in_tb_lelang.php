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
        Schema::table('tb_lelang', function (Blueprint $table) {
            $table->renameColumn('tgl_lelang', 'tgl_mulai_lelang');
            $table->dateTime('tgl_akhir_lelang')->nullable()->after('tgl_mulai_lelang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_lelang', function (Blueprint $table) {
            $table->renameColumn('tgl_mulai_lelang', 'tgl_lelang');
            $table->dropColumn('tgl_akhir_lelang');
        });
    }
};
