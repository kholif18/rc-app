@extends('partial.master')

@section('title')
    Laporan Hutang & Pembayaran
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Laporan Hutang & Pembayaran
        </a>
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header text-white">
            <h5 class="mb-0">Laporan Hutang dan Pembayaran</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.debt') }}" class="row mb-4 g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Dari Tanggal:</label>
                    <input type="date" name="from" value="{{ $from }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sampai Tanggal:</label>
                    <input type="date" name="to" value="{{ $to }}" max="{{ date('Y-m-d') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('reports.debt') }}" class="btn btn-secondary">
                        <i class="fas fa-sync-alt"></i> Reset
                    </a>
                </div>
                <div class="col-md-3 text-end">
                    <div class="btn-group">
                        <div class="mt-3 d-flex justify-content-between">
                            <a class="btn btn-outline-primary ms-2" href="{{ route('reports.export', ['format' => 'pdf', 'from' => $from, 'to' => $to]) }}" target="_blank"><i class="bx bxs-file-pdf"></i> PDF</a>
                            <a class="btn btn-outline-primary ms-2" href="{{ route('reports.export', ['format' => 'excel', 'from' => $from, 'to' => $to]) }}"><i class="bx bxs-file-export"></i> Excel</a>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-danger">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Hutang</h5>
                            <h3 class="text-danger">Rp {{ number_format($totalDebts, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Pembayaran</h5>
                            <h3 class="text-success">Rp {{ number_format($totalPayments, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-warning">
                        <div class="card-body text-center">
                            <h5 class="card-title">Sisa Hutang</h5>
                            <h3 class="text-warning">Rp {{ number_format($sisaHutang, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                @if($totalDebts > 0)
                    <div class="progress mt-2">
                        <div class="progress-bar bg-success" role="progressbar" 
                            style="width: {{ ($totalPayments/$totalDebts)*100 }}%" 
                            aria-valuenow="{{ ($totalPayments/$totalDebts)*100 }}" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                            {{ round(($totalPayments/$totalDebts)*100, 2) }}%
                        </div>
                    </div>
                @endif
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Detail Hutang</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Tanggal</th>
                                            <th class="text-end">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($debts as $debt)
                                            <tr>
                                                <td>{{ $debt->customer->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($debt->date)->format('d M Y') }}</td>
                                                <td class="text-end text-danger">Rp {{ number_format($debt->amount, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @php
                                    $currentPage = $debts->currentPage();
                                    $lastPage = $debts->lastPage();
                                @endphp

                                @if ($lastPage > 1)
                                <nav aria-label="Page navigation payments">
                                    <ul class="pagination justify-content-center">
                                        {{-- Tombol Previous --}}
                                        <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $debts->previousPageUrl() }}" aria-label="Previous">
                                                <i class="tf-icon bx bx-chevron-left"></i>
                                            </a>
                                        </li>

                                        {{-- Nomor halaman --}}
                                        @for ($page = 1; $page <= $lastPage; $page++)
                                            <li class="page-item {{ $currentPage == $page ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $debts->url($page) }}">{{ $page }}</a>
                                            </li>
                                        @endfor

                                        {{-- Tombol Next --}}
                                        <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $debts->nextPageUrl() }}" aria-label="Next">
                                                <i class="tf-icon bx bx-chevron-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Detail Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Tanggal</th>
                                            <th class="text-end">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payments as $payment)
                                            <tr>
                                                <td>{{ $payment->debt->customer->name ?? 'Tanpa Nama' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y H:i') }}</td>
                                                <td class="text-end text-success">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @php
                                    $currentPage = $payments->currentPage();
                                    $lastPage = $payments->lastPage();
                                @endphp
    
                                @if ($lastPage > 1)
                                <nav aria-label="Page navigation payments">
                                    <ul class="pagination justify-content-center">
                                        {{-- Tombol Previous --}}
                                        <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $payments->previousPageUrl() }}" aria-label="Previous">
                                                <i class="tf-icon bx bx-chevron-left"></i>
                                            </a>
                                        </li>
    
                                        {{-- Nomor halaman --}}
                                        @for ($page = 1; $page <= $lastPage; $page++)
                                            <li class="page-item {{ $currentPage == $page ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $payments->url($page) }}">{{ $page }}</a>
                                            </li>
                                        @endfor
    
                                        {{-- Tombol Next --}}
                                        <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $payments->nextPageUrl() }}" aria-label="Next">
                                                <i class="tf-icon bx bx-chevron-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-between">
                <div>
                    @if($debts->count() > 0 || $payments->count() > 0)
                        <small class="text-muted">
                            Data ditampilkan: {{ $debts->count() }} hutang dan {{ $payments->count() }} pembayaran
                        </small>
                    @else
                        <div class="alert alert-warning">
                            Tidak ada data yang ditemukan untuk periode ini
                        </div>
                    @endif
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