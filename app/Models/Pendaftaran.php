<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_pendaftaran',
        'pasien_id',
        'dokter_id',
        'jadwal_id',
        'tgl_kunjungan',
        'keluhan_awal',
    ];

    protected $casts = [
        'tgl_kunjungan' => 'date',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalDokter::class, 'jadwal_id');
    }

    // Perbaikan: Ganti nama fungsi dari 'antrian' menjadi 'antrean'
    // dan hubungkan ke Antrean::class
    public function antrean()
    {
        return $this->hasOne(Antrean::class, 'pendaftaran_id');
    }
}