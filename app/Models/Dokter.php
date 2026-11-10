<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kode_dokter',
        'nama_dokter',
        'spesialisasi',
        'no_telepon',
        'alamat',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwal()
    {
        return $this->hasMany(JadwalDokter::class);
    }
}