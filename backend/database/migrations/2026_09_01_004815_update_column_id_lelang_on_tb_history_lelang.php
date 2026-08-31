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
        Schema::table('history_lelang', function (Blueprint $table) {
            $table->dropForeign('history_lelang_id_lelang_foreign');
            $table->foreign('id_lelang')
                ->references('id_lelang')->on('tb_lelang')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history_lelang', function (Blueprint $table) {
            $table->foreignId('id_lelang')->constrained('tb_lelang', 'id_lelang')->cascadeOnUpdate();
        });
    }
};
