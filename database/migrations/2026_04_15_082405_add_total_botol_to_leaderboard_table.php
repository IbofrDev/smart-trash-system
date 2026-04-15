<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leaderboard', function (Blueprint $table) {
            $table->integer('total_botol')->default(0)->after('total_berat_kg');
        });
    }

    public function down(): void
    {
        Schema::table('leaderboard', function (Blueprint $table) {
            $table->dropColumn('total_botol');
        });
    }
};