<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voucher_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa');
            $table->string('kode_voucher', 10)->unique();
            $table->enum('status', ['aktif', 'terpakai', 'expired'])->default('aktif');
            $table->integer('koin_digunakan')->default(20);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('expired_at');
            $table->timestamp('used_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_mahasiswa');
    }
};