<x-guest-layout>
    <!-- Session Status (Notifikasi jika ada pesan sukses/reset password) -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Header Form -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-slate-800">Selamat Datang Kembali</h2>
        <p class="text-xs text-slate-500 mt-1">Silakan masuk ke akun Anda untuk melanjutkan</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" class="text-slate-700 font-semibold text-xs uppercase tracking-wider" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <x-text-input id="email" class="block w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
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

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-xs pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500 cursor-pointer" name="remember">
                <span class="ms-2 text-slate-600 font-medium">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-slate-500 hover:text-emerald-600 font-medium transition" href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <!-- Tombol Masuk Full Width -->
        <div class="pt-2">
            <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 text-white font-bold text-sm hover:bg-emerald-700 active:bg-emerald-800 transition shadow-lg shadow-emerald-600/25">
                Masuk
            </button>
        </div>

        <!-- Opsi Daftar Akun -->
        @if (Route::has('register'))
            <div class="text-center pt-4 mt-2 border-t border-slate-100">
                <p class="text-xs text-slate-500">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:text-emerald-700 transition ms-1">
                        Daftar Sekarang
                    </a>
                </p>
            </div>
        @endif
    </form>
</x-guest-layout>