<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SettingPoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Get Profile
     * GET /api/profile
     */
    public function index(Request $request)
    {
        $mahasiswa = $request->user();
        $koinPerVoucher = SettingPoin::where('nama_setting', 'koin_per_voucher')
            ->value('value') ?? 20;

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $mahasiswa->id,
                'name' => $mahasiswa->name,
                'email' => $mahasiswa->email,
                'avatar' => $mahasiswa->avatar,
                'nim' => $mahasiswa->nim,
                'prodi' => $mahasiswa->prodi,
                'rfid_uid' => $mahasiswa->rfid_uid,
                'total_poin' => $mahasiswa->total_poin,
                'total_koin_botol' => $mahasiswa->total_koin_botol,
                'koin_per_voucher' => (int) $koinPerVoucher,
                'level' => $mahasiswa->level ? [
                    'id' => $mahasiswa->level->id,
                    'nama_level' => $mahasiswa->level->nama_level,
                    'min_poin' => $mahasiswa->level->min_poin,
                    'max_poin' => $mahasiswa->level->max_poin,
                ] : null,
            ]
        ], 200);
    }

    /**
     * Update Profile
     * PUT /api/profile
     */
    public function update(Request $request)
    {
        $mahasiswa = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:100',
            'nim' => 'sometimes|string|max:20',
            'prodi' => 'sometimes|string|max:30',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $mahasiswa->update($request->only(['name', 'nim', 'prodi']));

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => [
                'name' => $mahasiswa->name,
                'nim' => $mahasiswa->nim,
                'prodi' => $mahasiswa->prodi,
            ]
        ], 200);
    }

    /**
     * Register/Update RFID
     * POST /api/profile/rfid
     */
    public function updateRfid(Request $request)
    {
        $mahasiswa = $request->user();

        $validator = Validator::make($request->all(), [
            'rfid_uid' => 'required|string|max:20|unique:mahasiswa,rfid_uid,' . $mahasiswa->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $mahasiswa->update(['rfid_uid' => $request->rfid_uid]);

        return response()->json([
            'success' => true,
            'message' => 'RFID berhasil didaftarkan',
            'data' => [
                'rfid_uid' => $mahasiswa->rfid_uid
            ]
        ], 200);
    }

    /**
     * Update FCM Token (for push notification)
     * POST /api/profile/fcm-token
     */
    public function updateFcmToken(Request $request)
    {
        $mahasiswa = $request->user();

        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $mahasiswa->update(['fcm_token' => $request->fcm_token]);

        return response()->json([
            'success' => true,
            'message' => 'FCM Token berhasil diperbarui'
        ], 200);
    }
}