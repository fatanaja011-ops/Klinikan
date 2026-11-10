<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6 print:hidden">
            <h2 class="text-2xl font-bold text-gray-800">Detail Rekam Medis</h2>
            <div class="space-x-2">
                <button onclick="window.print()" class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition">
                    Cetak Rekam Medis
                </button>
                <a href="{{ route('admin.rekam-medis.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    Kembali
                </a>
            </div>
        </div>

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
            <!-- Header Surat -->
            <div class="border-b border-gray-200 pb-6 mb-6 flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-emerald-600">KLINIK SEHAT MEDIKA</h1>
                    <p class="text-sm text-gray-500">Jl. Raya Kesehatan No. 123 | Telp: (021) 555-0192</p>
                </div>
                <div class="text-right">
                    <span class="text-xs font-semibold text-gray-400 block">TANGGAL PERIKSA</span>
                    <span class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($rekamMedis->tanggal_periksa)->translatedFormat('d F Y') }}</span>
                </div>
            </div>

            <!-- Informasi Pasien & Dokter -->
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Identitas Pasien</h3>
                    <p class="text-base font-bold text-gray-900">{{ $rekamMedis->pasien->nama }}</p>
                    <p class="text-xs text-gray-600">No. RM: <span class="font-mono font-bold">{{ $rekamMedis->pasien->no_rm }}</span></p>
                    <p class="text-xs text-gray-600">NIK: {{ $rekamMedis->pasien->nik }}</p>
                    <p class="text-xs text-gray-600">Jenis Kelamin: {{ $rekamMedis->pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Dokter Pemeriksa</h3>
                    <p class="text-base font-bold text-gray-900">{{ $rekamMedis->dokter->nama_dokter }}</p>
                    <p class="text-xs text-gray-600">Spesialisasi: {{ $rekamMedis->dokter->spesialisasi }}</p>
                    <p class="text-xs text-gray-600">No. Telp: {{ $rekamMedis->dokter->no_telepon }}</p>
                </div>
            </div>

            <!-- Detail Diagnosa -->
            <div class="space-y-4 mb-6">
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase">Keluhan Pasien</h4>
                    <p class="text-gray-800 bg-gray-50 p-3 rounded-md mt-1">{{ $rekamMedis->keluhan }}</p>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase">Diagnosa</h4>
                    <p class="text-gray-800 bg-gray-50 p-3 rounded-md mt-1 font-medium">{{ $rekamMedis->diagnosa }}</p>
                </div>

                @if($rekamMedis->tindakan)
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase">Tindakan Medis</h4>
                        <p class="text-gray-800 bg-gray-50 p-3 rounded-md mt-1">{{ $rekamMedis->tindakan }}</p>
                    </div>
                @endif
            </div>

            <!-- Resep Obat -->
            <div>
                <h4 class="text-xs font-bold text-gray-500 uppercase mb-2">Resep Obat Yang Diberikan</h4>
                <table class="w-full text-left text-sm border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                        <tr>
                            <th class="px-4 py-2 border-b">Nama Obat</th>
                            <th class="px-4 py-2 border-b">Jumlah</th>
                            <th class="px-4 py-2 border-b">Aturan Pakai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($rekamMedis->detailResep as $detail)
                            <tr>
                                <td class="px-4 py-2 font-medium text-gray-800">{{ $detail->obat->nama_obat }}</td>
                                <td class="px-4 py-2">{{ $detail->jumlah }} {{ $detail->obat->satuan }}</td>
                                <td class="px-4 py-2 text-emerald-700 font-medium">{{ $detail->aturan_pakai }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-2 text-center text-gray-400">Tidak ada resep obat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>