@extends('partial.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
{{-- kosongkan breadcrumb di dashboard --}}
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-none border border-primary mb-3">
        <div class="card-header">Total Hutang</div>
        <div class="card-body">
            <h2 class="card-title text-primary">Rp {{ number_format($totalDebt, 0, ',', '.') }}</h2>
        </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-none border border-success mb-3">
        <div class="card-header">Total Pembayaran</div>
        <div class="card-body">
            <h2 class="card-title text-success">Rp {{ number_format($totalPaid, 0, ',', '.') }}</h2>
        </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-none border border-danger mb-3">
        <div class="card-header">Sisa Hutang</div>
        <div class="card-body">
            <h2 class="card-title text-danger">Rp {{ number_format($remainingDebt, 0, ',', '.') }}</h2>
        </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-none border border-info mb-3">
        <div class="card-header">Jumlah Pelanggan</div>
        <div class="card-body">
            <h2 class="card-title text-info">{{ $customerCount }}</h2>
        </div>
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-header">Daftar Pelanggan dengan Hutang Aktif</div>
    <div class="card-body">
        <div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Transaksi</th>
                        <th>Nama Pelanggan</th>
                        <th>Total Hutang</th>
                        <th>Total Pembayaran</th>
                        <th>Sisa Hutang</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paginatedCustomers as $customer)
                        @php
                            $totalDebt = $customer->debts->sum('amount');
                            $totalPaid = $customer->debts->flatMap->payments->sum('amount');
                            $remaining = $totalDebt - $totalPaid;

                            $lastDebtDate = $customer->debts->max('created_at');
                            $lastPaymentDate = $customer->debts->flatMap->payments->max('payment_date');
                            $lastTransactionDate = $lastDebtDate && $lastPaymentDate
                                ? ($lastDebtDate > $lastPaymentDate ? $lastDebtDate : $lastPaymentDate)
                                : ($lastDebtDate ?? $lastPaymentDate);
                        @endphp
                        <tr>
                            <td>{{ $lastTransactionDate ? $lastTransactionDate->format('d M Y H:i') : '-' }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>Rp {{ number_format($totalDebt, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($totalPaid, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($remaining, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('payments.create', ['debt_id' => $customer->debts->last()?->id]) }}" class="btn btn-sm btn-warning">Bayar Hutang</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada pelanggan dengan hutang aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Paginasi --}}
            @php
                $currentPage = $paginatedCustomers->currentPage();
                $lastPage = $paginatedCustomers->lastPage();
            @endphp

            @if ($lastPage > 1)
                <nav>
                    <ul class="pagination justify-content-center">
                        {{-- Tombol Previous --}}
                        <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $paginatedCustomers->url($currentPage - 1) }}" aria-label="Previous">
                                <i class="tf-icon bx bx-chevron-left"></i>
                            </a>
                        </li>

                        {{-- Nomor Halaman --}}
                        @for ($page = 1; $page <= $lastPage; $page++)
                            <li class="page-item {{ $currentPage == $page ? 'active' : '' }}">
                                <a class="page-link" href="{{ $paginatedCustomers->url($page) }}">{{ $page }}</a>
                            </li>
                        @endfor

                        {{-- Tombol Next --}}
                        <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $paginatedCustomers->url($currentPage + 1) }}" aria-label="Next">
                                <i class="tf-icon bx bx-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            @endif
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Riwayat Transaksi Terbaru</div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Tipe</th>
                    <th>Nama Pelanggan</th>
                    <th>Jumlah</th>
                    <th>Catatan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentActivities as $activity)
                    <tr>
                        <td>
                            @if($activity->type === 'debt')
                                <span class="badge bg-danger">Hutang</span>
                            @else
                                <span class="badge bg-success">Pembayaran</span>
                            @endif
                        </td>
                        <td>{{ $activity->customer_name }}</td>
                        <td>Rp {{ number_format($activity->amount, 0, ',', '.') }}</td>
                        <td>{{ $activity->note ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($activity->date)->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection


