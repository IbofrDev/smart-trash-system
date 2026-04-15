<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_session', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa');
            $table->string('session_token', 64)->unique();
            $table->integer('jumlah_botol')->default(0);
            $table->integer('jumlah_kaleng')->default(0);
            $table->enum('status', [
                'pending',
                'tapped',
                'weighing',
                'counting',
                'completed',
                'expired'
            ])->default('pending');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('expired_at');
            $table->timestamp('completed_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_session');
    }
};