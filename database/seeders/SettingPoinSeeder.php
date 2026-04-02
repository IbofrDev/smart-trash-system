<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SettingPoin;

class SettingPoinSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'nama_setting' => 'bonus_level_up',
                'value' => 100,
                'deskripsi' => 'Bonus poin yang didapat saat naik level',
            ],
            [
                'nama_setting' => 'minimum_berat',
                'value' => 10,
                'deskripsi' => 'Berat minimum (gram) untuk transaksi valid',
            ],
            [
                'nama_setting' => 'maksimum_berat_harian',
                'value' => 50000,
                'deskripsi' => 'Berat maksimum (gram) per hari per mahasiswa',
            ],
        ];

        foreach ($settings as $setting) {
            SettingPoin::create($setting);
        }
    }
}