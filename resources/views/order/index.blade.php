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
    <form method="GET" action="{{ route('order.index') }}">
        <div class="card mb-4">
            <div class="row">
                <!-- Search -->
                <div class="col-md-4 mb-3 mb-md-0">
                    <label for="searchInput" class="form-label">Cari Order</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" id="searchInput"
                            placeholder="Cari berdasarkan no. order/nama..."
                            value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Filter Status -->
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label">Filter Status</label>
                    <input type="hidden" name="status" id="statusInput" value="{{ request('status', 'all') }}">
                    <div class="btn-group w-100">
                        @foreach(['all' => 'Semua', 'Menunggu' => 'Menunggu', 'Dikerjakan' => 'Proses', 'Selesai' => 'Selesai'] as $key => $label)
                            <button type="button"
                                    class="btn btn-sm btn-outline-secondary filter-status {{ request('status', 'all') == $key ? 'active' : '' }}"
                                    data-status="{{ $key }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Filter Layanan -->
                <div class="col-md-4">
                    <label class="form-label">Filter Layanan</label>
                    <input type="hidden" name="service" id="serviceInput" value="{{ request('service', 'all') }}">
                    <div class="btn-group w-100">
                        @foreach(['all' => 'Semua', 'Ketik' => 'Ketik', 'Desain' => 'Desain', 'Cetak' => 'Cetak'] as $key => $label)
                            <button type="button"
                                    class="btn btn-sm btn-outline-secondary filter-service {{ request('service', 'all') == $key ? 'active' : '' }}"
                                    data-service="{{ $key }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="section-title mb-0"><i class="fas fa-list me-2"></i>Daftar Order</h5>
                </div>
                <div class="col-md-6 text-end">
                    <div class="dropdown d-inline-block me-2">
                        <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-sort me-1"></i>Urutkan
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item sort-option {{ request('sort') == 'deadline-asc' ? 'active' : '' }}" href="{{ route('order.index', ['sort' => 'deadline-asc']) }}" data-sort="deadline-asc">Deadline (Terdekat)</a></li>
                            <li><a class="dropdown-item sort-option {{ request('sort') == 'deadline-desc' ? 'active' : '' }}" href="{{ route('order.index', ['sort' => 'deadline-desc']) }}" data-sort="deadline-desc">Deadline (Terjauh)</a></li>
                            <li><a class="dropdown-item sort-option {{ request('sort') == 'date-desc' ? 'active' : '' }}" href="{{ route('order.index', ['sort' => 'date-desc']) }}" data-sort="date-desc">Terbaru</a></li>
                            <li><a class="dropdown-item sort-option {{ request('sort') == 'date-assc' ? 'active' : '' }}" href="{{ route('order.index', ['sort' => 'date-asc']) }}" data-sort="date-asc">Terlama</a></li>
                        </ul>
                    </div>
                    <span class="badge bg-light text-dark">
                        Order remaining: {{ $progressTotal }}
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
                                    $statusFinal = ['Selesai', 'Diambil', 'Batal'];

                                    if (in_array($order->status, $statusFinal)) {
                                        // Jika status termasuk status akhir
                                        $color = 'text-muted';
                                        $label = $order->status;
                                    } else {
                                        // Hitung sisa hari
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
                    {{-- Tombol Previous --}}
                    <li class="page-item {{ $orders->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $orders->previousPageUrl() ?? '#' }}" tabindex="-1">Previous</a>
                    </li>

                    {{-- Tombol Angka Halaman --}}
                    @for ($i = 1; $i <= $orders->lastPage(); $i++)
                        <li class="page-item {{ $orders->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $orders->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    {{-- Tombol Next --}}
                    <li class="page-item {{ $orders->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $orders->nextPageUrl() ?? '#' }}">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.filter-status').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('statusInput').value = button.dataset.status;
            button.closest('form').submit();
        });
    });

    document.querySelectorAll('.filter-service').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('serviceInput').value = button.dataset.service;
            button.closest('form').submit();
        });
    });
</script>
@endpush
@endsection
