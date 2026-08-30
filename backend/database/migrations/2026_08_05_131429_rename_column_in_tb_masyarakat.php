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
            $table->renameColumn('id', 'id_user');
            $table->renameColumn('name', 'username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_masyarakat', function (Blueprint $table) {
            $table->renameColumn('id_user', 'id');
            $table->renameColumn('username', 'name');
            //
        });
    }
};
