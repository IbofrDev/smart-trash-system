<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievement', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->text('deskripsi')->nullable();
            $table->string('icon', 255)->nullable();
            $table->enum('syarat_type', ['total_kg', 'streak', 'transaksi_count', 'first_time']);
            $table->integer('syarat_value')->default(0);
            $table->integer('poin_bonus')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievement');
    }
};