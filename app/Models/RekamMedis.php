<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    use HasFactory;

    protected $fillable = [
        'antrean_id',
        'pasien_id',
        'dokter_id',
        'keluhan',
        'diagnosa',
        'tindakan',
        'tanggal_periksa',
    ];

    protected $casts = [
        'tanggal_periksa' => 'date',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class);
    }

    public function antrean()
    {
        return $this->belongsTo(Antrean::class);
    }

    public function detailResep()
    {
        return $this->hasMany(DetailResep::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pemeriksaan_id');
    }
}
