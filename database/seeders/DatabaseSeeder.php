<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LevelSeeder::class,
            JenisSampahSeeder::class,
            AchievementSeeder::class,
            SettingPoinSeeder::class,
            UserSeeder::class,
            LokasiSeeder::class,      // ← Tambahkan ini
            BakSampahSeeder::class,   // ← Tambahkan ini
        ]);
    }
}