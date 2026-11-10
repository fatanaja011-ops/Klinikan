<?php

// Namespace untuk controller
namespace App\Http\Controllers;

// Import model dan class yang dibutuhkan
use App\Models\Tindakan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TindakanController extends Controller
{
    // Fungsi privat untuk membuat kode tindakan otomatis (contoh: TIN001)
    private function generateKode(): string
    {
        // Ambil data tindakan terakhir berdasarkan ID
        $latest = Tindakan::latest('id')->first();
        // Ambil angka dari kode terakhir lalu tambahkan 1, jika belum ada set ke 1
        $number = $latest ? ((int) preg_replace('/\D/', '', $latest->kode_tindakan)) + 1 : 1;
        // Format kode dengan awalan TIN dan 3 digit angka
        $kode = 'TIN' . str_pad($number, 3, '0', STR_PAD_LEFT);

        // Pastikan kode unik jika ternyata sudah terpakai di database
        while (Tindakan::where('kode_tindakan', $kode)->exists()) {
            $number++;
            $kode = 'TIN' . str_pad($number, 3, '0', STR_PAD_LEFT);
        }

        // Kembalikan kode tindakan yang siap digunakan
        return $kode;
    }

    // Menampilkan daftar data tindakan
    public function index()
    {
        // Ambil data tindakan terbaru dengan paginasi 10 item
        $tindakans = Tindakan::latest()->paginate(10);

        // Tampilkan view indeks tindakan
        return view('admin.tindakan.index', compact('tindakans'));
    }

    // Menampilkan halaman form tambah tindakan
    public function create()
    {
        // Tampilkan view form create tindakan
        return view('admin.tindakan.create');
    }

    // Menyimpan data tindakan baru ke database
    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'nama_tindakan' => ['required', 'string', 'max:255', 'unique:tindakans,nama_tindakan'],
            'tarif' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string'],
        ]);

        // Simpan data tindakan beserta kode tindakan otomatis
        Tindakan::create([...$validated, 'kode_tindakan' => $this->generateKode()]);

        // Redirect ke indeks tindakan dengan pesan sukses
        return redirect()->route('admin.tindakan.index')->with('success', 'Data tindakan berhasil ditambahkan.');
    }

    // Menampilkan halaman form edit tindakan
    public function edit(Tindakan $tindakan)
    {
        // Tampilkan view form edit tindakan
        return view('admin.tindakan.edit', compact('tindakan'));
    }

    // Memperbarui data tindakan di database
    public function update(Request $request, Tindakan $tindakan)
    {
        // Validasi input pembaruan data (mengabaikan keunikan nama untuk ID sendiri)
        $validated = $request->validate([
            'nama_tindakan' => ['required', 'string', 'max:255', Rule::unique('tindakans', 'nama_tindakan')->ignore($tindakan->id)],
            'tarif' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string'],
        ]);

        // Perbarui record tindakan di database
        $tindakan->update($validated);

        // Redirect ke indeks tindakan dengan pesan sukses
        return redirect()->route('admin.tindakan.index')->with('success', 'Data tindakan berhasil diperbarui.');
    }

    // Menghapus data tindakan dari database
    public function destroy(Tindakan $tindakan)
    {
        // Hapus record tindakan
        $tindakan->delete();

        // Redirect ke indeks tindakan dengan pesan sukses
        return redirect()->route('admin.tindakan.index')->with('success', 'Data tindakan berhasil dihapus.');
    }
}