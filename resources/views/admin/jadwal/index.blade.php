<x-app-layout>
    <div class="p-6 space-y-6">

        {{-- Header Section --}}
        <div class="flex justify-between items-center">
            <div>
                <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Pengaturan Praktik</span>
                <h2 class="text-2xl font-bold text-gray-800">Jadwal Dokter</h2>
            </div>
            <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm transition">
                + Tambah Jadwal
            </button>
        </div>

        {{-- Flash Alerts --}}
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Tabel Data --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3">NO</th>
                            <th class="px-6 py-3">NAMA DOKTER</th>
                            <th class="px-6 py-3">HARI</th>
                            <th class="px-6 py-3">JAM PRAKTIK</th>
                            <th class="px-6 py-3">RUANG</th>
                            <th class="px-6 py-3">KUOTA</th>
                            <th class="px-6 py-3 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($jadwal as $key => $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-700">{{ $jadwal->firstItem() + $key }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $item->dokter->nama_dokter ?? $item->dokter->nama ?? '-' }}</div>
                                <div class="text-xs text-gray-400">{{ $item->dokter->spesialisasi ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-100 rounded-full">
                                    {{ $item->hari }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono text-gray-800">
                                {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-700">{{ $item->ruang }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 font-bold rounded text-xs">
                                    {{ $item->kuota }} Pasien
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.jadwal.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 text-xs font-bold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400">Belum ada data jadwal dokter.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $jadwal->links() }}
            </div>
        </div>

    </div>

    {{-- Modal Tambah Jadwal --}}
    <div id="modalTambah" class="fixed inset-0 bg-gray-900/50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-6 space-y-4">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-bold text-gray-800">Tambah Jadwal Dokter</h3>
                <button onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Pilih Dokter</label>
                    <select name="dokter_id" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">-- Pilih Dokter --</option>
                        @foreach($dokters as $d)
                            <option value="{{ $d->id }}">{{ $d->nama_dokter ?? $d->nama }} ({{ $d->spesialisasi }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Hari</label>
                    <select name="hari" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                            <option value="{{ $h }}">{{ $h }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Jam Mulai</label>
                        <input type="time" name="jam_mulai" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Jam Selesai</label>
                        <input type="time" name="jam_selesai" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Ruangan</label>
                        <input type="text" name="ruang" placeholder="Misal: Poliklinik A" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Kuota Pasien</label>
                        <input type="number" name="kuota" min="1" placeholder="20" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg font-medium">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>