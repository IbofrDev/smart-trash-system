<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSampah extends Model
{
    protected $table = 'jenis_sampah';
    protected $fillable = [
        'nama',
        'deskripsi',
        'poin_per_kg',
        'berat_min_gram',
        'berat_max_gram',
        'satuan',
        'is_active',
    ];

    public function transaksiSampah(): HasMany
    {
        return $this->hasMany(TransaksiSampah::class);
    }
}