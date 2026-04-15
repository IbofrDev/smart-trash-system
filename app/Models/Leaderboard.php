<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leaderboard extends Model
{
    protected $table = 'leaderboard';
    public $timestamps = true;

    protected $fillable = [
        'mahasiswa_id',
        'ranking_harian',
        'ranking_mingguan',
        'ranking_bulanan',
        'ranking_alltime',
        'total_berat_gram',
        'total_botol',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}