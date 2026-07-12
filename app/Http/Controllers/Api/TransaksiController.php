<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransaksiSampah;
use App\Models\TransaksiSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransaksiController extends Controller
{
    /**
     * GET /api/transaksi
     * List riwayat transaksi
     */
      public function index(Request $request)
    {
        $user = $request->user();
        if ($user instanceof \App\Models\Mahasiswa) {
            $mahasiswa = $user;
        } else {
            $mahasiswa = \App\Models\Mahasiswa::where('email', $user->email)->first();
        }
        if (!$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Akses hanya untuk mahasiswa.'], 403);
        }

        $period = $request->query('period', 'all');

        $query = TransaksiSession::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'completed')
            ->whereHas('transaksiItems')
            ->with(['transaksiItems.jenisSampah', 'transaksiItems.bakSampah.lokasi']);

        if ($period === '7days') {
            $query->where('completed_at', '>=', now()->subDays(7));
        } elseif ($period === '30days') {
            $query->where('completed_at', '>=', now()->subDays(30));
        }

        $sessions = $query->orderByDesc('completed_at')
            ->paginate(10);

        $mapped = $sessions->through(function ($session) {
            $items = $session->transaksiItems;
            $firstItem = $items->first();
            $hasAnomali = $items->contains('status_validasi', 'anomali');

            return [
                'id' => $session->id,
                'jenis_sampah' => $items->count() > 1
                    ? $items->count() . ' Jenis Sampah'
                    : ($firstItem?->jenisSampah?->nama ?? 'Sampah Daur Ulang'),
                'jenis_breakdown' => $items->map(function ($item) {
                    $nama = $item->jenisSampah?->nama ?? 'Sampah Daur Ulang';
                    $jumlah = (int) ($item->jumlah_final ?? 0);
                    return $jumlah > 0 ? "{$nama} ({$jumlah}x)" : $nama;
                })->values()->all(),
                'berat' => (int) $items->sum('berat'),
                'jumlah_final' => (int) $items->sum('jumlah_final'),
                'poin' => (int) $items->sum('poin_didapat'),
                'koin' => (int) $items->sum('koin_didapat'),
                'status_validasi' => $hasAnomali ? 'anomali' : 'valid',
                'lokasi' => $firstItem?->bakSampah?->lokasi?->nama_lokasi ?? '-',
                'tanggal' => $session->completed_at?->toDateTimeString()
                    ?? $session->created_at?->toDateTimeString()
                    ?? now()->toDateTimeString(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $mapped,
        ]);
    }

    /**
     * GET /api/transaksi/{id}
     * Detail transaksi
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        if ($user instanceof \App\Models\Mahasiswa) {
            $mahasiswa = $user;
        } else {
            $mahasiswa = \App\Models\Mahasiswa::where('email', $user->email)->first();
        }

        if (!$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Akses hanya untuk mahasiswa.'], 403);
        }

        $transaksi = TransaksiSampah::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->with(['bakSampah.lokasi', 'session', 'jenisSampah'])
            ->first();

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $transaksi->id,
                'berat_gram' => $transaksi->berat,
                'jumlah_botol' => $transaksi->jumlah_input_botol,
                'jumlah_terhitung' => $transaksi->jumlah_terhitung,
                'jumlah_final' => $transaksi->jumlah_final,
                'status_validasi' => $transaksi->status_validasi,
                'poin_didapat' => $transaksi->poin_didapat,
                'koin_didapat' => $transaksi->koin_didapat,
                'jenis_sampah' => $transaksi->jenisSampah ? [
                    'id' => $transaksi->jenisSampah->id,
                    'nama' => $transaksi->jenisSampah->nama,
                    'poin_per_kg' => $transaksi->jenisSampah->poin_per_kg,
                    'satuan' => $transaksi->jenisSampah->satuan,
                ] : null,
                'bak_sampah' => [
                    'nama' => $transaksi->bakSampah->nama ?? '-',
                    'lokasi' => $transaksi->bakSampah->lokasi->nama_lokasi ?? '-',
                ],
                'tanggal_transaksi' => $transaksi->tanggal_transaksi,
            ],
        ]);
    }
    /**
     * POST /api/transaksi/session
     * Buat session baru (input jumlah botol & kaleng dari mobile)
     */
    public function createSession(Request $request)
    {
        // Ambil jenis sampah aktif dari DB sebagai acuan valid keys
        $jenisList = \App\Models\JenisSampah::where('is_active', 1)->get();

        // Bangun valid keys dari nama jenis sampah (ekstrak angka)
        $validKeys = [];
        foreach ($jenisList as $j) {
            if (preg_match('/(\d+)/', $j->nama, $matches)) {
                $validKeys[] = $matches[1];
            } else {
                // Nama tanpa angka → fallback ke id (sama dengan Flutter sizeKey)
                $validKeys[] = (string) $j->id;
            }
        }

        $request->validate([
            'botol_breakdown' => 'required|array',
            'botol_breakdown.*' => 'integer|min:0|max:50',
        ], [
            'botol_breakdown.required' => 'Breakdown botol wajib diisi.',
            'botol_breakdown.array' => 'Format breakdown tidak valid.',
        ]);

        // Filter hanya ukuran valid dari DB (jika validKeys kosong, terima semua)
        $breakdown = [];
        $totalBotol = 0;
        foreach ($request->botol_breakdown as $size => $count) {
            $count = (int) $count;
            if ($count > 0 && (empty($validKeys) || in_array((string) $size, $validKeys))) {
                $breakdown[(string) $size] = $count;
                $totalBotol += $count;
            }
        }

        if ($totalBotol < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Total botol minimal 1.',
            ], 422);
        }

        if ($totalBotol > 50) {
            return response()->json([
                'success' => false,
                'message' => 'Total botol maksimal 50.',
            ], 422);
        }

        $user = $request->user();
        if ($user instanceof \App\Models\Mahasiswa) {
            $mahasiswa = $user;
        } else {
            $mahasiswa = \App\Models\Mahasiswa::where('email', $user->email)->first();
        }
        if (!$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Akses hanya untuk mahasiswa.'], 403);
        }

        // Expire session lama
        TransaksiSession::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', ['pending', 'tapped', 'weighing', 'counting'])
            ->where('expired_at', '<', now())
            ->update(['status' => 'expired']);

        TransaksiSession::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', ['tapped', 'counting', 'weighing'])
            ->where('created_at', '<', now()->subMinutes(15))
            ->update(['status' => 'expired']);

        $activeSession = TransaksiSession::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', ['pending', 'tapped', 'weighing', 'counting'])
            ->where('expired_at', '>', now())
            ->first();

        if ($activeSession) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu masih memiliki session aktif. Selesaikan atau tunggu hingga expired.',
                'data' => [
                    'session_token' => $activeSession->session_token,
                    'status' => $activeSession->status,
                    'expired_at' => $activeSession->expired_at,
                ],
            ], 422);
        }

        $session = TransaksiSession::create([
            'mahasiswa_id' => $mahasiswa->id,
            'session_token' => Str::random(64),
            'jumlah_botol' => $totalBotol,
            'botol_breakdown' => $breakdown,
            'status' => 'pending',
            'created_at' => now(),
            'expired_at' => now()->addMinutes(10),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'session_token' => $session->session_token,
                'jumlah_botol' => $session->jumlah_botol,
                'botol_breakdown' => $session->botol_breakdown,
                'total_input' => $session->jumlah_botol,
                'status' => $session->status,
                'expired_at' => $session->expired_at,
            ],
            'message' => 'Session berhasil dibuat. Silakan tap KTM ke mesin.',
        ]);
    }

    /**
     * DELETE /api/transaksi/session/{token}
     * Cancel session dari mobile (user keluar dari menu)
     */
    public function cancelSession(Request $request, $token)
    {
        $user = $request->user();
        if ($user instanceof \App\Models\Mahasiswa) {
            $mahasiswa = $user;
        } else {
            $mahasiswa = \App\Models\Mahasiswa::where('email', $user->email)->first();
        }
        if (!$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Akses hanya untuk mahasiswa.'], 403);
        }
        $session = TransaksiSession::where('session_token', $token)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', ['pending', 'tapped', 'counting', 'weighing'])
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Session tidak ditemukan atau sudah tidak aktif.',
            ], 404);
        }



        $session->update(['status' => 'expired']);

        return response()->json([
            'success' => true,
            'message' => 'Session berhasil dibatalkan.',
        ]);
    }

        /**
     * GET /api/transaksi/session-detail/{id}
     * Detail transaksi per session (untuk dashboard recent transactions)
     */
    public function sessionDetail(Request $request, $id)
    {
        $user = $request->user();
        if ($user instanceof \App\Models\Mahasiswa) {
            $mahasiswa = $user;
        } else {
            $mahasiswa = \App\Models\Mahasiswa::where('email', $user->email)->first();
        }
        if (!$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Akses hanya untuk mahasiswa.'], 403);
        }

        $session = TransaksiSession::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'completed')
            ->with(['transaksiItems.jenisSampah', 'transaksiItems.bakSampah.lokasi'])
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Session tidak ditemukan.',
            ], 404);
        }

        $items = $session->transaksiItems;
        $firstItem = $items->first();

        $itemDetails = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'jenis_sampah' => $item->jenisSampah ? [
                    'id' => $item->jenisSampah->id,
                    'nama' => $item->jenisSampah->nama,
                    'poin_per_kg' => $item->jenisSampah->poin_per_kg,
                ] : null,
                'berat_gram' => (int) $item->berat,
                'jumlah_input_botol' => $item->jumlah_input_botol,
                'jumlah_terhitung' => $item->jumlah_terhitung,
                'jumlah_final' => $item->jumlah_final,
                'status_validasi' => $item->status_validasi,
                'poin_didapat' => $item->poin_didapat,
                'koin_didapat' => $item->koin_didapat,
            ];
        })->values()->all();

        return response()->json([
            'success' => true,
            'data' => [
                'session_id' => $session->id,
                'status' => $session->status,
                'completed_at' => $session->completed_at?->toDateTimeString(),
                'created_at' => $session->created_at?->toDateTimeString(),
                'bak_sampah' => [
                    'nama' => $firstItem?->bakSampah->nama ?? '-',
                    'lokasi' => $firstItem?->bakSampah->lokasi->nama_lokasi ?? '-',
                ],
                'summary' => [
                    'total_jenis' => $items->count(),
                    'total_berat' => (int) $items->sum('berat'),
                    'total_jumlah_final' => (int) $items->sum('jumlah_final'),
                    'total_poin' => (int) $items->sum('poin_didapat'),
                    'total_koin' => (int) $items->sum('koin_didapat'),
                    'has_anomali' => $items->contains('status_validasi', 'anomali'),
                ],
                'items' => $itemDetails,
            ],
        ]);
    }

    /**
     * GET /api/transaksi/session/{token}
     * Cek status session (polling dari mobile)
     */
    public function checkSession(Request $request, $token)
    {
        $user = $request->user();
        if ($user instanceof \App\Models\Mahasiswa) {
            $mahasiswa = $user;
        } else {
            $mahasiswa = \App\Models\Mahasiswa::where('email', $user->email)->first();
        }
        if (!$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Akses hanya untuk mahasiswa.'], 403);
        }

        $session = TransaksiSession::where('session_token', $token)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Session tidak ditemukan.',
            ], 404);
        }

        // Auto expire
        if ($session->isExpired() && !in_array($session->status, ['completed', 'expired'])) {
            $session->update(['status' => 'expired']);
        }
        $data = [
            'session_token' => $session->session_token,
            'jumlah_botol' => $session->jumlah_botol,
            'total_input' => $session->jumlah_botol,
            'status' => $session->status,
            'expired_at' => $session->expired_at,
            'completed_at' => $session->completed_at,
        ];

        // Jika sudah completed, sertakan hasil transaksi
        if ($session->status === 'completed') {
            $transaksi = TransaksiSampah::where('session_id', $session->id)->first();
            if ($transaksi) {
                $data['transaksi'] = [
                    'id' => $transaksi->id,
                    'berat_gram' => $transaksi->berat,
                    'jumlah_final' => $transaksi->jumlah_final,
                    'status_validasi' => $transaksi->status_validasi,
                    'poin_didapat' => $transaksi->poin_didapat,
                    'koin_didapat' => $transaksi->koin_didapat,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}