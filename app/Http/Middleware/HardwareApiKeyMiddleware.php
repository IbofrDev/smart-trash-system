<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\BakSampah;

class HardwareApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     * Validasi API Key dari header X-API-Key
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');

        // Cek apakah header X-API-Key ada
        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'API Key tidak ditemukan. Sertakan header X-API-Key.'
            ], 401);
        }

        // Cek apakah API Key valid (terdaftar di tabel bak_sampah)
        $bakSampah = BakSampah::where('api_key', $apiKey)->first();

        if (!$bakSampah) {
            return response()->json([
                'success' => false,
                'message' => 'API Key tidak valid.'
            ], 401);
        }

        // Cek apakah bak sampah aktif
        if ($bakSampah->status !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Bak sampah tidak aktif. Status: ' . $bakSampah->status
            ], 403);
        }

        // Simpan data bak_sampah ke request untuk digunakan di controller
        $request->merge(['bak_sampah' => $bakSampah]);

        return $next($request);
    }
}