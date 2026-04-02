<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TransaksiSampah;
use App\Models\Mahasiswa;
use App\Models\JenisSampah;
use App\Models\BakSampah;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiSampah::with(['mahasiswa', 'jenisSampah', 'bakSampah']);

        // Filter tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_sampai);
        }

        // Filter jenis sampah
        if ($request->filled('jenis_sampah_id')) {
            $query->where('jenis_sampah_id', $request->jenis_sampah_id);
        }

        // Filter bak sampah
        if ($request->filled('bak_sampah_id')) {
            $query->where('bak_sampah_id', $request->bak_sampah_id);
        }

        // Filter mahasiswa
        if ($request->filled('mahasiswa_id')) {
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $transaksis = $query->orderBy('tanggal_transaksi', 'desc')->paginate(20);

        // Data untuk filter
        $jenisSampahs = JenisSampah::where('is_active', 1)->orderBy('nama')->get();
        $bakSampahs = BakSampah::orderBy('nama')->get();

        // Summary
        $summary = [
            'total_transaksi' => $query->count(),
            'total_berat' => $query->sum('berat'),
            'total_poin' => $query->sum('poin_didapat'),
        ];

        return view('admin.transaksi.index', compact(
            'transaksis',
            'jenisSampahs',
            'bakSampahs',
            'summary'
        ));
    }

    public function show(TransaksiSampah $transaksi)
    {
        $transaksi->load(['mahasiswa.level', 'jenisSampah', 'bakSampah.lokasi']);
        return view('admin.transaksi.show', compact('transaksi'));
    }
}