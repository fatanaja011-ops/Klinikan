<?php

// Namespace untuk controller
namespace App\Http\Controllers;

// Import model dan class yang dibutuhkan
use App\Models\Dokter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Menampilkan daftar data pengguna (users)
    public function index()
    {
        // Ambil data user terbaru dengan paginasi 10 item per halaman
        $users = User::latest()->paginate(10);

        // Tampilkan view indeks manajemen user
        return view('admin.user.index', compact('users'));
    }

    // Menampilkan halaman form tambah pengguna baru
    public function create()
    {
        // Tampilkan view form create user
        return view('admin.user.create');
    }

    // Menyimpan data pengguna baru ke database
    public function store(Request $request)
    {
        // Validasi input data dari form pendaftaran user
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'dokter', 'pasien'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Enkripsi kata sandi menggunakan Hash
        $validated['password'] = Hash::make($validated['password']);
        // Simpan data user baru ke database
        $user = User::create($validated);

        // Jika role yang dipilih adalah 'dokter', buatkan profil Dokter secara otomatis
        if ($user->role === 'dokter') {
            Dokter::create([
                'user_id' => $user->id,
                'kode_dokter' => $this->generateKodeDokter(),
                'nama_dokter' => $user->name,
                'spesialisasi' => 'Umum',
                'no_telepon' => '-',
                'alamat' => '-',
                'status' => 'aktif',
            ]);
        }

        // Redirect ke indeks user dengan pesan sukses
        return redirect()->route('admin.user.index')->with('success', 'Data pengguna berhasil ditambahkan.');
    }

    // Menampilkan halaman form edit pengguna
    public function edit(User $user)
    {
        // Tampilkan view form edit user dengan data user terkait
        return view('admin.user.edit', compact('user'));
    }

    // Memperbarui data pengguna di database
    public function update(Request $request, User $user)
    {
        // Validasi input data pembaharuan user
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'dokter', 'pasien'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Cek jika field password diisi, lakukan enkripsi; jika kosong, hapus dari array pembaharuan
        if (filled($validated['password'] ?? null)) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Perbarui data user di database
        $user->update($validated);

        // Jika role diubah menjadi 'dokter' dan belum punya profil dokter, buatkan profilnya
        if ($user->role === 'dokter' && !$user->dokter) {
            Dokter::create([
                'user_id' => $user->id,
                'kode_dokter' => $this->generateKodeDokter(),
                'nama_dokter' => $user->name,
                'spesialisasi' => 'Umum',
                'no_telepon' => '-',
                'alamat' => '-',
                'status' => 'aktif',
            ]);
        }

        // Redirect ke indeks user dengan pesan sukses
        return redirect()->route('admin.user.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    // Fungsi privat untuk memformat kode unik dokter (contoh: DOC001)
    private function generateKodeDokter(): string
    {
        // Ambil data dokter terakhir berdasarkan ID
        $latest = Dokter::latest('id')->first();
        // Ambil angka dari kode terakhir lalu tambahkan 1, jika belum ada set ke 1
        $number = $latest ? ((int) preg_replace('/\D/', '', $latest->kode_dokter)) + 1 : 1;
        // Format kode dengan awalan DOC dan 3 digit angka
        $kode = 'DOC' . str_pad($number, 3, '0', STR_PAD_LEFT);

        // Pastikan kode unik jika ternyata sudah terpakai di database
        while (Dokter::where('kode_dokter', $kode)->exists()) {
            $number++;
            $kode = 'DOC' . str_pad($number, 3, '0', STR_PAD_LEFT);
        }

        // Kembalikan kode dokter yang siap digunakan
        return $kode;
    }

    // Menghapus data pengguna dari database
    public function destroy(User $user)
    {
        // Mencegah pengguna menghapus akunnya sendiri yang sedang digunakan untuk login
        if ($user->is(auth()->user())) {
            return back()->with('error', 'Akun yang sedang digunakan tidak dapat dihapus.');
        }

        // Hapus record user dari database
        $user->delete();

        // Redirect ke indeks user dengan pesan sukses
        return redirect()->route('admin.user.index')->with('success', 'Data pengguna berhasil dihapus.');
    }
}