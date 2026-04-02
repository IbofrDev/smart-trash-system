<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [
                'nama_level' => 'Eco Starter',
                'min_poin' => 0,
                'max_poin' => 999,
                'urutan' => 1,
            ],
            [
                'nama_level' => 'Green Warrior',
                'min_poin' => 1000,
                'max_poin' => 2999,
                'urutan' => 2,
            ],
            [
                'nama_level' => 'Recycler',
                'min_poin' => 3000,
                'max_poin' => 5999,
                'urutan' => 3,
            ],
            [
                'nama_level' => 'Eco Champion',
                'min_poin' => 6000,
                'max_poin' => 9999,
                'urutan' => 4,
            ],
            [
                'nama_level' => 'Planet Guardian',
                'min_poin' => 10000,
                'max_poin' => 19999,
                'urutan' => 5,
            ],
            [
                'nama_level' => 'Eco Legend',
                'min_poin' => 20000,
                'max_poin' => 999999,
                'urutan' => 6,
            ],
        ];

        foreach ($levels as $level) {
            Level::create($level);
        }
    }
}