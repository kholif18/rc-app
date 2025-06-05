@extends('partial.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
{{-- kosongkan breadcrumb di dashboard --}}
@endsection

@section('content')

    <div class="header">
        <h1 class="page-title">Dashboard</h1>
        <div class="text-end">
            <a class="btn btn-primary" href="{{ route('order.create') }}"><i class="fas fa-plus"></i> Tambah Order Baru</a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="card summary-card">
            <div class="summary-icon">
                <i class='bx bx-edit-alt service-ketik'></i>
            </div>
            <h3 class="card-title">Jasa Ketik</h3>
            <div class="card-value">{{ $typingToday }}</div>
            <div class="card-change {{ $typingChange >= 0 ? 'positive' : 'negative' }}">
                <i class='bx {{ $typingChange >= 0 ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}'></i>
                <span> {{ $typingChange >= 0 ? '+' : '' }}{{ $typingChange }} dari kemarin</span>
            </div>
        </div>
        
        <div class="card summary-card">
            <div class="summary-icon">
                <i class='bx bx-palette summary-icon service-desain'></i>
            </div>
            <h3 class="card-title">Jasa Desain</h3>
            <div class="card-value">{{ $designToday }}</div>
            <div class="card-change {{ $designChange >= 0 ? 'positive' : 'negative' }}">
                <i class='bx {{ $typingChange >= 0 ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}'></i>
                <span> {{ $designChange >= 0 ? '+' : '' }}{{ $designChange }} dari kemarin</span>
            </div>
        </div>
        
        <div class="card summary-card">
            <div class="summary-icon">
                <i class='bx bx-printer summary-icon service-cetak'></i>
            </div>
            <h3 class="card-title">Percetakan</h3>
            <div class="card-value">{{ $printToday }}</div>
            <div class="card-change {{ $printChange >= 0 ? 'positive' : 'negative' }}">
                <i class='bx {{ $typingChange >= 0 ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}'></i>
                <span> {{ $printChange >= 0 ? '+' : '' }}{{ $printChange }} dari kemarin</span>
            </div>
        </div>
        
        <div class="card summary-card">
            <div class="summary-icon">
                <i class='bx bx-money summary-icon hutang'></i>
            </div>
            <h3 class="card-title">Sisa Hutang</h3>
            <div class="card-value">Rp {{ number_format($remainingDebt, 0, ',', '.') }}</div>
            <div class="card-change {{ $customerDebtDifference >= 0 ? 'positive' : 'negative' }}">
                <i class='bx  {{ $customerDebtDifference >= 0 ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}'></i>
                <span>{{ $customerDebtDifference >= 0 ? '+' : '' }}{{ $customerDebtDifference }} Customers</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="card mb-3">
                <h4 class="card-header d-flex justify-content-between">
                    Daftar Order Terakhir
                    <a href="{{ route('order.index') }}" class="see-all">Lihat Semua</a>
                </h4>
                
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>NO. ORDER</th>
                            <th>PELANGGAN</th>
                            <th>LAYANAN</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestOrders as $order)
                            <tr class="clickable-row" data-href="{{ route('order.show', $order->id) }}">
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->customer->name ?? '-' }}</td>
                                <td>@foreach ($order->services as $svc)
                                        <span class="service-tag service-{{ strtolower($svc) }}">
                                            @if ($svc == 'Ketik') <i class="fas fa-keyboard me-1"></i> @endif
                                            @if ($svc == 'Desain') <i class="fas fa-palette me-1"></i> @endif
                                            @if ($svc == 'Cetak') <i class="fas fa-print me-1"></i> @endif
                                            {{ $svc }}
                                        </span>
                                    @endforeach
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($order->status) {
                                            'Menunggu' => 'badge bg-secondary',
                                            'Dikerjakan' => 'badge bg-warning text-dark',
                                            'Selesai' => 'badge bg-success',
                                            'Diambil' => 'badge bg-primary',
                                            'Batal' => 'badge bg-danger',
                                            default => 'badge bg-light text-dark',
                                        };
                                        $statusIcon = match($order->status) {
                                            'Menunggu' => 'fas fa-clock',
                                            'Dikerjakan' => 'fas fa-spinner',
                                            'Selesai' => 'fas fa-check-circle',
                                            'Diambil' => 'fas fa-motorcycle',
                                            'Batal' => 'fas fa-times',
                                            default => '',
                                        };
                                    @endphp
                                    <span class="{{ $statusClass }}">
                                        @if ($statusIcon)
                                            <i class="{{ $statusIcon }} me-1"></i>
                                        @endif
                                        {{ $order->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada order terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-6">
            <div class="card mb-3">
                <h4 class="card-header d-flex justify-content-between">
                    Order yang Terlambat
                    <a href="{{ route('order.index') }}" class="see-all">Lihat Semua</a>
                </h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>NO. ORDER</th>
                            <th>PELANGGAN</th>
                            <th>LAYANAN</th>
                            <th>DEADLINE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lateOrders as $order)
                            @php
                                $deadline = \Carbon\Carbon::parse($order->deadline)->startOfDay();
                                $today = \Carbon\Carbon::today();
                                $isLate = $deadline->lt($today);
                                $deadlineDiff = $today->diffInDays($deadline, false); // minus jika sudah lewat
                            @endphp
                            <tr onclick="window.location='{{ route('order.show', $order->id) }}'" style="cursor:pointer;">
                                <td class="{{ $isLate ? 'urgent' : '' }}">{{ $order->id }}</td>
                                <td>{{ $order->customer->name }}</td>
                                <td>@foreach ($order->services as $svc)
                                        <span class="service-tag service-{{ strtolower($svc) }}">
                                            @if ($svc == 'Ketik') <i class="fas fa-keyboard me-1"></i> @endif
                                            @if ($svc == 'Desain') <i class="fas fa-palette me-1"></i> @endif
                                            @if ($svc == 'Cetak') <i class="fas fa-print me-1"></i> @endif
                                            {{ $svc }}
                                        </span>
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge {{ $isLate ? 'bg-danger' : 'bg-warning text-dark' }}">
                                    <i class="fas fa-calendar-times me-1"></i>
                                    {{ $isLate ? abs($deadlineDiff) . ' HARI TERLAMBAT' : 'SISA ' . $deadlineDiff . ' HARI' }}
                                </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Tidak ada order yang terlambat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <h4 class="card-header">Daftar Pelanggan dengan Hutang Aktif</h4>
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


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rows = document.querySelectorAll('.clickable-row');

        rows.forEach(row => {
            row.addEventListener('click', function () {
                window.location = this.dataset.href;
            });
        });
    });
</script>
@endsection


