<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherMahasiswa extends Model
{
    public $timestamps = false;

    protected $table = 'voucher_mahasiswa';

    protected $fillable = [
        'mahasiswa_id',
        'kode_voucher',
        'status',
        'koin_digunakan',
        'created_at',
        'expired_at',
        'used_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'expired_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function isExpired()
    {
        return now()->greaterThan($this->expired_at) && $this->status === 'aktif';
    }
}