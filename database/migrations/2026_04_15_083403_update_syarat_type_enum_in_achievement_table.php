<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE achievement MODIFY COLUMN syarat_type ENUM('total_kg','total_botol','streak','transaksi_count','first_time') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE achievement MODIFY COLUMN syarat_type ENUM('total_kg','streak','transaksi_count','first_time') NOT NULL");
    }
};