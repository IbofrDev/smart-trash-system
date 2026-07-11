<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSampahSeeder extends Seeder
{
    public function run(): void
    {
        // Hanya update/insert Botol Plastik, tidak truncate supaya data lama aman
        DB::table('jenis_sampah')->updateOrInsert(
            ['id' => 1],
            [
                'nama' => 'Botol Plastik',
                'deskripsi' => 'Botol plastik bekas minuman yang sudah dikosongkan',
                'poin_per_kg' => 100,
                'berat_min_gram' => 10,
                'berat_max_gram' => 25,
                'satuan' => 'pcs',
                'is_active' => 1,
                'updated_at' => now(),
            ]
        );

        // Nonaktifkan kaleng
        DB::table('jenis_sampah')->where('id', 2)->update(['is_active' => 0]);
    }
}