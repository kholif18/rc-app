<!DOCTYPE html>
<html>
<head>
    <title>Laporan Order</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        table, th, td {
            border: 1px solid black;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h3>Laporan Order</h3>
    <p>Periode: {{ $from }} s.d {{ $to }}</p>
    <table>
        <thead>
            <tr>
                <th>ID Order</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Jenis Layanan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                <td>{{ $order->customer->name ?? '-' }}</td>
                <td>{{ implode(', ', $order->services ?? []) }}</td>
                <td>{{ $order->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
