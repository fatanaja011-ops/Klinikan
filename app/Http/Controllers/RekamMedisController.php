<?php

// Namespace untuk controller
namespace App\Http\Controllers;

// Import model dan fasilitas yang dibutuhkan
use App\Models\RekamMedis;
use App\Models\DetailResep;
use App\Models\Antrean;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekamMedisController extends Controller
{
    // Menampilkan daftar rekam medis pasien
    public function index()
    {
        // Ambil data rekam medis beserta relasinya secara berurutan terbaru dengan paginasi
        $rekamMedis = RekamMedis::with(['pasien', 'dokter', 'detailResep.obat'])
            ->latest()
            ->paginate(10);

        // Tampilkan halaman daftar rekam medis
        return view('admin.rekam_medis.index', compact('rekamMedis'));
    }

    // Menampilkan form pendaftaran rekam medis baru
    public function create()
    {
        // Ambil antrean pasien yang berstatus Menunggu, Dipanggil, atau Diperiksa
        $antreans = Antrean::with(['pasien', 'dokter'])
            ->whereIn('status', ['Menunggu', 'Dipanggil', 'Diperiksa'])
            ->get();

        // Ambil daftar obat yang stoknya lebih dari 0
        $obats = Obat::where('stok', '>', 0)->get();

        // Tampilkan halaman form tambah rekam medis
        return view('admin.rekam_medis.create', compact('antreans', 'obats'));
    }

    // Menyimpan data rekam medis dan resep obat ke database
    public function store(Request $request)
    {
        // Validasi input data dari form
        $request->validate([
            'antrean_id'   => 'required|exists:antreans,id',
            'diagnosa'     => 'required|string',
            'tindakan'     => 'nullable|string',
            'obat_id'      => 'nullable|array',
            'obat_id.*'    => 'nullable|exists:obats,id',
            'jumlah'       => 'nullable|array',
            'aturan_pakai' => 'nullable|array',
        ]);

        // Cari data antrean berdasarkan ID
        $antrean = Antrean::findOrFail($request->antrean_id);

        // Jalankan transaksi database
        DB::transaction(function () use ($request, $antrean) {
            // 1. Simpan record Rekam Medis utama
            $rekamMedis = RekamMedis::create([
                'antrean_id'      => $antrean->id,
                'pasien_id'       => $antrean->pasien_id,
                'dokter_id'       => $antrean->dokter_id,
                'keluhan'         => $antrean->keluhan,
                'diagnosa'        => $request->diagnosa,
                'tindakan'        => $request->tindakan,
                'tanggal_periksa' => now(),
            ]);

            // 2. Simpan rincian resep obat dan kurangi stok obat jika ada
            if ($request->has('obat_id') && is_array($request->obat_id)) {
                foreach ($request->obat_id as $index => $obatId) {
                    if ($obatId && !empty($request->jumlah[$index])) {
                        $qty = (int) $request->jumlah[$index];

                        // Simpan detail resep obat
                        DetailResep::create([
                            'rekam_medis_id' => $rekamMedis->id,
                            'obat_id'        => $obatId,
                            'jumlah'         => $qty,
                            'aturan_pakai'   => $request->aturan_pakai[$index] ?? '-',
                        ]);

                        // Kurangi stok obat sesuai dengan jumlah resep
                        $obat = Obat::find($obatId);
                        if ($obat) {
                            $obat->decrement('stok', $qty);
                        }
                    }
                }
            }

            // 3. Perbarui status antrean menjadi 'Selesai'
            $antrean->update(['status' => 'Selesai']);
        });

        // Redirect kembali ke indeks rekam medis dengan pesan sukses
        return redirect()->route('admin.rekam-medis.index')
            ->with('success', 'Pemeriksaan & Rekam Medis berhasil disimpan!');
    }

    // Menampilkan rincian detail rekam medis tertentu
    public function show($id)
    {
        // Cari rekam medis beserta relasinya berdasarkan ID
        $rekamMedis = RekamMedis::with(['pasien', 'dokter', 'detailResep.obat'])->findOrFail($id);
        
        // Tampilkan halaman detail rekam medis
        return view('admin.rekam_medis.show', compact('rekamMedis'));
    }
}