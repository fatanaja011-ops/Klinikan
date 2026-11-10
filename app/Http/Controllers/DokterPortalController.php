<?php

// Namespace controller
namespace App\Http\Controllers;

// Import model dan class yang dibutuhkan
use App\Models\Antrean;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Obat;
use App\Models\Pemeriksaan;
use App\Models\RekamMedis;
use App\Models\Tindakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DokterPortalController extends Controller
{
    // Helper untuk mengambil data profil dokter yang sedang login
    private function dokter(): ?Dokter
    {
        return auth()->user()->dokter;
    }

    // Tampilkan dashboard dokter
    public function dashboard()
    {
        // Ambil profil dokter yang login
        $dokter = $this->dokter();
        // Ambil daftar pasien hari ini berstatus aktif
        $pasienHariIni = Antrean::with('pasien')
            ->where('dokter_id', $dokter?->id)
            ->whereDate('tanggal_berobat', today())
            ->whereIn('status', ['Menunggu', 'Dipanggil', 'Diperiksa'])
            ->latest()
            ->get();

        // Kirim data dashboard ke view
        return view('dokter.dashboard', [
            'dokter' => $dokter,
            'pasienHariIni' => $pasienHariIni,
            'jumlahPasienHariIni' => $pasienHariIni->count(),
            'jumlahSelesai' => Antrean::where('dokter_id', $dokter?->id)->whereDate('tanggal_berobat', today())->where('status', 'Selesai')->count(),
        ]);
    }

    // Tampilkan daftar antrean pemeriksaan
    public function pemeriksaan()
    {
        // Ambil antrean pasien aktif dengan paginasi
        $antreans = Antrean::with('pasien')
            ->where('dokter_id', $this->dokter()?->id)
            ->whereIn('status', ['Menunggu', 'Dipanggil', 'Diperiksa'])
            ->latest()
            ->paginate(10);

        // Kirim data ke view index pemeriksaan
        return view('dokter.pemeriksaan.index', compact('antreans'));
    }

    // Tampilkan form input pemeriksaan baru
    public function createPemeriksaan()
    {
        // Kirim data antrean aktif dan daftar tindakan ke view
        return view('dokter.pemeriksaan.create', [
            'antreans' => Antrean::with(['pasien', 'dokter'])
                ->where('dokter_id', $this->dokter()?->id)
                ->whereIn('status', ['Menunggu', 'Dipanggil', 'Diperiksa'])
                ->get(),
            'tindakans' => Tindakan::orderBy('nama_tindakan')->get(),
        ]);
    }

    // Simpan hasil pemeriksaan dan buat rekam medis
    public function storePemeriksaan(Request $request)
    {
        // Ambil dokter login
        $dokter = $this->dokter();
        // Validasi input form
        $validated = $request->validate([
            'antrean_id' => ['required', 'exists:antreans,id'],
            'keluhan' => ['required', 'string'],
            'pemeriksaan_fisik' => ['nullable', 'string'],
            'diagnosis' => ['required', 'string'],
            'tindakan_id' => ['nullable', 'exists:tindakans,id'],
            'tindakan' => ['nullable', 'string'],
            'catatan' => ['nullable', 'string'],
        ]);

        // Cari antrean terkait
        $antrean = Antrean::where('dokter_id', $dokter?->id)->findOrFail($validated['antrean_id']);

        // Mulai transaksi DB
        DB::beginTransaction();
        try {
            // 1. Simpan data pemeriksaan
            $pemeriksaan = Pemeriksaan::create([
                'antrean_id' => $antrean->id,
                'pasien_id' => $antrean->pasien_id,
                'dokter_id' => $antrean->dokter_id,
                'tindakan_id' => $validated['tindakan_id'] ?? null,
                'keluhan' => $validated['keluhan'],
                'pemeriksaan_fisik' => $validated['pemeriksaan_fisik'] ?? null,
                'diagnosis' => $validated['diagnosis'],
                'tindakan' => $validated['tindakan'] ?? null,
                'catatan' => $validated['catatan'] ?? null,
            ]);

            // 2. Buat rekam medis otomatis
            RekamMedis::create([
                'pasien_id' => $antrean->pasien_id,
                'dokter_id' => $antrean->dokter_id,
                'pemeriksaan_id' => $pemeriksaan->id,
                'keluhan' => $validated['keluhan'],
                'diagnosa' => $validated['diagnosis'],
                'tindakan' => $validated['tindakan'] ?? null,
                'tgl_rekam_medis' => now(),
            ]);

            // 3. Ubah status antrean jadi 'Selesai'
            $antrean->update(['status' => 'Selesai']);

            // Simpan permanen transaksi
            DB::commit();

            // Redirect ke index pemeriksaan dengan pesan sukses
            return redirect()->route('dokter.pemeriksaan.index')->with('success', 'Pemeriksaan dan Rekam Medis berhasil disimpan.');
        } catch (\Exception $e) {
            // Batalkan semua transaksi jika terjadi error
            DB::rollBack();
            // Kembali ke form dengan pesan error
            return back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    // Tampilkan riwayat rekam medis pasien dokter ini
    public function rekamMedis()
    {
        // Ambil rekam medis + relasinya dengan paginasi
        $data = RekamMedis::with(['pasien', 'dokter', 'detailResep.obat'])
            ->where('dokter_id', $this->dokter()?->id)
            ->latest()
            ->paginate(10);

        // Kirim data ke view rekam medis
        return view('dokter.rekam-medis.index', compact('data'));
    }

    // Tampilkan daftar resep yang pernah dibuat
    public function resep()
    {
        // Ambil rekam medis yang punya resep obat
        $data = RekamMedis::with(['pasien', 'detailResep.obat'])
            ->where('dokter_id', $this->dokter()?->id)
            ->whereHas('detailResep')
            ->latest()
            ->paginate(10);
        // Ambil stok obat yang tersedia
        $obats = Obat::where('stok', '>', 0)->orderBy('nama_obat')->get();

        // Kirim data ke view resep
        return view('dokter.resep.index', compact('data', 'obats'));
    }

    // Tampilkan jadwal praktik dokter ini
    public function jadwal()
    {
        // Ambil jadwal dokter urut berdasarkan hari dan jam
        $data = JadwalDokter::where('dokter_id', $this->dokter()?->id)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        // Kirim data ke view jadwal
        return view('dokter.jadwal.index', compact('data'));
    }
}