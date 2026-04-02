<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BakSampah;
use Illuminate\Support\Str;

class BakSampahSeeder extends Seeder
{
    public function run(): void
    {
        $bakSampah = [
            [
                'nama' => 'Smart Bin A1',
                'lokasi_id' => 1,
                'api_key' => Str::random(64),
                'status' => 'aktif',
                'kapasitas_max' => 50.00,
            ],
            [
                'nama' => 'Smart Bin B2',
                'lokasi_id' => 2,
                'api_key' => Str::random(64),
                'status' => 'aktif',
                'kapasitas_max' => 50.00,
            ],
            [
                'nama' => 'Smart Bin Kantin',
                'lokasi_id' => 3,
                'api_key' => Str::random(64),
                'status' => 'aktif',
                'kapasitas_max' => 75.00,
            ],
        ];

        foreach ($bakSampah as $item) {
            BakSampah::create($item);
        }
    }
}