<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jenis_sampah', function (Blueprint $table) {
            $table->integer('berat_min_gram')->default(0)->after('poin_per_kg');
            $table->integer('berat_max_gram')->default(0)->after('berat_min_gram');
        });
    }

    public function down(): void
    {
        Schema::table('jenis_sampah', function (Blueprint $table) {
            $table->dropColumn(['berat_min_gram', 'berat_max_gram']);
        });
    }
};