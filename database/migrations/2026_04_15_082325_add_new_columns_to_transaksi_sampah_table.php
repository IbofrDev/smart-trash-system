<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_sampah', function (Blueprint $table) {
            $table->integer('jumlah_input_botol')->default(0);
            $table->integer('jumlah_input_kaleng')->default(0)->after('jumlah_input_botol');
            $table->integer('jumlah_terhitung')->default(0)->after('jumlah_input_kaleng');
            $table->integer('jumlah_final')->default(0)->after('jumlah_terhitung');
            $table->enum('status_validasi', ['valid', 'anomali'])->default('valid')->after('jumlah_final');
            $table->integer('koin_didapat')->default(0)->after('poin_didapat');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_sampah', function (Blueprint $table) {
            $table->dropColumn([
                'jumlah_input_botol',
                'jumlah_input_kaleng',
                'jumlah_terhitung',
                'jumlah_final',
                'status_validasi',
                'koin_didapat',
            ]);
        });
    }
};