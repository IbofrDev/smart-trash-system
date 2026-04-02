<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@smarttrash.com',
            'password' => Hash::make('password123'),
            'phone' => '081234567890',
            'role' => 'admin',
            'is_active' => 1,
        ]);

        // Pengelola
        User::create([
            'name' => 'Pengelola 1',
            'email' => 'pengelola@smarttrash.com',
            'password' => Hash::make('password123'),
            'phone' => '081234567891',
            'role' => 'pengelola',
            'is_active' => 1,
        ]);
    }
}