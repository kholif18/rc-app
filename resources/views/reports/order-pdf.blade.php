<!DOCTYPE html>
<html>
<head>
    <title>Laporan Order</title>
    <link rel="icon" type="image/x-icon" href="{{ $setting?->favicon ? asset('storage/favicon/' . $setting->favicon) : asset('favicon.png') }}" />
    <style>
        .summary { margin-bottom: 30px; }
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

    <div class="summary">
        <table class="table">
            <tr>
                <th>Total Pesanan</th>
                <td class="text-right text-danger">{{ $totalOrders }}</td>
            </tr>
            <tr>
                <th>Selesai + Diambil</th>
                <td class="text-right text-success">{{ $completedOrders }}</td>
            </tr>
            <tr>
                <th>Proses</th>
                <td class="text-right">{{ $processingOrders }}</td>
            </tr>
            <tr>
                <th>Batal</th>
                <td class="text-right">{{ $canceledOrders }}</td>
            </tr>
        </table>
    </div>

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
