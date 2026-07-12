<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bak_sampah', function (Blueprint $table) {
            $table->integer('kapasitas_max_botol')->default(200)->after('kapasitas_max');
            $table->integer('jumlah_botol_terisi')->default(0)->after('kapasitas_max_botol');
        });
    }

    public function down(): void
    {
        Schema::table('bak_sampah', function (Blueprint $table) {
            $table->dropColumn(['kapasitas_max_botol', 'jumlah_botol_terisi']);
        });
    }
};