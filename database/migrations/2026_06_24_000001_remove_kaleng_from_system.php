<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Nonaktifkan kaleng di jenis_sampah (tidak delete, jaga FK data lama)
        DB::table('jenis_sampah')->where('id', 2)->update(['is_active' => 0]);

        // Hapus kolom jumlah_kaleng dari transaksi_session
        Schema::table('transaksi_session', function (Blueprint $table) {
            $table->dropColumn('jumlah_kaleng');
        });

        // Hapus kolom jumlah_input_kaleng dari transaksi_sampah
        Schema::table('transaksi_sampah', function (Blueprint $table) {
            $table->dropColumn('jumlah_input_kaleng');
        });
    }

    public function down(): void
    {
        DB::table('jenis_sampah')->where('id', 2)->update(['is_active' => 1]);

        Schema::table('transaksi_session', function (Blueprint $table) {
            $table->integer('jumlah_kaleng')->default(0)->after('jumlah_botol');
        });

        Schema::table('transaksi_sampah', function (Blueprint $table) {
            $table->integer('jumlah_input_kaleng')->nullable()->after('jumlah_input_botol');
        });
    }
};