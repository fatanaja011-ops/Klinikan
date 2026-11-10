<?php

// Namespace untuk controller
namespace App\Http\Controllers;

// Import model dan class yang dibutuhkan
use App\Models\Pembayaran;
use App\Models\Pemeriksaan;
use App\Models\RekamMedis;
use App\Models\DetailPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    // Inisialisasi konfigurasi Midtrans API
    private function initMidtrans()
    {
        // Cek apakah server key Midtrans sudah diatur di .env
        if (!config('services.midtrans.server_key')) {
            throw new \RuntimeException('MIDTRANS_SERVER_KEY belum diatur di file .env.');
        }

        // Set kredensial dan opsi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // Menampilkan daftar data pembayaran dengan paginasi
    public function pembayaran_data()
    {
        // Ambil data pembayaran beserta relasinya secara urut terbaru
        $pembayaran = Pembayaran::with(['pemeriksaan.pasien', 'pemeriksaan.dokter', 'detailPembayaran'])
            ->latest()
            ->paginate(10);

        // Tampilkan halaman daftar pembayaran
        return view('admin.pembayaran.index', compact('pembayaran'));
    }

    // Menampilkan form pendaftaran/tambah pembayaran baru
    public function pembayaran_add()
    {
        // Ambil data pemeriksaan yang belum memiliki pembayaran
        $pemeriksaans = Pemeriksaan::with(['pasien', 'dokter', 'antrean'])
            ->whereDoesntHave('pembayaran')
            ->latest()
            ->get()
            ->map(function (Pemeriksaan $pemeriksaan) {
                // Hitung rincian tagihan untuk tiap pemeriksaan
                $bill = $this->billFor($pemeriksaan);
                $pemeriksaan->total_tagihan = $bill['total'];
                $pemeriksaan->rincian_tagihan = $bill['items'];

                return $pemeriksaan;
            });

        // Format data rincian pembayaran untuk response frontend
        $paymentData = $pemeriksaans->keyBy('id')->map(function (Pemeriksaan $pemeriksaan) {
            return [
                'patient'   => $pemeriksaan->pasien->nama ?? '-',
                'doctor'    => $pemeriksaan->dokter->nama_dokter ?? '-',
                'diagnosis' => $pemeriksaan->diagnosis ?? '-',
                'date'      => optional($pemeriksaan->created_at)->format('d/m/Y'),
                'total'     => $pemeriksaan->total_tagihan,
                'items'     => $pemeriksaan->rincian_tagihan,
            ];
        });

        // Tampilkan halaman form tambah pembayaran
        return view('admin.pembayaran.create', compact('pemeriksaans', 'paymentData'));
    }

    // Membuat Snap Token Midtrans dari form pendaftaran pembayaran
    public function getSnapToken(Request $request)
    {
        // Validasi input ID pemeriksaan
        $request->validate([
            'pemeriksaan_id' => 'required|exists:pemeriksaans,id',
        ]);

        // Cari data pemeriksaan terkait
        $pemeriksaan = Pemeriksaan::with(['pasien', 'antrean'])->findOrFail($request->pemeriksaan_id);
        // Hitung total tagihan
        $bill = $this->billFor($pemeriksaan);

        // Inisialisasi API Midtrans
        $this->initMidtrans();

        // Buat order ID unik
        $orderId = 'PB-' . now()->format('YmdHis');

        // Parameter transaksi untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $bill['total'],
            ],
            'customer_details' => [
                'first_name' => $pemeriksaan->pasien->nama ?? 'Pasien',
                'phone'      => $pemeriksaan->pasien->no_hp ?? '08123456789',
            ],
        ];

        try {
            // Minta Snap Token ke Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Simpan record pembayaran dan detailnya ke database via transaksi
            DB::transaction(function () use ($bill, $pemeriksaan, $orderId, $snapToken) {
                $pembayaran = Pembayaran::create([
                    'no_pembayaran'     => $orderId,
                    'pemeriksaan_id'    => $pemeriksaan->id,
                    'pasien_id'         => $pemeriksaan->pasien_id,
                    'total_bayar'       => $bill['total'],
                    'nominal_dibayar'   => $bill['total'],
                    'kembalian'         => 0,
                    'status_pembayaran' => 'Lunas',
                    'metode_pembayaran' => 'Midtrans',
                    'snap_token'        => $snapToken,
                    'order_id'          => $orderId,
                    'tgl_pembayaran'    => now()->toDateString(),
                ]);

                // Simpan item rincian tagihan
                foreach ($bill['items'] as $item) {
                    DetailPembayaran::create([
                        'pembayaran_id'   => $pembayaran->id,
                        'jenis_pelayanan' => $item['jenis_pelayanan'],
                        'nama_pelayanan'  => $item['nama_pelayanan'],
                        'jumlah'          => $item['jumlah'],
                        'harga'           => $item['harga'],
                        'subtotal'        => $item['subtotal'],
                    ]);
                }

                // Perbarui status antrean menjadi Selesai jika ada
                if ($pemeriksaan->antrean) {
                    $pemeriksaan->antrean->update(['status' => 'Selesai']);
                }
            });

            // Kembalikan Snap Token dalam bentuk JSON
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            // Tangani kesalahan pembuatan token
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Menyimpan data pembayaran dengan metode tunai
    public function pembayaran_store(Request $request)
    {
        // Validasi input data pembayaran tunai
        $request->validate([
            'pemeriksaan_id' => 'required|exists:pemeriksaans,id',
            'nominal_bayar'  => 'required|numeric|min:0',
        ], [
            'pemeriksaan_id.required' => 'Pilih pemeriksaan terlebih dahulu.',
            'nominal_bayar.required'  => 'Nominal dibayar wajib diisi.',
            'nominal_bayar.numeric'   => 'Nominal dibayar harus berupa angka.',
        ]);

        // Ambil data pemeriksaan dan kalkulasi total bill
        $pemeriksaan = Pemeriksaan::with(['pasien', 'antrean'])->findOrFail($request->pemeriksaan_id);
        $bill = $this->billFor($pemeriksaan);

        // Cek jika nominal dibayar kurang dari total tagihan
        if ((float) $request->nominal_bayar < $bill['total']) {
            return back()
                ->withInput()
                ->withErrors(['nominal_bayar' => 'Nominal dibayar kurang dari total tagihan (Rp ' . number_format($bill['total'], 0, ',', '.') . ').']);
        }

        // Hitung nominal kembalian
        $kembalian = (float) $request->nominal_bayar - $bill['total'];

        // Simpan data transaksi pembayaran tunai beserta rinciannya
        DB::transaction(function () use ($bill, $pemeriksaan, $request, $kembalian) {
            $pembayaran = Pembayaran::create([
                'no_pembayaran'     => 'PB-' . now()->format('YmdHis'),
                'pemeriksaan_id'    => $pemeriksaan->id,
                'pasien_id'         => $pemeriksaan->pasien_id,
                'total_bayar'       => $bill['total'],
                'nominal_dibayar'   => $request->nominal_bayar,
                'kembalian'         => $kembalian,
                'status_pembayaran' => 'Lunas',
                'metode_pembayaran' => 'Tunai',
                'tgl_pembayaran'    => now()->toDateString(),
            ]);

            // Simpan setiap item rincian transaksi
            foreach ($bill['items'] as $item) {
                DetailPembayaran::create([
                    'pembayaran_id'   => $pembayaran->id,
                    'jenis_pelayanan' => $item['jenis_pelayanan'],
                    'nama_pelayanan'  => $item['nama_pelayanan'],
                    'jumlah'          => $item['jumlah'],
                    'harga'           => $item['harga'],
                    'subtotal'        => $item['subtotal'],
                ]);
            }

            // Ubah status antrean menjadi Selesai jika ada
            if ($pemeriksaan->antrean) {
                $pemeriksaan->antrean->update(['status' => 'Selesai']);
            }
        });

        // Redirect ke index pembayaran dengan pesan sukses
        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil diproses dan disimpan!');
    }

    // Memproses lanjutan/perubahan metode pembayaran dari daftar transaksi
    public function pembayaran_proses(Request $request, $id)
    {
        // Cari data pembayaran yang akan diproses
        $pembayaran = Pembayaran::with(['pemeriksaan.pasien'])->findOrFail($id);

        // Jika metode yang dipilih adalah Tunai
        if ($request->metode_pembayaran === 'Tunai') {
            DB::transaction(function () use ($pembayaran) {
                // Update status transaksi menjadi Tunai & Lunas
                $pembayaran->update([
                    'metode_pembayaran' => 'Tunai',
                    'status_pembayaran' => 'Lunas',
                    'tgl_pembayaran'    => now()->toDateString(),
                ]);

                // Update status antrean menjadi Selesai
                if ($pembayaran->pemeriksaan && $pembayaran->pemeriksaan->antrean) {
                    $pembayaran->pemeriksaan->antrean->update(['status' => 'Selesai']);
                }
            });

            return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran tunai berhasil diproses!');
        }

        // Jika metode Midtrans, inisialisasi konfigurasi Midtrans
        $this->initMidtrans();

        // Buat Order ID baru
        $orderId = 'KLINIK-' . $pembayaran->id . '-' . time();

        // Parameter request untuk Midtrans Snap
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $pembayaran->total_bayar,
            ],
            'customer_details' => [
                'first_name' => $pembayaran->pemeriksaan->pasien->nama ?? 'Pasien',
                'phone'      => $pembayaran->pemeriksaan->pasien->no_hp ?? '08123456789',
            ],
        ];

        try {
            // Minta Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Simpan Snap Token dan Order ID baru ke database
            $pembayaran->update([
                'metode_pembayaran' => 'Midtrans',
                'snap_token'        => $snapToken,
                'order_id'          => $orderId,
            ]);

            // Kembalikan response JSON berisi Snap Token
            return response()->json([
                'status'     => 'success',
                'snap_token' => $snapToken
            ]);
        } catch (\Exception $e) {
            // Kembalikan response JSON jika terjadi kegagalan
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Helper fungsi internal untuk kalkulasi rincian & total biaya pemeriksaan
    protected function billFor(Pemeriksaan $pemeriksaan): array
    {
        // Pastikan relasi tindakanMaster telah di-load
        $pemeriksaan->loadMissing('tindakanMaster');
        $tindakan = $pemeriksaan->tindakanMaster;

        // Inisialisasi item tagihan awal (Jasa Pemeriksaan/Tindakan)
        $items = [[
            'jenis_pelayanan' => $tindakan ? 'Tindakan' : 'Pemeriksaan',
            'nama_pelayanan'  => $tindakan?->nama_tindakan ?? 'Jasa Pemeriksaan Dokter',
            'jumlah'          => 1,
            'harga'           => $tindakan?->tarif ?? 50000,
            'subtotal'        => $tindakan?->tarif ?? 50000,
        ]];

        // Ambil rekam medis terkait untuk mendapatkan resep obat
        $rekamMedis = RekamMedis::with('detailResep.obat')
            ->where('antrean_id', $pemeriksaan->antrean_id)
            ->first();

        // Jika rekam medis dan resep obat ada, tambahkan obat ke dalam rincian tagihan
        if ($rekamMedis && $rekamMedis->detailResep) {
            foreach ($rekamMedis->detailResep as $detail) {
                if (!$detail->obat) {
                    continue;
                }

                $items[] = [
                    'jenis_pelayanan' => 'Obat',
                    'nama_pelayanan'  => $detail->obat->nama_obat,
                    'jumlah'          => $detail->jumlah,
                    'harga'           => $detail->obat->harga,
                    'subtotal'        => $detail->jumlah * $detail->obat->harga,
                ];
            }
        }

        // Kembalikan array berisi daftar item dan total tagihan
        return [
            'items' => $items,
            'total' => collect($items)->sum('subtotal'),
        ];
    }

    // Menampilkan halaman kuitansi / struk pembayaran untuk dicetak
    public function pembayaran_cetak($id)
    {
        // Cari data pembayaran berdasarkan ID
        $pembayaran = Pembayaran::with(['pemeriksaan.pasien', 'pemeriksaan.dokter', 'detailPembayaran'])
            ->findOrFail($id);

        // Tampilkan halaman cetak kuitansi
        return view('admin.pembayaran.cetak', compact('pembayaran'));
    }
}