<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\Level;
use App\Models\Achievement;
use App\Models\Leaderboard;
use App\Models\TransaksiSampah;
use App\Models\Notifikasi;
use App\Models\SettingPoin;
use App\Models\TierRewardClaim;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class GamifikasiService
{
    /**
     * Check dan update level mahasiswa berdasarkan total poin
     */
    // Mapping hadiah per tier (urutan level)
    private array $tierRewards = [
        2 => ['Air mineral 600mL', 'Teh gelas', 'Es teh', 'Permen', 'Jelly'],
        3 => ['Roti', 'Biskuit', 'Wafer', 'Mie cup', 'Susu UHT'],
        4 => ['Kopi botol', 'Minuman isotonik', 'Jus', 'Voucher makan Rp10.000', 'Paket snack'],
        5 => ['Paket nasi + ayam', 'Paket nasi + telur'],
    ];

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

        // 🎁 Buat reward jika tier punya hadiah (urutan 2-5)
        $rewardInfo = null;
        if (isset($this->tierRewards[$newLevel->urutan])) {
            $alreadyClaimed = TierRewardClaim::where('mahasiswa_id', $mahasiswa->id)
                ->where('level_urutan', $newLevel->urutan)
                ->exists();

            if (!$alreadyClaimed) {
                $hadiah = $this->tierRewards[$newLevel->urutan][0]; // default hadiah pertama di tier

                do {
                    $kode = 'RWD-' . strtoupper(Str::random(6));
                } while (TierRewardClaim::where('kode_reward', $kode)->exists());

                TierRewardClaim::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'level_urutan' => $newLevel->urutan,
                    'nama_hadiah'  => $hadiah,
                    'kode_reward'  => $kode,
                    'status'       => 'aktif',
                ]);

                $rewardInfo = $hadiah;

                Notifikasi::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'judul'        => '🎁 Hadiah Tier Tersedia!',
                    'pesan'        => "Selamat naik ke {$newLevel->nama_level}! Kamu mendapat hadiah: {$hadiah}. Kode reward sudah tersedia di menu Reward.",
                    'tipe'         => 'level_up',
                    'is_read'      => 0,
                ]);
            }
        } else {
            Notifikasi::create([
                'mahasiswa_id' => $mahasiswa->id,
                'judul' => 'Level Up! 🎉',
                'pesan' => "Selamat! Kamu naik ke level {$newLevel->nama_level}!"
                    . ($bonusPoin > 0 ? " Bonus +{$bonusPoin} poin!" : ""),
                'tipe' => 'level_up',
                'is_read' => 0,
            ]);
        }

        return [
            'old_level'   => $currentLevel->nama_level,
            'new_level'   => $newLevel->nama_level,
            'bonus_poin'  => $bonusPoin,
            'reward'      => $rewardInfo,
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
            ->where('status_validasi', 'valid')
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
        // Hanya hitung transaksi VALID
        $validQuery = TransaksiSampah::where('mahasiswa_id', $mahasiswa->id)
            ->where('status_validasi', 'valid');

        $totalBeratGram = (int) (clone $validQuery)->sum('berat');
        $totalBotol = (int) (clone $validQuery)->sum('jumlah_final');
        $totalKoin = (int) (clone $validQuery)->sum('koin_didapat');

        // Sync total_koin_botol di mahasiswa dari sumber kebenaran
        $mahasiswa->update(['total_koin_botol' => $totalKoin]);

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

        // Recalculate semua periode ranking berdasarkan total koin
        $this->recalculateRanking('ranking_alltime', 'total_koin_botol');
        $this->recalculateRanking('ranking_mingguan', 'total_koin_botol');
        $this->recalculateRanking('ranking_bulanan', 'total_koin_botol');
        $this->recalculateRanking('ranking_harian', 'total_koin_botol');
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