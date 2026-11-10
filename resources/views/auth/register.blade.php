<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-slate-800">Buat Akun Baru</h2>
        <p class="text-xs text-slate-500 mt-1">Lengkapi data untuk mengakses layanan klinik</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nama Lengkap" class="text-slate-700 font-semibold text-xs uppercase tracking-wider" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <x-text-input id="name" class="block w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" type="text" name="name" :value="old('name')" required autofocus placeholder="Masukkan nama lengkap" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" class="text-slate-700 font-semibold text-xs uppercase tracking-wider" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <x-text-input id="email" class="block w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" type="email" name="email" :value="old('email')" required placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- NIK -->
        <div>
            <x-input-label for="nik" value="NIK (Nomor Induk Kependudukan)" class="text-slate-700 font-semibold text-xs uppercase tracking-wider" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0h4"></path></svg>
                </div>
                <x-text-input id="nik" class="block w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" type="text" name="nik" :value="old('nik')" required maxlength="16" placeholder="16 digit NIK" />
            </div>
            <x-input-error :messages="$errors->get('nik')" class="mt-1" />
        </div>

        <!-- Grid 2 Kolom: Jenis Kelamin & Tanggal Lahir -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Jenis Kelamin -->
            <div>
                <x-input-label for="jenis_kelamin" value="Jenis Kelamin" class="text-slate-700 font-semibold text-xs uppercase tracking-wider" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="block w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm text-slate-700 bg-white" required>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-1" />
            </div>

            <!-- Tanggal Lahir -->
            <div>
                <x-input-label for="tanggal_lahir" value="Tanggal Lahir" class="text-slate-700 font-semibold text-xs uppercase tracking-wider" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <x-text-input id="tanggal_lahir" class="block w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" type="date" name="tanggal_lahir" :value="old('tanggal_lahir')" required />
                </div>
                <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-1" />
            </div>
        </div>

        <!-- No Telepon -->
        <div>
            <x-input-label for="no_telepon" value="No. Telepon / WhatsApp" class="text-slate-700 font-semibold text-xs uppercase tracking-wider" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                </div>
                <x-text-input id="no_telepon" class="block w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" type="text" name="no_telepon" :value="old('no_telepon')" required placeholder="08xxxxxxxxxx" />
            </div>
            <x-input-error :messages="$errors->get('no_telepon')" class="mt-1" />
        </div>

        <!-- Alamat -->
        <div>
            <x-input-label for="alamat" value="Alamat Lengkap" class="text-slate-700 font-semibold text-xs uppercase tracking-wider" />
            <div class="relative mt-1">
                <div class="absolute top-3 left-0 pl-3.5 flex items-start pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <textarea id="alamat" name="alamat" rows="2" class="block w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm text-slate-700" required placeholder="Masukkan alamat domisili lengkap">{{ old('alamat') }}</textarea>
            </div>
            <x-input-error :messages="$errors->get('alamat')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Kata Sandi" class="text-slate-700 font-semibold text-xs uppercase tracking-wider" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <x-text-input id="password" class="block w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" type="password" name="password" required placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" class="text-slate-700 font-semibold text-xs uppercase tracking-wider" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <x-text-input id="password_confirmation" class="block w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" type="password" name="password_confirmation" required placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex items-center justify-between pt-4">
            <a class="text-xs text-slate-500 hover:text-emerald-600 font-medium transition" href="{{ route('login') }}">
                Sudah punya akun?
            </a>

            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-sm hover:bg-emerald-700 active:bg-emerald-800 transition shadow-lg shadow-emerald-600/25">
                Daftar
            </button>
        </div>
    </form>
</x-guest-layout>