<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-8 py-2">
        
        <!-- Header Section -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <p class="text-xs font-semibold uppercase tracking-widest text-emerald-600">Pelayanan Klinik</p>
                </div>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Daftar Antrean Hari Ini</h1>
                <p class="mt-0.5 text-sm text-gray-500">Kelola dan pantau alur pemeriksaan pasien secara real-time.</p>
            </div>
            <div>
                <a href="{{ route('admin.antrean.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-emerald-700 hover:shadow focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Daftarkan Antrean
                </a>
            </div>
        </div>

        <!-- Metric Summary Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Antrean -->
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500">Total Antrean Hari Ini</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900">{{ $antreans->total() ?? $antreans->count() }}</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Menunggu -->
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500">Menunggu</p>
                        <p class="mt-1 text-2xl font-bold text-amber-600">
                            {{ $antreans->where('status', 'Menunggu')->count() }}
                        </p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Dipanggil / Diperiksa -->
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500">Sedang Diperiksa / Dipanggil</p>
                        <p class="mt-1 text-2xl font-bold text-blue-600">
                            {{ $antreans->whereIn('status', ['Dipanggil', 'Diperiksa'])->count() }}
                        </p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                </div>
            </div>

            <!-- Selesai -->
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500">Selesai</p>
                        <p class="mt-1 text-2xl font-bold text-emerald-600">
                            {{ $antreans->where('status', 'Selesai')->count() }}
                        </p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card Container -->
        <div class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-800">Antrean Pemeriksaan Pasien</h2>
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                        {{ date('d M Y') }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50/80 text-xs font-semibold uppercase tracking-wider text-gray-500 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3.5">No. Antrean</th>
                            <th scope="col" class="px-6 py-3.5">Nama Pasien</th>
                            <th scope="col" class="px-6 py-3.5">Dokter Tujuan</th>
                            <th scope="col" class="px-6 py-3.5">Keluhan</th>
                            <th scope="col" class="px-6 py-3.5">Status</th>
                            <th scope="col" class="px-6 py-3.5">Aksi Status</th>
                            <th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($antreans as $item)
                            <tr class="transition hover:bg-gray-50/60">
                                <!-- No Antrean -->
                                <td class="px-6 py-4 font-bold text-emerald-600">
                                    <span class="inline-flex items-center rounded-lg bg-emerald-50 px-2.5 py-1 text-sm font-bold text-emerald-700">
                                        {{ $item->no_antrean }}
                                    </span>
                                </td>

                                <!-- Nama Pasien & RM/NIK -->
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $item->pasien->nama ?? '-' }}</div>
                                    <div class="text-xs text-gray-400">RM: {{ $item->pasien->no_rm ?? $item->pasien->nik ?? '-' }}</div>
                                </td>

                                <!-- Dokter Tujuan -->
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-800">{{ $item->dokter->nama_dokter ?? '-' }}</div>
                                    <div class="text-xs text-gray-400">{{ $item->dokter->spesialisasi ?? 'Spesialisasi belum diatur' }}</div>
                                </td>

                                <!-- Keluhan -->
                                <td class="px-6 py-4 text-gray-600 max-w-xs truncate">
                                    {{ $item->keluhan ?? '-' }}
                                </td>

                                <!-- Badge Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($item->status)
                                        @case('Menunggu')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                Menunggu
                                            </span>
                                            @break
                                        @case('Dipanggil')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-ping"></span>
                                                Dipanggil
                                            </span>
                                            @break
                                        @case('Diperiksa')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-2.5 py-1 text-xs font-semibold text-purple-700 ring-1 ring-inset ring-purple-700/10">
                                                <span class="h-1.5 w-1.5 rounded-full bg-purple-500"></span>
                                                Diperiksa
                                            </span>
                                            @break
                                        @case('Selesai')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                Selesai
                                            </span>
                                            @break
                                        @case('Batal')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 ring-1 ring-inset ring-rose-600/10">
                                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                                Batal
                                            </span>
                                            @break
                                    @endswitch
                                </td>

                                <!-- Tombol Aksi Status Berjenjang -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        @if($item->status === 'Menunggu')
                                            <form action="{{ route('admin.antrean.update-status', $item->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="Dipanggil">
                                                <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-blue-700 shadow-sm">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                                    Panggil
                                                </button>
                                            </form>
                                        @elseif($item->status === 'Dipanggil')
                                            <form action="{{ route('admin.antrean.update-status', $item->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="Diperiksa">
                                                <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-purple-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-purple-700 shadow-sm">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                    Periksa
                                                </button>
                                            </form>
                                        @elseif($item->status === 'Diperiksa')
                                            <form action="{{ route('admin.antrean.update-status', $item->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="Selesai">
                                                <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-emerald-700 shadow-sm">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    Selesai
                                                </button>
                                            </form>
                                        @endif

                                        @if(in_array($item->status, ['Menunggu', 'Dipanggil']))
                                            <form action="{{ route('admin.antrean.update-status', $item->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="Batal">
                                                <button type="submit" class="rounded-lg border border-gray-200 bg-white px-2.5 py-1.5 text-xs font-medium text-gray-600 transition hover:bg-gray-50 hover:text-gray-900">Batal</button>
                                            </form>
                                        @elseif(in_array($item->status, ['Selesai', 'Batal']))
                                            <span class="text-xs text-gray-400 font-medium whitespace-nowrap">-</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Aksi Hapus -->
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <form action="{{ route('admin.antrean.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus antrean ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-rose-50 px-2.5 py-1.5 text-xs font-medium text-rose-600 transition hover:bg-rose-100 hover:text-rose-700">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <h3 class="mt-3 text-sm font-semibold text-gray-900">Belum Ada Antrean</h3>
                                    <p class="mt-1 text-xs text-gray-500">Tidak ada antrean pemeriksaan yang terdaftar untuk hari ini.</p>
                                    <div class="mt-4">
                                        <a href="{{ route('admin.antrean.create') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                                            + Daftarkan Antrean Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            @if(method_exists($antreans, 'hasPages') && $antreans->hasPages())
                <div class="border-t border-gray-100 bg-gray-50/50 px-6 py-4">
                    {{ $antreans->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>