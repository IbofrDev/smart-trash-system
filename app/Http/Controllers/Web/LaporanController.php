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

        // Summary
        $summary = [
            'total_transaksi' => (clone $query)->count(),
            'total_berat' => (clone $query)->sum('berat'),
            'total_poin' => (clone $query)->sum('poin_didapat'),
            'mahasiswa_aktif' => (clone $query)->distinct('mahasiswa_id')->count('mahasiswa_id'),
        ];

        // Per Jenis Sampah
        $perJenisSampah = TransaksiSampah::select(
                'jenis_sampah_id',
                DB::raw('SUM(berat) as total_berat'),
                DB::raw('SUM(poin_didapat) as total_poin'),
                DB::raw('COUNT(*) as total_transaksi')
            )
            ->whereDate('tanggal_transaksi', '>=', $tanggalDari)
            ->whereDate('tanggal_transaksi', '<=', $tanggalSampai)
            ->groupBy('jenis_sampah_id')
            ->with('jenisSampah')
            ->get();

        // Per Hari (untuk chart)
        $perHari = TransaksiSampah::select(
                DB::raw('DATE(tanggal_transaksi) as tanggal'),
                DB::raw('SUM(berat) as total_berat'),
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
            ->withSum('transaksiSampah', 'berat');

        // Sort
        $sortBy = $request->get('sort', 'total_poin');
        $sortDir = $request->get('dir', 'desc');

        if ($sortBy === 'total_berat') {
            $query->orderBy('transaksi_sampah_sum_berat', $sortDir);
        } else {
            $query->orderBy($sortBy, $sortDir);
        }

        $mahasiswas = $query->get();

        // Summary
        $summary = [
            'total_mahasiswa' => $mahasiswas->count(),
            'total_poin' => $mahasiswas->sum('total_poin'),
            'total_berat' => $mahasiswas->sum('transaksi_sampah_sum_berat'),
            'total_transaksi' => $mahasiswas->sum('transaksi_sampah_count'),
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
            ->orderBy('total_poin', 'desc')
            ->get();

        $summary = [
            'total_mahasiswa' => $mahasiswas->count(),
            'total_poin' => $mahasiswas->sum('total_poin'),
            'total_berat' => $mahasiswas->sum('transaksi_sampah_sum_berat'),
        ];

        $pdf = Pdf::loadView('admin.laporan.mahasiswa-pdf', compact('mahasiswas', 'summary'));

        return $pdf->download('laporan-mahasiswa-' . date('Y-m-d') . '.pdf');
    }
}