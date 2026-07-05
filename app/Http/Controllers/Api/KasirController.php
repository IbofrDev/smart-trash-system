<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VoucherMahasiswa;
use Illuminate\Http\Request;

class KasirController extends Controller
{

    /**
     * Ambil data kasir yang sedang login
     * GET /api/kasir/me
     */
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => (bool) $user->is_active,
            ],
        ]);
    }
/**
     * Validasi & gunakan voucher berdasarkan kode
     * POST /api/kasir/voucher/validate
     */
    public function validateVoucher(Request $request)
    {
        $request->validate([
            'kode_voucher' => 'required|string',
        ], [
            'kode_voucher.required' => 'Kode voucher wajib diisi.',
        ]);

        $kode = strtoupper(trim($request->kode_voucher));

        // Cari voucher berdasarkan kode
        $voucher = VoucherMahasiswa::with('mahasiswa')
            ->where('kode_voucher', $kode)
            ->first();

        // Voucher tidak ditemukan
        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Kode voucher tidak ditemukan.',
            ], 404);
        }

        // Cek expired
        if ($voucher->isExpired()) {
            $voucher->update(['status' => 'expired']);
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah expired.',
                'data' => [
                    'kode_voucher' => $voucher->kode_voucher,
                    'status' => 'expired',
                    'expired_at' => $voucher->expired_at,
                ],
            ], 422);
        }

        // Cek status
        if ($voucher->status === 'terpakai') {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah pernah digunakan.',
                'data' => [
                    'kode_voucher' => $voucher->kode_voucher,
                    'status' => 'terpakai',
                    'used_at' => $voucher->used_at,
                    'mahasiswa' => [
                        'name' => $voucher->mahasiswa->name,
                        'nim' => $voucher->mahasiswa->nim,
                    ],
                ],
            ], 422);
        }

        if ($voucher->status !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => "Voucher tidak valid. Status: {$voucher->status}",
            ], 422);
        }

        // Semua valid — tandai sebagai terpakai
        $voucher->update([
            'status' => 'terpakai',
            'used_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil divalidasi!',
            'data' => [
                'kode_voucher' => $voucher->kode_voucher,
                'status' => 'terpakai',
                'koin_digunakan' => $voucher->koin_digunakan,
                'used_at' => $voucher->used_at,
                'mahasiswa' => [
                    'name' => $voucher->mahasiswa->name,
                    'nim' => $voucher->mahasiswa->nim,
                    'avatar' => $voucher->mahasiswa->avatar,
                ],
            ],
        ]);
    }

    /**
     * Cek info voucher tanpa menggunakannya (preview)
     * GET /api/kasir/voucher/check/{kode}
     */
    public function checkVoucher(string $kode)
    {
        $kode = strtoupper(trim($kode));

        $voucher = VoucherMahasiswa::with('mahasiswa')
            ->where('kode_voucher', $kode)
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Kode voucher tidak ditemukan.',
            ], 404);
        }

        // Auto update jika expired
        if ($voucher->isExpired() && $voucher->status === 'aktif') {
            $voucher->update(['status' => 'expired']);
            $voucher->refresh();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'kode_voucher' => $voucher->kode_voucher,
                'status' => $voucher->status,
                'koin_digunakan' => $voucher->koin_digunakan,
                'created_at' => $voucher->created_at,
                'expired_at' => $voucher->expired_at,
                'used_at' => $voucher->used_at,
                'mahasiswa' => [
                    'name' => $voucher->mahasiswa->name,
                    'nim' => $voucher->mahasiswa->nim,
                    'avatar' => $voucher->mahasiswa->avatar,
                ],
            ],
        ]);
    }
}