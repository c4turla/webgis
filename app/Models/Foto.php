<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    protected $table = 'foto_tempat';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    public $timestamps = false;
    protected $fillable = [ 'id_tempat', 'nama_file', 'deskripsi', 'is_utama', 'urutan', 'path', 'created_at', 'updated_at']; 

    public function tempat()
    {
        return $this->hasMany(Tempat::class, 'tempat_id', 'id_tempat');
    }
}
