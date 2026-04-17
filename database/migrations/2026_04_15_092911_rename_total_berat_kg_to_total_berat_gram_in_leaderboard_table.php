<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leaderboard', function (Blueprint $table) {
            $table->renameColumn('total_berat_kg', 'total_berat_gram');
        });
    }

    public function down(): void
    {
        Schema::table('leaderboard', function (Blueprint $table) {
            $table->renameColumn('total_berat_gram', 'total_berat_kg');
        });
    }
};