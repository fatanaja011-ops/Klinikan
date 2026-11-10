<?php

// Namespace controller
namespace App\Http\Controllers;

// Import model dan class yang dibutuhkan
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    // Helper untuk membuat kode obat otomatis secara unik
    private function generateKodeObat()
    {
        // Ambil data obat terakhir berdasarkan ID terbesar
        $latest = Obat::orderBy('id', 'desc')->first();
        // Ambil angka dari kode obat terakhir lalu tambah 1 (default: 1)
        $number = $latest ? ((int) preg_replace('/[^0-9]/', '', $latest->kode_obat)) + 1 : 1;
        // Format kode baru (contoh: OBT001)
        $kode_obat = 'OBT' . str_pad($number, 3, '0', STR_PAD_LEFT);

        // Pastikan kode belum dipakai di database, jika ada naikkan angkanya
        while (Obat::where('kode_obat', $kode_obat)->exists()) {
            $number++;
            $kode_obat = 'OBT' . str_pad($number, 3, '0', STR_PAD_LEFT);
        }

        // Kembalikan kode obat yang siap digunakan
        return $kode_obat;
    }

    // Menampilkan daftar data obat dengan paginasi
    public function index()
    {
        // Ambil data obat urut dari yang terbaru, 10 data per halaman
        $obats = Obat::latest()->paginate(10);
        // Kirim data ke view index
        return view('admin.obat.index', compact('obats'));
    }

    // Menampilkan form tambah obat
    public function create()
    {
        // Generate kode obat otomatis
        $kode_obat = $this->generateKodeObat();
        // Kirim kode obat ke view form create
        return view('admin.obat.create', compact('kode_obat'));
    }

    // Menyimpan data obat baru ke database
    public function store(Request $request)
    {
        // Generate kode obat unik baru
        $kode_obat = $this->generateKodeObat();

        // Validasi input form
        $request->validate([
            'nama_obat' => 'required',
            'jenis_obat' => 'required',
            'satuan' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'tanggal_kedaluwarsa' => 'required|date',
        ]);

        // Simpan data obat ke database
        Obat::create([
            'kode_obat' => $kode_obat,
            'nama_obat' => $request->nama_obat,
            'jenis_obat' => $request->jenis_obat,
            'satuan' => $request->satuan,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'tanggal_kedaluwarsa' => $request->tanggal_kedaluwarsa,
            'status' => $request->stok > 0 ? 'Tersedia' : 'Habis',
        ]);

        // Redirect ke index dengan pesan sukses
        return redirect()->route('admin.obat.index')->with('success', 'Data obat berhasil ditambahkan!');
    }

    // Menampilkan form edit obat
    public function edit(Obat $obat)
    {
        // Kirim data obat ke view form edit
        return view('admin.obat.edit', compact('obat'));
    }

    // Memperbarui data obat di database
    public function update(Request $request, Obat $obat)
    {
        // Validasi input form edit
        $request->validate([
            'nama_obat' => 'required',
            'jenis_obat' => 'required',
            'satuan' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'tanggal_kedaluwarsa' => 'required|date',
        ]);

        // Update data obat
        $obat->update([
            'nama_obat' => $request->nama_obat,
            'jenis_obat' => $request->jenis_obat,
            'satuan' => $request->satuan,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'tanggal_kedaluwarsa' => $request->tanggal_kedaluwarsa,
            'status' => $request->stok > 0 ? 'Tersedia' : 'Habis',
        ]);

        // Redirect ke index dengan pesan sukses
        return redirect()->route('admin.obat.index')->with('success', 'Data obat berhasil diperbarui!');
    }

    // Menghapus data obat
    public function destroy(Obat $obat)
    {
        // Hapus data obat dari database
        $obat->delete();
        // Redirect ke index dengan pesan sukses
        return redirect()->route('admin.obat.index')->with('success', 'Data obat berhasil dihapus!');
    }
}