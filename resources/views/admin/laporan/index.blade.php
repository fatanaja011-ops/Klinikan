<x-app-layout>
    <div class="p-6 space-y-6">
        
        {{-- Header Section --}}
        <div class="flex justify-between items-center">
            <div>
                <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Rekapitulasi Data</span>
                <h2 class="text-2xl font-bold text-gray-800">Laporan Klinik</h2>
            </div>
        </div>

        {{-- Form Filter --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-bold text-gray-800 mb-4">Filter Laporan</h3>
            <form action="{{ route('admin.laporan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Jenis Laporan</label>
                    <select name="jenis_laporan" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm">
                        <option value="pembayaran" {{ $jenis_laporan == 'pembayaran' ? 'selected' : '' }}>Laporan Transaksi Pembayaran</option>
                        <option value="rekam_medis" {{ $jenis_laporan == 'rekam_medis' ? 'selected' : '' }}>Laporan Rekam Medis Pasien</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm" value="{{ $tgl_mulai }}">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Tanggal Selesai</label>
                    <input type="date" name="tgl_selesai" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm" value="{{ $tgl_selesai }}">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm transition">
                        Tampilkan
                    </button>
                    <a href="{{ route('admin.laporan.cetak', ['jenis_laporan' => $jenis_laporan, 'tgl_mulai' => $tgl_mulai, 'tgl_selesai' => $tgl_selesai]) }}" target="_blank" class="w-full px-4 py-2.5 text-sm font-medium text-center text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                        Cetak
                    </a>
                </div>
            </form>
        </div>

        {{-- Tabel Data --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                @if($jenis_laporan == 'pembayaran')
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3">NO</th>
                                <th class="px-6 py-3">KODE TRX</th>
                                <th class="px-6 py-3">PASIEN</th>
                                <th class="px-6 py-3">TANGGAL</th>
                                <th class="px-6 py-3">TOTAL BIAYA</th>
                                <th class="px-6 py-3">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($data as $key => $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-700">{{ $key + 1 }}</td>
                                <td class="px-6 py-4 font-mono font-bold text-gray-900">{{ $item->no_pembayaran }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $item->pemeriksaan->pasien->nama ?? '-' }}</div>
                                    <div class="text-xs text-gray-400 font-mono">RM: {{ $item->pemeriksaan->pasien->no_rm ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-700">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-emerald-600">
                                    Rp {{ number_format($item->total_bayar, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->status_pembayaran == 'Lunas')
                                        <span class="px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-100 rounded-full">Lunas</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold text-amber-700 bg-amber-100 rounded-full">Belum Bayar</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400">Tidak ada data transaksi pada rentang tanggal ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                @else
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3">NO</th>
                                <th class="px-6 py-3">TANGGAL PERIKSA</th>
                                <th class="px-6 py-3">PASIEN</th>
                                <th class="px-6 py-3">DOKTER</th>
                                <th class="px-6 py-3">DIAGNOSA</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($data as $key => $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-700">{{ $key + 1 }}</td>
                                <td class="px-6 py-4 font-medium text-gray-700">
                                    {{ \Carbon\Carbon::parse($item->tanggal_periksa)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $item->pasien->nama ?? '-' }}</div>
                                    <div class="text-xs text-gray-400 font-mono">RM: {{ $item->pasien->no_rm ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-800">{{ $item->dokter->nama_dokter ?? $item->dokter->nama ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->dokter->spesialisasi ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 max-w-xs truncate">{{ $item->diagnosa }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">Tidak ada data rekam medis pada rentang tanggal ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- Pagination jika controller memakai paginate --}}
            @if(method_exists($data, 'links'))
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $data->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>