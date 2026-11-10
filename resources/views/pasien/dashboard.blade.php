<x-app-layout>
    <div class="p-6 space-y-6 max-w-7xl mx-auto">
        {{-- Banner / Header Welcome --}}
        <div class="bg-gradient-to-r from-emerald-600 to-teal-500 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full uppercase tracking-wider text-emerald-100">
                    Portal Pasien
                </span>
                <h2 class="text-2xl md:text-3xl font-bold mt-2">
                    Selamat Datang, {{ auth()->user()->name ?? 'Pasien' }}! 👋
                </h2>
                <p class="text-emerald-100 text-sm mt-1">
                    Kelola pendaftaran berobat dan pantau status antreanmu di sini.
                </p>
            </div>
            <a href="{{ route('pasien.pendaftaran.create') }}" class="px-5 py-3 text-sm font-bold text-emerald-700 bg-white hover:bg-emerald-50 rounded-xl shadow transition transform hover:-translate-y-0.5">
                + Buat Janji Berobat
            </a>
        </div>

        {{-- Section Status Antrean Aktif --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Card Nomor Antrean --}}
            <div class="md:col-span-1 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm text-center flex flex-col justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Antrean Hari Ini</span>
                    @if($antreanAktif)
                        <div class="my-4">
                            <span class="text-5xl font-black text-emerald-600 font-mono">
                                A-{{ sprintf('%03d', $antreanAktif->no_antrean) }}
                            </span>
                        </div>
                        <div class="space-y-1 text-xs text-gray-500">
                            <p class="font-semibold text-gray-800">{{ $antreanAktif->dokter->nama_dokter ?? '-' }}</p>
                            <p>{{ $antreanAktif->pendaftaran->jadwal->hari ?? '' }} | {{ $antreanAktif->pendaftaran->jadwal->jam_mulai ?? '' }} - {{ $antreanAktif->pendaftaran->jadwal->jam_selesai ?? '' }}</p>
                        </div>
                    @else
                        <div class="my-8 text-gray-400">
                            <p class="text-sm">Belum ada antrean aktif hari ini.</p>
                        </div>
                    @endif
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100">
                    @if($antreanAktif)
                        @php
                            $status = $antreanAktif->status;
                        @endphp
                        @if($status == 'Dipanggil')
                            <span class="px-3 py-1.5 text-xs font-bold text-blue-700 bg-blue-100 rounded-full animate-pulse inline-block">
                                📢 Giliran Kamu Dipanggil!
                            </span>
                        @elseif($status == 'Diperiksa')
                            <span class="px-3 py-1.5 text-xs font-bold text-purple-700 bg-purple-100 rounded-full inline-block">
                                🩺 Sedang Diperiksa Dokter
                            </span>
                        @else
                            <span class="px-3 py-1.5 text-xs font-bold text-amber-700 bg-amber-100 rounded-full inline-block">
                                ⏳ Menunggu Antrean
                            </span>
                        @endif
                    @else
                        <span class="px-3 py-1.5 text-xs font-medium text-gray-400 bg-gray-100 rounded-full inline-block">
                            Tidak Ada Kunjungan
                        </span>
                    @endif
                </div>
            </div>

            {{-- Card Ringkasan Aktivitas --}}
            <div class="md:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-base font-bold text-gray-800 mb-4">Informasi Rekam Medis & Profil</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <span class="text-xs text-gray-400 font-medium">Nomor Rekam Medis (RM)</span>
                            <p class="text-lg font-bold font-mono text-gray-800 mt-1">
                                {{ auth()->user()->pasien->no_rm ?? '-' }}
                            </p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <span class="text-xs text-gray-400 font-medium">Total Kunjungan</span>
                            <p class="text-lg font-bold text-gray-800 mt-1">
                                {{ $totalKunjungan ?? 0 }} Kali
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between text-xs text-gray-500 bg-emerald-50 p-3 rounded-xl border border-emerald-100">
                    <span>💡 Pastikan kamu datang 15 menit sebelum jam praktek dokter dimulai.</span>
                </div>
            </div>
        </div>

        {{-- Table Riwayat Pendaftaran Terakhir --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Riwayat Pendaftaran Terakhir</h3>
                <a href="{{ route('pasien.pendaftaran.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                    Lihat Semua →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3">NO. REGISTRASI</th>
                            <th class="px-6 py-3">DOKTER</th>
                            <th class="px-6 py-3">TGL KUNJUNGAN</th>
                            <th class="px-6 py-3">KELUHAN</th>
                            <th class="px-6 py-3 text-center">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($riwayatPendaftaran as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-mono font-bold text-emerald-600">
                                    {{ $item->no_pendaftaran }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $item->dokter->nama_dokter ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($item->tgl_kunjungan)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-gray-500 max-w-xs truncate">
                                    {{ $item->keluhan_awal ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php $status = $item->antrean->status ?? 'Menunggu'; @endphp
                                    @if($status == 'Selesai')
                                        <span class="px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-100 rounded-full">Selesai</span>
                                    @elseif($status == 'Batal')
                                        <span class="px-2.5 py-1 text-xs font-semibold text-rose-700 bg-rose-100 rounded-full">Batal</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold text-amber-700 bg-amber-100 rounded-full">Proses</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-gray-400">
                                    Belum ada riwayat pendaftaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>