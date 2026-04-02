<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Achievement extends Model
{
    protected $table = 'achievement';
    protected $fillable = [
        'nama',
        'deskripsi',
        'icon',
        'syarat_type',
        'syarat_value',
        'poin_bonus',
    ];

    public function mahasiswas(): BelongsToMany
    {
        return $this->belongsToMany(Mahasiswa::class, 'mahasiswa_achievement')
                    ->withTimestamps()
                    ->withPivot('unlocked_at');
    }
}