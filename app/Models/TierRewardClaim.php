<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TierRewardClaim extends Model
{
    protected $table = 'tier_reward_claims';

    protected $fillable = [
        'mahasiswa_id',
        'level_urutan',
        'nama_hadiah',
        'kode_reward',
        'status',
        'used_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}