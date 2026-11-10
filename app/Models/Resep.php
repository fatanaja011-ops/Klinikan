<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemeriksaan_id',
        'dokter_id',
        'pasien_id',
        'tgl_resep',
    ];

    public function detailResep()
    {
        return $this->hasMany(DetailResep::class, 'resep_id');
    }

    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class);
    }
}