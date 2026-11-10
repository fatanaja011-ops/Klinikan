<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Midtrans Snap JS -->
        <script type="text/javascript"
          src="https://app.sandbox.midtrans.com/snap/snap.js"
          data-client-key="{{ config('services.midtrans.client_key') }}"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50">
            <!-- Sidebar dan Navbar dipanggil di sini -->
            @include('layouts.navigation')

            <!-- Pembungkus Konten Utama (Di-geser ke kanan dengan sm:ml-64 agar tidak tertutup sidebar) -->
            <div class="p-4 sm:ml-64 pt-20">
                <!-- Page Heading -->
                @isset($header)
                    <header class="mb-4">
                        <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>