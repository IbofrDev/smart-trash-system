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
        $mahasiswa = $request->user();
        $period    = $request->query('period', 'all');

        $query = TransaksiSampah::where('mahasiswa_id', $mahasiswa->id)
            ->with(['bakSampah.lokasi']);

        if ($period === '7days') {
            $query->where('tanggal_transaksi', '>=', now()->subDays(7));
        } elseif ($period === '30days') {
            $query->where('tanggal_transaksi', '>=', now()->subDays(30));
        }

        $transaksi = $query->orderByDesc('tanggal_transaksi')
            ->paginate(10)
            ->through(function ($item) {
                return [
                    'id'              => $item->id,
                    'berat_gram'      => $item->berat,
                    'jumlah_final'    => $item->jumlah_final,
                    'status_validasi' => $item->status_validasi,
                    'poin_didapat'    => $item->poin_didapat,
                    'koin_didapat'    => $item->koin_didapat,
                    'bak_sampah'      => $item->bakSampah->nama ?? '-',
                    'lokasi'          => $item->bakSampah->lokasi->nama_lokasi ?? '-',
                    'tanggal'         => $item->tanggal_transaksi,
                ];
            });

        return response()->json([
            'success' => true,
            'data'    => $transaksi,
        ]);
    }

    /**
     * GET /api/transaksi/{id}
     * Detail transaksi
     */
    public function show(Request $request, $id)
    {
        $mahasiswa = $request->user();

        $transaksi = TransaksiSampah::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->with(['bakSampah.lokasi', 'session'])
            ->first();

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'id'                  => $transaksi->id,
                'berat_gram'          => $transaksi->berat,
                'jumlah_input_botol'  => $transaksi->jumlah_input_botol,
                'jumlah_input_kaleng' => $transaksi->jumlah_input_kaleng,
                'jumlah_terhitung'    => $transaksi->jumlah_terhitung,
                'jumlah_final'        => $transaksi->jumlah_final,
                'status_validasi'     => $transaksi->status_validasi,
                'poin_didapat'        => $transaksi->poin_didapat,
                'koin_didapat'        => $transaksi->koin_didapat,
                'bak_sampah'          => [
                    'nama'   => $transaksi->bakSampah->nama ?? '-',
                    'lokasi' => $transaksi->bakSampah->lokasi->nama_lokasi ?? '-',
                ],
                'tanggal_transaksi'   => $transaksi->tanggal_transaksi,
            ],
        ]);
    }

    /**
     * POST /api/transaksi/session
     * Buat session baru (input jumlah botol & kaleng dari mobile)
     */
    public function createSession(Request $request)
    {
        $request->validate([
            'jumlah_botol'  => 'required|integer|min:0',
            'jumlah_kaleng' => 'required|integer|min:0',
        ]);

        $mahasiswa = $request->user();

        // Validasi minimal 1 item
        if ($request->jumlah_botol + $request->jumlah_kaleng < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah botol dan kaleng tidak boleh keduanya 0.',
            ], 422);
        }

        // Expire session lama yang masih pending
        TransaksiSession::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', ['pending', 'tapped', 'weighing', 'counting'])
            ->where('expired_at', '<', now())
            ->update(['status' => 'expired']);

        // Cek apakah ada session aktif
        $activeSession = TransaksiSession::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', ['pending', 'tapped', 'weighing', 'counting'])
            ->where('expired_at', '>', now())
            ->first();

        if ($activeSession) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu masih memiliki session aktif. Selesaikan atau tunggu hingga expired.',
                'data'    => [
                    'session_token' => $activeSession->session_token,
                    'status'        => $activeSession->status,
                    'expired_at'    => $activeSession->expired_at,
                ],
            ], 422);
        }

        // Buat session baru
        $session = TransaksiSession::create([
            'mahasiswa_id'  => $mahasiswa->id,
            'session_token' => Str::random(64),
            'jumlah_botol'  => $request->jumlah_botol,
            'jumlah_kaleng' => $request->jumlah_kaleng,
            'status'        => 'pending',
            'created_at'    => now(),
            'expired_at'    => now()->addMinutes(10),
        ]);

        return response()->json([
            'success' => true,
            'data'    => [
                'session_token' => $session->session_token,
                'jumlah_botol'  => $session->jumlah_botol,
                'jumlah_kaleng' => $session->jumlah_kaleng,
                'total_input'   => $session->jumlah_botol + $session->jumlah_kaleng,
                'status'        => $session->status,
                'expired_at'    => $session->expired_at,
            ],
            'message' => 'Session berhasil dibuat. Silakan tap KTM ke mesin.',
        ]);
    }

    /**
     * GET /api/transaksi/session/{token}
     * Cek status session (polling dari mobile)
     */
    public function checkSession(Request $request, $token)
    {
        $mahasiswa = $request->user();

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
            'jumlah_botol'  => $session->jumlah_botol,
            'jumlah_kaleng' => $session->jumlah_kaleng,
            'total_input'   => $session->jumlah_botol + $session->jumlah_kaleng,
            'status'        => $session->status,
            'expired_at'    => $session->expired_at,
            'completed_at'  => $session->completed_at,
        ];

        // Jika sudah completed, sertakan hasil transaksi
        if ($session->status === 'completed') {
            $transaksi = TransaksiSampah::where('session_id', $session->id)->first();
            if ($transaksi) {
                $data['transaksi'] = [
                    'id'              => $transaksi->id,
                    'berat_gram'      => $transaksi->berat,
                    'jumlah_final'    => $transaksi->jumlah_final,
                    'status_validasi' => $transaksi->status_validasi,
                    'poin_didapat'    => $transaksi->poin_didapat,
                    'koin_didapat'    => $transaksi->koin_didapat,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }
}