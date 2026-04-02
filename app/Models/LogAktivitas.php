<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';
    
    protected $fillable = [
        'user_type',
        'user_id',
        'aktivitas',
        'tabel_target',
        'ip_address',
    ];

    // Disable timestamps karena tabel hanya punya created_at
    public $timestamps = false;

    // Set created_at otomatis saat create
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->created_at = now();
        });
    }
}