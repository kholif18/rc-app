<!DOCTYPE html>
<html>
<head>
    <title>Laporan Hutang dan Pembayaran</title>
    <link rel="icon" type="image/x-icon" href="{{ $setting?->favicon ? asset('storage/favicon/' . $setting->favicon) : asset('favicon.png') }}" />

    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .period { margin-bottom: 20px; }
        .summary { margin-bottom: 30px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; }
        .table th { background-color: #f2f2f2; text-align: left; }
        .text-right { text-align: right; }
        .text-danger { color: #dc3545; }
        .text-success { color: #28a745; }
        .section-title { background-color: #f8f9fa; padding: 10px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Hutang dan Pembayaran</h2>
        <p>Periode: {{ $from }} s/d {{ $to }}</p>
    </div>

    <div class="summary">
        <table class="table">
            <tr>
                <th>Total Hutang</th>
                <td class="text-right text-danger">Rp {{ number_format($totalDebts, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Pembayaran</th>
                <td class="text-right text-success">Rp {{ number_format($totalPayments, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Sisa Hutang</th>
                <td class="text-right">Rp {{ number_format($totalDebts - $totalPayments, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">
        <h3>Detail Hutang</h3>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Customer</th>
                <th>Tanggal</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($debts as $index => $debt)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $debt->customer->name ?? '-' }}</td>
                <td>{{ $debt->date }}</td>
                <td class="text-right">Rp {{ number_format($debt->amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">
        <h3>Detail Pembayaran</h3>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Customer</th>
                <th>Tanggal</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $index => $payment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $payment->debt->customer->name ?? '-' }}</td>
                <td>{{ $payment->payment_date }}</td>
                <td class="text-right">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: right;">
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>