<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah enum role: tambah 'kasir'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pengelola', 'kasir') DEFAULT 'pengelola'");
    }

    public function down(): void
    {
        // Kembalikan ke semula
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pengelola') DEFAULT 'pengelola'");
    }
};