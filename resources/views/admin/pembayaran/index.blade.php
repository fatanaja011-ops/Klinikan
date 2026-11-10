<x-app-layout>
    <div class="p-6 space-y-6">
        {{-- Header Section --}}
        <div class="flex justify-between items-center">
            <div>
                <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Pelayanan Klinik</span>
                <h2 class="text-2xl font-bold text-gray-800">Data Pembayaran</h2>
                <p class="text-xs text-gray-500">Kelola transaksi pembayaran kunjungan pasien klinik.</p>
            </div>
            <a href="{{ route('admin.pembayaran.create') }}" class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition">
                + Tambah Pembayaran
            </a>
        </div>

        {{-- Flash Alert --}}
        @if(session('success'))
            <div class="p-4 bg-emerald-100 text-emerald-800 rounded-lg font-medium text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-rose-100 text-rose-800 rounded-lg font-medium text-sm">{{ session('error') }}</div>
        @endif

        {{-- Table Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3">NO. PEMBAYARAN</th>
                            <th class="px-6 py-3">PASIEN</th>
                            <th class="px-6 py-3">DOKTER</th>
                            <th class="px-6 py-3">TGL PEMBAYARAN</th>
                            <th class="px-6 py-3">METODE</th>
                            <th class="px-6 py-3">TOTAL BAYAR</th>
                            <th class="px-6 py-3">STATUS</th>
                            <th class="px-6 py-3 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pembayaran as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-emerald-600">{{ $item->no_pembayaran }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $item->pemeriksaan->pasien->nama ?? '-' }}</div>
                                    <div class="text-xs text-gray-400 font-mono">RM: {{ $item->pemeriksaan->pasien->no_rm ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $item->pemeriksaan->dokter->nama_dokter ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    {{ optional($item->tgl_pembayaran)->format('d M Y') ?? optional($item->created_at)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $item->metode_pembayaran == 'Tunai' ? 'bg-gray-100 text-gray-700' : 'bg-blue-50 text-blue-700' }}">
                                        {{ $item->metode_pembayaran }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-800">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    @if($item->status_pembayaran == 'Lunas')
                                        <span class="px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-100 rounded-full">Lunas</span>
                                    @elseif($item->status_pembayaran == 'Belum Bayar')
                                        <span class="px-2.5 py-1 text-xs font-semibold text-amber-700 bg-amber-100 rounded-full">Belum Bayar</span>
                                    @elseif($item->status_pembayaran == 'Batal')
                                        <span class="px-2.5 py-1 text-xs font-semibold text-rose-700 bg-rose-100 rounded-full">Batal</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded-full">{{ $item->status_pembayaran }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center space-x-2">
                                    <a href="{{ route('admin.pembayaran.cetak', $item->id) }}" class="inline-block px-3 py-1.5 text-xs font-medium text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition">
                                        Cetak
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-6 text-center text-gray-400">
                                    Belum ada transaksi pembayaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $pembayaran->links() }}
            </div>
        </div>
    </div>
</x-app-layout>