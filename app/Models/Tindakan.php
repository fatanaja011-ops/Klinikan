<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tindakan extends Model
{
    protected $fillable = [
        'kode_tindakan',
        'nama_tindakan',
        'tarif',
        'keterangan',
    ];

    protected $casts = [
        'tarif' => 'decimal:2',
    ];
}
