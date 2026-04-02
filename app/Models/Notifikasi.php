<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    protected $fillable = [
        'mahasiswa_id',
        'judul',
        'pesan',
        'tipe',
        'is_read',
    ];

    public $timestamps = true;

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}