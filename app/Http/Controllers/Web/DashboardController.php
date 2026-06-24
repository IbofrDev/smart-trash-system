<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\TransaksiSampah;
use App\Models\BakSampah;
use App\Models\VoucherMahasiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Statistik Umum
        $stats = [
            'total_mahasiswa' => Mahasiswa::count(),
            'total_transaksi' => TransaksiSampah::count(),
            'total_berat' => (int) TransaksiSampah::sum('berat'),
            'total_botol' => (int) TransaksiSampah::sum('jumlah_final'),
            'total_poin' => (int) TransaksiSampah::sum('poin_didapat'),
            'total_koin_distributed' => (int) TransaksiSampah::sum('koin_didapat'),
            'bak_aktif' => BakSampah::where('status', 'aktif')->count(),
            'total_bak' => BakSampah::count(),
            'voucher_aktif' => VoucherMahasiswa::where('status', 'aktif')
                ->where('expired_at', '>', now())
                ->count(),
            'voucher_total' => VoucherMahasiswa::count(),
        ];

        // Transaksi Hari Ini
        $today = Carbon::today();
        $statsToday = [
            'transaksi' => TransaksiSampah::whereDate('tanggal_transaksi', $today)->count(),
            'berat_gram' => (int) TransaksiSampah::whereDate('tanggal_transaksi', $today)->sum('berat'),
            'botol' => (int) TransaksiSampah::whereDate('tanggal_transaksi', $today)->sum('jumlah_final'),
            'poin' => (int) TransaksiSampah::whereDate('tanggal_transaksi', $today)->sum('poin_didapat'),
            'koin' => (int) TransaksiSampah::whereDate('tanggal_transaksi', $today)->sum('koin_didapat'),
        ];

        // Transaksi 7 Hari Terakhir (untuk chart)
        $perHari = TransaksiSampah::select(
            DB::raw('DATE(tanggal_transaksi) as tanggal'),
            DB::raw('SUM(berat) as perHari'),
            DB::raw('SUM(jumlah_final) as total_botol'),
            DB::raw('COUNT(*) as total_transaksi')
        )
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Top 5 Mahasiswa (by total poin)
        $topMahasiswa = Mahasiswa::with('level')
            ->orderByDesc('total_poin')
            ->limit(5)
            ->get();

        // Transaksi Terbaru
        $recentTransaksi = TransaksiSampah::with(['mahasiswa', 'bakSampah'])
            ->orderByDesc('tanggal_transaksi')
            ->limit(10)
            ->get();

        // Voucher Terbaru
        $recentVoucher = VoucherMahasiswa::with('mahasiswa')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Statistik Validasi (valid vs anomali)
        $validasiStats = TransaksiSampah::select(
            'status_validasi',
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('status_validasi')
            ->get()
            ->keyBy('status_validasi');

        $jenisSampahStats = TransaksiSampah::with('jenisSampah')
            ->select(
                'jenis_sampah_id',
                DB::raw('SUM(berat)/1000 as total_berat')
            )
            ->groupBy('jenis_sampah_id')
            ->get();

        return view('admin.dashboard', compact(
            'user',
            'stats',
            'statsToday',
            'perHari',
            'topMahasiswa',
            'recentTransaksi',
            'recentVoucher',
            'validasiStats',
            'jenisSampahStats' // ✅ TAMBAHKAN INI
        ));
    }
}