<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    protected $table = 'foto_tempat';
    protected $primaryKey = 'id_foto';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $fillable = [ 'id_tempat', 'nama_file', 'deskripsi', 'is_utama', 'urutan', 'path', 'created_at', 'updated_at']; 

    public function tempat()
    {
        return $this->belongsTo(Tempat::class, 'id_tempat', 'id_tempat');
    }   
    
}
