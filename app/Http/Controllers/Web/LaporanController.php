<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TransaksiSampah;
use App\Models\Mahasiswa;
use App\Models\JenisSampah;
use App\Models\BakSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Laporan Transaksi
     */
    public function transaksi(Request $request)
    {
        $tanggalDari = $request->get('tanggal_dari', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggalSampai = $request->get('tanggal_sampai', Carbon::now()->format('Y-m-d'));

        $query = TransaksiSampah::with(['mahasiswa', 'jenisSampah', 'bakSampah'])
            ->whereDate('tanggal_transaksi', '>=', $tanggalDari)
            ->whereDate('tanggal_transaksi', '<=', $tanggalSampai);

        // Summary — tambah total_koin + total_botol
        $summary = [
            'total_transaksi' => (clone $query)->count(),
            'total_berat' => (clone $query)->sum('berat'),
            'total_poin' => (clone $query)->sum('poin_didapat'),
            'total_koin' => (clone $query)->sum('koin_didapat'),
            'total_botol' => (clone $query)->sum('jumlah_final'),
            'mahasiswa_aktif' => (clone $query)->distinct('mahasiswa_id')->count('mahasiswa_id'),
            'total_anomali' => (clone $query)->where('status_validasi', 'anomali')->count(),
        ];

        // Per Jenis Sampah — tambah total_koin + total_botol
        $perJenisSampah = TransaksiSampah::select(
            'jenis_sampah_id',
            DB::raw('SUM(berat) as total_berat'),
            DB::raw('SUM(poin_didapat) as total_poin'),
            DB::raw('SUM(koin_didapat) as total_koin'),
            DB::raw('SUM(jumlah_final) as total_botol'),
            DB::raw('COUNT(*) as total_transaksi')
        )
            ->whereDate('tanggal_transaksi', '>=', $tanggalDari)
            ->whereDate('tanggal_transaksi', '<=', $tanggalSampai)
            ->groupBy('jenis_sampah_id')
            ->with('jenisSampah')
            ->get();

        // Per Hari (untuk chart) — tambah total_koin
        $perHari = TransaksiSampah::select(
            DB::raw('DATE(tanggal_transaksi) as tanggal'),
            DB::raw('SUM(berat) as total_berat'),
            DB::raw('SUM(koin_didapat) as total_koin'),
            DB::raw('COUNT(*) as total_transaksi')
        )
            ->whereDate('tanggal_transaksi', '>=', $tanggalDari)
            ->whereDate('tanggal_transaksi', '<=', $tanggalSampai)
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Detail transaksi
        $transaksis = $query->orderBy('tanggal_transaksi', 'desc')->get();

        return view('admin.laporan.transaksi', compact(
            'tanggalDari',
            'tanggalSampai',
            'summary',
            'perJenisSampah',
            'perHari',
            'transaksis'
        ));
    }

    /**
     * Export PDF Laporan Transaksi
     */
    public function transaksiPdf(Request $request)
    {
        $tanggalDari = $request->get('tanggal_dari', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggalSampai = $request->get('tanggal_sampai', Carbon::now()->format('Y-m-d'));

        $transaksis = TransaksiSampah::with(['mahasiswa', 'jenisSampah', 'bakSampah'])
            ->whereDate('tanggal_transaksi', '>=', $tanggalDari)
            ->whereDate('tanggal_transaksi', '<=', $tanggalSampai)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $summary = [
            'total_transaksi' => $transaksis->count(),
            'total_berat' => $transaksis->sum('berat'),
            'total_poin' => $transaksis->sum('poin_didapat'),
            'total_koin' => $transaksis->sum('koin_didapat'),
            'total_botol' => $transaksis->sum('jumlah_final'),
            'mahasiswa_aktif' => $transaksis->pluck('mahasiswa_id')->unique()->count(),
            'total_anomali' => $transaksis->where('status_validasi', 'anomali')->count(),
        ];

        $pdf = Pdf::loadView('admin.laporan.transaksi-pdf', compact(
            'tanggalDari',
            'tanggalSampai',
            'transaksis',
            'summary'
        ));

        $filename = "laporan-transaksi-{$tanggalDari}-to-{$tanggalSampai}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Laporan Mahasiswa
     */
    public function mahasiswa(Request $request)
    {
        $query = Mahasiswa::with('level')
            ->withCount('transaksiSampah')
            ->withSum('transaksiSampah', 'berat')
            ->withSum('transaksiSampah', 'poin_didapat')
            ->withSum('transaksiSampah', 'koin_didapat')
            ->withSum('transaksiSampah', 'jumlah_final');

        // Sort
        $sortBy = $request->get('sort', 'total_poin');
        $sortDir = $request->get('dir', 'desc');

        if ($sortBy === 'total_berat') {
            $query->orderBy('transaksi_sampah_sum_berat', $sortDir);
        } elseif ($sortBy === 'total_koin') {
            $query->orderBy('total_koin_botol', $sortDir);
        } elseif ($sortBy === 'transaksi_count') {
            $query->orderBy('transaksi_sampah_count', $sortDir);
        } else {
            $query->orderBy($sortBy, $sortDir);
        }

        $mahasiswas = $query->get();

        // Summary
        $summary = [
            'total_mahasiswa' => $mahasiswas->count(),
            'total_poin' => $mahasiswas->sum('total_poin'),
            'total_koin' => $mahasiswas->sum('total_koin_botol'),
            'total_berat' => $mahasiswas->sum('transaksi_sampah_sum_berat'),
            'total_transaksi' => $mahasiswas->sum('transaksi_sampah_count'),
            'total_botol' => $mahasiswas->sum('transaksi_sampah_sum_jumlah_final'),
        ];

        return view('admin.laporan.mahasiswa', compact('mahasiswas', 'summary'));
    }

    /**
     * Export PDF Laporan Mahasiswa
     */
    public function mahasiswaPdf()
    {
        $mahasiswas = Mahasiswa::with('level')
            ->withCount('transaksiSampah')
            ->withSum('transaksiSampah', 'berat')
            ->withSum('transaksiSampah', 'poin_didapat')
            ->withSum('transaksiSampah', 'koin_didapat')
            ->withSum('transaksiSampah', 'jumlah_final')
            ->orderBy('total_poin', 'desc')
            ->get();

        $summary = [
            'total_mahasiswa' => $mahasiswas->count(),
            'total_poin' => $mahasiswas->sum('total_poin'),
            'total_koin' => $mahasiswas->sum('total_koin_botol'),
            'total_berat' => $mahasiswas->sum('transaksi_sampah_sum_berat'),
            'total_transaksi' => $mahasiswas->sum('transaksi_sampah_count'),
            'total_botol' => $mahasiswas->sum('transaksi_sampah_sum_jumlah_final'),
        ];

        $pdf = Pdf::loadView('admin.laporan.mahasiswa-pdf', compact('mahasiswas', 'summary'));

        return $pdf->download('laporan-mahasiswa-' . date('Y-m-d') . '.pdf');
    }
}