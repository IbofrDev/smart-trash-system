<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens; // ← Tambahkan ini

class Mahasiswa extends Model
{
    use HasApiTokens; // ← Tambahkan ini

    protected $table = 'mahasiswa';
    protected $fillable = [
        'google_id',
        'email',
        'name',
        'avatar',
        'prodi',
        'nim',
        'rfid_uid',
        'total_poin',
        'level_id',
        'fcm_token',
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function transaksiSampah(): HasMany
    {
        return $this->hasMany(TransaksiSampah::class);
    }

    public function leaderboard(): HasOne
    {
        return $this->hasOne(Leaderboard::class);
    }

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'mahasiswa_achievement')
                    ->withTimestamps()
                    ->withPivot('unlocked_at');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }
}