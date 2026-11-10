<?php

namespace Tests\Feature;

use App\Models\Antrean;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\RekamMedis;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClinicalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_resep_reduces_stock_and_payment_calculates_total(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $pasien = Pasien::factory()->create([
            'jenis_kelamin' => 'L',
        ]);
        $dokter = Dokter::factory()->create();

        $jadwal = JadwalDokter::create([
            'dokter_id' => $dokter->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '12:00:00',
            'ruang' => 'Poli Umum',
            'kuota' => 20,
        ]);

        $pendaftaran = Pendaftaran::create([
            'no_pendaftaran' => 'P-001',
            'pasien_id' => $pasien->id,
            'dokter_id' => $dokter->id,
            'jadwal_id' => $jadwal->id,
            'tgl_kunjungan' => now()->toDateString(),
            'keluhan_awal' => 'Demam',
        ]);

        $obat = Obat::create([
            'kode_obat' => 'OBT-001',
            'nama_obat' => 'Paracetamol',
            'jenis_obat' => 'Tablet',
            'satuan' => 'box',
            'harga' => 15000,
            'stok' => 10,
            'tanggal_kedaluwarsa' => now()->addYear(),
            'status' => 'Tersedia',
        ]);

        $antrean = Antrean::create([
            'pendaftaran_id' => $pendaftaran->id,
            'no_antrean' => 'A-001',
            'pasien_id' => $pasien->id,
            'dokter_id' => $dokter->id,
            'tanggal_berobat' => now()->toDateString(),
            'keluhan' => 'Demam',
            'status' => 'Diperiksa',
        ]);

        $pemeriksaan = Pemeriksaan::create([
            'antrean_id' => $antrean->id,
            'pasien_id' => $pasien->id,
            'dokter_id' => $dokter->id,
            'keluhan' => 'Demam',
            'pemeriksaan_fisik' => 'Normal',
            'diagnosis' => 'Demam ringan',
            'tindakan' => 'Observasi',
            'catatan' => 'Istirahat',
        ]);

        $rekamMedis = RekamMedis::create([
            'antrean_id' => $antrean->id,
            'pasien_id' => $pasien->id,
            'dokter_id' => $dokter->id,
            'keluhan' => 'Demam',
            'diagnosa' => 'Demam ringan',
            'tindakan' => 'Observasi',
            'tanggal_periksa' => now()->toDateString(),
        ]);

        $rekamMedis->detailResep()->create([
            'obat_id' => $obat->id,
            'jumlah' => 2,
            'aturan_pakai' => '3x1 setelah makan',
        ]);

        $obat->refresh();
        $this->assertSame(8, (int) $obat->stok);

        $this->actingAs($user)
            ->post('/admin/pembayaran', [
                'pemeriksaan_id' => $pemeriksaan->id,
                'nominal_bayar' => 80000,
            ]);

        $this->assertDatabaseHas('pembayarans', [
            'pemeriksaan_id' => $pemeriksaan->id,
            'pasien_id' => $pasien->id,
            'total_bayar' => '80000.00',
            'status_pembayaran' => 'Lunas',
        ]);

        $this->assertDatabaseHas('detail_pembayarans', [
            'jenis_pelayanan' => 'Obat',
            'nama_pelayanan' => 'Paracetamol',
            'jumlah' => 2,
        ]);

        $this->assertDatabaseHas('detail_pembayarans', [
            'jenis_pelayanan' => 'Pemeriksaan',
            'nama_pelayanan' => 'Jasa Pemeriksaan Dokter',
            'jumlah' => 1,
        ]);
    }

    public function test_dokter_can_view_rekam_medis_with_medical_record_date(): void
    {
        $user = User::factory()->create(['role' => 'dokter']);
        $dokter = Dokter::factory()->create(['user_id' => $user->id]);
        $pasien = Pasien::factory()->create(['nama' => 'Budi Santoso']);

        RekamMedis::create([
            'pasien_id' => $pasien->id,
            'dokter_id' => $dokter->id,
            'keluhan' => 'Demam',
            'diagnosa' => 'Demam ringan',
            'tindakan' => 'Observasi',
            'tanggal_periksa' => '2026-09-25',
        ]);

        $response = $this->actingAs($user)
            ->get(route('dokter.rekam-medis.index'));

        $response
            ->assertOk()
            ->assertViewIs('dokter.rekam-medis.index')
            ->assertSee('Budi Santoso')
            ->assertSee('25/09/2026');
    }
}
