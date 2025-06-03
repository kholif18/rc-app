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
                <h4 class="card-header d-flex justify-content-between">
                    Daftar Order Hari Ini
                    <a href="#" class="see-all">Lihat Semua</a>
                </h4>
                
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
                <h4 class="card-header d-flex justify-content-between">
                    Order yang Terlambat
                    <a href="#" class="see-all">Lihat Semua</a>
                </h4>
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
@endsection


