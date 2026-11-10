<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased bg-gradient-to-br from-emerald-50/60 via-slate-50 to-teal-50/40 min-h-screen flex flex-col justify-center items-center py-10 px-4">
        <div class="w-full sm:max-w-md">
            
            <!-- Logo Klinik -->
            <div class="flex justify-center mb-6">
                <a href="/" class="flex items-center space-x-3 group">
                    <div class="p-2.5 bg-emerald-500 rounded-xl text-white shadow-md shadow-emerald-500/30 group-hover:scale-105 transition duration-200">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"></path>
                        </svg>
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="text-2xl font-black tracking-wide text-emerald-600">LIFE <span class="text-amber-500">LINE</span></span>
                        <span class="text-[10px] font-bold tracking-widest text-slate-400 uppercase mt-0.5">Klinik Medika</span>
                    </div>
                </a>
            </div>

            <!-- Card Form -->
            <div class="bg-white/90 backdrop-blur-sm border border-slate-100 shadow-xl shadow-slate-200/60 rounded-3xl p-8">
                {{ $slot }}
            </div>

        </div>
    </body>
</html>