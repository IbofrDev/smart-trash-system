<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Events\TransaksiCreated;
use App\Models\Mahasiswa;
use App\Models\JenisSampah;
use App\Models\TransaksiSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HardwareController extends Controller
{
    /**
     * Verify RFID UID
     * POST /api/hardware/verify-rfid
     */
    public function verifyRfid(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rfid_uid' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $mahasiswa = Mahasiswa::where('rfid_uid', $request->rfid_uid)
            ->with('level')
            ->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'RFID tidak terdaftar',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'mahasiswa_id' => $mahasiswa->id,
                'name' => $mahasiswa->name,
                'nim' => $mahasiswa->nim,
                'total_poin' => $mahasiswa->total_poin,
                'level' => $mahasiswa->level->nama_level ?? 'Eco Starter',
            ],
        ], 200);
    }

    /**
     * Store Transaksi from Hardware
     * POST /api/hardware/transaksi
     */
    public function storeTransaksi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rfid_uid' => 'required|string|max:20',
            'jenis_sampah_id' => 'required|exists:jenis_sampah,id',
            'berat' => 'required|numeric|min:0.01|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $mahasiswa = Mahasiswa::where('rfid_uid', $request->rfid_uid)->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'RFID tidak terdaftar',
            ], 404);
        }

        $jenisSampah = JenisSampah::find($request->jenis_sampah_id);

        if (!$jenisSampah || !$jenisSampah->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis sampah tidak valid atau tidak aktif',
            ], 400);
        }

        // Hitung poin berdasarkan berat × poin_per_kg
        $poinDidapat = (int) round($request->berat * $jenisSampah->poin_per_kg);

        // Ambil bak_sampah dari middleware
        $bakSampah = $request->bak_sampah;

        DB::beginTransaction();
        try {
            $transaksi = TransaksiSampah::create([
                'mahasiswa_id' => $mahasiswa->id,
                'bak_sampah_id' => $bakSampah->id,
                'jenis_sampah_id' => $jenisSampah->id,
                'berat' => $request->berat,
                'poin_didapat' => $poinDidapat,
                'tanggal_transaksi' => now(),
            ]);

            // Update total poin mahasiswa
            $mahasiswa->increment('total_poin', $poinDidapat);

            // Trigger gamifikasi: achievement check, level up, leaderboard update
            event(new TransaksiCreated($transaksi));

            // Refresh untuk mendapatkan data terbaru setelah gamifikasi
            $mahasiswa->refresh();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'data' => [
                    'transaksi_id' => $transaksi->id,
                    'mahasiswa' => [
                        'name' => $mahasiswa->name,
                        'total_poin' => $mahasiswa->total_poin,
                        'level' => $mahasiswa->level->nama_level ?? 'Eco Starter',
                    ],
                    'berat' => $transaksi->berat,
                    'poin_didapat' => $poinDidapat,
                    'jenis_sampah' => $jenisSampah->nama,
                ],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan transaksi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get list jenis sampah aktif
     * GET /api/hardware/jenis-sampah
     */
    public function getJenisSampah()
    {
        $jenisSampah = JenisSampah::where('is_active', 1)
            ->select('id', 'nama', 'poin_per_kg', 'satuan')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $jenisSampah,
        ], 200);
    }

    /**
     * Heartbeat - hardware status check
     * POST /api/hardware/heartbeat
     */
    public function heartbeat(Request $request)
    {
        $bakSampah = $request->bak_sampah;

        return response()->json([
            'success' => true,
            'message' => 'Hardware terhubung',
            'data' => [
                'bak_sampah_id' => $bakSampah->id,
                'nama' => $bakSampah->nama,
                'status' => $bakSampah->status,
                'server_time' => now()->toDateTimeString(),
            ],
        ], 200);
    }
}