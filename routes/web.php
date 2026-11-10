<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\AntreanController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\JadwalDokterController;
use App\Http\Controllers\TindakanController;
use App\Http\Controllers\PasienPortalController;
use App\Http\Controllers\DokterPortalController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route bawaan Breeze untuk Profil Pengguna
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Group Hak Akses - Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'adminIndex'])->name('admin.dashboard');

    // CRUD Data Obat
    Route::resource('admin/obat', ObatController::class)->names('admin.obat');

    // Resource Data Tindakan
    Route::resource('admin/tindakan', TindakanController::class)
        ->except(['show'])
        ->names('admin.tindakan');

    // Resource Data Dokter
    Route::resource('admin/dokter', DokterController::class)->names('admin.dokter');

    // Resource Data Pasien
    Route::resource('admin/pasien', PasienController::class)->names('admin.pasien');

    // Resource Data Pengguna
    Route::resource('admin/user', UserController::class)
        ->except(['show'])
        ->names('admin.user');

    // Resource Data Pendaftaran
    Route::get('admin/pendaftaran', [PendaftaranController::class, 'pendaftaran_data'])->name('admin.pendaftaran.index');
    Route::get('admin/pendaftaran/tambah', [PendaftaranController::class, 'pendaftaran_tambah'])->name('admin.pendaftaran.create');
    Route::post('admin/pendaftaran/simpan', [PendaftaranController::class, 'pendaftaran_add'])->name('admin.pendaftaran.store');
    Route::put('admin/pendaftaran/{id}', [PendaftaranController::class, 'pendaftaran_edit'])->name('admin.pendaftaran.update');
    Route::delete('admin/pendaftaran/{id}', [PendaftaranController::class, 'pendaftaran_hapus'])->name('admin.pendaftaran.destroy');

    // Resource Data Antrean
    Route::resource('admin/antrean', AntreanController::class)->names('admin.antrean');
    Route::patch('admin/antrean/{antrean}/update-status', [AntreanController::class, 'updateStatus'])->name('admin.antrean.update-status');

    // Resource Data Pemeriksaan
    Route::get('/pemeriksaan', [PemeriksaanController::class, 'pemeriksaan_data'])->name('pemeriksaan.data');
    Route::get('/pemeriksaan/create', [PemeriksaanController::class, 'create'])->name('pemeriksaan.create');
    Route::post('/pemeriksaan', [PemeriksaanController::class, 'pemeriksaan_add'])->name('pemeriksaan.add');
    Route::get('/pemeriksaan/{id}/edit', [PemeriksaanController::class, 'edit'])->name('pemeriksaan.edit_form');
    Route::put('/pemeriksaan/{id}', [PemeriksaanController::class, 'pemeriksaan_edit'])->name('pemeriksaan.edit');
    Route::delete('/pemeriksaan/{id}', [PemeriksaanController::class, 'pemeriksaan_hapus'])->name('pemeriksaan.hapus');

    // Resource Data Rekam Medis
    Route::resource('admin/rekam-medis', RekamMedisController::class)->names('admin.rekam-medis');

    // Resource Data Pembayaran
    Route::get('admin/pembayaran', [PembayaranController::class, 'pembayaran_data'])->name('admin.pembayaran.index');
    Route::get('admin/pembayaran/create', [PembayaranController::class, 'pembayaran_add'])->name('admin.pembayaran.create');
    Route::post('admin/pembayaran', [PembayaranController::class, 'pembayaran_store'])->name('admin.pembayaran.store');
    Route::post('admin/pembayaran/snap-token', [PembayaranController::class, 'getSnapToken'])->name('admin.pembayaran.snap_token');
    Route::post('admin/pembayaran/{id}/proses', [PembayaranController::class, 'pembayaran_proses'])->name('admin.pembayaran.proses');
    Route::get('admin/pembayaran/{id}/cetak', [PembayaranController::class, 'pembayaran_cetak'])->name('admin.pembayaran.cetak');

    // Resource Data Laporan
    Route::get('/admin/laporan', [LaporanController::class, 'laporan_data'])->name('admin.laporan.index');
    Route::get('/admin/laporan/cetak', [LaporanController::class, 'laporan_cetak'])->name('admin.laporan.cetak');

    // Resource Data Jadwal Dokter
    Route::get('admin/jadwal', [JadwalDokterController::class, 'jadwal_data'])->name('admin.jadwal.index');
    Route::post('admin/jadwal', [JadwalDokterController::class, 'jadwal_add'])->name('admin.jadwal.store');
    Route::put('admin/jadwal/{id}', [JadwalDokterController::class, 'jadwal_edit'])->name('admin.jadwal.update');
    Route::delete('admin/jadwal/{id}', [JadwalDokterController::class, 'jadwal_hapus'])->name('admin.jadwal.destroy');
});

// Group Hak Akses - Dokter
Route::middleware(['auth', 'role:dokter'])->group(function () {
    Route::get('/dokter/dashboard', [DokterPortalController::class, 'dashboard'])->name('dokter.dashboard');
    Route::get('/dokter/pemeriksaan', [DokterPortalController::class, 'pemeriksaan'])->name('dokter.pemeriksaan.index');
    Route::get('/dokter/pemeriksaan/create', [DokterPortalController::class, 'createPemeriksaan'])->name('dokter.pemeriksaan.create');
    Route::post('/dokter/pemeriksaan', [DokterPortalController::class, 'storePemeriksaan'])->name('dokter.pemeriksaan.store');
    Route::get('/dokter/rekam-medis', [DokterPortalController::class, 'rekamMedis'])->name('dokter.rekam-medis.index');
    Route::get('/dokter/resep', [DokterPortalController::class, 'resep'])->name('dokter.resep.index');
    Route::get('/dokter/jadwal', [DokterPortalController::class, 'jadwal'])->name('dokter.jadwal.index');
});

// Group Hak Akses - Pasien
Route::middleware(['auth', 'role:pasien'])->group(function () {
    Route::get('/pasien/dashboard', [PasienPortalController::class, 'dashboard'])->name('pasien.dashboard');
    Route::get('/pasien/pendaftaran', [PasienPortalController::class, 'pendaftaranIndex'])->name('pasien.pendaftaran.index');
    Route::get('/pasien/pendaftaran/create', [PasienPortalController::class, 'createPendaftaran'])->name('pasien.pendaftaran.create');
    Route::post('/pasien/pendaftaran', [PasienPortalController::class, 'storePendaftaran'])->name('pasien.pendaftaran.store');
    Route::get('/pasien/antrean', [PasienPortalController::class, 'antreanIndex'])->name('pasien.antrean.index');
    Route::get('/pasien/rekam-medis', [PasienPortalController::class, 'rekamMedisIndex'])->name('pasien.rekam-medis.index');
    Route::get('/pasien/pembayaran', [PasienPortalController::class, 'pembayaranIndex'])->name('pasien.pembayaran.index');
});

require __DIR__.'/auth.php';