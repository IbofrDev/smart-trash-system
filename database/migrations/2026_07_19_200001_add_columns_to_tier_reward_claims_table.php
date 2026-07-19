 
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tier_reward_claims', function (Blueprint $table) {
            $table->foreignId('mahasiswa_id')->after('id')->constrained('mahasiswa')->onDelete('cascade');
            $table->integer('level_urutan')->after('mahasiswa_id');
            $table->string('nama_hadiah')->after('level_urutan');
            $table->string('kode_reward')->unique()->after('nama_hadiah');
            $table->enum('status', ['aktif', 'terpakai', 'expired'])->default('aktif')->after('kode_reward');
            $table->timestamp('used_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('tier_reward_claims', function (Blueprint $table) {
            $table->dropForeign(['mahasiswa_id']);
            $table->dropColumn(['mahasiswa_id', 'level_urutan', 'nama_hadiah', 'kode_reward', 'status', 'used_at']);
        });
    }
};