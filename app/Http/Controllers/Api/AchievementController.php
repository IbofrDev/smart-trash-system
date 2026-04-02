<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Achievement;

class AchievementController extends Controller
{
    /**
     * Get All Achievements + Status Unlock
     * GET /api/achievements
     */
    public function index(Request $request)
    {
        $mahasiswa = $request->user();

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
        $mahasiswa = $request->user();

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