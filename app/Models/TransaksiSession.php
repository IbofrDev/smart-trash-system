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
        'jumlah_kaleng',
        'status',
        'created_at',
        'expired_at',
        'completed_at',
    ];

    protected $casts = [
        'created_at'   => 'datetime',
        'expired_at'   => 'datetime',
        'completed_at' => 'datetime',
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

    public function getTotalInputAttribute()
    {
        return $this->jumlah_botol + $this->jumlah_kaleng;
    }
}