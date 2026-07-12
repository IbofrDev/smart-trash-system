<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\TransaksiSampah;
use App\Models\TransaksiSession;
use App\Models\BakSampah;
use App\Models\VoucherMahasiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function chartData(\Illuminate\Http\Request $request)
    {
        $periode = (int) $request->input('periode', 7);
        if (!in_array($periode, [3, 7, 30])) {
            $periode = 7;
        }

        $rawPerHari = TransaksiSampah::select(
            DB::raw('DATE(tanggal_transaksi) as tanggal'),
            DB::raw('SUM(berat) as total_berat_gram')
        )
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays($periode - 1)->startOfDay())
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $labels = [];
        $data = [];
        for ($i = $periode - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $key = $date->format('Y-m-d');
            $row = $rawPerHari->get($key);
            $labels[] = $date->format('d M');
            $data[] = (float) ($row->total_berat_gram ?? 0);
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'periode' => $periode,
        ]);
    }

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
        $rawPerHari = TransaksiSampah::select(
            DB::raw('DATE(tanggal_transaksi) as tanggal'),
            DB::raw('SUM(berat) as total_berat_gram'),
            DB::raw('SUM(jumlah_final) as total_botol'),
            DB::raw('COUNT(*) as total_transaksi')
        )
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $perHari = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $row = $rawPerHari->get($date);
            $perHari->push((object) [
                'tanggal' => $date,
                'total_berat_gram' => $row->total_berat_gram ?? 0,
                'total_botol' => $row->total_botol ?? 0,
                'total_transaksi' => $row->total_transaksi ?? 0,
            ]);
        }

        // Top 5 Mahasiswa (by total poin)
        $topMahasiswa = Mahasiswa::with('level')
            ->orderByDesc('total_poin')
            ->limit(5)
            ->get();

            // Transaksi Terbaru (session-based)
        $recentTransaksi = TransaksiSession::with([
            'transaksiItems.jenisSampah',
            'transaksiItems.bakSampah',
            'transaksiItems.mahasiswa',
        ])
            ->where('status', 'completed')
            ->whereHas('transaksiItems')
            ->orderByDesc('completed_at')
            ->limit(8)
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