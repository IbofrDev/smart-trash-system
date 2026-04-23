<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransaksiSampah;
use App\Models\VoucherMahasiswa;
use App\Models\SettingPoin;

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
        $totalBeratGram = (int) TransaksiSampah::where('mahasiswa_id', $mahasiswa->id)
            ->sum('berat');

        $totalBotol = (int) TransaksiSampah::where('mahasiswa_id', $mahasiswa->id)
            ->sum('jumlah_final');

        $totalTransaksi = TransaksiSampah::where('mahasiswa_id', $mahasiswa->id)
            ->count();

        $ranking = $mahasiswa->leaderboard->ranking_alltime ?? null;

        // Voucher stats
        $koinPerVoucher = (int) SettingPoin::where('nama_setting', 'koin_per_voucher')->value('value');

        $voucherAktif = VoucherMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'aktif')
            ->where('expired_at', '>', now())
            ->count();

        $recentTransactions = TransaksiSampah::where('mahasiswa_id', $mahasiswa->id)
            ->with(['bakSampah.lokasi', 'jenisSampah'])
            ->orderByDesc('tanggal_transaksi')
            ->limit(3)
            ->get()
            ->map(function ($transaksi) {
                return [
                    'id' => $transaksi->id,
                    'jenis_sampah' => $transaksi->jenisSampah?->nama ?? '-',
                    'berat' => (int) $transaksi->berat,
                    'jumlah_final' => $transaksi->jumlah_final,
                    'poin' => $transaksi->poin_didapat,
                    'koin' => $transaksi->koin_didapat,
                    'status_validasi' => $transaksi->status_validasi,
                    'lokasi' => $transaksi->bakSampah?->lokasi?->nama_lokasi ?? '-',
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
            if ($mahasiswa->total_poin < $currentLevel->min_poin) {
                $levelProgress = 0;
            } else {
                $range = $nextLevel->min_poin - $currentLevel->min_poin;
                $progress = $mahasiswa->total_poin - $currentLevel->min_poin;
                $levelProgress = min(100, max(0, ($progress / $range) * 100));
            }
        } else {
            $levelProgress = 100;
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
                'koin' => [
                    'total' => $mahasiswa->total_koin_botol,
                    'koin_per_voucher' => $koinPerVoucher,
                    'voucher_aktif' => $voucherAktif,
                ],
                'stats' => [
                    'total_berat_gram' => $totalBeratGram,
                    'total_botol' => $totalBotol,
                    'total_transaksi' => $totalTransaksi,
                    'total_koin' => $mahasiswa->total_koin_botol,
                    'ranking' => $ranking,
                ],
                'recent_transactions' => $recentTransactions,
            ],
        ]);
    }
}