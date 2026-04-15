<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSampahSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('jenis_sampah')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('jenis_sampah')->insert([
            [
                'nama' => 'Botol Plastik',
                'deskripsi' => 'Botol plastik bekas minuman yang sudah dikosongkan',
                'poin_per_kg' => 100,
                'berat_min_gram' => 15,
                'berat_max_gram' => 35,
                'satuan' => 'pcs',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kaleng Aluminium',
                'deskripsi' => 'Kaleng aluminium bekas minuman yang sudah dikosongkan',
                'poin_per_kg' => 120,
                'berat_min_gram' => 10,
                'berat_max_gram' => 25,
                'satuan' => 'pcs',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}