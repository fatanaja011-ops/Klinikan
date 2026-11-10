<?php

// Namespace untuk controller
namespace App\Http\Controllers;

// Import model dan class yang dibutuhkan
use App\Models\Pemeriksaan;
use App\Models\Antrean;
use App\Models\Tindakan;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemeriksaanController extends Controller
{
    // 1. Menampilkan daftar data pemeriksaan pasien
    public function pemeriksaan_data()
    {
        // Ambil data pemeriksaan beserta relasi pasien, dokter, dan antrean dengan paginasi
        $pemeriksaans = Pemeriksaan::with(['pasien', 'dokter', 'antrean'])
            ->latest()
            ->paginate(10);

        // Tampilkan halaman index pemeriksaan
        return view('admin.pemeriksaan.index', compact('pemeriksaans'));
    }

    // Menampilkan form tambah data pemeriksaan
    public function create()
    {
        // Ambil data antrean yang statusnya masih 'Menunggu', 'Dipanggil', atau 'Diperiksa'
        $antreans = Antrean::with(['pasien', 'dokter'])
            ->whereIn('status', ['Menunggu', 'Dipanggil', 'Diperiksa'])
            ->get();
            
        // Ambil semua master data tindakan berurutan berdasarkan nama
        $tindakans = Tindakan::orderBy('nama_tindakan')->get();

        // Tampilkan halaman form create pemeriksaan
        return view('admin.pemeriksaan.create', compact('antreans', 'tindakans'));
    }

    // 2. Menyimpan data pemeriksaan baru (oleh Admin)
    public function pemeriksaan_add(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'antrean_id'        => 'required|exists:antreans,id',
            'keluhan'           => 'required|string',
            'pemeriksaan_fisik' => 'nullable|string',
            'diagnosis'         => 'required|string',
            'tindakan_id'       => 'nullable|exists:tindakans,id',
            'tindakan'          => 'nullable|string',
            'catatan'           => 'nullable|string',
        ]);

        // Cari data antrean berdasarkan ID
        $antrean = Antrean::findOrFail($request->antrean_id);

        // Mulai database transaction
        DB::beginTransaction();
        try {
            // Simpan data pemeriksaan baru
            $pemeriksaan = Pemeriksaan::create([
                'antrean_id'        => $antrean->id,
                'pasien_id'         => $antrean->pasien_id,
                'dokter_id'         => $antrean->dokter_id,
                'tindakan_id'       => $request->tindakan_id,
                'keluhan'           => $request->keluhan,
                'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
                'diagnosis'         => $request->diagnosis,
                'tindakan'          => $request->tindakan,
                'catatan'           => $request->catatan,
            ]);

            // Buat record Rekam Medis secara otomatis
            RekamMedis::create([
                'pasien_id'       => $antrean->pasien_id,
                'dokter_id'       => $antrean->dokter_id,
                'pemeriksaan_id'  => $pemeriksaan->id,
                'keluhan'         => $request->keluhan,
                'diagnosa'        => $request->diagnosis,
                'tindakan'        => $request->tindakan,
                'tgl_rekam_medis' => now(),
            ]);

            // Update status antrean pasien menjadi 'Selesai'
            $antrean->update(['status' => 'Selesai']);

            // Simpan permanen perubahan ke database
            DB::commit();

            // Redirect ke halaman index dengan pesan sukses
            return redirect()->route('pemeriksaan.data')->with('success', 'Data pemeriksaan berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Batalkan semua transaksi jika terjadi error
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    // Menampilkan form edit data pemeriksaan
    public function edit($id)
    {
        // Cari data pemeriksaan berdasarkan ID
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        
        // Tampilkan halaman form edit
        return view('admin.pemeriksaan.edit', compact('pemeriksaan'));
    }

    // 3. Memperbarui data pemeriksaan
    public function pemeriksaan_edit(Request $request, $id)
    {
        // Validasi input dari form edit
        $request->validate([
            'keluhan'           => 'required|string',
            'pemeriksaan_fisik' => 'nullable|string',
            'diagnosis'         => 'required|string',
            'tindakan'          => 'nullable|string',
            'catatan'           => 'nullable|string',
        ]);

        // Cari data pemeriksaan berdasarkan ID
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        
        // Update data pemeriksaan
        $pemeriksaan->update([
            'keluhan'           => $request->keluhan,
            'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
            'diagnosis'         => $request->diagnosis,
            'tindakan'          => $request->tindakan,
            'catatan'           => $request->catatan,
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('pemeriksaan.data')->with('success', 'Data pemeriksaan berhasil diperbarui!');
    }

    // 4. Menghapus data pemeriksaan
    public function pemeriksaan_hapus($id)
    {
        // Cari data pemeriksaan berdasarkan ID
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        
        // Hapus data pemeriksaan
        $pemeriksaan->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('pemeriksaan.data')->with('success', 'Data pemeriksaan berhasil dihapus!');
    }
}