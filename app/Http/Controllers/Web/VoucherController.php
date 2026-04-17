<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\VoucherMahasiswa;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = VoucherMahasiswa::with('mahasiswa')
            ->orderByDesc('created_at');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter search mahasiswa
        if ($request->filled('search')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nim', 'like', '%' . $request->search . '%');
            });
        }

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $vouchers = $query->paginate(15)->withQueryString();

        // Statistik
        $stats = [
            'total'    => VoucherMahasiswa::count(),
            'aktif'    => VoucherMahasiswa::where('status', 'aktif')
                            ->where('expired_at', '>', now())->count(),
            'terpakai' => VoucherMahasiswa::where('status', 'terpakai')->count(),
            'expired'  => VoucherMahasiswa::where('status', 'expired')
                            ->orWhere(function ($q) {
                                $q->where('status', 'aktif')
                                  ->where('expired_at', '<', now());
                            })->count(),
        ];

        return view('admin.voucher.index', compact('vouchers', 'stats'));
    }
}