<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_sampah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('bak_sampah_id')->constrained('bak_sampah')->onDelete('cascade');
            $table->foreignId('jenis_sampah_id')->constrained('jenis_sampah')->onDelete('cascade');
            $table->decimal('berat', 10, 2);
            $table->integer('poin_didapat')->default(0);
            $table->timestamp('tanggal_transaksi')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_sampah');
    }
};