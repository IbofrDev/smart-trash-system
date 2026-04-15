<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingPoinSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('setting_poin')->truncate();

        DB::table('setting_poin')->insert([
            [
                'nama_setting' => 'koin_per_voucher',
                'value'        => 20,
                'deskripsi'    => 'Jumlah koin yang dibutuhkan untuk menukar 1 voucher',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'nama_setting' => 'voucher_expired_days',
                'value'        => 7,
                'deskripsi'    => 'Masa berlaku voucher dalam hari',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'nama_setting' => 'voucher_min_belanja',
                'value'        => 10000,
                'deskripsi'    => 'Minimal belanja di ETU untuk menggunakan voucher (Rp)',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'nama_setting' => 'voucher_max_diskon',
                'value'        => 5000,
                'deskripsi'    => 'Maksimal diskon yang diberikan voucher (Rp)',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'nama_setting' => 'timeout_ultrasonik',
                'value'        => 30,
                'deskripsi'    => 'Timeout deteksi ultrasonik dalam detik',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);
    }
}