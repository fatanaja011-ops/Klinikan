<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Pemeriksaan Pasien</span>
                    <h2 class="text-2xl font-bold text-gray-800">Riwayat Rekam Medis</h2>
                </div>
                <a href="{{ route('admin.rekam-medis.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium shadow-sm transition">
                    + Periksa / Imput Rekam Medis
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-100 text-emerald-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3">TANGGAL</th>
                                <th class="px-6 py-3">PASIEN</th>
                                <th class="px-6 py-3">DOKTER</th>
                                <th class="px-6 py-3">DIAGNOSA</th>
                                <th class="px-6 py-3">RESEP OBAT</th>
                                <th class="px-6 py-3 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($rekamMedis as $rm)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-700">
                                        {{ \Carbon\Carbon::parse($rm->tanggal_periksa)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $rm->pasien->nama }}</div>
                                        <div class="text-xs text-gray-400 font-mono">RM: {{ $rm->pasien->no_rm }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-800">{{ $rm->dokter->nama_dokter }}</div>
                                        <div class="text-xs text-gray-500">{{ $rm->dokter->spesialisasi }}</div>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs truncate">{{ $rm->diagnosa }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded-full border border-emerald-200">
                                            {{ $rm->detailResep->count() }} Jenis Obat
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.rekam-medis.show', $rm->id) }}" class="px-3 py-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-md text-xs font-medium">
                                            Detail Rekam Medis
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-400">Belum ada data rekam medis.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4">
                    {{ $rekamMedis->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>