<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lokasi;

class LokasiSeeder extends Seeder
{
    public function run(): void
    {
        $lokasi = [
            [
                'nama_lokasi' => 'Gedung A - Lantai 1',
                'alamat' => 'Kampus Politeknik Negeri Banjarmasin',
                'koordinat' => '-3.3191,114.5903',
            ],
            [
                'nama_lokasi' => 'Gedung B - Lantai 2',
                'alamat' => 'Kampus Politeknik Negeri Banjarmasin',
                'koordinat' => '-3.3192,114.5904',
            ],
            [
                'nama_lokasi' => 'Kantin Utama',
                'alamat' => 'Kampus Politeknik Negeri Banjarmasin',
                'koordinat' => '-3.3193,114.5905',
            ],
        ];

        foreach ($lokasi as $item) {
            Lokasi::create($item);
        }
    }
}