<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_obat',
        'nama_obat',
        'jenis_obat',
        'satuan',
        'harga',
        'stok',
        'tanggal_kedaluwarsa',
        'status',
    ];

    public function detailResep()
    {
        return $this->hasMany(DetailResep::class);
    }
}