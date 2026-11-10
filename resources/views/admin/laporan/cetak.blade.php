<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Klinik</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2>KLINIK MEDIKA LIFE LINE</h2>
        <p>Laporan {{ ucfirst($jenis_laporan) }} (Periode: {{ $tgl_mulai }} s/d {{ $tgl_selesai }})</p>
    </div>

    @if($jenis_laporan == 'pembayaran')
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Trx</th>
                <th>Pasien</th>
                <th>Tanggal Bayar</th>
                <th>Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->no_pembayaran }}</td>
                <td>{{ $item->pemeriksaan->pasien->nama ?? '-' }}</td>
                <td>{{ optional($item->tgl_pembayaran)->format('d/m/Y') ?? $item->created_at->format('d/m/Y') }}</td>
                <td>Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pasien</th>
                <th>Dokter</th>
                <th>Diagnosa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->tanggal_periksa }}</td>
                <td>{{ $item->pasien->nama ?? '-' }}</td>
                <td>{{ $item->dokter->nama ?? '-' }}</td>
                <td>{{ $item->diagnosa }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</body>
</html>