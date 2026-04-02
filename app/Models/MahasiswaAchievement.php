<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MahasiswaAchievement extends Pivot
{
    protected $table = 'mahasiswa_achievement';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = ['mahasiswa_id', 'achievement_id', 'unlocked_at'];
}