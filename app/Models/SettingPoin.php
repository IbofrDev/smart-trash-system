<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingPoin extends Model
{
    protected $table = 'setting_poin';
    protected $fillable = ['nama_setting', 'value', 'deskripsi'];
}