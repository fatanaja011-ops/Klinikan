<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 p-6 text-white shadow-lg">
            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-100">Portal Dokter</p>
            <h1 class="mt-2 text-3xl font-bold">Selamat datang, {{ $dokter->nama_dokter ?? auth()->user()->name }}</h1>
            <p class="mt-1 text-sm text-emerald-100">Pantau jadwal dan pasien yang perlu ditangani hari ini.</p>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Pasien hari ini</p><p class="mt-2 text-3xl font-bold text-gray-900">{{ $jumlahPasienHariIni }}</p></div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Pemeriksaan selesai</p><p class="mt-2 text-3xl font-bold text-emerald-600">{{ $jumlahSelesai }}</p></div>
        </div>
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm"><div class="border-b border-gray-100 px-5 py-4"><h2 class="font-semibold text-gray-900">Pasien yang Perlu Diperiksa</h2></div><table class="min-w-full text-left text-sm"><thead class="bg-gray-50"><tr><th class="px-5 py-3">Antrean</th><th class="px-5 py-3">Pasien</th><th class="px-5 py-3">Keluhan</th><th class="px-5 py-3">Status</th></tr></thead><tbody class="divide-y divide-gray-100">@forelse($pasienHariIni as $item)<tr><td class="px-5 py-4 font-mono text-emerald-700">{{ $item->no_antrean }}</td><td class="px-5 py-4 font-semibold">{{ $item->pasien->nama ?? '-' }}</td><td class="px-5 py-4">{{ $item->keluhan }}</td><td class="px-5 py-4">{{ $item->status }}</td></tr>@empty<tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">Belum ada pasien hari ini.</td></tr>@endforelse</tbody></table></div>
    </div>
</x-app-layout>
