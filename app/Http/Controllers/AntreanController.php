<?php

// Namespace controller
namespace App\Http\Controllers;

// Import model dan class yang dibutuhkan
use App\Models\Antrean;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AntreanController extends Controller
{
    // Tampilkan daftar antrean dengan paginasi
    public function index()
    {
        // Ambil antrean + relasi, urutkan dari yang terbaru
        $antreans = Antrean::with(['pasien', 'dokter', 'pendaftaran'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Kirim data ke view index
        return view('admin.antrean.index', compact('antreans'));
    }

    // Tampilkan form tambah antrean
    public function create()
    {
        // Ambil pendaftaran yang belum punya antrean
        $pendaftarans = Pendaftaran::with(['pasien', 'dokter'])
            ->whereDoesntHave('antrean')
            ->get();

        // Kirim data ke view create
        return view('admin.antrean.create', compact('pendaftarans'));
    }

    // Simpan antrean baru ke database
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
        ]);

        // Gunakan transaksi DB agar aman
        DB::transaction(function () use ($request) {
            // Ambil data pendaftaran
            $pendaftaran = Pendaftaran::findOrFail($request->pendaftaran_id);
            // Tentukan tanggal berobat (default: hari ini)
            $tglBerobat  = $pendaftaran->tgl_kunjungan ?? today()->format('Y-m-d');

            // Cari antrean terakhir dokter pada tanggal tersebut
            $lastAntrean = Antrean::where('dokter_id', $pendaftaran->dokter_id)
                ->whereDate('tanggal_berobat', $tglBerobat)
                ->orderBy('id', 'desc')
                ->first();

            // Ekstrak angka dari nomor antrean terakhir
            $lastNumber = $lastAntrean ? (int) preg_replace('/[^0-9]/', '', $lastAntrean->no_antrean) : 0;
            // Generate nomor antrean baru (contoh: A-001)
            $noAntrean  = 'A-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

            // Simpan data antrean baru
            Antrean::create([
                'no_antrean'      => $noAntrean,
                'pendaftaran_id'  => $pendaftaran->id,
                'pasien_id'       => $pendaftaran->pasien_id,
                'dokter_id'       => $pendaftaran->dokter_id,
                'tanggal_berobat' => $tglBerobat,
                'keluhan'         => $pendaftaran->keluhan_awal,
                'status'          => 'Menunggu',
            ]);
        });

        // Redirect ke index dengan pesan sukses
        return redirect()->route('admin.antrean.index')->with('success', 'Berhasil mendaftarkan antrean!');
    }

    // Update status antrean
    public function updateStatus(Request $request, Antrean $antrean)
    {
        // Validasi status
        $request->validate([
            'status' => 'required|in:Menunggu,Dipanggil,Diperiksa,Selesai,Batal',
        ]);

        // Update status di database
        $antrean->update(['status' => $request->status]);

        // Kembali ke halaman sebelumnya
        return redirect()->back()->with('success', 'Status antrean berhasil diperbarui!');
    }

    // Hapus data antrean
    public function destroy(Antrean $antrean)
    {
        // Hapus antrean
        $antrean->delete();
        // Redirect ke index dengan pesan sukses
        return redirect()->route('admin.antrean.index')->with('success', 'Antrean berhasil dihapus!');
    }
}