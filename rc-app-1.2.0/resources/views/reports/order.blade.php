@extends('partial.master')

@section('title')
    Laporan Order
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Laporan Order
        </a>
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-title d-flex justify-content-between">
            <h5 class="mt-3">Laporan Order</h5>
            <div class="mb-1 text-end">
                <div class="mt-2 btn-group">
                    <div class="d-flex justify-content-between">
                        <a class="btn btn-outline-primary ms-2" href="{{ route('reports.order.export', ['format' => 'pdf', 'from' => $from, 'to' => $to]) }}" target="_blank"><i class="bx bxs-file-pdf"></i> PDF</a>
                        <a class="btn btn-outline-primary ms-2" href="{{ route('reports.order.export', ['format' => 'excel', 'from' => $from, 'to' => $to]) }}"><i class="bx bxs-file-export"></i> Excel</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.order') }}" class="mb-4 g-3 align-items-end">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Dari Tanggal:</label>
                        <input type="date" name="from" value="{{ $from }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sampai Tanggal:</label>
                        <input type="date" name="to" value="{{ $to }}" max="{{ date('Y-m-d') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jenis Layanan</label>
                        <select name="services" class="form-select">
                            <option value="" {{ request('services') == '' ? 'selected' : '' }}>Semua Jenis</option>
                            <option value="Ketik" {{ request('services') == 'Ketik' ? 'selected' : '' }}>Ketik</option>
                            <option value="Desain" {{ request('services') == 'Desain' ? 'selected' : '' }}>Desian</option>
                            <option value="Cetak" {{ request('services') == 'Cetak' ? 'selected' : '' }}>Percetakan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Status</option>
                            <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Dikerjakan" {{ request('status') == 'Dikerjakan' ? 'selected' : '' }}>Dikerjakan</option>
                            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Diambil" {{ request('status') == 'Diambil' ? 'selected' : '' }}>Diambil</option>
                            <option value="Batal" {{ request('status') == 'Batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('reports.order') }}" class="btn btn-secondary">
                            <i class="fas fa-sync-alt"></i> Reset
                        </a>
                    </div>
                </div>
                
            </form>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center" style="background-color: #e3f2fd;">
                        <div class="card-title">Total Pesanan</div>
                        <div class="card-value">{{ $totalOrders }}</div>
                        <div><i class="fas fa-file-text" style="font-size: 2rem; color: #2196f3;"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center" style="background-color: #e8f5e9;">
                        <div class="card-title">Pesanan Selesai</div>
                        <div class="card-value">{{ $completedOrders }}</div>
                        <div><i class="fas fa-check-circle" style="font-size: 2rem; color: #4caf50;"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center" style="background-color: #fff3e0;">
                        <div class="card-title">Pesanan Proses</div>
                        <div class="card-value">{{ $processingOrders }}</div>
                        <div><i class="fas fa-hourglass" style="font-size: 2rem; color: #ff9800;"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center" style="background-color: #ffebee;">
                        <div class="card-title">Pesanan Batal</div>
                        <div class="card-value">{{ $canceledOrders }}</div>
                        <div><i class="fas fa-close" style="font-size: 2rem; color: #f44336;"></i></div>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="report-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-list-ul"></i> Daftar Order</span>
                    <div class="no-print">
                        <span class="me-3">Total Order: <strong>{{ $totalOrders }}</strong></span>
                        <a href="{{ route('reports.order') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Refresh
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID Order</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Jenis Layanan</th>
                                    <th>Detail Produk</th>
                                    <th>Status</th>
                                    <th class="no-print">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $index => $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>{{ $order->customer->name }}</td>
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
                                        @php
                                            $details = [];

                                            if (in_array('Ketik', $order->services)) {
                                                $details[] = $order->page_count . ' halaman';
                                                $details[] = $order->doc_type;
                                            }

                                            if (in_array('Desain', $order->services)) {
                                                $details[] = $order->design_type;
                                                $details[] = $order->design_size;
                                            }

                                            if (in_array('Cetak', $order->services)) {
                                                $details[] = $order->print_type;
                                                $details[] = $order->print_quantity . ' lembar';
                                                $details[] = optional($order->bahanCetak)->nama_bahan ?? '-';
                                            }
                                        @endphp

                                        {{ \Illuminate\Support\Str::limit(implode(', ', array_filter($details)), 20) }}
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
                                        <a href="{{ route('order.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mb-0">
                            {{-- Tombol Previous --}}
                            @if ($orders->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $orders->previousPageUrl() }}{{ http_build_query(request()->except('page')) ? '&' . http_build_query(request()->except('page')) : '' }}">Previous</a>
                                </li>
                            @endif

                            {{-- Tombol Angka Halaman --}}
                            @for ($i = 1; $i <= $orders->lastPage(); $i++)
                                <li class="page-item {{ $orders->currentPage() == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $orders->url($i) }}{{ http_build_query(request()->except('page')) ? '&' . http_build_query(request()->except('page')) : '' }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- Tombol Next --}}
                            @if ($orders->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $orders->nextPageUrl() }}{{ http_build_query(request()->except('page')) ? '&' . http_build_query(request()->except('page')) : '' }}">Next</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">Next</span>
                                </li>
                            @endif
                        </ul>
                    </nav>

                </div>
            </div>

            <div class="mt-3 d-flex justify-content-between">
                <div>
                    <small class="text-muted">
                        Data ditampilkan untuk periode: {{ $from }} s.d. {{ $to }}
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            // Format tanggal dengan flatpickr jika diperlukan
            $('input[type="date"]').flatpickr({
                dateFormat: 'Y-m-d',
                allowInput: true
            });
        });
    </script>
@endsection