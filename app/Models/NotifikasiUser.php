<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotifikasiUser extends Model
{
    protected $table = 'notifikasi_user';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'bak_sampah_id',
        'is_read',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bakSampah(): BelongsTo
    {
        return $this->belongsTo(BakSampah::class);
    }
}