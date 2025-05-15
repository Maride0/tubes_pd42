<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembelian Bahan Baku</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #444;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h2>Laporan Pembelian Bahan Baku</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Faktur</th>
                <th>Supplier</th>
                <th>Tanggal</th>
                <th>Metode</th>
                <th>Jatuh Tempo</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembelian as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->no_faktur }}</td>
                    <td>{{ $item->supplier->nama_supplier ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tgl_beli)->format('d-m-Y') }}</td>
                    <td>{{ ucfirst($item->metode_pembayaran) }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->jatuh_tempo)->format('d-m-Y') }}</td>
                    <td>{{ rupiah($item->total_beli) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
