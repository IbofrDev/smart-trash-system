<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BakSampah;
use App\Models\LogPengosonganBak;
use Illuminate\Http\Request;

class PengosonganBakController extends Controller
{
    public function index()
    {
        $bakSampahs = BakSampah::with('lokasi')
            ->where('status', 'aktif')
            ->orderByDesc('jumlah_botol_terisi')
            ->get()
            ->map(function ($bak) {
                $max  = $bak->kapasitas_max_botol ?: 200;
                $terisi = $bak->jumlah_botol_terisi ?? 0;
                $persen = $max > 0 ? min(100, round(($terisi / $max) * 100)) : 0;

                $bak->persen        = $persen;
                $bak->status_kapasitas = match(true) {
                    $persen >= 100 => 'penuh',
                    $persen >= 80  => 'hampir_penuh',
                    default        => 'normal',
                };
                return $bak;
            });

        $logPengosongan = LogPengosonganBak::with(['bakSampah', 'user'])
            ->orderByDesc('dikosongkan_at')
            ->limit(20)
            ->get();

        return view('admin.bak-sampah.monitoring', compact('bakSampahs', 'logPengosongan'));
    }

    public function kosongkan(Request $request, BakSampah $bakSampah)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:255',
        ]);

        LogPengosonganBak::create([
            'bak_sampah_id'       => $bakSampah->id,
            'user_id'             => auth()->id(),
            'jumlah_botol_sebelum'=> $bakSampah->jumlah_botol_terisi ?? 0,
            'catatan'             => $request->catatan,
            'dikosongkan_at'      => now(),
        ]);

        $bakSampah->update(['jumlah_botol_terisi' => 0]);

        return back()->with('success', "Bak {$bakSampah->nama} berhasil dikosongkan.");
    }
}