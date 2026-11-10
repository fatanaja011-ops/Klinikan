<?php

// Namespace controller
namespace App\Http\Controllers;

// Import model dan class yang dibutuhkan
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PasienController extends Controller
{
    // Helper untuk membuat nomor Rekam Medis (RM) otomatis
    private function generateNoRM()
    {
        // Ambil data pasien terakhir berdasarkan ID terbesar
        $latest = Pasien::orderBy('id', 'desc')->first();
        // Ambil angka dari no_rm terakhir lalu tambah 1 (default: 1)
        $number = $latest ? ((int) preg_replace('/[^0-9]/', '', $latest->no_rm)) + 1 : 1;
        // Format nomor RM baru (contoh: RM0001)
        $kode = 'RM' . str_pad($number, 4, '0', STR_PAD_LEFT);

        // Pastikan nomor RM belum dipakai di database, jika ada naikkan angkanya
        while (Pasien::where('no_rm', $kode)->exists()) {
            $number++;
            $kode = 'RM' . str_pad($number, 4, '0', STR_PAD_LEFT);
        }

        // Kembalikan nomor RM yang siap digunakan
        return $kode;
    }

    // Menampilkan daftar data pasien dengan paginasi
    public function index()
    {
        // Ambil data pasien urut dari yang terbaru, 10 data per halaman
        $pasiens = Pasien::latest()->paginate(10);
        // Kirim data ke view index
        return view('admin.pasien.index', compact('pasiens'));
    }

    // Menampilkan form tambah data pasien
    public function create()
    {
        // Generate nomor RM otomatis
        $no_rm = $this->generateNoRM();
        // Kirim nomor RM ke view form create
        return view('admin.pasien.create', compact('no_rm'));
    }

    // Menyimpan data pasien baru ke database
    public function store(Request $request)
    {
        // Validasi input data pasien
        $request->validate([
            'nik' => 'required|numeric|digits:16|unique:pasiens,nik',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'no_telepon' => 'required|string',
            'alamat' => 'required|string',
        ]);

        // Simpan data pasien ke database
        Pasien::create([
            'no_rm' => $this->generateNoRM(),
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
        ]);

        // Redirect ke index dengan pesan sukses
        return redirect()->route('admin.pasien.index')->with('success', 'Data pasien berhasil ditambahkan!');
    }

    // Menampilkan form edit data pasien
    public function edit(Pasien $pasien)
    {
        // Kirim data pasien ke view form edit
        return view('admin.pasien.edit', compact('pasien'));
    }

    // Memperbarui data pasien di database
    public function update(Request $request, Pasien $pasien)
    {
        // Validasi input form edit (abaikan unik NIK untuk pasien ini sendiri)
        $request->validate([
            'nik' => 'required|numeric|digits:16|unique:pasiens,nik,' . $pasien->id,
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'no_telepon' => 'required|string',
            'alamat' => 'required|string',
        ]);

        // Update seluruh data pasien dari request
        $pasien->update($request->all());

        // Redirect ke index dengan pesan sukses
        return redirect()->route('admin.pasien.index')->with('success', 'Data pasien berhasil diperbarui!');
    }

    // Menghapus data pasien beserta akun user terkait
    public function destroy(Pasien $pasien)
    {
        // Mulai transaksi database
        DB::beginTransaction();
        try {
            // Ambil relasi akun user yang terhubung dengan pasien
            $user = $pasien->user;

            // Hapus data pasien dari database
            $pasien->delete();

            // Jika akun user ada, hapus akun user terkait
            if ($user) {
                $user->delete();
            }

            // Simpan perubahan permanen ke database
            DB::commit();
            // Redirect ke index dengan pesan sukses
            return redirect()->route('admin.pasien.index')->with('success', 'Data pasien beserta akun user berhasil dihapus!');
        } catch (\Exception $e) {
            // Batalkan semua perubahan jika terjadi error
            DB::rollBack();
            // Redirect ke index dengan pesan error
            return redirect()->route('admin.pasien.index')->with('error', 'Gagal menghapus data pasien: ' . $e->getMessage());
        }
    }
}