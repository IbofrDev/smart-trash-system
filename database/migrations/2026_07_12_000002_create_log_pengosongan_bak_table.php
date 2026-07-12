<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_pengosongan_bak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bak_sampah_id')->constrained('bak_sampah')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('jumlah_botol_sebelum')->default(0);
            $table->text('catatan')->nullable();
            $table->timestamp('dikosongkan_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_pengosongan_bak');
    }
};