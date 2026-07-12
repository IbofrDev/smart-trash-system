<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSampahSeeder extends Seeder
{
        public function run(): void
    {
        $sizes = [
            ['id' => 1, 'nama' => 'Botol Plastik 220ml', 'berat_min_gram' => 6,  'berat_max_gram' => 12],
            ['id' => 2, 'nama' => 'Botol Plastik 250ml', 'berat_min_gram' => 7,  'berat_max_gram' => 13],
            ['id' => 3, 'nama' => 'Botol Plastik 330ml', 'berat_min_gram' => 9,  'berat_max_gram' => 15],
            ['id' => 4, 'nama' => 'Botol Plastik 350ml', 'berat_min_gram' => 10, 'berat_max_gram' => 16],
            ['id' => 5, 'nama' => 'Botol Plastik 390ml', 'berat_min_gram' => 12, 'berat_max_gram' => 18],
            ['id' => 6, 'nama' => 'Botol Plastik 500ml', 'berat_min_gram' => 14, 'berat_max_gram' => 22],
            ['id' => 7, 'nama' => 'Botol Plastik 600ml', 'berat_min_gram' => 16, 'berat_max_gram' => 26],
        ];

        foreach ($sizes as $size) {
            DB::table('jenis_sampah')->updateOrInsert(
                ['id' => $size['id']],
                [
                    'nama'           => $size['nama'],
                    'deskripsi'      => 'Botol plastik bekas minuman yang sudah dikosongkan',
                    'poin_per_kg'    => 100,
                    'berat_min_gram' => $size['berat_min_gram'],
                    'berat_max_gram' => $size['berat_max_gram'],
                    'satuan'         => 'pcs',
                    'is_active'      => 1,
                    'updated_at'     => now(),
                ]
            );
        }
    }
}