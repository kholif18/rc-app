<table>
    <tr>
        <td colspan="2"><strong>Laporan Hutang dan Pembayaran</strong></td>
    </tr>
    <tr>
        <td colspan="2">Periode: {{ $from }} s/d {{ $to }}</td>
    </tr>
</table>

<table>
    <tr>
        <th>Total Hutang</th>
        <td>Rp {{ number_format($totalDebts, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <th>Total Pembayaran</th>
        <td>Rp {{ number_format($totalPayments, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <th>Sisa Hutang</th>
        <td>Rp {{ number_format($totalDebts - $totalPayments, 0, ',', '.') }}</td>
    </tr>
</table>

<br><br>

<table>
    <tr><th colspan="4">Detail Hutang</th></tr>
    <tr>
        <th>No</th>
        <th>Nama Customer</th>
        <th>Tanggal</th>
        <th>Jumlah</th>
    </tr>
    @foreach($debts as $index => $debt)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $debt->customer->name ?? '-' }}</td>
        <td>{{ $debt->debt_date }}</td>
        <td>Rp {{ number_format($debt->amount, 0, ',', '.') }}</td>
    </tr>
    @endforeach
</table>

<br><br>

<table>
    <tr><th colspan="4">Detail Pembayaran</th></tr>
    <tr>
        <th>No</th>
        <th>Nama Customer</th>
        <th>Tanggal</th>
        <th>Jumlah</th>
    </tr>
    @foreach($payments as $index => $payment)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $payment->debt->customer->name ?? '-' }}</td>
        <td>{{ $payment->payment_date }}</td>
        <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
    </tr>
    @endforeach
</table>
