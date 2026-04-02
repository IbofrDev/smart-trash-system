<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bak_sampah', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->foreignId('lokasi_id')->constrained('lokasi')->onDelete('cascade');
            $table->string('api_key', 64)->unique();
            $table->enum('status', ['aktif', 'nonaktif', 'maintenance'])->default('aktif');
            $table->decimal('kapasitas_max', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bak_sampah');
    }
};