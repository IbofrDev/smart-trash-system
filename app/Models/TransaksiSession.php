<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiSession extends Model
{
    public $timestamps = false;

    protected $table = 'transaksi_session';

    protected $fillable = [
        'mahasiswa_id',
        'session_token',
        'jumlah_botol',
        'botol_breakdown',
        'status',
        'created_at',
        'expired_at',
        'completed_at',
    ];

    protected $casts = [
        'created_at'      => 'datetime',
        'expired_at'      => 'datetime',
        'completed_at'    => 'datetime',
        'botol_breakdown' => 'array',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function transaksiSampah()
    {
        return $this->hasOne(TransaksiSampah::class, 'session_id');
    }

    public function isExpired()
    {
        return now()->greaterThan($this->expired_at);
    }

    public function isLocked()
    {
        return $this->status === 'locked';
    }

    public function getTotalInputAttribute()
    {
        return $this->jumlah_botol;
    }
}