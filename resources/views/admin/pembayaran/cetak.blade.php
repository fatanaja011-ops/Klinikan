<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk {{ $pembayaran->no_pembayaran }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 text-gray-800">
    <main class="mx-auto max-w-xl rounded-xl bg-white p-6 shadow-sm print:shadow-none">
        <div class="border-b border-dashed border-gray-300 pb-4 text-center">
            <h1 class="text-xl font-bold text-emerald-700">LIFE LINE</h1>
            <p class="text-xs text-gray-500">Klinik Medika</p>
            <p class="mt-3 font-mono text-sm">{{ $pembayaran->no_pembayaran }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4 border-b border-dashed border-gray-300 py-4 text-sm">
            <div><p class="text-gray-500">Pasien</p><p class="font-semibold">{{ $pembayaran->pemeriksaan->pasien->nama ?? '-' }}</p></div>
            <div><p class="text-gray-500">Dokter</p><p class="font-semibold">{{ $pembayaran->pemeriksaan->dokter->nama_dokter ?? '-' }}</p></div>
            <div><p class="text-gray-500">Tanggal</p><p class="font-semibold">{{ optional($pembayaran->tgl_pembayaran)->format('d/m/Y') ?? '-' }}</p></div>
            <div><p class="text-gray-500">Status</p><p class="font-semibold text-emerald-600">{{ $pembayaran->status_pembayaran }}</p></div>
        </div>

        <div class="space-y-3 py-4 text-sm">
            @foreach($pembayaran->detailPembayaran as $detail)
                <div class="flex justify-between gap-4">
                    <span>{{ $detail->nama_pelayanan }} x{{ $detail->jumlah }}</span>
                    <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="flex justify-between border-t border-gray-300 pt-4 text-lg font-bold">
            <span>Total</span>
            <span class="text-emerald-700">Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}</span>
        </div>

        <div class="mt-6 flex justify-center print:hidden">
            <button onclick="window.print()" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Cetak Struk</button>
        </div>
    </main>
</body>
</html>
