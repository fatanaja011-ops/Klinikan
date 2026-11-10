<?php

// Namespace controller
namespace App\Http\Controllers;

// Import model dan class yang dibutuhkan
use App\Models\Pembayaran;
use App\Models\RekamMedis;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    // Menampilkan halaman data laporan dengan filter
    public function laporan_data(Request $request)
    {
        // Ambil jenis laporan dari request (default: pembayaran)
        $jenis_laporan = $request->get('jenis_laporan', 'pembayaran');
        // Ambil tanggal mulai dari request (default: tanggal 1 bulan ini)
        $tgl_mulai = $request->get('tgl_mulai', date('Y-m-01'));
        // Ambil tanggal selesai dari request (default: hari ini)
        $tgl_selesai = $request->get('tgl_selesai', date('Y-m-d'));

        // Inisialisasi variabel penampung data
        $data = [];

        // Jika jenis laporan yang dipilih adalah pembayaran
        if ($jenis_laporan == 'pembayaran') {
            // Ambil data pembayaran beserta relasi berdasarkan rentang tanggal
            $data = Pembayaran::with(['pemeriksaan.pasien', 'pemeriksaan.dokter'])
                ->whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59'])
                ->latest()
                ->get();
        // Jika jenis laporan yang dipilih adalah rekam medis
        } elseif ($jenis_laporan == 'rekam_medis') {
            // Ambil data rekam medis beserta relasi berdasarkan rentang tanggal
            $data = RekamMedis::with(['pasien', 'dokter'])
                ->whereBetween('tanggal_periksa', [$tgl_mulai, $tgl_selesai])
                ->latest()
                ->get();
        }

        // Kirim data laporan dan filter ke view index
        return view('admin.laporan.index', compact('data', 'jenis_laporan', 'tgl_mulai', 'tgl_selesai'));
    }

    // Menampilkan halaman khusus cetak/print laporan
    public function laporan_cetak(Request $request)
    {
        // Ambil jenis laporan dari request (default: pembayaran)
        $jenis_laporan = $request->get('jenis_laporan', 'pembayaran');
        // Ambil tanggal mulai dari request (default: tanggal 1 bulan ini)
        $tgl_mulai = $request->get('tgl_mulai', date('Y-m-01'));
        // Ambil tanggal selesai dari request (default: hari ini)
        $tgl_selesai = $request->get('tgl_selesai', date('Y-m-d'));

        // Ambil data pembayaran jika jenis laporan adalah pembayaran
        if ($jenis_laporan == 'pembayaran') {
            $data = Pembayaran::with(['pemeriksaan.pasien', 'pemeriksaan.dokter'])
                ->whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59'])
                ->get();
        // Ambil data rekam medis jika jenis laporan lain dipilih
        } else {
            $data = RekamMedis::with(['pasien', 'dokter'])
                ->whereBetween('tanggal_periksa', [$tgl_mulai, $tgl_selesai])
                ->get();
        }

        // Kirim data ke view khusus halaman cetak
        return view('admin.laporan.cetak', compact('data', 'jenis_laporan', 'tgl_mulai', 'tgl_selesai'));
    }
}