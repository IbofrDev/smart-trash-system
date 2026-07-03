<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\Level;
use App\Models\Achievement;
use App\Models\Leaderboard;
use App\Models\TransaksiSampah;
use App\Models\Notifikasi;
use App\Models\SettingPoin;
use Illuminate\Support\Facades\DB;

class GamifikasiService
{
    /**
     * Check dan update level mahasiswa berdasarkan total poin
     */
    public function checkLevelUp(Mahasiswa $mahasiswa): ?array
    {
        $currentLevel = $mahasiswa->level;

        $newLevel = Level::where('min_poin', '<=', $mahasiswa->total_poin)
            ->where('max_poin', '>=', $mahasiswa->total_poin)
            ->first();

        if (!$newLevel || $newLevel->id === $currentLevel->id) {
            return null;
        }

        $mahasiswa->update(['level_id' => $newLevel->id]);

        $bonusPoin = (int) (SettingPoin::where('nama_setting', 'bonus_level_up')->value('value') ?? 0);
        if ($bonusPoin > 0) {
            $mahasiswa->increment('total_poin', $bonusPoin);
        }

        Notifikasi::create([
            'mahasiswa_id' => $mahasiswa->id,
            'judul' => 'Level Up! 🎉',
            'pesan' => "Selamat! Kamu naik ke level {$newLevel->nama_level}!"
                . ($bonusPoin > 0 ? " Bonus +{$bonusPoin} poin!" : ""),
            'tipe' => 'level_up',
            'is_read' => 0,
        ]);

        return [
            'old_level' => $currentLevel->nama_level,
            'new_level' => $newLevel->nama_level,
            'bonus_poin' => $bonusPoin,
        ];
    }

    /**
     * Check dan unlock achievement
     */
    public function checkAchievements(Mahasiswa $mahasiswa): array
    {
        $unlockedAchievements = [];

        $achievements = Achievement::whereNotIn('id', function ($query) use ($mahasiswa) {
            $query->select('achievement_id')
                ->from('mahasiswa_achievement')
                ->where('mahasiswa_id', $mahasiswa->id);
        })->get();

        foreach ($achievements as $achievement) {
            $unlocked = false;

            switch ($achievement->syarat_type) {
                case 'first_time':
                    $transaksiCount = TransaksiSampah::where('mahasiswa_id', $mahasiswa->id)->count();
                    $unlocked = ($transaksiCount >= 1);
                    break;

                case 'total_kg':
                    // berat disimpan dalam gram, konversi ke kg
                    $totalBeratKg = TransaksiSampah::where('mahasiswa_id', $mahasiswa->id)->sum('berat') / 1000;
                    $unlocked = ($totalBeratKg >= $achievement->syarat_value);
                    break;

                case 'total_botol':
                    $totalBotol = TransaksiSampah::where('mahasiswa_id', $mahasiswa->id)->sum('jumlah_final');
                    $unlocked = ($totalBotol >= $achievement->syarat_value);
                    break;

                case 'transaksi_count':
                    $transaksiCount = TransaksiSampah::where('mahasiswa_id', $mahasiswa->id)->count();
                    $unlocked = ($transaksiCount >= $achievement->syarat_value);
                    break;

                case 'streak':
                    $unlocked = $this->checkStreak($mahasiswa, $achievement->syarat_value);
                    break;
            }

            if ($unlocked) {
                DB::table('mahasiswa_achievement')->insert([
                    'mahasiswa_id' => $mahasiswa->id,
                    'achievement_id' => $achievement->id,
                    'unlocked_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($achievement->poin_bonus > 0) {
                    $mahasiswa->increment('total_poin', $achievement->poin_bonus);
                }

                Notifikasi::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'judul' => 'Achievement Unlocked! 🏆',
                    'pesan' => "Kamu mendapatkan achievement '{$achievement->nama}'!"
                        . ($achievement->poin_bonus > 0 ? " Bonus +{$achievement->poin_bonus} poin!" : ""),
                    'tipe' => 'achievement',
                    'is_read' => 0,
                ]);

                $unlockedAchievements[] = [
                    'achievement' => $achievement->nama,
                    'bonus_poin' => $achievement->poin_bonus,
                ];
            }
        }

        return $unlockedAchievements;
    }

    /**
     * Check streak hari berturut-turut
     */
    private function checkStreak(Mahasiswa $mahasiswa, int $targetStreak): bool
    {
        $today = now()->format('Y-m-d');

        $dates = TransaksiSampah::where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('tanggal_transaksi', 'desc')
            ->pluck('tanggal_transaksi')
            ->map(fn($d) => $d->format('Y-m-d'))
            ->unique()
            ->values();

        // Streak harus dimulai dari hari ini
        if ($dates->isEmpty() || $dates->first() !== $today) {
            return false;
        }

        if ($dates->count() < $targetStreak) {
            return false;
        }

        $streak = 1;
        for ($i = 0; $i < $dates->count() - 1; $i++) {
            $current = \Carbon\Carbon::parse($dates[$i]);
            $next = \Carbon\Carbon::parse($dates[$i + 1]);

            if ($current->diffInDays($next) === 1) {
                $streak++;
                if ($streak >= $targetStreak) {
                    return true;
                }
            } else {
                break; // streak sudah putus, tidak perlu lanjut
            }
        }

        return $streak >= $targetStreak;
    }

    /**
     * Update atau buat entry leaderboard
     */
    public function updateLeaderboard(Mahasiswa $mahasiswa): void
    {
        $totalBeratGram = (int) TransaksiSampah::where('mahasiswa_id', $mahasiswa->id)->sum('berat');
        $totalBotol = (int) TransaksiSampah::where('mahasiswa_id', $mahasiswa->id)->sum('jumlah_final');

        $leaderboard = Leaderboard::where('mahasiswa_id', $mahasiswa->id)->first();

        if ($leaderboard) {
            $leaderboard->update([
                'total_berat_gram' => $totalBeratGram,
                'total_botol' => $totalBotol,
                'updated_at' => now(),
            ]);
        } else {
            DB::table('leaderboard')->insert([
                'mahasiswa_id' => $mahasiswa->id,
                'total_berat_gram' => $totalBeratGram,
                'total_botol' => $totalBotol,
                'updated_at' => now(),
            ]);
        }

        // Recalculate semua periode ranking
        $this->recalculateRanking('ranking_alltime', 'total_poin');
        $this->recalculateRanking('ranking_mingguan', 'total_poin');
        $this->recalculateRanking('ranking_bulanan', 'total_poin');
        $this->recalculateRanking('ranking_harian', 'total_poin');
    }

    /**
     * Recalculate ranking
     */
    private function recalculateRanking(string $rankingColumn, string $orderBy): void
    {
        $mahasiswas = DB::table('leaderboard')
            ->join('mahasiswa', 'leaderboard.mahasiswa_id', '=', 'mahasiswa.id')
            ->orderBy('mahasiswa.' . $orderBy, 'desc')
            ->pluck('leaderboard.mahasiswa_id');

        foreach ($mahasiswas as $index => $mahasiswaId) {
            DB::table('leaderboard')
                ->where('mahasiswa_id', $mahasiswaId)
                ->update([$rankingColumn => $index + 1]);
        }
    }

}