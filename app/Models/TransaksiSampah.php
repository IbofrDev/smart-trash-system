<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiSampah extends Model
{
    protected $table = 'transaksi_sampah';
    public $timestamps = false;

    protected $fillable = [
        'mahasiswa_id',
        'bak_sampah_id',
        'session_id',
        'jenis_sampah_id',
        'berat',
        'jumlah_input_botol',
        'jumlah_input_kaleng',
        'jumlah_terhitung',
        'jumlah_final',
        'status_validasi',
        'poin_didapat',
        'koin_didapat',
        'tanggal_transaksi',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function bakSampah(): BelongsTo
    {
        return $this->belongsTo(BakSampah::class);
    }

    public function jenisSampah(): BelongsTo
    {
        return $this->belongsTo(JenisSampah::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(TransaksiSession::class, 'session_id');
    }
}