<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tempat extends Model
{
    use HasFactory;
    protected $table = 'tempat';
    protected $primaryKey = 'id_tempat';
    protected $fillable = [
        'nama_tempat', 'deskripsi', 'alamat', 'latitude', 'longitude',
        'kategori_id', 'jam_buka', 'jam_tutup', 'harga_tiket',
        'fasilitas', 'kontak', 'status'
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id_kategori');
    }

    public function foto(): HasMany
    {
        return $this->hasMany(Foto::class, 'id_tempat', 'id_tempat');
    }
}
