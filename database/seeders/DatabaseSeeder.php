<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dokter;
use App\Models\Tindakan;
use App\Models\Obat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Pengguna (3 Role)
        User::create([
            'name' => 'Admin Klinik',
            'email' => 'admin@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $dokterUser = User::create([
            'name' => 'dr. Budi Santoso',
            'email' => 'dokter@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'dokter',
        ]);

        Dokter::create([
            'user_id' => $dokterUser->id,
            'kode_dokter' => 'DOC001',
            'nama_dokter' => 'dr. Budi Santoso',
            'spesialisasi' => 'Umum',
            'no_telepon' => '-',
            'alamat' => '-',
            'status' => 'aktif',
        ]);

        User::create([
            'name' => 'Pasien Umum',
            'email' => 'pasien@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'pasien',
        ]);

        // 2. Data Master Tindakan & Obat Contoh
        Tindakan::create(['kode_tindakan' => 'TIN001', 'nama_tindakan' => 'Pemeriksaan Umum', 'tarif' => 50000]);
        Tindakan::create(['kode_tindakan' => 'TIN002', 'nama_tindakan' => 'Pembersihan Luka', 'tarif' => 75000]);

        Obat::create([
            'kode_obat' => 'OBT001',
            'nama_obat' => 'Paracetamol 500mg',
            'jenis_obat' => 'Tablet',
            'satuan' => 'Strip',
            'harga' => 10000,
            'stok' => 50,
            'tanggal_kedaluwarsa' => '2027-12-31',
            'status' => 'Tersedia',
        ]);
    }
}