@extends('partial.master')

@section('title')
    Manajemen Order
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Manajemen Order
        </a>
    </li>
@endsection

@section('content')
<div class="card">
    <div class="row mb-2">
        <div class="col-md-6">
            <h4><i class="fas fa-clipboard-list me-2"></i>Manajemen Order</h4>
            <p class="text-muted">Daftar semua order jasa ketik, desain, dan percetakan</p>
        </div>
        <div class="col-md-6 text-end">
            <a class="btn btn-primary" href="{{ route('order.create') }}">Tambah Order Baru</a>
        </div>
    </div>

    <!-- Filter dan Pencarian -->
    <div class="card mb-4">
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0">
                <label for="searchInput" class="form-label">Cari Order</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInput" placeholder="Cari berdasarkan no. order/nama...">
                    <button class="btn btn-outline-primary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <label class="form-label">Filter Status</label>
                <div class="btn-group w-100">
                    <button type="button" class="btn btn-sm btn-outline-secondary filter-status active" data-status="all">Semua</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary filter-status" data-status="Menunggu">Menunggu</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary filter-status" data-status="Dikerjakan">Proses</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary filter-status" data-status="Selesai">Selesai</button>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Filter Layanan</label>
                <div class="btn-group w-100">
                    <button type="button" class="btn btn-sm btn-outline-secondary filter-service active" data-service="all">Semua</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary filter-service" data-service="Ketik">Ketik</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary filter-service" data-service="Desain">Desain</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary filter-service" data-service="Cetak">Cetak</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="section-title mb-0"><i class="fas fa-list me-2"></i>Daftar Order</h5>
                </div>
                <div class="col-md-6 text-end">
                    <div class="dropdown d-inline-block me-2">
                        <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-sort me-1"></i>Urutkan
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item sort-option" href="#" data-sort="deadline-asc">Deadline (Terdekat)</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="deadline-desc">Deadline (Terjauh)</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="date-desc">Terbaru</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="date-asc">Terlama</a></li>
                        </ul>
                    </div>
                    <span class="badge bg-light text-dark">
                        Total: 24 order
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="120">No. Order</th>
                            <th width="150">Tanggal</th>
                            <th>Pelanggan</th>
                            <th width="150">Layanan</th>
                            <th width="150">Deadline</th>
                            <th width="150">Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr class="order-row">
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="fw-bold">{{ $order->customer->name }}</div>
                                <div class="text-muted small">{{ implode(', ', $order->services) }}</div>
                            </td>
                            <td>
                                @foreach ($order->services as $svc)
                                    <span class="service-tag service-{{ strtolower($svc) }}">
                                        @if ($svc == 'Ketik') <i class="fas fa-keyboard me-1"></i> @endif
                                        @if ($svc == 'Desain') <i class="fas fa-palette me-1"></i> @endif
                                        @if ($svc == 'Cetak') <i class="fas fa-print me-1"></i> @endif
                                        {{ $svc }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                <div class="fw-bold">{{ $order->deadline->format('d M Y') }}</div>
                                @php
                                    $daysLeft = now()->diffInDays($order->deadline, false); // false agar bisa bernilai negatif jika sudah lewat
                                    $daysLeftRounded = round($daysLeft); // Membulatkan nilai hari
                                    if ($daysLeftRounded < 0) {
                                        $color = 'text-danger'; // lewat deadline
                                        $label = 'Terlambat ' . abs($daysLeftRounded) . ' hari';
                                    } elseif ($daysLeftRounded <= 2) {
                                        $color = 'text-warning'; // mendekati deadline
                                        $label = 'Sisa ' . $daysLeftRounded . ' hari';
                                    } else {
                                        $color = 'text-success'; // aman
                                        $label = 'Sisa ' . $daysLeftRounded . ' hari';
                                    }
                                @endphp

                                <small class="{{ $color }}">{{ $label }}</small>
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
                            <td>
                                <a href="{{ route('order.edit', $order) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('order.show', $order) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection