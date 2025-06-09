<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Produksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>Daftar Produksi</h2>
    <table>
        <thead>
            <tr>
                <th>Kode Produksi</th>
                <th>Nama Karyawan</th>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produksi as $p)
            <tr>
                <td>{{ $p->kode_produksi }}</td>
                <td>{{ optional($p->karyawan)->nama }}</td>
                <td>{{ optional($p->menu)->nama_menu }}</td>
                <td>{{ $p->jumlah }}</td>
                <td>{{ $p->tgl_produksi->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
