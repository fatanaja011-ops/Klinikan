<?php

// Namespace untuk controller
namespace App\Http\Controllers;

// Import controller utama dan model yang digunakan
use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Antrean;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PendaftaranController extends Controller
{
    // Menampilkan daftar data pendaftaran pasien
    public function pendaftaran_data(Request $request)
    {
        // Cek parameter query 'riwayat' untuk memfilter status
        $riwayat = $request->boolean('riwayat');
        // Load pendaftaran beserta relasi pasien, dokter, jadwal, dan antrean
        $query = Pendaftaran::with(['pasien', 'dokter', 'jadwal', 'antrean']);

        // Jika riwayat true, tampilkan pendaftaran yang antreannya sudah Selesai/Batal
        if ($riwayat) {
            $query->whereHas('antrean', function ($antrean) {
                $antrean->whereIn('status', ['Selesai', 'Batal']);
            });
        } else {
            // Jika false, tampilkan yang belum punya antrean atau status antrean aktif
            $query->where(function ($q) {
                $q->whereDoesntHave('antrean')
                  ->orWhereHas('antrean', function ($antrean) {
                      $antrean->whereNotIn('status', ['Selesai', 'Batal']);
                  });
            });
        }

        // Ambil data terbaru dengan paginasi 10 items & pertahankan query string URL
        $pendaftaran = $query->latest()->paginate(10)->withQueryString();
        // Hitung total kunjungan pasien hari ini
        $kunjunganHariIni = Pendaftaran::whereDate('tgl_kunjungan', today())->count();

        // Tampilkan view pendaftaran index
        return view('admin.pendaftaran.index', compact('pendaftaran', 'kunjunganHariIni', 'riwayat'));
    }

    // Menampilkan form tambah pendaftaran
    public function pendaftaran_tambah()
    {
        // Ambil data semua pasien, dokter beserta akun user, dan jadwal dokter
        $pasiens = Pasien::all();
        $dokters = Dokter::with('user')->get(); 
        $jadwals = JadwalDokter::with('dokter')->get();

        // Tampilkan view form create pendaftaran
        return view('admin.pendaftaran.create', compact('pasiens', 'dokters', 'jadwals'));
    }

    // Memproses dan menyimpan data pendaftaran baru
    public function pendaftaran_add(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'pasien_id'        => 'required|exists:pasiens,id',
            'dokter_id'        => 'required|exists:dokters,id',
            'jadwal_dokter_id' => [
                'required',
                Rule::exists('jadwal_dokters', 'id')->where(fn ($query) => $query->where('dokter_id', $request->dokter_id)),
            ],
            'tgl_kunjungan'    => 'required|date',
            'keluhan_awal'     => 'required|string',
        ]);

        // Mapping nama field jadwal_dokter_id menjadi jadwal_id
        $validated['jadwal_id'] = $validated['jadwal_dokter_id'];
        unset($validated['jadwal_dokter_id']);

        // Mulai transaksi database
        DB::beginTransaction();
        try {
            // Format nomor pendaftaran otomatis (REG-YYYYMMDD-XXX)
            $today = date('Ymd');
            $count = Pendaftaran::whereDate('created_at', today())->count() + 1;
            $validated['no_pendaftaran'] = 'REG-' . $today . '-' . sprintf('%03d', $count);

            // Simpan record pendaftaran
            $pendaftaran = Pendaftaran::create($validated);

            // Cari antrean terakhir untuk dokter pada tanggal kunjungan tersebut
            $lastAntrean = Antrean::where('dokter_id', $validated['dokter_id'])
                ->whereDate('tanggal_berobat', $validated['tgl_kunjungan'])
                ->orderBy('id', 'desc')
                ->first();

            // Tentukan nomor antrean baru (A-001, A-002, dst.)
            $lastNumber = $lastAntrean ? (int) preg_replace('/[^0-9]/', '', $lastAntrean->no_antrean) : 0;
            $noAntrean  = 'A-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

            // Otomatis buatkan record antrean untuk pasien
            Antrean::create([
                'pendaftaran_id'  => $pendaftaran->id,
                'pasien_id'       => $pendaftaran->pasien_id,
                'dokter_id'       => $validated['dokter_id'],
                'no_antrean'      => $noAntrean,
                'tanggal_berobat' => $validated['tgl_kunjungan'],
                'keluhan'         => $validated['keluhan_awal'],
                'status'          => 'Menunggu'
            ]);

            // Simpan permanen perubahan transaksi
            DB::commit();

            // Redirect ke index dengan pesan sukses
            return redirect()->route('admin.pendaftaran.index')
                ->with('success', 'Pendaftaran pasien berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Batalkan semua transaksi jika terjadi kesalahan
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan pendaftaran: ' . $e->getMessage());
        }
    }

    // Memperbarui data pendaftaran
    public function pendaftaran_edit(Request $request, $id)
    {
        // Validasi input data pembaharuan
        $validated = $request->validate([
            'dokter_id'        => 'required|exists:dokters,id',
            'jadwal_dokter_id' => 'required|exists:jadwal_dokters,id',
            'tgl_kunjungan'    => 'required|date',
            'keluhan_awal'     => 'required|string',
        ]);

        // Mapping nama field jadwal_dokter_id menjadi jadwal_id
        $validated['jadwal_id'] = $validated['jadwal_dokter_id'];
        unset($validated['jadwal_dokter_id']);

        // Cari data pendaftaran dan perbarui
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update($validated);

        // Redirect ke index pendaftaran dengan pesan sukses
        return redirect()->route('admin.pendaftaran.index')
            ->with('success', 'Data pendaftaran berhasil diperbarui.');
    }

    // Menghapus data pendaftaran beserta antreannya
    public function pendaftaran_hapus($id)
    {
        // Cari data pendaftaran berdasarkan ID
        $pendaftaran = Pendaftaran::findOrFail($id);
        
        // Hapus data antrean terkait jika ada
        if ($pendaftaran->antrean) {
            $pendaftaran->antrean()->delete();
        }
        
        // Hapus data pendaftaran
        $pendaftaran->delete();

        // Redirect ke index pendaftaran dengan pesan sukses
        return redirect()->route('admin.pendaftaran.index')
            ->with('success', 'Data pendaftaran berhasil dihapus.');
    }
}