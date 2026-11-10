<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'antrean_id',
        'pasien_id',
        'dokter_id',
        'tindakan_id',
        'keluhan',
        'pemeriksaan_fisik',
        'diagnosis',
        'tindakan',
        'catatan',
    ];

    public function antrean()
    {
        return $this->belongsTo(Antrean::class);
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class);
    }

    public function tindakanMaster()
    {
        return $this->belongsTo(Tindakan::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }
}