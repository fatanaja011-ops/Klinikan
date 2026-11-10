<?php

// Namespace controller
namespace App\Http\Controllers;

// Import controller dasar dan model yang dibutuhkan
use App\Http\Controllers\Controller;
use App\Models\JadwalDokter;
use App\Models\Dokter;
use Illuminate\Http\Request;

class JadwalDokterController extends Controller
{
    // Menampilkan data jadwal dokter dengan filter
    public function jadwal_data(Request $request)
    {
        // Query awal model JadwalDokter beserta relasi dokter
        $query = JadwalDokter::with('dokter');

        // Filter berdasarkan dokter jika dipilih
        if ($request->has('dokter_id') && $request->dokter_id != '') {
            $query->where('dokter_id', $request->dokter_id);
        }

        // Filter berdasarkan hari jika dipilih
        if ($request->has('hari') && $request->hari != '') {
            $query->where('hari', $request->hari);
        }

        // Urutkan jadwal, tambahkan paginasi 10 data
        $jadwal = $query->orderBy('hari')->orderBy('jam_mulai')->paginate(10);
        // Ambil semua data dokter untuk opsi filter/form
        $dokters = Dokter::all();

        // Kirim data jadwal dan dokter ke view index
        return view('admin.jadwal.index', compact('jadwal', 'dokters'));
    }

    // Menyimpan data jadwal dokter baru
    public function jadwal_add(Request $request)
    {
        // Validasi input form
        $request->validate([
            'dokter_id'   => 'required|exists:dokters,id',
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruang'       => 'required|string|max:50',
            'kuota'       => 'required|integer|min:1',
        ]);

        // Cek apakah jam praktik dokter bertabrakan dengan jadwal lain
        $bentrokDokter = JadwalDokter::where('dokter_id', $request->dokter_id)
            ->where('hari', $request->hari)
            ->where(function ($q) use ($request) {
                $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhere(function ($sub) use ($request) {
                      $sub->where('jam_mulai', '<=', $request->jam_mulai)
                          ->where('jam_selesai', '>=', $request->jam_selesai);
                  });
            })->exists();

        // Jika jadwal dokter bentrok, kembali dengan pesan error
        if ($bentrokDokter) {
            return back()->withInput()->with('error', 'Gagal! Dokter sudah memiliki jadwal lain pada hari dan jam tersebut.');
        }

        // Cek apakah pemakaian ruangan bertabrakan dengan jadwal dokter lain
        $bentrokRuang = JadwalDokter::where('ruang', $request->ruang)
            ->where('hari', $request->hari)
            ->where(function ($q) use ($request) {
                $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhere(function ($sub) use ($request) {
                      $sub->where('jam_mulai', '<=', $request->jam_mulai)
                          ->where('jam_selesai', '>=', $request->jam_selesai);
                  });
            })->exists();

        // Jika ruangan bentrok, kembali dengan pesan error
        if ($bentrokRuang) {
            return back()->withInput()->with('error', 'Gagal! Ruangan tersebut sudah digunakan oleh dokter lain pada jam yang sama.');
        }

        // Simpan data jadwal baru ke database
        JadwalDokter::create($request->all());

        // Redirect ke index dengan pesan sukses
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal dokter berhasil ditambahkan.');
    }

    // Memperbarui data jadwal dokter
    public function jadwal_edit(Request $request, $id)
    {
        // Validasi input form edit
        $request->validate([
            'dokter_id'   => 'required|exists:dokters,id',
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'ruang'       => 'required|string|max:50',
            'kuota'       => 'required|integer|min:1',
        ]);

        // Cari data jadwal berdasarkan ID
        $jadwal = JadwalDokter::findOrFail($id);

        // Cek bentrok jadwal dokter (mengabaikan ID jadwal yang sedang di-edit)
        $bentrokDokter = JadwalDokter::where('dokter_id', $request->dokter_id)
            ->where('hari', $request->hari)
            ->where('id', '!=', $id)
            ->where(function ($q) use ($request) {
                $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai]);
            })->exists();

        // Jika bentrok, kembali dengan pesan error
        if ($bentrokDokter) {
            return back()->withInput()->with('error', 'Gagal! Jadwal dokter bertabrakan dengan jadwal lainnya.');
        }

        // Update data jadwal di database
        $jadwal->update($request->all());

        // Redirect ke index dengan pesan sukses
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal dokter berhasil diperbarui.');
    }

    // Menghapus data jadwal dokter
    public function jadwal_hapus($id)
    {
        // Cari data jadwal berdasarkan ID
        $jadwal = JadwalDokter::findOrFail($id);
        // Hapus data jadwal dari database
        $jadwal->delete();

        // Redirect ke index dengan pesan sukses
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal dokter berhasil dihapus.');
    }
}