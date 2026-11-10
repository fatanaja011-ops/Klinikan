<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'KlinikCare') }} - Solusi Kesehatan Terpercaya</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-slate-50 text-slate-800 font-sans selection:bg-emerald-500 selection:text-white">

    <!-- Floating Navbar -->
    <header class="fixed top-4 inset-x-0 z-50 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white/90 backdrop-blur-md border border-slate-200/80 rounded-2xl shadow-lg shadow-slate-900/5 px-5 py-3 flex items-center justify-between transition-all">
            
            <!-- Logo Klinik -->
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-emerald-500 rounded-xl text-white shadow-md shadow-emerald-500/25 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"></path>
                    </svg>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="text-lg font-black tracking-wide text-emerald-600">LIFE <span class="text-amber-500">LINE</span></span>
                    <span class="text-[9px] font-bold tracking-widest text-slate-400 uppercase mt-0.5">Klinik Medika</span>
                </div>
            </div>

            <!-- Menu Navigasi -->
            <nav class="hidden md:flex items-center space-x-8 text-sm font-medium text-slate-600">
                <a href="#beranda" class="text-emerald-600 font-semibold relative py-1 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-emerald-500 after:rounded-full">Beranda</a>
                <a href="#layanan" class="hover:text-emerald-600 transition py-1">Layanan</a>
                <a href="#keunggulan" class="hover:text-emerald-600 transition py-1">Mengapa Kami</a>
            </nav>

            <!-- Tombol Auth -->
            <div class="flex items-center space-x-2 sm:space-x-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-semibold text-sm hover:bg-emerald-700 transition shadow-sm shadow-emerald-600/20">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-semibold text-slate-700 hover:text-emerald-600 transition">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-semibold text-sm hover:bg-emerald-700 transition shadow-md shadow-emerald-600/20">
                                Daftar
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

        </div>
    </header>

    <!-- Hero Section (Diberikan pt-36 / pt-44 agar tidak tertimpa Floating Navbar) -->
    <section id="beranda" class="relative overflow-hidden pt-36 pb-20 lg:pt-44 lg:pb-28 bg-gradient-to-b from-emerald-50/50 via-slate-50/30 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-12 gap-12 items-center">
            
            <!-- Kolom Teks Kiri -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-block">
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-100/80 text-emerald-800 border border-emerald-200/60 tracking-wider uppercase">
                        Total Health Care Solution
                    </span>
                </div>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.15]">
                    Layanan Kesehatan <br class="hidden sm:inline" />
                    <span class="bg-gradient-to-r from-emerald-600 via-teal-500 to-emerald-500 bg-clip-text text-transparent">Terbaik & Modern</span> <br class="hidden sm:inline" />
                    Untuk Keluarga
                </h1>
                
                <p class="text-slate-600 text-base sm:text-lg leading-relaxed max-w-2xl mx-auto lg:mx-0">
                    Kami memberikan pelayanan medis profesional, cepat, dan efisien dengan dukungan tim dokter spesialis berpengalaman.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3.5 pt-2">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-7 py-3.5 rounded-xl bg-emerald-600 text-white font-bold text-sm text-center hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/25">
                        Daftar Antrean Online
                    </a>
                    <a href="#layanan" class="w-full sm:w-auto px-7 py-3.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-sm text-center hover:bg-slate-50 transition shadow-sm">
                        Lihat Layanan Kami
                    </a>
                </div>
            </div>

            <!-- Kolom Widget/Kartu Kanan -->
            <div class="lg:col-span-5 flex justify-center">
                <div class="w-full max-w-md bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl shadow-slate-200/60 p-6 border border-slate-100/80 space-y-4">
                    
                    <!-- Kartu 1 -->
                    <div class="p-4 bg-emerald-50/80 border border-emerald-100 rounded-2xl flex items-center space-x-4">
                        <div class="w-12 h-12 bg-emerald-500 text-white rounded-xl flex items-center justify-center font-bold text-xl shadow-md shadow-emerald-500/20 shrink-0">
                            ⚡
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Pelayanan Antrean Cepat</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Booking jadwal konsultasi dari rumah</p>
                        </div>
                    </div>

                    <!-- Kartu 2 -->
                    <div class="p-4 bg-white border border-slate-100 rounded-2xl flex items-center justify-between shadow-sm">
                        <div class="flex items-center space-x-3.5">
                            <div class="p-2.5 bg-slate-50 rounded-xl text-emerald-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-sm text-slate-800">Pemeriksaan Umum</p>
                                <p class="text-xs text-slate-500">Dokter Spesialis & Umum</p>
                            </div>
                        </div>
                        <span class="text-[11px] font-bold text-emerald-700 bg-emerald-100/70 px-2.5 py-1 rounded-full">
                            Tersedia
                        </span>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- Section Layanan Utama -->
    <section id="layanan" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl tracking-tight">Layanan Utama</h2>
                <p class="mt-3 text-slate-600">Pilihan fasilitas kesehatan unggulan untuk kenyamanan Anda.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-white rounded-2xl border-2 border-emerald-400/80 p-8 text-center shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-emerald-500 rounded-xl mx-auto mb-5 flex items-center justify-center text-white text-xl shadow-md shadow-emerald-500/20">🩺</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Pemeriksaan Medis</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-5">Konsultasi lengkap bersama dokter ahli sesuai kebutuhan kesehatan Anda.</p>
                    <a href="{{ route('login') }}" class="text-emerald-600 font-bold text-sm hover:text-emerald-700 inline-flex items-center space-x-1">
                        <span>Selengkapnya</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-2xl border-2 border-amber-300 p-8 text-center shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-amber-400 rounded-xl mx-auto mb-5 flex items-center justify-center text-white text-xl shadow-md shadow-amber-400/20">🚨</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">UGD & Darurat</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-5">Penanganan pertama yang sigap dan responsif oleh tim medis siaga.</p>
                    <a href="{{ route('login') }}" class="text-amber-600 font-bold text-sm hover:text-amber-700 inline-flex items-center space-x-1">
                        <span>Selengkapnya</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-2xl border-2 border-emerald-400/80 p-8 text-center shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-emerald-500 rounded-xl mx-auto mb-5 flex items-center justify-center text-white text-xl shadow-md shadow-emerald-500/20">🦷</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Klinik Gigi & Umum</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-5">Perawatan kesehatan gigi, mulut, dan pemeriksaan tubuh secara berkala.</p>
                    <a href="{{ route('login') }}" class="text-emerald-600 font-bold text-sm hover:text-emerald-700 inline-flex items-center space-x-1">
                        <span>Selengkapnya</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

</body>
</html>