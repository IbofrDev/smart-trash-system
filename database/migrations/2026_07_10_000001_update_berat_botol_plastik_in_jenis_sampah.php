<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('jenis_sampah')
            ->where('id', 1)
            ->where('nama', 'Botol Plastik')
            ->update([
                'berat_min_gram' => 5,
                'berat_max_gram' => 50,
                'updated_at'     => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('jenis_sampah')
            ->where('id', 1)
            ->where('nama', 'Botol Plastik')
            ->update([
                'berat_min_gram' => 15,
                'berat_max_gram' => 35,
                'updated_at'     => now(),
            ]);
    }
};