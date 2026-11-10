<x-app-layout>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header & Welcome Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-600 to-teal-700 p-6 sm:p-8 text-white shadow-xl">
            <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
                <svg class="w-96 h-96" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                </svg>
            </div>
            
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-xs font-medium text-emerald-100 border border-white/20">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Sistem Manajemen Klinik Active</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">
                        Selamat Datang Kembali, {{ Auth::user()->name }}!
                    </h1>
                    <p class="text-emerald-100 text-sm max-w-xl">
                        Pantau seluruh aktivitas operasional klinik, antrean pasien, dan riwayat pemeriksaan secara real-time dari panel kontrol ini.
                    </p>
                </div>
                
                <div class="flex-shrink-0">
                    <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-md px-4 py-3 rounded-2xl border border-white/20">
                        <div class="w-10 h-10 rounded-xl bg-white text-emerald-700 flex items-center justify-center font-bold text-lg">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="text-left">
                            <div class="text-xs text-emerald-200">Akses Hak User</div>
                            <div class="text-sm font-semibold capitalize">{{ Auth::user()->role ?? 'Petugas' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Stat 1: Total Pasien -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Pasien</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalPasien ?? 0) }}</h3>
                        <p class="text-xs text-emerald-600 font-medium mt-1 flex items-center gap-1">
                            <span>Terdaftar di sistem</span>
                        </p>
                    </div>
                    <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Stat 2: Antrean Hari Ini -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Antrean Hari Ini</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $antreanHariIni ?? 0 }}</h3>
                        <p class="text-xs text-amber-600 font-medium mt-1">{{ $antreanMenunggu ?? 0 }} Sedang Menunggu</p>
                    </div>
                    <div class="p-3 rounded-xl bg-amber-50 text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Stat 3: Pemeriksaan Selesai -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Pemeriksaan Selesai</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $pemeriksaanSelesai ?? 0 }}</h3>
                        <p class="text-xs text-emerald-600 font-medium mt-1">Selesai Ditangani Hari Ini</p>
                    </div>
                    <div class="p-3 rounded-xl bg-blue-50 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Stat 4: Dokter Bertugas -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Dokter Bertugas</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $dokterBertugas ?? 0 }}</h3>
                        <p class="text-xs text-gray-500 font-medium mt-1">Aktif di Poli</p>
                    </div>
                    <div class="p-3 rounded-xl bg-purple-50 text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access Menu -->
        <div class="space-y-4">
            <h2 class="text-lg font-bold text-gray-900">Akses Cepat Layanan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                
                <a href="{{ route('pemeriksaan.data') }}" class="group bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm hover:border-emerald-500 hover:shadow-md transition-all duration-200 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 group-hover:text-emerald-600 transition-colors">Data Pemeriksaan</h3>
                            <p class="text-xs text-gray-500">Lihat & catat rekam medis pasien</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>

                <a href="#" class="group bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm hover:border-blue-500 hover:shadow-md transition-all duration-200 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Kelola Antrean</h3>
                            <p class="text-xs text-gray-500">Atur nomor antrean pasien</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>

                <a href="#" class="group bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm hover:border-purple-500 hover:shadow-md transition-all duration-200 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 rounded-xl bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">Pembayaran</h3>
                            <p class="text-xs text-gray-500">Proses transaksi kasir</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>

            </div>
        </div>

    </div>
</x-app-layout>