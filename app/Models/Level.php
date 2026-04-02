<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    protected $table = 'level';
    protected $fillable = ['nama_level', 'min_poin', 'max_poin', 'urutan'];

    public function mahasiswas(): HasMany
    {
        return $this->hasMany(Mahasiswa::class);
    }
}