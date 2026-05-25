<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VoucherMahasiswa;
use App\Models\SettingPoin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    /**
     * List voucher milik user
     */
    public function index(Request $request)
    {
        $mahasiswa = $request->user();

        // Auto-expire voucher yang sudah lewat
        VoucherMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'aktif')
            ->where('expired_at', '<', now())
            ->update(['status' => 'expired']);

        $vouchers = VoucherMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_koin' => $mahasiswa->total_koin_botol,
                'koin_per_voucher' => (int) SettingPoin::where('nama_setting', 'koin_per_voucher')->value('value'),
                'vouchers' => $vouchers,
            ],
        ]);
    }

    /**
     * Tukar koin jadi voucher
     */
    public function redeem(Request $request)
    {
        $mahasiswa = $request->user();
        $koinPerVoucher = (int) SettingPoin::where('nama_setting', 'koin_per_voucher')->value('value');
        $expiredDays = (int) SettingPoin::where('nama_setting', 'voucher_expired_days')->value('value');

        // Validasi koin cukup
        if ($mahasiswa->total_koin_botol < $koinPerVoucher) {
            return response()->json([
                'success' => false,
                'message' => "Koin tidak cukup. Butuh {$koinPerVoucher} koin, kamu punya {$mahasiswa->total_koin_botol} koin.",
            ], 422);
        }

        // Generate kode voucher unik
        do {
            $kode = 'VCH-' . strtoupper(Str::random(6));
        } while (VoucherMahasiswa::where('kode_voucher', $kode)->exists());

        // Buat voucher
        $voucher = VoucherMahasiswa::create([
            'mahasiswa_id'  => $mahasiswa->id,
            'kode_voucher'  => $kode,
            'status'        => 'aktif',
            'koin_digunakan' => $koinPerVoucher,
            'created_at'    => now(),
            'expired_at'    => now()->addDays($expiredDays),
        ]);

        // Kurangi koin
        $mahasiswa->decrement('total_koin_botol', $koinPerVoucher);

        return response()->json([
            'success' => true,
            'data' => $voucher,
            'message' => "Voucher berhasil dibuat! Kode: {$kode}",
        ]);
    }

    /**
     * Endpoint lama - sekarang voucher hanya bisa divalidasi oleh kasir
     */
    public function useVoucher(Request $request, $id)
    {
        return response()->json([
            'success' => false,
            'message' => 'Voucher hanya bisa divalidasi oleh kasir. Tunjukkan kode voucher ke kasir untuk digunakan.',
        ], 403);
    }
}