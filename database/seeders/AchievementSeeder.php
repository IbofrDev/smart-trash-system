<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            [
                'nama' => 'First Timer',
                'deskripsi' => 'Selamat! Kamu telah melakukan setoran sampah pertama.',
                'icon' => 'first_timer.png',
                'syarat_type' => 'first_time',
                'syarat_value' => 1,
                'poin_bonus' => 50,
            ],
            [
                'nama' => 'Week Streak',
                'deskripsi' => 'Melakukan setoran sampah selama 7 hari berturut-turut.',
                'icon' => 'week_streak.png',
                'syarat_type' => 'streak',
                'syarat_value' => 7,
                'poin_bonus' => 500,
            ],
            [
                'nama' => 'Monthly Hero',
                'deskripsi' => 'Melakukan 20 kali transaksi dalam satu bulan.',
                'icon' => 'monthly_hero.png',
                'syarat_type' => 'transaksi_count',
                'syarat_value' => 20,
                'poin_bonus' => 500,
            ],
            [
                'nama' => 'Century Club',
                'deskripsi' => 'Total berat sampah yang disetorkan mencapai 100 kg.',
                'icon' => 'century_club.png',
                'syarat_type' => 'total_kg',
                'syarat_value' => 100,
                'poin_bonus' => 1000,
            ],
            [
                'nama' => 'Half Ton Hero',
                'deskripsi' => 'Total berat sampah yang disetorkan mencapai 500 kg.',
                'icon' => 'half_ton_hero.png',
                'syarat_type' => 'total_kg',
                'syarat_value' => 500,
                'poin_bonus' => 2000,
            ],
            [
                'nama' => 'Two Week Warrior',
                'deskripsi' => 'Melakukan setoran sampah selama 14 hari berturut-turut.',
                'icon' => 'two_week_warrior.png',
                'syarat_type' => 'streak',
                'syarat_value' => 14,
                'poin_bonus' => 1000,
            ],
            [
                'nama' => 'Fifty Transactions',
                'deskripsi' => 'Melakukan 50 kali transaksi setoran sampah.',
                'icon' => 'fifty_transactions.png',
                'syarat_type' => 'transaksi_count',
                'syarat_value' => 50,
                'poin_bonus' => 1500,
            ],
            [
                'nama' => 'Ton Master',
                'deskripsi' => 'Total berat sampah yang disetorkan mencapai 1000 kg.',
                'icon' => 'ton_master.png',
                'syarat_type' => 'total_kg',
                'syarat_value' => 1000,
                'poin_bonus' => 5000,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }
}