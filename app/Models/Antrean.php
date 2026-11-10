<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrean extends Model
{
    use HasFactory;

    protected $table = 'antreans';

    protected $fillable = [
        'pendaftaran_id',
        'no_antrean',
        'pasien_id',
        'dokter_id',
        'tanggal_berobat',
        'keluhan',
        'status',
    ];

    protected $casts = [
        'tanggal_berobat' => 'date',
    ];

    protected $attributes = [
        'status' => 'Menunggu',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }
}