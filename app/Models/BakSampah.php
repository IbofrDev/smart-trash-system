<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BakSampah extends Model

{
    protected $table = 'bak_sampah';
    protected $fillable = [
        'nama',
        'lokasi_id',
        'api_key',
        'status',
        'kapasitas_max',
    ];

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function transaksiSampah(): HasMany
    {
        return $this->hasMany(TransaksiSampah::class);
    }

    public function transaksiSessions()
    {
        return TransaksiSession::whereHas('transaksiItems', function ($q) {
            $q->where('bak_sampah_id', $this->id);
        });
    }
}