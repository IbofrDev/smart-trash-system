<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Achievement;
use App\Models\Mahasiswa;

class AchievementController extends Controller
{
    /**
     * Get All Achievements + Status Unlock
     * GET /api/achievements
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $mahasiswa = \App\Models\Mahasiswa::where('user_id', $user->id)->first();

        if (!$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Data mahasiswa tidak ditemukan.'], 404);
        }

        $unlockedIds = $mahasiswa->achievements()->pluck('achievement.id')->toArray();

        $achievements = Achievement::all()->map(function ($achievement) use ($unlockedIds) {
            return [
                'id' => $achievement->id,
                'nama' => $achievement->nama,
                'deskripsi' => $achievement->deskripsi,
                'icon' => $achievement->icon,
                'syarat_type' => $achievement->syarat_type,
                'syarat_value' => $achievement->syarat_value,
                'poin_bonus' => $achievement->poin_bonus,
                'is_unlocked' => in_array($achievement->id, $unlockedIds),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $achievements
        ], 200);
    }

    /**
     * Get My Unlocked Achievements
     * GET /api/achievements/my
     */
    public function myAchievements(Request $request)
    {
        $user = $request->user();

        if ($user instanceof \App\Models\Mahasiswa) {
            $mahasiswa = $user;
        } else {
            $mahasiswa = \App\Models\Mahasiswa::where('email', $user->email)->first();
        }

        if (!$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Data mahasiswa tidak ditemukan.'], 404);
        }

        $achievements = $mahasiswa->achievements()
            ->orderBy('mahasiswa_achievement.unlocked_at', 'desc')
            ->get()
            ->map(function ($achievement) {
                return [
                    'id' => $achievement->id,
                    'nama' => $achievement->nama,
                    'deskripsi' => $achievement->deskripsi,
                    'icon' => $achievement->icon,
                    'poin_bonus' => $achievement->poin_bonus,
                    'unlocked_at' => $achievement->pivot->unlocked_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $achievements
        ], 200);
    }
}