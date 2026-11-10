<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium uppercase tracking-[0.2em] text-emerald-600">Data master</p>
                <h1 class="mt-1 text-3xl font-bold tracking-tight text-gray-900">Manajemen Data Obat</h1>
            </div>

            <a href="{{ route('admin.obat.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                + Tambah Obat
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-sm text-gray-500">Total obat</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $obats->total() }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-sm text-gray-500">Stok tersedia</p>
                <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $obats->sum('stok') }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-sm text-gray-500">Obat habis</p>
                <p class="mt-2 text-3xl font-bold text-red-500">{{ $obats->where('status', 'Habis')->count() }}</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-5 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Obat Klinik</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Nama Obat</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Jenis / Satuan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Harga</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Stok</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Kedaluwarsa</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($obats as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-mono text-sm text-gray-700">{{ $item->kode_obat }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $item->nama_obat }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->jenis_obat }} / {{ $item->satuan }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $item->stok }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $item->tanggal_kedaluwarsa }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $item->status === 'Tersedia' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-sm">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.obat.edit', $item->id) }}" class="rounded-lg bg-indigo-50 px-2.5 py-1.5 font-medium text-indigo-700 transition hover:bg-indigo-100">Edit</a>
                                        <form action="{{ route('admin.obat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus obat ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg bg-red-50 px-2.5 py-1.5 font-medium text-red-700 transition hover:bg-red-100">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500">Belum ada data obat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($obats->hasPages())
                <div class="border-t border-gray-200 px-5 py-4">
                    {{ $obats->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
