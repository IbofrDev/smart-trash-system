<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\MahasiswaController;
use App\Http\Controllers\Web\LokasiController;
use App\Http\Controllers\Web\BakSampahController;
use App\Http\Controllers\Web\JenisSampahController;
use App\Http\Controllers\Web\LevelController;
use App\Http\Controllers\Web\AchievementController;
use App\Http\Controllers\Web\SettingPoinController;
use App\Http\Controllers\Web\TransaksiController;
use App\Http\Controllers\Web\LaporanController;
use App\Http\Controllers\Web\LogAktivitasController;
use App\Http\Controllers\Web\VoucherController;
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // Jika sudah login, langsung ke dashboard
    if (Auth::check()) {
        return redirect()->route($routePrefix . '.dashboard'); 
    }
    return view('welcome');
})->name('welcome');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes (Admin Only)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])->name('dashboard.chart-data');

    // User Management (Admin Only)
    Route::resource('users', UserController::class);

    // Master Data - Lokasi
    Route::resource('lokasi', LokasiController::class);

    // Master Data - Bak Sampah
    Route::resource('bak-sampah', BakSampahController::class);
    Route::post('bak-sampah/{bakSampah}/regenerate-api-key', [BakSampahController::class, 'regenerateApiKey'])
        ->name('bak-sampah.regenerate-api-key');

    // Master Data - Jenis Sampah
    Route::resource('jenis-sampah', JenisSampahController::class);

    // Master Data - Level
    Route::resource('level', LevelController::class);

    // Master Data - Achievement
    Route::resource('achievement', AchievementController::class);

    // Master Data - Setting Poin
    Route::resource('setting-poin', SettingPoinController::class)->only(['index', 'edit', 'update']);

    // Mahasiswa
    Route::resource('mahasiswa', MahasiswaController::class)->except(['create', 'store']);

    // Transaksi
    Route::get('transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');

    // Voucher
    Route::get('voucher', [VoucherController::class, 'index'])->name('voucher.index');
    // Laporan
    Route::get('laporan/transaksi', [LaporanController::class, 'transaksi'])->name('laporan.transaksi');
    Route::get('laporan/transaksi/pdf', [LaporanController::class, 'transaksiPdf'])->name('laporan.transaksi.pdf');
    Route::get('laporan/mahasiswa', [LaporanController::class, 'mahasiswa'])->name('laporan.mahasiswa');
    Route::get('laporan/mahasiswa/pdf', [LaporanController::class, 'mahasiswaPdf'])->name('laporan.mahasiswa.pdf');

    // Log Aktivitas
    Route::get('log-aktivitas', [LogAktivitasController::class, 'index'])->name('log-aktivitas.index');
});

/*
|--------------------------------------------------------------------------
| Pengelola Routes (Admin & Pengelola)
|--------------------------------------------------------------------------
*/

Route::prefix('pengelola')->name('pengelola.')->middleware(['role:admin,pengelola'])->group(function () {

    // Dashboard (sama dengan admin)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])->name('dashboard.chart-data');

    // View Only - Mahasiswa
    Route::get('mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::get('mahasiswa/{mahasiswa}', [MahasiswaController::class, 'show'])->name('mahasiswa.show');

    // View Only - Transaksi
    Route::get('transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::get('voucher', [VoucherController::class, 'index'])->name('voucher.index');
    // View Only - Laporan
    Route::get('laporan/transaksi', [LaporanController::class, 'transaksi'])->name('laporan.transaksi');
    Route::get('laporan/transaksi/pdf', [LaporanController::class, 'transaksiPdf'])->name('laporan.transaksi.pdf');
    Route::get('laporan/mahasiswa', [LaporanController::class, 'mahasiswa'])->name('laporan.mahasiswa');
    Route::get('laporan/mahasiswa/pdf', [LaporanController::class, 'mahasiswaPdf'])->name('laporan.mahasiswa.pdf');
});