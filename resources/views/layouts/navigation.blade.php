<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start">
        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path></svg>
        </button>
        <a href="{{ route('dashboard') }}" class="flex ms-2 md:me-24">
            <x-application-logo />
        </a>
      </div>
      <div class="flex items-center">
          <div class="flex items-center ms-3">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white hover:text-emerald-600 focus:outline-none">
                        <div>{{ Auth::user()->name }}</div>
                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </div>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">Profil</x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Keluar</x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
          </div>
        </div>
    </div>
  </div>
</nav>

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
      <ul class="space-y-1.5 font-medium">
         
         {{-- MENU UNTUK PASIEN --}}
         @if(auth()->user()->role === 'pasien')
            <!-- Dashboard Pasien -->
            <li>
               <a href="{{ route('pasien.dashboard') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('pasien.dashboard') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                  <span class="ms-3">Dashboard</span>
               </a>
            </li>

            <!-- Pendaftaran Mandiri -->
            <li>
               <a href="{{ route('pasien.pendaftaran.create') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('pasien.pendaftaran.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                  <span class="ms-3">Pendaftaran Mandiri</span>
               </a>
            </li>

            <!-- Status Antrean -->
            <li>
               <a href="{{ route('pasien.antrean.index') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('pasien.antrean.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                  <span class="ms-3">Status Antrean</span>
               </a>
            </li>

            <!-- Riwayat Rekam Medis Pasien -->
            <li>
               <a href="{{ route('pasien.rekam-medis.index') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('pasien.rekam-medis.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                  <span class="ms-3">Riwayat Rekam Medis</span>
               </a>
            </li>

            <!-- Riwayat Pembayaran Pasien -->
            <li>
               <a href="{{ route('pasien.pembayaran.index') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('pasien.pembayaran.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 002-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                  <span class="ms-3">Riwayat Pembayaran</span>
               </a>
            </li>
         @endif

            {{-- MENU KHUSUS DOKTER --}}
            @if(auth()->user()->role === 'dokter')
            <li>
               <a href="{{ route('dokter.dashboard') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('dokter.dashboard') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                  <span class="ms-3">Dashboard</span>
               </a>
            </li>

            <li><a href="{{ route('dokter.pemeriksaan.index') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('dokter.pemeriksaan.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6m-7 4h8m-8 4h5m-6 5h8a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v11a2 2 0 002 2z"/></svg><span class="ms-3">Pemeriksaan Pasien</span></a></li>
            <li><a href="{{ route('dokter.rekam-medis.index') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('dokter.rekam-medis.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 6.707V19a2 2 0 01-2 2z"/></svg><span class="ms-3">Rekam Medis</span></a></li>
            <li><a href="{{ route('dokter.resep.index') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('dokter.resep.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="M7 3h10a2 2 0 012 2v14H5V5a2 2 0 012-2z"/></svg><span class="ms-3">Resep Obat</span></a></li>
            <li><a href="{{ route('dokter.jadwal.index') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('dokter.jadwal.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><span class="ms-3">Jadwal Saya</span></a></li>
         @endif

         {{-- MENU DASHBOARD KHUSUS ADMIN --}}
         @if(auth()->user()->role === 'admin')
            <!-- Dashboard Admin -->
            <li>
               <a href="{{ route('dashboard') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm8 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V4zM3 12a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1v-4zm8 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                  <span class="ms-3">Dashboard</span>
               </a>
            </li>
         @endif

         {{-- MENU KHUSUS ADMIN --}}
         @if(auth()->user()->role === 'admin')
            <!-- Data Tindakan -->
            <li>
               <a href="{{ route('admin.tindakan.index') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('admin.tindakan.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6m-7 4h8m-8 4h5m-6 5h8a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v11a2 2 0 002 2z"></path></svg>
                  <span class="ms-3">Data Tindakan</span>
               </a>
            </li>

            <!-- Data Obat -->
            <li>
               <a href="{{ route('admin.obat.index') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('admin.obat.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                  <span class="ms-3">Data Obat</span>
               </a>
            </li>

            <!-- Data Dokter -->
            <li>
               <a href="{{ route('admin.dokter.index') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('admin.dokter.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                  <span class="ms-3">Data Dokter</span>
               </a>
            </li>

            <!-- Jadwal Dokter -->
            <li>
               <a href="{{ route('admin.jadwal.index') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('admin.jadwal.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                  <span class="ms-3">Jadwal Dokter</span>
               </a>
            </li>

            <!-- Data Pasien -->
            <li>
               <a href="{{ route('admin.pasien.index') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('admin.pasien.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                  <span class="ms-3">Data Pasien</span>
               </a>
            </li>

            <!-- Data Pengguna -->
            <li>
               <a href="{{ route('admin.user.index') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('admin.user.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7zM19 8v6m3-3h-6"></path></svg>
                  <span class="ms-3">Data Pengguna</span>
               </a>
            </li>
         @endif

         {{-- MENU PEMERIKSAAN & OPERASIONAL (KHUSUS ADMIN) --}}
         @if(auth()->user()->role === 'admin')
            <!-- Pendaftaran Pasien -->
            <li>
               <a href="{{ route('admin.pendaftaran.index') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('admin.pendaftaran.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                  <span class="ms-3">Pendaftaran Pasien</span>
               </a>
            </li>

            <!-- Antrean Pasien -->
            <li>
               <a href="{{ route('admin.antrean.index') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('admin.antrean.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                  <span class="ms-3">Antrean Pasien</span>
               </a>
            </li>

            <!-- Pemeriksaan Pasien -->
            <li>
               <a href="{{ route('pemeriksaan.data') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('pemeriksaan.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                  <span class="ms-3">Pemeriksaan Pasien</span>
               </a>
            </li>

            <!-- Rekam Medis -->
            <li>
               <a href="{{ route('admin.rekam-medis.index') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('admin.rekam-medis.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                  <span class="ms-3">Rekam Medis</span>
               </a>
            </li>
         @endif

         {{-- KASIR DAN LAPORAN (KHUSUS ADMIN) --}}
         @if(auth()->user()->role === 'admin')
            <!-- Pembayaran / Kasir -->
            <li>
               <a href="{{ route('admin.pembayaran.index') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('admin.pembayaran.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 002-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                  <span class="ms-3">Pembayaran</span>
               </a>
            </li>

            <!-- Laporan Klinik -->
            <li>
               <a href="{{ route('admin.laporan.index') }}" class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 group {{ request()->routeIs('admin.laporan.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                  <svg class="w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                  <span class="ms-3">Laporan Klinik</span>
               </a>
            </li>
         @endif
      </ul>
   </div>
</aside>