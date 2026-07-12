<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Events\TransaksiCreated;
use App\Models\Mahasiswa;
use App\Models\JenisSampah;
use App\Models\TransaksiSampah;
use App\Models\TransaksiSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HardwareController extends Controller
{
    /**
     * POST /api/hardware/verify-rfid
     * Verifikasi RFID & cari session aktif dari mobile
     */
    public function verifyRfid(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rfid_uid' => 'required|string|max:20',
            'bak_sampah_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cari mahasiswa by RFID
        $mahasiswa = Mahasiswa::where('rfid_uid', $request->rfid_uid)
            ->with('level')
            ->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'error' => 'RFID_NOT_FOUND',
                'message' => 'RFID tidak terdaftar. Mahasiswa harus register KTM di aplikasi.',
            ], 404);
        }

        // Cari session aktif milik mahasiswa ini
        $session = TransaksiSession::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'pending')
            ->where('expired_at', '>', now())
            ->latest('created_at')
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'error' => 'NO_SESSION',
                'message' => 'Tidak ada session aktif. Silakan input jumlah di aplikasi terlebih dahulu.',
            ], 404);
        }

        // Hitung expected weight range berdasarkan breakdown dari DB
        $expected = $this->calculateExpectedWeight($session->botol_breakdown, $session->jumlah_botol);
        $minExpected = $expected['min'];
        $maxExpected = $expected['max'];
        // Update status session -> tapped dan refresh waktu aktif
        $session->update([
            'status' => 'tapped',
            'expired_at' => now()->addMinutes(10),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'mahasiswa' => [
                    'id' => $mahasiswa->id,
                    'name' => $mahasiswa->name,
                    'nim' => $mahasiswa->nim,
                ],
                'session' => [
                    'id' => $session->id,
                    'token' => $session->session_token,
                    'jumlah_botol' => $session->jumlah_botol,
                    'total_input' => $session->jumlah_botol,
                ],
                'expected_weight' => [
                    'min_gram' => (int) ($minExpected * 0.9),
                    'max_gram' => (int) ($maxExpected * 1.1),
                ],
            ],
            'message' => 'Halo ' . $mahasiswa->name . '! Silakan timbang ' . $session->jumlah_botol . ' botol plastik.',
        ]);
    }

    /**
     * POST /api/hardware/start-counting
     * Dipanggil Arduino saat objek pertama terdeteksi
     */
    public function startCounting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_token' => 'required|string',
            'bak_sampah_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $session = TransaksiSession::where('session_token', $request->session_token)->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Session tidak ditemukan.',
            ], 404);
        }

        if ($session->status === 'expired' || $session->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Session sudah dibatalkan atau expired.',
            ], 422);
        }

        // Idempotent: kalau sudah mulai proses, anggap sukses
        if (in_array($session->status, ['counting', 'weighing', 'completed'])) {
            return response()->json([
                'success' => true,
                'message' => 'Session sudah mulai diproses.',
                'data' => [
                    'status' => $session->status,
                ],
            ]);
        }

        if ($session->status !== 'tapped') {
            return response()->json([
                'success' => false,
                'message' => 'Status session tidak valid untuk mulai counting.',
            ], 422);
        }

        $session->update([
            'status' => 'counting',
            'expired_at' => now()->addMinutes(10),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Counting dimulai.',
            'data' => [
                'status' => 'counting',
            ],
        ]);
    }

    /**
     * POST /api/hardware/submit-weight
     * Kirim data berat dari load cell ΓÇö DILAKUKAN SETELAH count
     */
    public function submitWeight(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_token' => 'required|string',
            'berat_gram' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cek status 'counting' — sudah dihitung, belum ditimbang
        $session = TransaksiSession::where('session_token', $request->session_token)
            ->where('status', 'counting')
            ->where('expired_at', '>', now())
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'error' => 'SESSION_INVALID',
                'message' => 'Session tidak valid atau sudah expired.',
            ], 404);
        }

        // Ambil data count dari cache
        $countData = cache()->get('session_count_' . $session->id);

        if (!$countData) {
            return response()->json([
                'success' => false,
                'message' => 'Data hitungan tidak ditemukan. Ulangi proses.',
            ], 422);
        }

         $jumlahInput = $session->jumlah_botol;
        $jumlahFinal = $countData['jumlah_final'];

        $expected = $this->calculateExpectedWeight($session->botol_breakdown, $jumlahInput);
        $minExpected = $expected['min'];
        $maxExpected = $expected['max'];

        // Toleransi ┬▒15%
        $minTolerance = $minExpected * 0.85;
        $maxTolerance = $maxExpected * 1.15;

        $isValid = $request->berat_gram >= $minTolerance
            && $request->berat_gram <= $maxTolerance;

        $statusValidasi = $isValid ? 'valid' : 'anomali';

        // Update status → weighing
        $session->update(['status' => 'weighing']);

        // Simpan berat di cache
        cache()->put(
            'session_weight_' . $session->id,
            [
                'berat_gram' => $request->berat_gram,
                'status_validasi' => $statusValidasi,
            ],
            now()->addMinutes(15)
        );

        return response()->json([
            'success' => true,
            'data' => [
                'berat_gram' => $request->berat_gram,
                'status_validasi' => $statusValidasi,
                'min_tolerance' => (int) $minTolerance,
                'max_tolerance' => (int) $maxTolerance,
                'pesan' => $isValid
                    ? 'Berat sesuai. Siap menyelesaikan transaksi.'
                    : 'Berat tidak sesuai, transaksi akan ditandai anomali.',
                'ready_to_complete' => true,
            ],
        ]);
    }

    /**
     * POST /api/hardware/submit-count
     * Kirim jumlah dari ultrasonik — DILAKUKAN DULU sebelum timbang
     */
    public function submitCount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_token' => 'required|string',
            'jumlah_terhitung' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cek status 'tapped' — user sudah tap RFID, belum counting
        $session = TransaksiSession::where('session_token', $request->session_token)
            ->whereIn('status', ['tapped', 'counting'])
            ->where('expired_at', '>', now())
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'error' => 'SESSION_INVALID',
                'message' => 'Session tidak valid atau sudah expired.',
            ], 404);
        }

        // Anti-cheat: jika terhitung > input, pakai input sebagai batas atas
        $totalInput = $session->jumlah_botol;
        $jumlahTerhitung = $request->jumlah_terhitung;
        $jumlahFinal = min($jumlahTerhitung, $totalInput);
        // Update status ke counting
        $session->update([
            'status' => 'counting',
            'expired_at' => now()->addMinutes(15),
        ]);

        cache()->put(
            'session_count_' . $session->id,
            [
                'jumlah_terhitung' => $jumlahTerhitung,
                'jumlah_final' => $jumlahFinal,
            ],
            now()->addMinutes(15)
        );

        return response()->json([
            'success' => true,
            'data' => [
                'jumlah_terhitung' => $jumlahTerhitung,
                'jumlah_final' => $jumlahFinal,
                'pesan' => 'Sampah terhitung. Silakan tunggu proses penimbangan.',
                'ready_to_weigh' => true,
            ],
        ]);
    }

    /**
     * POST /api/hardware/complete
     * Finalisasi transaksi
     */
    public function complete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $session = TransaksiSession::where('session_token', $request->session_token)
            ->where('status', 'weighing')
            ->where('expired_at', '>', now())
            ->with('mahasiswa')
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'error' => 'SESSION_INVALID',
                'message' => 'Session tidak valid atau sudah expired.',
            ], 404);
        }

        // Ambil data dari cache
        $countData = cache()->get('session_count_' . $session->id);
        $weightData = cache()->get('session_weight_' . $session->id);

        if (!$countData || !$weightData) {
            return response()->json([
                'success' => false,
                'message' => 'Data transaksi tidak lengkap. Ulangi proses.',
            ], 422);
        }

        $mahasiswa = $session->mahasiswa;
        $jumlahTerhitung = $countData['jumlah_terhitung'];
        $jumlahFinal = $countData['jumlah_final'];
        $beratGram = $weightData['berat_gram'];
        $statusValidasi = $weightData['status_validasi'];

        // Reject jika jumlah final 0
        if ($jumlahFinal < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah sampah tidak valid. Transaksi dibatalkan.',
            ], 422);
        }

        // Tentukan jenis_sampah_id dari breakdown session (ambil id pertama yang aktif)
        $jenisSampahId = $this->resolveJenisSampahId($session->botol_breakdown);
        $botol = JenisSampah::find($jenisSampahId);

        if (!$botol) {
            return response()->json([
                'success' => false,
                'message' => 'Konfigurasi jenis sampah tidak ditemukan.',
            ], 500);
        }

        $rataRataPoin = $botol->poin_per_kg;

        // Anomali = poin 0 DAN koin 0
        if ($statusValidasi === 'anomali') {
            $poinDidapat = 0;
            $koinDidapat = 0;
        } else {
            $poinDidapat = (int) round(($beratGram / 1000) * $rataRataPoin);
            $koinDidapat = $jumlahFinal;
        }

        DB::beginTransaction();
        try {
            // Simpan transaksi
            $transaksi = TransaksiSampah::create([
                'mahasiswa_id' => $mahasiswa->id,
                'bak_sampah_id' => $request->bak_sampah_id ?? 1,
                'session_id' => $session->id,
                               'jenis_sampah_id' => $jenisSampahId,
                'berat' => $beratGram,
                'jumlah_input_botol' => $session->jumlah_botol,
                'jumlah_terhitung' => $jumlahTerhitung,
                'jumlah_final' => $jumlahFinal,
                'status_validasi' => $statusValidasi,
                'poin_didapat' => $poinDidapat,
                'koin_didapat' => $koinDidapat,
                'tanggal_transaksi' => now(),
            ]);

            // Update mahasiswa
            $mahasiswa->increment('total_poin', $poinDidapat);
            $mahasiswa->increment('total_koin_botol', $koinDidapat);


            // Update session → completed
            $session->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            DB::commit(); // ← commit dulu sebelum apapun

            // Bersihkan cache setelah commit
            cache()->forget('session_weight_' . $session->id);
            cache()->forget('session_count_' . $session->id);

            // Refresh data mahasiswa terbaru
            $mahasiswa->refresh();

            $levelNama = $mahasiswa->level->nama_level ?? 'Eco Starter';
            $levelUp = false;

            // Trigger gamifikasi setelah commit
            event(new TransaksiCreated($transaksi));

            return response()->json([
                'success' => true,
                'data' => [
                    'transaksi_id' => $transaksi->id,
                    'mahasiswa_name' => $mahasiswa->name,
                    'berat_gram' => $beratGram,
                    'jumlah_final' => $jumlahFinal,
                    'status_validasi' => $statusValidasi,
                    'poin_didapat' => $poinDidapat,
                    'koin_didapat' => $koinDidapat,
                    'total_poin' => $mahasiswa->total_poin,
                    'total_koin' => $mahasiswa->total_koin_botol,
                    'level' => $levelNama,
                    'level_up' => $levelUp,
                ],
                'message' => "Transaksi berhasil! +{$koinDidapat} koin, +{$poinDidapat} poin.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/hardware/jenis-sampah
     */
    public function getJenisSampah()
    {
        $jenisSampah = JenisSampah::where('is_active', 1)
            ->select('id', 'nama', 'poin_per_kg', 'berat_min_gram', 'berat_max_gram', 'satuan')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $jenisSampah,
        ]);
    }

    /**
     * GET /api/hardware/session-status/{token}
     * Dipolling Arduino sebelum objek pertama masuk
     */
    public function sessionStatus(Request $request, $token)
    {
        $session = TransaksiSession::where('session_token', $token)->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Session tidak ditemukan.',
                'should_stop' => true,
                'status' => 'not_found',
            ], 404);
        }

        if ($session->status === 'expired' || $session->isExpired()) {
            return response()->json([
                'success' => true,
                'message' => 'Session dibatalkan atau expired.',
                'should_stop' => true,
                'status' => 'expired',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Session aktif.',
            'should_stop' => false,
            'status' => $session->status,
        ]);
    }

    /**
     * POST /api/hardware/heartbeat
     */
    public function heartbeat(Request $request)
    {
        $bakSampah = $request->bak_sampah;

        return response()->json([
            'success' => true,
            'message' => 'Hardware terhubung',
            'data' => [
                'bak_sampah_id' => $bakSampah['id'] ?? null,
                'nama' => $bakSampah['nama'] ?? null,
                'status' => $bakSampah['status'] ?? null,
                'server_time' => now()->toDateTimeString(),
            ],
        ]);
    }

    /**
     * Hitung range berat expected dari breakdown botol
     */
     private function calculateExpectedWeight(?array $breakdown, int $totalBotol): array
    {
        // Ambil semua jenis sampah aktif dari DB, key by nama (lowercase, tanpa spasi)
        $jenisList = JenisSampah::where('is_active', 1)->get();

        // Buat map: nama_key => [min, max]
        // nama_key diambil dari nama jenis sampah, misal "Botol Plastik 220ml" → "220"
        // Fallback: gunakan field satuan atau nama untuk matching
        $dbRanges = [];
        foreach ($jenisList as $j) {
            // Coba ekstrak angka dari nama sebagai key (misal "220", "500", dll)
            if (preg_match('/(\d+)/', $j->nama, $matches)) {
                $key = $matches[1];
                $dbRanges[$key] = [
                    'min' => $j->berat_min_gram ?? 6,
                    'max' => $j->berat_max_gram ?? 26,
                    'jenis_sampah_id' => $j->id,
                ];
            }
        }

        // Fallback default jika DB kosong
        $defaultMin = 6;
        $defaultMax = 26;

        if (empty($breakdown)) {
            return [
                'min' => $totalBotol * $defaultMin,
                'max' => $totalBotol * $defaultMax,
            ];
        }

        $min = 0;
        $max = 0;
        foreach ($breakdown as $size => $count) {
            if (isset($dbRanges[$size])) {
                $min += $dbRanges[$size]['min'] * $count;
                $max += $dbRanges[$size]['max'] * $count;
            } else {
                // Ukuran tidak ada di DB, pakai default
                $min += $defaultMin * $count;
                $max += $defaultMax * $count;
            }
        }

        return ['min' => $min, 'max' => $max];
    }

    /**
     * Tentukan jenis_sampah_id utama dari breakdown
     * Ambil id dari jenis sampah aktif pertama yang cocok dengan breakdown
     */
    private function resolveJenisSampahId(?array $breakdown): int
    {
        if (empty($breakdown)) {
            // Fallback: ambil jenis sampah aktif pertama
            $default = JenisSampah::where('is_active', 1)->first();
            return $default ? $default->id : 1;
        }

        $jenisList = JenisSampah::where('is_active', 1)->get();

        foreach ($jenisList as $j) {
            if (preg_match('/(\d+)/', $j->nama, $matches)) {
                if (isset($breakdown[$matches[1]])) {
                    return $j->id;
                }
            }
        }

        // Fallback: jenis aktif pertama
        $default = JenisSampah::where('is_active', 1)->first();
        return $default ? $default->id : 1;
    }
}

