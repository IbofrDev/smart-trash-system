<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Level;
use Illuminate\Support\Facades\Validator;
use Google\Client as GoogleClient;

class AuthController extends Controller
{
    /**
     * Login with Google ID Token
     * POST /api/auth/google
     */
    public function loginGoogle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verify Google ID Token
            $client = new GoogleClient(['client_id' => config('services.google.client_id')]);
            $payload = $client->verifyIdToken($request->id_token);

            if (!$payload) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token Google tidak valid'
                ], 401);
            }

            // Extract user data from token
            $googleId = $payload['sub'];
            $email = $payload['email'];
            $name = $payload['name'];
            $avatar = $payload['picture'] ?? null;

            // Find or create mahasiswa
            $mahasiswa = Mahasiswa::where('google_id', $googleId)->first();

            if (!$mahasiswa) {
                // Buat mahasiswa baru
                $mahasiswa = Mahasiswa::create([
                    'google_id' => $googleId,
                    'email' => $email,
                    'name' => $name,
                    'avatar' => $avatar,
                    'level_id' => 1, // Default: Eco Starter
                    'total_poin' => 0,
                ]);
            } else {
                // Update data jika sudah ada
                $mahasiswa->update([
                    'email' => $email,
                    'name' => $name,
                    'avatar' => $avatar,
                ]);
            }

            // Create Sanctum token
            $token = $mahasiswa->createToken('mobile-app')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'mahasiswa' => [
                        'id' => $mahasiswa->id,
                        'name' => $mahasiswa->name,
                        'email' => $mahasiswa->email,
                        'avatar' => $mahasiswa->avatar,
                        'nim' => $mahasiswa->nim,
                        'prodi' => $mahasiswa->prodi,
                        'total_poin' => $mahasiswa->total_poin,
                        'level' => $mahasiswa->level->nama_level ?? 'Eco Starter',
                        'rfid_registered' => !empty($mahasiswa->rfid_uid),
                    ],
                    'token' => $token,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat verifikasi Google',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout - revoke token
     * POST /api/auth/logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }
}