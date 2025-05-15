<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; } 
    </style>
</head>
<body>
    <h2>Rekap Penjualan</h2>
    <p>Periode: {{ \Carbon\Carbon::parse($tanggal_awal)->format('d M Y') }} -{{ \Carbon\Carbon::parse($tanggal_akhir)->format('d M Y') }}</p>
    <p>Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>No Penjualan</th>
                <th>Nama Pembeli</th>
                <th>Status Member</th>
                <th>Total Tagihan</th>
                <th>Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan as $p)
            <tr>
                <td>{{ $p->no_penjualan }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->id_member ? 'Member' : 'Non-member' }}</td>
                <td class="text-right">{{ rupiah($p->tagihan) }}</td>
                <td>{{ $p->tanggal }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>