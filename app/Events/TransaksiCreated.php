<?php

namespace App\Events;

use App\Models\TransaksiSampah;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransaksiCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TransaksiSampah $transaksi;

    /**
     * Create a new event instance.
     */
    public function __construct(TransaksiSampah $transaksi)
    {
        $this->transaksi = $transaksi;
    }
}