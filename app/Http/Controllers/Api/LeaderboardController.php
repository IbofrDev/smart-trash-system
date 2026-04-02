<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leaderboard;

class LeaderboardController extends Controller
{
    /**
     * Get Leaderboard
     * GET /api/leaderboard?period=alltime
     */
    public function index(Request $request)
    {
        $period = $request->query('period', 'alltime'); // harian, mingguan, bulanan, alltime

        $rankingField = match($period) {
            'harian' => 'ranking_harian',
            'mingguan' => 'ranking_mingguan',
            'bulanan' => 'ranking_bulanan',
            default => 'ranking_alltime',
        };

        $leaderboard = Leaderboard::with(['mahasiswa.level'])
            ->whereNotNull($rankingField)
            ->orderBy($rankingField, 'asc')
            ->limit(100)
            ->get()
            ->map(function ($item) use ($rankingField) {
                return [
                    'ranking' => $item->{$rankingField},
                    'name' => $item->mahasiswa->name,
                    'avatar' => $item->mahasiswa->avatar,
                    'level' => $item->mahasiswa->level->nama_level ?? 'Eco Starter',
                    'total_poin' => $item->mahasiswa->total_poin,
                    'total_berat_kg' => $item->total_berat_kg,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $leaderboard
        ], 200);
    }

    /**
     * Get My Rank
     * GET /api/leaderboard/my-rank?period=alltime
     */
    public function myRank(Request $request)
    {
        $mahasiswa = $request->user();
        $period = $request->query('period', 'alltime');

        $rankingField = match($period) {
            'harian' => 'ranking_harian',
            'mingguan' => 'ranking_mingguan',
            'bulanan' => 'ranking_bulanan',
            default => 'ranking_alltime',
        };

        $myLeaderboard = Leaderboard::where('mahasiswa_id', $mahasiswa->id)->first();

        if (!$myLeaderboard) {
            return response()->json([
                'success' => true,
                'data' => [
                    'ranking' => null,
                    'total_poin' => $mahasiswa->total_poin,
                    'total_berat_kg' => 0,
                ]
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'ranking' => $myLeaderboard->{$rankingField},
                'total_poin' => $mahasiswa->total_poin,
                'total_berat_kg' => $myLeaderboard->total_berat_kg,
            ]
        ], 200);
    }
}