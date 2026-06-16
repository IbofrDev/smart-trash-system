<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\AchievementController;
use App\Http\Controllers\Api\NotifikasiController;
use App\Http\Controllers\Api\HardwareController;
use App\Http\Controllers\Api\VoucherController;
use App\Http\Controllers\Api\KasirController;

/*
|--------------------------------------------------------------------------
| Hardware Routes
|--------------------------------------------------------------------------
*/
Route::prefix('hardware')->middleware('hardware.apikey')->group(function () {
    Route::post('/verify-rfid', [HardwareController::class, 'verifyRfid']);
    Route::post('/submit-weight', [HardwareController::class, 'submitWeight']);
    Route::post('/submit-count', [HardwareController::class, 'submitCount']);
    Route::post('/complete', [HardwareController::class, 'complete']);
    Route::get('/jenis-sampah', [HardwareController::class, 'getJenisSampah']);
    Route::post('/heartbeat', [HardwareController::class, 'heartbeat']);
});

/*
|--------------------------------------------------------------------------
| Auth Routes (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::post('/auth/google', [AuthController::class, 'loginGoogle']);

// TEMPORARY: Mock Login untuk testing Flutter - HAPUS sebelum production!
Route::post('/auth/mock-login', function (\Illuminate\Http\Request $request) {
    $mahasiswa = $request->mahasiswa_id
        ? \App\Models\Mahasiswa::find($request->mahasiswa_id)
        : \App\Models\Mahasiswa::first();

    if (!$mahasiswa) {
        return response()->json([
            'success' => false,
            'message' => 'Tidak ada data mahasiswa di database'
        ], 404);
    }

    $token = $mahasiswa->createToken('mobile-app')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Mock login berhasil',
        'data' => [
            'token' => $token,
            'mahasiswa' => [
                'id' => $mahasiswa->id,
                'name' => $mahasiswa->name,
                'email' => $mahasiswa->email,
                'avatar' => $mahasiswa->avatar,
                'nim' => $mahasiswa->nim,
                'prodi' => $mahasiswa->prodi,
                'rfid_uid' => $mahasiswa->rfid_uid,
                'total_poin' => $mahasiswa->total_poin,
                'total_koin_botol' => $mahasiswa->total_koin_botol,
                'level_id' => $mahasiswa->level_id,
                'level' => $mahasiswa->level,
            ]
        ]
    ]);
});


/*
|--------------------------------------------------------------------------
| Kasir Auth Routes (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::prefix('auth/kasir')->group(function () {
    Route::post('/login', [AuthController::class, 'loginKasir']);
});

/*
|--------------------------------------------------------------------------
| Kasir Protected Routes
|--------------------------------------------------------------------------
*/
Route::prefix('kasir')
    ->middleware(['auth:sanctum', 'role:kasir'])
    ->group(function () {
        Route::get('/me', [KasirController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logoutKasir']);
        Route::post('/voucher/validate', [KasirController::class, 'validateVoucher']);
        Route::get('/voucher/check/{kode}', [KasirController::class, 'checkVoucher']);
    });

/*
|--------------------------------------------------------------------------
| Protected Routes (Mahasiswa - Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/rfid', [ProfileController::class, 'updateRfid']);
    Route::post('/profile/fcm-token', [ProfileController::class, 'updateFcmToken']);

    // Transaksi Session (BARU)
    Route::post('/transaksi/session', [TransaksiController::class, 'createSession']);
    Route::get('/transaksi/session/{token}', [TransaksiController::class, 'checkSession']);
    Route::delete('/transaksi/session/{token}', [TransaksiController::class, 'cancelSession']);

    // Transaksi History
    Route::get('/transaksi', [TransaksiController::class, 'index']);
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show']);

    // Voucher (BARU)
    Route::get('/vouchers', [VoucherController::class, 'index']);
    Route::post('/vouchers/redeem', [VoucherController::class, 'redeem']);
    Route::post('/vouchers/{id}/use', [VoucherController::class, 'useVoucher']);

    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index']);
    Route::get('/leaderboard/my-rank', [LeaderboardController::class, 'myRank']);

    // Achievement
    Route::get('/achievements', [AchievementController::class, 'index']);
    Route::get('/achievements/my', [AchievementController::class, 'myAchievements']);

    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index']);
    Route::put('/notifikasi/{id}/read', [NotifikasiController::class, 'markAsRead']);
    Route::put('/notifikasi/read-all', [NotifikasiController::class, 'markAllAsRead']);
    Route::get('/notifikasi/unread-count', [NotifikasiController::class, 'unreadCount']);
});