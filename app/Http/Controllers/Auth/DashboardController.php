<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Antrean;
use App\Models\Pemeriksaan;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total seluruh pasien
        $totalPasien = Pasien::count();

        // 2. Antrean hari ini
        $antreanHariIni = Antrean::whereDate('created_at', Carbon::today())->count();
        $antreanMenunggu = Antrean::whereDate('created_at', Carbon::today())
            ->where('status', 'menunggu') // sesuaikan nama kolom & nilai status di database kamu
            ->count();

        // 3. Pemeriksaan selesai hari ini
        $pemeriksaanSelesai = Pemeriksaan::whereDate('created_at', Carbon::today())->count();

        // 4. Dokter bertugas/aktif
        $dokterBertugas = Dokter::count(); // Atau tambahkan kondisi misal ->where('status', 'aktif')

        return view('dashboard', compact(
            'totalPasien',
            'antreanHariIni',
            'antreanMenunggu',
            'pemeriksaanSelesai',
            'dokterBertugas'
        ));
    }
}