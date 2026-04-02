<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiSampah extends Model
{
    protected $table = 'transaksi_sampah';
    protected $fillable = [
        'mahasiswa_id',
        'bak_sampah_id',
        'jenis_sampah_id',
        'berat',
        'poin_didapat',
        'tanggal_transaksi',
    ];

    public $timestamps = false; // hanya buat created (tanggal_transaksi), tidak perlu updated_at

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
}