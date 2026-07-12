<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_session', function (Blueprint $table) {
            $table->json('botol_breakdown')->nullable()->after('jumlah_botol');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_session', function (Blueprint $table) {
            $table->dropColumn('botol_breakdown');
        });
    }
};