<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDokter extends Model
{
    use HasFactory;

    protected $fillable = [
        'dokter_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruang',
        'kuota',
    ];

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }
}