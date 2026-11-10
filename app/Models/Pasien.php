<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'no_rm',
        'nik',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
    ];

    /**
     * Relasi ke model User (1 Pasien milik 1 User Login)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke model Pendaftaran (1 Pasien punya banyak riwayat Pendaftaran)
     */
    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'pasien_id');
    }
}