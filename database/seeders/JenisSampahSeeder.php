<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisSampah;

class JenisSampahSeeder extends Seeder
{
    public function run(): void
    {
        $jenisSampah = [
            [
                'nama' => 'Plastik',
                'deskripsi' => 'Botol plastik, kantong plastik, kemasan plastik',
                'poin_per_kg' => 100,
                'satuan' => 'kg',
                'is_active' => 1,
            ],
            [
                'nama' => 'Kertas',
                'deskripsi' => 'Kertas HVS, kardus, koran, majalah',
                'poin_per_kg' => 80,
                'satuan' => 'kg',
                'is_active' => 1,
            ],
            [
                'nama' => 'Logam',
                'deskripsi' => 'Kaleng aluminium, besi, tembaga',
                'poin_per_kg' => 150,
                'satuan' => 'kg',
                'is_active' => 1,
            ],
            [
                'nama' => 'Kaca',
                'deskripsi' => 'Botol kaca, pecahan kaca',
                'poin_per_kg' => 70,
                'satuan' => 'kg',
                'is_active' => 1,
            ],
            [
                'nama' => 'Elektronik',
                'deskripsi' => 'Komponen elektronik, kabel, baterai',
                'poin_per_kg' => 200,
                'satuan' => 'kg',
                'is_active' => 1,
            ],
            [
                'nama' => 'Organik',
                'deskripsi' => 'Sisa makanan, daun, ranting',
                'poin_per_kg' => 50,
                'satuan' => 'kg',
                'is_active' => 1,
            ],
        ];

        foreach ($jenisSampah as $jenis) {
            JenisSampah::create($jenis);
        }
    }
}