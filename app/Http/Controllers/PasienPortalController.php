<?php

// Namespace controller
namespace App\Http\Controllers;

// Import model dan class yang dibutuhkan
use App\Models\Pendaftaran;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Antrean;
use App\Models\RekamMedis;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PasienPortalController extends Controller
{
    // 1. Menampilkan halaman Dashboard Pasien
    public function dashboard()
    {
        // Cari data pasien berdasarkan ID user yang sedang login
        $pasien = Pasien::where('user_id', Auth::id())->first();

        // Inisialisasi variabel awal
        $antreanAktif = null;
        $totalKunjungan = 0;
        $riwayatPendaftaran = collect();

        // Jika data pasien ditemukan
        if ($pasien) {
            // Ambil antrean aktif pasien yang belum selesai atau dibatalkan
            $antreanAktif = Antrean::where('pasien_id', $pasien->id)
                ->with(['dokter', 'pendaftaran.jadwal'])
                ->whereNotIn('status', ['Selesai', 'Batal'])
                ->latest()
                ->first();

            // Hitung total kunjungan/pendaftaran pasien
            $totalKunjungan = Pendaftaran::where('pasien_id', $pasien->id)->count();

            // Ambil 5 riwayat pendaftaran terbaru pasien
            $riwayatPendaftaran = Pendaftaran::where('pasien_id', $pasien->id)
                ->with(['dokter', 'antrean'])
                ->latest()
                ->take(5)
                ->get();
        }

        // Kirim data ke view dashboard pasien
        return view('pasien.dashboard', compact('pasien', 'antreanAktif', 'totalKunjungan', 'riwayatPendaftaran'));
    }

    // 2. Menampilkan riwayat pendaftaran pasien
    public function pendaftaranIndex()
    {
        // Cari data pasien yang login
        $pasien = Pasien::where('user_id', Auth::id())->first();
        $data = collect();

        // Jika pasien ada, ambil data pendaftaran beserta relasinya (10 data per halaman)
        if ($pasien) {
            $data = Pendaftaran::where('pasien_id', $pasien->id)
                ->with(['dokter', 'jadwal', 'antrean'])
                ->latest()
                ->paginate(10);
        }

        // Kirim data ke view index pendaftaran pasien
        return view('pasien.pendaftaran.index', compact('data'));
    }

    // 3. Menampilkan form pendaftaran berobat baru
    public function createPendaftaran()
    {
        // Ambil daftar dokter beserta relasi user-nya
        $dokters = Dokter::with('user')->get();
        // Ambil jadwal dokter beserta relasi dokter-nya
        $jadwals = JadwalDokter::with('dokter')->get();

        // Kirim data dokter dan jadwal ke view form create
        return view('pasien.pendaftaran.create', compact('dokters', 'jadwals'));
    }

    // 4. Memproses dan menyimpan pendaftaran mandiri pasien
    public function storePendaftaran(Request $request)
    {
        // Validasi input form pendaftaran
        $request->validate([
            'dokter_id'     => 'required|exists:dokters,id',
            'jadwal_id'     => 'required|exists:jadwal_dokters,id',
            'tgl_kunjungan' => 'required|date',
            'keluhan_awal'  => 'required|string',
        ]);

        // Cari profil pasien dari user login
        $pasien = Pasien::where('user_id', Auth::id())->first();

        // Jika profil pasien belum ada, buatkan otomatis
        if (!$pasien) {
            $user = Auth::user();
            $countPasien = Pasien::count() + 1;

            $pasien = Pasien::create([
                'user_id'       => $user->id,
                'no_rm'         => 'RM' . sprintf('%05d', $countPasien),
                'nik'           => '35' . date('Ymd') . sprintf('%04d', $user->id),
                'nama'          => $user->name,
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2000-01-01',
                'alamat'        => '-',
                'no_telepon'    => '080000000000',
            ]);
        }

        // Mulai transaksi database
        DB::beginTransaction();
        try {
            // Generate nomor pendaftaran unik (contoh: REG-20260330-001)
            $today = date('Ymd');
            $count = Pendaftaran::whereDate('created_at', today())->count() + 1;
            $noPendaftaran = 'REG-' . $today . '-' . sprintf('%03d', $count);

            // Simpan data pendaftaran
            $pendaftaran = Pendaftaran::create([
                'no_pendaftaran' => $noPendaftaran,
                'pasien_id'      => $pasien->id,
                'dokter_id'      => $request->dokter_id,
                'jadwal_id'      => $request->jadwal_id,
                'tgl_kunjungan'  => $request->tgl_kunjungan,
                'keluhan_awal'   => $request->keluhan_awal,
            ]);

            // Hitung nomor urut antrean dokter untuk hari ini
            $nomorAntrean = Antrean::whereDate('created_at', today())
                ->where('dokter_id', $request->dokter_id)
                ->count() + 1;

            // Simpan data antrean baru
            Antrean::create([
                'pendaftaran_id' => $pendaftaran->id,
                'pasien_id'      => $pasien->id,
                'dokter_id'      => $request->dokter_id,
                'no_antrean'     => $nomorAntrean,
                'tanggal_berobat'=> $request->tgl_kunjungan,
                'keluhan'        => $request->keluhan_awal,
                'status'         => 'Menunggu',
            ]);

            // Simpan permanen transaksi ke database
            DB::commit();

            // Redirect ke dashboard pasien dengan pesan sukses
            return redirect()->route('pasien.dashboard')
                ->with('success', 'Pendaftaran mandiri berhasil dikirim!');

        } catch (\Exception $e) {
            // Batalkan semua query jika terjadi kesalahan
            DB::rollBack();
            // Kembali ke halaman sebelumnya dengan input dan pesan error
            return back()->withInput()->with('error', 'Gagal melakukan pendaftaran: ' . $e->getMessage());
        }
    }

    // 5. Menampilkan halaman status antrean pasien
    public function antreanIndex()
    {
        // Cari data pasien login
        $pasien = Pasien::where('user_id', Auth::id())->first();
        $data = collect();

        // Jika pasien ada, ambil data pendaftaran dan antrean pasien
        if ($pasien) {
            $data = Pendaftaran::where('pasien_id', $pasien->id)
                ->with(['dokter', 'antrean'])
                ->latest()
                ->paginate(10);
        }

        // Kirim data ke view antrean pasien
        return view('pasien.antrean.index', compact('data'));
    }

    // 6. Menampilkan halaman riwayat rekam medis pasien
    public function rekamMedisIndex()
    {
        // Cari data pasien login
        $pasien = Pasien::where('user_id', Auth::id())->first();

        // Query data rekam medis pasien (jika pasien tidak ditemukan, hasilkan query kosong)
        $data = RekamMedis::query()
            ->when($pasien, fn ($query) => $query->where('pasien_id', $pasien->id))
            ->when(!$pasien, fn ($query) => $query->whereRaw('1 = 0'))
            ->with('dokter')
            ->latest()
            ->paginate(10);

        // Kirim data ke view rekam medis pasien
        return view('pasien.rekam-medis.index', compact('data'));
    }

    // 7. Menampilkan halaman riwayat pembayaran pasien
    public function pembayaranIndex()
    {
        // Cari data pasien login
        $pasien = Pasien::where('user_id', Auth::id())->first();

        // Query data pembayaran pasien (jika pasien tidak ditemukan, hasilkan query kosong)
        $data = Pembayaran::query()
            ->when($pasien, fn ($query) => $query->where('pasien_id', $pasien->id))
            ->when(!$pasien, fn ($query) => $query->whereRaw('1 = 0'))
            ->latest()
            ->paginate(10);

        // Kirim data ke view pembayaran pasien
        return view('pasien.pembayaran.index', compact('data'));
    }
}