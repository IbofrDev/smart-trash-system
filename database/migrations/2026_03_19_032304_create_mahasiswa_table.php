<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('google_id', 100)->unique();
            $table->string('email', 75)->unique();
            $table->string('name', 100);
            $table->string('avatar', 255)->nullable();
            $table->string('prodi', 30)->nullable();
            $table->string('nim', 20)->nullable();
            $table->string('rfid_uid', 20)->unique()->nullable();
            $table->integer('total_poin')->default(0);
            $table->foreignId('level_id')->constrained('level')->default(1);
            $table->string('fcm_token', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};