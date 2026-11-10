<x-app-layout>
    <div class="p-4 sm:ml-64 pt-20">
        <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-3">Proses Pembayaran Kasir</h2>

            <form action="{{ route('admin.pembayaran.update', $pembayaran->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Detail Pasien & Dokter --}}
                <div class="grid grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded-lg text-sm">
                    <div>
                        <p class="text-gray-500">Kode Transaksi</p>
                        <p class="font-bold text-gray-800">{{ $pembayaran->kode_pembayaran }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Nama Pasien</p>
                        <p class="font-bold text-gray-800">{{ $pembayaran->rekamMedis->pasien->nama ?? '-' }}</p>
                    </div>
                </div>

                {{-- Rincian Tagihan --}}
                <div class="space-y-3 mb-6 text-sm">
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Jasa Dokter / Pemeriksaan</span>
                        <span class="font-medium text-gray-800">Rp {{ number_format($pembayaran->biaya_dokter, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Total Obat</span>
                        <span class="font-medium text-gray-800">Rp {{ number_format($pembayaran->biaya_obat, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between pt-2 text-base font-bold">
                        <span class="text-gray-800">Total Tagihan</span>
                        <span class="text-emerald-600">Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Field Input Pembayaran --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Uang Diterima (Rp)</label>
                        <input type="number" id="jumlah_bayar" name="jumlah_bayar" min="{{ $pembayaran->total_bayar }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm"
                            oninput="hitungKembalian({{ $pembayaran->total_bayar }})">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kembalian (Rp)</label>
                        <input type="text" id="kembalian" readonly 
                            class="w-full rounded-lg border-gray-200 bg-gray-50 text-gray-700 text-sm font-semibold">
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.pembayaran.index') }}" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</a>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 font-semibold">Konfirmasi Lunas</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function hitungKembalian(totalTagihan) {
            const bayar = parseFloat(document.getElementById('jumlah_bayar').value) || 0;
            const kembalian = bayar - totalTagihan;
            document.getElementById('kembalian').value = kembalian >= 0 
                ? new Intl.NumberFormat('id-ID').format(kembalian) 
                : 'Uang Kurang';
        }
    </script>
</x-app-layout>