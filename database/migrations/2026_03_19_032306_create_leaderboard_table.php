<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaderboard', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->integer('ranking_harian')->nullable();
            $table->integer('ranking_mingguan')->nullable();
            $table->integer('ranking_bulanan')->nullable();
            $table->integer('ranking_alltime')->nullable();
            $table->decimal('total_berat_kg', 10, 2)->default(0);
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaderboard');
    }
};