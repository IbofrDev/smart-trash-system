<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransaksiSampah;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get Dashboard Summary
     * GET /api/dashboard
     */
    public function index(Request $request)
    {
        $mahasiswa = $request->user();

        // Summary statistik
        $totalBerat = TransaksiSampah::where('mahasiswa_id', $mahasiswa->id)
            ->sum('berat');

        $totalTransaksi = TransaksiSampah::where('mahasiswa_id', $mahasiswa->id)
            ->count();

        $ranking = $mahasiswa->leaderboard->ranking_alltime ?? null;

        // Recent transactions (3 terakhir)
        $recentTransactions = TransaksiSampah::where('mahasiswa_id', $mahasiswa->id)
            ->with(['jenisSampah', 'bakSampah.lokasi'])
            ->orderBy('tanggal_transaksi', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($transaksi) {
                return [
                    'id' => $transaksi->id,
                    'jenis_sampah' => $transaksi->jenisSampah->nama,
                    'berat' => $transaksi->berat,
                    'poin' => $transaksi->poin_didapat,
                    'lokasi' => $transaksi->bakSampah->nama ?? '-',
                    'tanggal' => $transaksi->tanggal_transaksi,
                ];
            });

        // Level progress
        $currentLevel = $mahasiswa->level;
        $nextLevel = \App\Models\Level::where('urutan', '>', $currentLevel->urutan)
            ->orderBy('urutan', 'asc')
            ->first();

        $levelProgress = 0;
        if ($nextLevel) {
            // Fix: pastikan poin mahasiswa >= min_poin level saat ini
            if ($mahasiswa->total_poin < $currentLevel->min_poin) {
                // Edge case: poin lebih kecil dari min level (data manual)
                $levelProgress = 0;
            } else {
                $range = $nextLevel->min_poin - $currentLevel->min_poin;
                $progress = $mahasiswa->total_poin - $currentLevel->min_poin;
                $levelProgress = min(100, max(0, ($progress / $range) * 100)); // ← Tambahkan max(0, ...)
            }
        } else {
            $levelProgress = 100; // Max level
        }

        return response()->json([
            'success' => true,
            'data' => [
                'mahasiswa' => [
                    'name' => $mahasiswa->name,
                    'avatar' => $mahasiswa->avatar,
                    'nim' => $mahasiswa->nim,
                    'prodi' => $mahasiswa->prodi,
                ],
                'poin' => [
                    'total' => $mahasiswa->total_poin,
                    'level' => $currentLevel->nama_level,
                    'level_urutan' => $currentLevel->urutan,
                    'next_level' => $nextLevel ? $nextLevel->nama_level : null,
                    'progress_percentage' => round($levelProgress, 2),
                ],
                'stats' => [
                    'total_berat_kg' => round($totalBerat, 2),
                    'total_transaksi' => $totalTransaksi,
                    'ranking' => $ranking,
                ],
                'recent_transactions' => $recentTransactions,
            ]
        ], 200);
    }
}