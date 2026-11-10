<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium uppercase tracking-[0.2em] text-emerald-600">Data master</p>
                <h1 class="mt-1 text-3xl font-bold tracking-tight text-gray-900">Data Tindakan</h1>
                <p class="mt-1 text-sm text-gray-500">Kelola jenis tindakan medis dan tarif layanan klinik.</p>
            </div>
            <a href="{{ route('admin.tindakan.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                + Tambah Tindakan
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
        @endif

        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-sm text-gray-500">Total tindakan</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $tindakans->total() }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-sm text-gray-500">Total nilai tarif</p>
                <p class="mt-2 text-3xl font-bold text-emerald-600">Rp {{ number_format($tindakans->sum('tarif'), 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">No</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Kode</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Nama Tindakan</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Keterangan</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Tarif</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($tindakans as $index => $tindakan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 text-sm text-gray-600">{{ $tindakans->firstItem() + $index }}</td>
                                <td class="px-5 py-4 font-mono text-sm text-gray-700">{{ $tindakan->kode_tindakan }}</td>
                                <td class="px-5 py-4 text-sm font-semibold text-gray-900">{{ $tindakan->nama_tindakan }}</td>
                                <td class="px-5 py-4 text-sm text-gray-600">{{ $tindakan->keterangan ?: '-' }}</td>
                                <td class="px-5 py-4 text-sm font-semibold text-emerald-600">Rp {{ number_format($tindakan->tarif, 0, ',', '.') }}</td>
                                <td class="px-5 py-4 text-center text-sm">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.tindakan.edit', $tindakan) }}" class="rounded-lg bg-indigo-50 px-2.5 py-1.5 font-medium text-indigo-700 transition hover:bg-indigo-100">Edit</a>
                                        <form action="{{ route('admin.tindakan.destroy', $tindakan) }}" method="POST" onsubmit="return confirm('Hapus tindakan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg bg-red-50 px-2.5 py-1.5 font-medium text-red-700 transition hover:bg-red-100">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-8 text-center text-sm text-gray-500">Belum ada data tindakan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($tindakans->hasPages())
                <div class="border-t border-gray-200 px-5 py-4">{{ $tindakans->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
