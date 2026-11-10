<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-6 py-2">

        <!-- Header Section -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Pelayanan Klinik</span>
                </div>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ $riwayat ? 'Riwayat Pendaftaran Pasien' : 'Data Pendaftaran Pasien' }}</h1>
                <p class="mt-0.5 text-sm text-gray-500">{{ $riwayat ? 'Lihat pendaftaran yang sudah selesai atau dibatalkan.' : 'Kelola pendaftaran kunjungan pasien yang masih aktif.' }}</p>
            </div>
            <div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.pendaftaran.index', ['riwayat' => $riwayat ? 0 : 1]) }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200">
                        {{ $riwayat ? 'Pendaftaran Aktif' : 'Lihat Riwayat' }}
                    </a>
                    @if(!$riwayat)
                        <a href="{{ route('admin.pendaftaran.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-emerald-700 hover:shadow focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Tambah Pendaftaran
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Flash Alert Success -->
        @if(session('success'))
            <div class="flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-emerald-100 p-2 text-emerald-600">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Flash Alert Error -->
        @if(session('error'))
            <div class="flex items-center justify-between rounded-xl border border-red-200 bg-red-50 p-4 text-red-800 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-red-100 p-2 text-red-600">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 012 0v4a1 1 0 11-2 0V9zm1-4a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Summary Metric Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500">Total Pendaftaran</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900">{{ method_exists($pendaftaran, 'total') ? $pendaftaran->total() : count($pendaftaran) }}</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500">Kunjungan Hari Ini</p>
                        <p class="mt-1 text-2xl font-bold text-blue-600">
                            {{ $kunjunganHariIni }}
                        </p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500">Status Sistem</p>
                        <p class="mt-1 text-sm font-semibold text-emerald-600">Pelayanan Aktif</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Data Table Container -->
        <div class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm">
            <!-- Table Header Bar -->
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-base font-semibold text-gray-800">{{ $riwayat ? 'Riwayat Pendaftaran' : 'Daftar Pendaftaran Aktif' }}</h3>
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                        Total: {{ method_exists($pendaftaran, 'total') ? $pendaftaran->total() : count($pendaftaran) }} Registrasi
                    </span>
                </div>
            </div>

            <!-- Table Body -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="border-b border-gray-100 bg-gray-50/80 text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th scope="col" class="px-6 py-3.5">No. Registrasi</th>
                            <th scope="col" class="px-6 py-3.5">Pasien</th>
                            <th scope="col" class="px-6 py-3.5">Dokter & Ruang</th>
                            <th scope="col" class="px-6 py-3.5">Tgl Kunjungan</th>
                            <th scope="col" class="px-6 py-3.5">Keluhan Awal</th>
                            <th scope="col" class="px-6 py-3.5">Status</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pendaftaran as $item)
                            <tr class="transition-colors duration-150 hover:bg-gray-50/60">
                                <!-- No. Registrasi -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-lg border border-emerald-100 bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">
                                        {{ $item->no_pendaftaran }}
                                    </span>
                                </td>

                                <!-- Pasien -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700">
                                            {{ strtoupper(substr($item->pasien->nama ?? 'P', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $item->pasien->nama ?? '-' }}</div>
                                            <div class="text-xs text-gray-400">RM: {{ $item->pasien->no_rm ?? $item->pasien->nik ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Dokter & Ruang -->
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-800">{{ $item->dokter->nama_dokter ?? $item->dokter->user->name ?? '-' }}</div>
                                    <div class="mt-0.5 flex items-center gap-1.5 text-xs text-gray-500">
                                        <span class="rounded bg-gray-100 px-1.5 py-0.5 text-[11px] font-medium text-gray-600">
                                            {{ $item->jadwal->ruang ?? 'Poliklinik' }}
                                        </span>
                                        @if(isset($item->jadwal->jam_mulai))
                                            <span class="text-gray-400">• {{ $item->jadwal->jam_mulai }} - {{ $item->jadwal->jam_selesai }}</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Tanggal Kunjungan -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-700">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ \Carbon\Carbon::parse($item->tgl_kunjungan)->translatedFormat('d M Y') }}
                                    </div>
                                </td>

                                <!-- Keluhan Awal -->
                                <td class="px-6 py-4 max-w-xs truncate text-gray-600">
                                    <span title="{{ $item->keluhan_awal }}">{{ $item->keluhan_awal ?? '-' }}</span>
                                </td>

                                <!-- Status Pelayanan -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php($statusPendaftaran = $item->antrean?->status ?? 'Belum Diantrekan')
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusPendaftaran === 'Selesai' ? 'bg-emerald-100 text-emerald-700' : ($statusPendaftaran === 'Batal' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                        {{ $statusPendaftaran }}
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <form action="{{ route('admin.pendaftaran.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pendaftaran ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg border border-red-100 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 transition-colors hover:bg-red-100 hover:text-red-700">
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
                                    <h3 class="mt-3 text-sm font-semibold text-gray-900">Belum Ada Data Pendaftaran</h3>
                                    <p class="mt-1 text-xs text-gray-500">Klik tombol di bawah untuk mendaftarkan pasien baru.</p>
                                    <div class="mt-4">
                                        <a href="{{ route('admin.pendaftaran.create') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                                            + Tambah Pendaftaran Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(method_exists($pendaftaran, 'hasPages') && $pendaftaran->hasPages())
                <div class="border-t border-gray-100 bg-gray-50/50 px-6 py-4">
                    {{ $pendaftaran->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>