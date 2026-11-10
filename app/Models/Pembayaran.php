<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $casts = [
        'total_bayar' => 'decimal:2',
        'nominal_dibayar' => 'decimal:2',
        'kembalian' => 'decimal:2',
        'tgl_pembayaran' => 'date',
    ];

    protected $fillable = [
        'no_pembayaran',
        'pemeriksaan_id',
        'pasien_id',
        'total_bayar',
        'nominal_dibayar',
        'kembalian',
        'status_pembayaran',
        'metode_pembayaran',
        'snap_token',
        'order_id',
        'tgl_pembayaran',
    ];

    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class);
    }

    public function detailPembayaran()
    {
        return $this->hasMany(DetailPembayaran::class);
    }
}