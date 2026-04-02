<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransaksiSampah;

class TransaksiController extends Controller
{
    /**
     * Get List Transaksi (with pagination)
     * GET /api/transaksi
     */
    public function index(Request $request)
    {
        $mahasiswa = $request->user();

        // Filter by period (optional)
        $period = $request->query('period', 'all'); // all, 7days, 30days

        $query = TransaksiSampah::where('mahasiswa_id', $mahasiswa->id)
            ->with(['jenisSampah', 'bakSampah.lokasi']);

        if ($period === '7days') {
            $query->where('tanggal_transaksi', '>=', now()->subDays(7));
        } elseif ($period === '30days') {
            $query->where('tanggal_transaksi', '>=', now()->subDays(30));
        }

        $transaksi = $query->orderBy('tanggal_transaksi', 'desc')
            ->paginate(10)
            ->through(function ($item) {
                return [
                    'id' => $item->id,
                    'jenis_sampah' => $item->jenisSampah->nama,
                    'berat' => $item->berat,
                    'poin' => $item->poin_didapat,
                    'bak_sampah' => $item->bakSampah->nama ?? '-',
                    'lokasi' => $item->bakSampah->lokasi->nama_lokasi ?? '-',
                    'tanggal' => $item->tanggal_transaksi,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $transaksi
        ], 200);
    }

    /**
     * Get Detail Transaksi
     * GET /api/transaksi/{id}
     */
    public function show(Request $request, $id)
    {
        $mahasiswa = $request->user();

        $transaksi = TransaksiSampah::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->with(['jenisSampah', 'bakSampah.lokasi'])
            ->first();

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $transaksi->id,
                'jenis_sampah' => [
                    'id' => $transaksi->jenisSampah->id,
                    'nama' => $transaksi->jenisSampah->nama,
                    'poin_per_kg' => $transaksi->jenisSampah->poin_per_kg,
                ],
                'berat' => $transaksi->berat,
                'poin_didapat' => $transaksi->poin_didapat,
                'bak_sampah' => [
                    'nama' => $transaksi->bakSampah->nama ?? '-',
                    'lokasi' => $transaksi->bakSampah->lokasi->nama_lokasi ?? '-',
                ],
                'tanggal_transaksi' => $transaksi->tanggal_transaksi,
            ]
        ], 200);
    }
}