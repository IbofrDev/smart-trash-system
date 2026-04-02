<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lokasi extends Model
{
    protected $table = 'lokasi';
    protected $fillable = ['nama_lokasi', 'alamat', 'koordinat'];

    public function bakSampahs(): HasMany
    {
        return $this->hasMany(BakSampah::class);
    }
}