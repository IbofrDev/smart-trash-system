<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogPengosonganBak extends Model
{
    public $timestamps = false;

    protected $table = 'log_pengosongan_bak';

    protected $fillable = [
        'bak_sampah_id',
        'user_id',
        'jumlah_botol_sebelum',
        'catatan',
        'dikosongkan_at',
    ];

    protected $casts = [
        'dikosongkan_at' => 'datetime',
    ];

    public function bakSampah(): BelongsTo
    {
        return $this->belongsTo(BakSampah::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}