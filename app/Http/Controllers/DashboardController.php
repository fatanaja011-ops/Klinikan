<?php

// Namespace controller
namespace App\Http\Controllers;

// Import model dan class yang dibutuhkan
use App\Models\Antrean;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Pemeriksaan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Handler utama dashboard (pengarah berdasarkan role)
    public function index()
    {
        // Ambil role user yang sedang login
        $role = auth()->user()->role;

        // Redirect jika role admin
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        // Redirect jika role dokter
        } elseif ($role === 'dokter') {
            return redirect()->route('dokter.dashboard');
        // Redirect jika role pasien
        } elseif ($role === 'pasien') {
            return redirect()->route('pasien.dashboard');
        }

        // Tampilkan view default jika role tidak cocok
        return view('dashboard', $this->stats());
    }

    // Dashboard khusus admin
    public function adminIndex()
    {
        // Tampilkan view dashboard dengan data statistik
        return view('dashboard', $this->stats());
    }

    // Dashboard khusus dokter
    public function dokterIndex()
    {
        // Tampilkan view dashboard dengan data statistik
        return view('dashboard', $this->stats());
    }

    // Dashboard khusus pasien
    public function pasienIndex()
    {
        // Tampilkan view dashboard dengan data statistik
        return view('dashboard', $this->stats());
    }

    // Helper untuk mengambil data statistik ringkasan
    protected function stats(): array
    {
        // Hitung total seluruh pasien
        $totalPasien = Pasien::count();
        // Hitung total antrean hari ini
        $antreanHariIni = Antrean::whereDate('created_at', Carbon::today())->count();
        // Hitung antrean berstatus 'Menunggu' hari ini
        $antreanMenunggu = Antrean::whereDate('created_at', Carbon::today())
            ->where('status', 'Menunggu')
            ->count();
        // Hitung total pemeriksaan selesai hari ini
        $pemeriksaanSelesai = Pemeriksaan::whereDate('created_at', Carbon::today())->count();
        // Hitung total dokter
        $dokterBertugas = Dokter::count();

        // Kembalikan semua variabel sebagai array
        return compact(
            'totalPasien',
            'antreanHariIni',
            'antreanMenunggu',
            'pemeriksaanSelesai',
            'dokterBertugas'
        );
    }
}