<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'nama_aplikasi',
        'deskripsi',
        'nama_instansi',
        'alamat',
        'no_hp',
        'email',
        'website'
    ];
}
