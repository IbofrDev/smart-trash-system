<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('achievement')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('achievement')->insert([
            [
                'nama' => 'First Timer',
                'deskripsi' => 'Selamat! Kamu berhasil melakukan transaksi pertamamu.',
                'syarat_type' => 'first_time',
                'syarat_value' => 1,
                'poin_bonus' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Collector 50',
                'deskripsi' => 'Kumpulkan total 50 botol atau kaleng.',
                'syarat_type' => 'total_botol',
                'syarat_value' => 50,
                'poin_bonus' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Collector 100',
                'deskripsi' => 'Kumpulkan total 100 botol atau kaleng.',
                'syarat_type' => 'total_botol',
                'syarat_value' => 100,
                'poin_bonus' => 500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Collector 500',
                'deskripsi' => 'Kumpulkan total 500 botol atau kaleng.',
                'syarat_type' => 'total_botol',
                'syarat_value' => 500,
                'poin_bonus' => 2000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Week Streak',
                'deskripsi' => 'Buang sampah 7 hari berturut-turut.',
                'syarat_type' => 'streak',
                'syarat_value' => 7,
                'poin_bonus' => 300,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dedicated 20',
                'deskripsi' => 'Lakukan 20 kali transaksi buang sampah.',
                'syarat_type' => 'transaksi_count',
                'syarat_value' => 20,
                'poin_bonus' => 400,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kilogram Master',
                'deskripsi' => 'Kumpulkan total berat 10 kg sampah.',
                'syarat_type' => 'total_kg',
                'syarat_value' => 10,
                'poin_bonus' => 1000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}