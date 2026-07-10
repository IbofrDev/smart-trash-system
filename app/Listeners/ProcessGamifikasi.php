<?php

namespace App\Listeners;

use App\Events\TransaksiCreated;
use App\Services\GamifikasiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessGamifikasi implements ShouldQueue
{
    protected GamifikasiService $gamifikasiService;

    /**
     * Create the event listener.
     */
    public function __construct(GamifikasiService $gamifikasiService)
    {
        $this->gamifikasiService = $gamifikasiService;
    }

    /**
     * Handle the event.
     */
    public function handle(TransaksiCreated $event): void
    {
        $transaksi = $event->transaksi;
        $mahasiswa = $transaksi->mahasiswa;

        // 1. Check Achievement
        $this->gamifikasiService->checkAchievements($mahasiswa);

        // 2. Check Level Up (setelah achievement karena bisa dapat bonus poin)
        $mahasiswa->refresh(); // Refresh untuk dapat total_poin terbaru
        $this->gamifikasiService->checkLevelUp($mahasiswa);
        $mahasiswa->refresh();
        $this->gamifikasiService->updateLeaderboard($mahasiswa);
    }
}