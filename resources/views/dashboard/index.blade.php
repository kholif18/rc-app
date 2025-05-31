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
    <button class="btn btn-primary">
        <i class="fas fa-plus"></i>
        Tambah Order Baru
    </button>
</div>

<!-- Summary Cards -->
<div class="summary-cards">
    <div class="card summary-card">
        <div class="summary-icon">
            <i class='bx bx-edit-alt service-ketik'></i>
        </div>
        <h3 class="card-title">Jasa Ketik</h3>
        <div class="card-value">18</div>
        <div class="card-change positive">
            <i class='bx bx-up-arrow-alt'></i>
            <span>+2 dari kemarin</span>
        </div>
    </div>
    
    <div class="card summary-card">
        <div class="summary-icon">
            <i class='bx bx-palette summary-icon service-desain'></i>
        </div>
        <h3 class="card-title">Jasa Desain</h3>
        <div class="card-value">9</div>
        <div class="card-change positive">
            <i class='bx bx-up-arrow-alt'></i>
            <span>+1 dari kemarin</span>
        </div>
    </div>
    
    <div class="card summary-card">
        <div class="summary-icon">
            <i class='bx bx-printer summary-icon service-cetak'></i>
        </div>
        <h3 class="card-title">Percetakan</h3>
        <div class="card-value">15</div>
        <div class="card-change negative">
            <i class='bx bx-down-arrow-alt'></i>
            <span>-3 dari kemarin</span>
        </div>
    </div>
    
    <div class="card summary-card">
        <div class="summary-icon">
            <i class='bx bx-money summary-icon hutang'></i>
        </div>
        <h3 class="card-title">Sisa Hutang</h3>
        <div class="card-value">Rp 0</div>
        <div class="card-change positive">
            <i class='bx bx-up-arrow-alt'></i>
            <span>+2 Customers</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="card mb-3">
            <h2 class="section-title">
                Daftar Order Hari Ini
                <a href="#" class="see-all">Lihat Semua</a>
            </h2>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>NO. ORDER</th>
                        <th>PELANGGAN</th>
                        <th>LAYANAN</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PRT-2023-156</td>
                        <td>Budi Santoso</td>
                        <td>Ketik Dokumen</td>
                        <td>
                            <span class="badge bg-label-primary">DALAM PENGERJAAN</span>
                        </td>
                    </tr>
                    <tr>
                        <td>PRT-2023-157</td>
                        <td>Siti Aminah</td>
                        <td>Desain Brosur</td>
                        <td>
                            <span class="badge bg-label-warning">MENUNGGU KONFIRMASI</span>
                        </td>
                    </tr>
                    <tr>
                        <td>PRT-2023-158</td>
                        <td>Andi Wijaya</td>
                        <td>Cetak Banner</td>
                        <td>
                            <span class="badge bg-label-success">SELESAI</span>
                        </td>
                    </tr>
                    <tr class="urgent">
                        <td>PRT-2023-159</td>
                        <td>Dewi Lestari</td>
                        <td>Paket Komplit</td>
                        <td>
                            <span class="badge bg-label-danger">DIAJUKAN</span>
                        </td>
                    </tr>
                    <tr>
                        <td>PRT-2023-156</td>
                        <td>Budi Santoso</td>
                        <td>Ketik Dokumen</td>
                        <td>
                            <span class="badge bg-label-primary">DALAM PENGERJAAN</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-6">
        <div class="card mb-3">
            <h2 class="section-title">
                Order yang Terlambat
                <a href="#" class="see-all">Lihat Semua</a>
            </h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>NO. ORDER</th>
                        <th>PELANGGAN</th>
                        <th>JENIS ORDER</th>
                        <th>KETERLAMBATAN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="urgent">PRT-2023-151</td>
                        <td>Ahmad Fauzi</td>
                        <td>Desain Logo</td>
                        <td>
                            <span class="badge bg-danger">2 HARI</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="urgent">PRT-2023-152</td>
                        <td>Rina Melati</td>
                        <td>Cetak Undangan</td>
                        <td>
                            <span class="badge bg-danger">1 HARI</span>
                        </td>
                    </tr>
                    <tr>
                        <td>PRT-2023-153</td>
                        <td>Joko Prasetyo</td>
                        <td>Ketik Skripsi</td>
                        <td>
                            <span class="badge bg-warning">HARI INI</span>
                        </td>
                    </tr>
                    <tr>
                        <td>PRT-2023-154</td>
                        <td>Linda Sari</td>
                        <td>Paket Komplit</td>
                        <td>
                            <span class="badge bg-info">BESOK DEADLINE</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="urgent">PRT-2023-152</td>
                        <td>Rina Melati</td>
                        <td>Cetak Undangan</td>
                        <td>
                            <span class="badge bg-danger">1 HARI</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Ringkasan Jumlah Pesanan per Layanan -->
{{-- <div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="row">
                <div class="col-9">
                    <h5 class="card-header mb-0"><i class="bx bxs-pie-chart-alt-2 me-2"></i>Ringkasan Pesanan per Layanan</h5>
                </div>
                <div class="col-3 text-center">
                    <a href="#" class="btn btn-primary mt-3">Tambah Order Baru</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center p-3">
                        <div class="summary-icon service-ketik">
                            <i class="bx bxs-keyboard"></i>
                        </div>
                        <h3>18</h3>
                        <p class="text-muted">Jasa Ketik</p>
                        <small class="text-success">+2 dari kemarin</small>
                    </div>
                    <div class="col-md-3 text-center p-3">
                        <div class="summary-icon service-desain">
                            <i class="bx bx-palette"></i>
                        </div>
                        <h3>9</h3>
                        <p class="text-muted">Jasa Desain</p>
                        <small class="text-success">+1 dari kemarin</small>
                    </div>
                    <div class="col-md-3 text-center p-3">
                        <div class="summary-icon service-cetak">
                            <i class="bx bx-printer"></i>
                        </div>
                        <h3>15</h3>
                        <p class="text-muted">Percetakan</p>
                        <small class="text-danger">-3 dari kemarin</small>
                    </div>
                    <div class="col-md-3 text-center p-3">
                        <div class="summary-icon hutang">
                            <i class="bx bx-wallet"></i>
                        </div>
                        <h3 class="card-title text-danger">Rp {{ number_format($totalDebt, 0, ',', '.') }}</h3>
                        <p class="text-muted">Sisa Hutang</p>
                        <small class="text-success">+2 Customers</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

{{-- <div class="row mb-4">
    <!-- Daftar Order Hari Ini -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-white">
                <h5 class="card-title mb-0"><i class="fas fa-list me-2"></i>Daftar Order Hari Ini</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No. Order</th>
                                <th>Pelanggan</th>
                                <th>Layanan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>PRT-2023-156</td>
                                <td>Budi Santoso</td>
                                <td><span class="service-ketik"><i class="fas fa-keyboard me-1"></i>Ketik Dokumen</span></td>
                                <td><span class="badge bg-warning">Dalam Pengerjaan</span></td>
                            </tr>
                            <tr>
                                <td>PRT-2023-157</td>
                                <td>Siti Aminah</td>
                                <td><span class="service-desain"><i class="fas fa-palette me-1"></i>Desain Brosur</span></td>
                                <td><span class="badge bg-info">Menunggu Konfirmasi</span></td>
                            </tr>
                            <tr>
                                <td>PRT-2023-158</td>
                                <td>Andi Wijaya</td>
                                <td><span class="service-cetak"><i class="fas fa-print me-1"></i>Cetak Banner</span></td>
                                <td><span class="badge bg-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td>PRT-2023-159</td>
                                <td>Dewi Lestari</td>
                                <td><span class="service-komplit"><i class="fas fa-tasks me-1"></i>Paket Komplit</span></td>
                                <td><span class="badge bg-secondary">Diambil</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-end mt-2">
                    <a href="#" class="btn btn-sm btn-outline-success">Lihat Semua</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Order yang Terlambat/Dalam Deadline -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-white">
                <h5 class="card-title mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Order yang Terlambat</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No. Order</th>
                                <th>Pelanggan</th>
                                <th>Jenis Order</th>
                                <th>Keterlambatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="urgent">
                                <td>PRT-2023-151</td>
                                <td>Ahmad Fauzi</td>
                                <td><span class="service-desain"><i class="fas fa-palette me-1"></i>Desain Logo</span></td>
                                <td><span class="badge badge-late">2 hari</span></td>
                            </tr>
                            <tr class="urgent">
                                <td>PRT-2023-152</td>
                                <td>Rina Melati</td>
                                <td><span class="service-cetak"><i class="fas fa-print me-1"></i>Cetak Undangan</span></td>
                                <td><span class="badge badge-late">1 hari</span></td>
                            </tr>
                            <tr>
                                <td>PRT-2023-153</td>
                                <td>Joko Prasetyo</td>
                                <td><span class="service-ketik"><i class="fas fa-keyboard me-1"></i>Ketik Skripsi</span></td>
                                <td><span class="badge badge-today">Mendung hari ini</span></td>
                            </tr>
                            <tr>
                                <td>PRT-2023-154</td>
                                <td>Linda Sari</td>
                                <td><span class="service-komplit"><i class="fas fa-tasks me-1"></i>Paket Komplit</span></td>
                                <td><span class="badge bg-warning">Besok deadline</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-end mt-2">
                    <a href="#" class="btn btn-sm btn-outline-danger">Lihat Semua</a>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="card mb-4">
    <h2 class="section-title">Daftar Pelanggan dengan Hutang Aktif</h2>
    {{-- <div class="card-body"> --}}
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
    {{-- </div> --}}
</div>

@endsection


