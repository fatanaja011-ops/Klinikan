<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailResep extends Model
{
    use HasFactory;

    protected $fillable = ['rekam_medis_id', 'obat_id', 'jumlah', 'aturan_pakai'];

    protected static function booted(): void
    {
        static::created(function (DetailResep $detailResep) {
            $obat = $detailResep->obat;

            if (!$obat) {
                return;
            }

            if ($obat->stok < $detailResep->jumlah) {
                throw new \RuntimeException('Stok obat tidak mencukupi untuk ' . $obat->nama_obat);
            }

            $obat->decrement('stok', $detailResep->jumlah);
        });
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class);
    }
}