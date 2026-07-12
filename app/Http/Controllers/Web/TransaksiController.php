<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TransaksiSampah;
use App\Models\TransaksiSession;
use App\Models\Mahasiswa;
use App\Models\JenisSampah;
use App\Models\BakSampah;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransaksiController extends Controller
{
       public function index(Request $request)
    {
        $query = TransaksiSession::with([
            'transaksiItems.jenisSampah',
            'transaksiItems.bakSampah.lokasi',
            'transaksiItems.mahasiswa',
        ])->where('status', 'completed')->whereHas('transaksiItems');

        // Filter tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('completed_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('completed_at', '<=', $request->tanggal_sampai);
        }

        // Filter jenis sampah
        if ($request->filled('jenis_sampah_id')) {
            $query->whereHas('transaksiItems', function ($q) use ($request) {
                $q->where('jenis_sampah_id', $request->jenis_sampah_id);
            });
        }

        // Filter bak sampah
        if ($request->filled('bak_sampah_id')) {
            $query->whereHas('transaksiItems', function ($q) use ($request) {
                $q->where('bak_sampah_id', $request->bak_sampah_id);
            });
        }

        // Filter status validasi
        if ($request->filled('status_validasi')) {
            $query->whereHas('transaksiItems', function ($q) use ($request) {
                $q->where('status_validasi', $request->status_validasi);
            });
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('transaksiItems.mahasiswa', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        // Summary
        $allItems = (clone $query)->get()->pluck('transaksiItems')->flatten();
        $summary = [
            'total_transaksi' => (clone $query)->count(),
            'total_berat'     => $allItems->sum('berat'),
            'total_poin'      => $allItems->sum('poin_didapat'),
            'total_koin'      => $allItems->sum('koin_didapat'),
            'total_anomali'   => $allItems->where('status_validasi', 'anomali')->count(),
        ];

        $transaksis = $query->orderByDesc('completed_at')->paginate(20)->withQueryString();

        $jenisSampahs = JenisSampah::where('is_active', 1)->orderBy('nama')->get();
        $bakSampahs = BakSampah::orderBy('nama')->get();

        return view('admin.transaksi.index', compact(
            'transaksis',
            'jenisSampahs',
            'bakSampahs',
            'summary'
        ));
    }

     public function show($id)
    {
        $session = TransaksiSession::where('id', $id)
            ->where('status', 'completed')
            ->with([
                'transaksiItems.jenisSampah',
                'transaksiItems.bakSampah.lokasi',
                'transaksiItems.mahasiswa.level',
            ])
            ->firstOrFail();

        return view('admin.transaksi.show', compact('session'));
    }
}