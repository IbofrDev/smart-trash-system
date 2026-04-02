<?php

namespace App\Listeners;

use App\Events\TransaksiCreated;
use App\Services\GamifikasiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateLeaderboard
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

        // Update leaderboard
        $this->gamifikasiService->updateLeaderboard($mahasiswa);
    }
}