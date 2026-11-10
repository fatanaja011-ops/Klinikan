<?php

// Namespace controller
namespace App\Http\Controllers;

// Import model dan class yang dibutuhkan
use App\Models\Dokter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DokterController extends Controller
{
    // Helper untuk membuat kode dokter otomatis
    private function generateKodeDokter()
    {
        // Ambil data dokter terakhir dari database
        $latest = Dokter::orderBy('id', 'desc')->first();
        // Ambil angka terakhir dan tambahkan 1 (default: 1)
        $number = $latest ? ((int) preg_replace('/[^0-9]/', '', $latest->kode_dokter)) + 1 : 1;
        // Format kode baru (contoh: DOC001)
        $kode = 'DOC' . str_pad($number, 3, '0', STR_PAD_LEFT);

        // Pastikan kode unik, jika sudah ada maka naikkan angkanya
        while (Dokter::where('kode_dokter', $kode)->exists()) {
            $number++;
            $kode = 'DOC' . str_pad($number, 3, '0', STR_PAD_LEFT);
        }

        // Kembalikan kode dokter unik
        return $kode;
    }

    // Tampilkan daftar dokter dengan paginasi
    public function index()
    {
        // Ambil data dokter beserta akun user, urutkan dari yang terbaru
        $dokters = Dokter::with('user')->latest()->paginate(10);
        // Kirim data ke view index
        return view('admin.dokter.index', compact('dokters'));
    }

    // Tampilkan form tambah dokter
    public function create()
    {
        // Generate kode dokter otomatis
        $kode_dokter = $this->generateKodeDokter();
        // Kirim kode ke view create
        return view('admin.dokter.create', compact('kode_dokter'));
    }

    // Simpan data dokter dan akun baru ke database
    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'nama_dokter' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'spesialisasi' => 'required|string',
            'no_telepon' => 'required|string',
            'alamat' => 'required|string',
        ]);

        // Buat akun user untuk login dokter
        $user = User::create([
            'name' => $request->nama_dokter,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dokter',
        ]);

        // Buat data profil dokter
        Dokter::create([
            'user_id' => $user->id,
            'kode_dokter' => $this->generateKodeDokter(),
            'nama_dokter' => $request->nama_dokter,
            'spesialisasi' => $request->spesialisasi,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'status' => 'Aktif',
        ]);

        // Redirect ke index dengan pesan sukses
        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter dan akun login berhasil dibuat!');
    }

    // Tampilkan form edit dokter
    public function edit(Dokter $dokter)
    {
        // Kirim data dokter ke view edit
        return view('admin.dokter.edit', compact('dokter'));
    }

    // Update data dokter di database
    public function update(Request $request, Dokter $dokter)
    {
        // Validasi input form
        $request->validate([
            'nama_dokter' => 'required|string|max:255',
            'spesialisasi' => 'required|string',
            'no_telepon' => 'required|string',
            'alamat' => 'required|string',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        // Update data profil dokter
        $dokter->update([
            'nama_dokter' => $request->nama_dokter,
            'spesialisasi' => $request->spesialisasi,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'status' => $request->status,
        ]);

        // Update nama pada akun user terkait
        $dokter->user->update(['name' => $request->nama_dokter]);

        // Redirect ke index dengan pesan sukses
        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil diperbarui!');
    }

    // Hapus data dokter dan akunnya
    public function destroy(Dokter $dokter)
    {
        // Hapus akun user terkait
        $dokter->user()->delete();
        // Hapus data profil dokter
        $dokter->delete();

        // Redirect ke index dengan pesan sukses
        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter beserta akunnya berhasil dihapus!');
    }
}