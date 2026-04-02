<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mahasiswa_achievement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('achievement_id')->constrained('achievement')->onDelete('cascade');
            $table->timestamp('unlocked_at')->useCurrent();
            $table->timestamps(); // ← Tambahkan ini

            $table->unique(['mahasiswa_id', 'achievement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_achievement');
    }
};