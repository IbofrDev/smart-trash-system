<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\TransaksiSampah;
use App\Models\BakSampah;
use App\Models\JenisSampah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard
     */
    public function index()
    {
        $user = auth()->user();

        // Statistik Umum
        $stats = [
            'total_mahasiswa' => Mahasiswa::count(),
            'total_transaksi' => TransaksiSampah::count(),
            'total_berat' => TransaksiSampah::sum('berat'),
            'total_poin_distributed' => TransaksiSampah::sum('poin_didapat'),
            'bak_sampah_aktif' => BakSampah::where('status', 'aktif')->count(),
            'bak_sampah_total' => BakSampah::count(),
        ];

        // Transaksi Hari Ini
        $today = Carbon::today();
        $statsToday = [
            'transaksi' => TransaksiSampah::whereDate('tanggal_transaksi', $today)->count(),
            'berat' => TransaksiSampah::whereDate('tanggal_transaksi', $today)->sum('berat'),
            'poin' => TransaksiSampah::whereDate('tanggal_transaksi', $today)->sum('poin_didapat'),
        ];

        // Transaksi 7 Hari Terakhir (untuk chart)
        $chartData = TransaksiSampah::select(
                DB::raw('DATE(tanggal_transaksi) as tanggal'),
                DB::raw('SUM(berat) as total_berat'),
                DB::raw('COUNT(*) as total_transaksi')
            )
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Top 5 Mahasiswa (by total poin)
        $topMahasiswa = Mahasiswa::with('level')
            ->orderBy('total_poin', 'desc')
            ->limit(5)
            ->get();

        // Distribusi Jenis Sampah
        $jenisSampahStats = TransaksiSampah::select(
                'jenis_sampah_id',
                DB::raw('SUM(berat) as total_berat'),
                DB::raw('COUNT(*) as total_transaksi')
            )
            ->with('jenisSampah:id,nama')
            ->groupBy('jenis_sampah_id')
            ->orderBy('total_berat', 'desc')
            ->get();

        // Transaksi Terbaru
        $recentTransaksi = TransaksiSampah::with(['mahasiswa', 'jenisSampah', 'bakSampah'])
            ->orderBy('tanggal_transaksi', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'user',
            'stats',
            'statsToday',
            'chartData',
            'topMahasiswa',
            'jenisSampahStats',
            'recentTransaksi'
        ));
    }
}