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

        $rankingField = match ($period) {
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
                    'mahasiswa_id' => $item->mahasiswa_id,
                    'ranking' => $item->{$rankingField},
                    'nama' => $item->mahasiswa->name,
                    'avatar' => $item->mahasiswa->avatar,
                    'nama_level' => $item->mahasiswa->level->nama_level ?? 'Eco Starter',
                    'total_poin' => $item->mahasiswa->total_poin,
                    'total_koin' => $item->mahasiswa->total_koin_botol,
                    'total_botol' => $item->total_botol,
                    'total_berat_kg' => (float) $item->total_berat_gram / 1000,
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

        $rankingField = match ($period) {
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
                    'total_koin' => $mahasiswa->total_koin_botol,
                    'total_botol' => 0,
                    'total_berat_kg' => 0,
                ]
            ], 200);
        }

        return response()->json([
            'success' => true,
                     'data' => [
                'ranking' => $myLeaderboard->{$rankingField},
                'total_poin' => $mahasiswa->total_poin,
                'total_koin' => $mahasiswa->total_koin_botol,
                'total_botol' => $myLeaderboard->total_botol,
                'total_berat_kg' => (float) $myLeaderboard->total_berat_gram / 1000,
            ]
        ], 200);
    }
}