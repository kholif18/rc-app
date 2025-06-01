@extends('partial.master')

@section('title')
    Customers
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Customers
        </a>
    </li>
@endsection

@section('content')
<div class="card">
    <div class="container-fluid py-4">
        <div class="row mb-2">
            <div class="col-md-6">
                <h2><i class="fas fa-clipboard-list me-2"></i>Manajemen Order</h2>
                <p class="text-muted">Daftar semua order jasa ketik, desain, dan percetakan</p>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-primary" onclick="location.href='tambah-order.html'">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Order Baru
                </button>
            </div>
        </div>

        <!-- Filter dan Pencarian -->
        <div class="card mb-4">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label for="searchInput" class="form-label">Cari Order</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari berdasarkan no. order/nama...">
                        <button class="btn btn-outline-secondary" type="button">
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
                                <th width="120">Status</th>
                                <th width="80">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Order 1 -->
                            <tr class="order-row urgent" onclick="window.location='detail-order.html?id=PRT-2023-101'">
                                <td>PRT-2023-101</td>
                                <td>30 Mei 2023</td>
                                <td>
                                    <div class="fw-bold">Budi Santoso</div>
                                    <div class="text-muted small">Ketik Skripsi Bab 1-3</div>
                                </td>
                                <td><span class="service-tag service-ketik"><i class="fas fa-keyboard me-1"></i>Ketik</span></td>
                                <td>
                                    <div class="fw-bold">1 Jun 2023</div>
                                    <div class="text-danger small"><i class="fas fa-exclamation-circle me-1"></i>2 hari lagi</div>
                                </td>
                                <td><span class="service-tag status-progress"><i class="fas fa-spinner me-1"></i>Dikerjakan</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation();">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Order 2 -->
                            <tr class="order-row" onclick="window.location='detail-order.html?id=PRT-2023-102'">
                                <td>PRT-2023-102</td>
                                <td>29 Mei 2023</td>
                                <td>
                                    <div class="fw-bold">Siti Aminah</div>
                                    <div class="text-muted small">Desain Logo Perusahaan</div>
                                </td>
                                <td><span class="service-tag service-desain"><i class="fas fa-palette me-1"></i>Desain</span></td>
                                <td>
                                    <div class="fw-bold">5 Jun 2023</div>
                                    <div class="text-success small">6 hari lagi</div>
                                </td>
                                <td><span class="service-tag status-waiting"><i class="fas fa-clock me-1"></i>Menunggu</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation();">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Order 3 -->
                            <tr class="order-row priority-express" onclick="window.location='detail-order.html?id=PRT-2023-103'">
                                <td>PRT-2023-103</td>
                                <td>28 Mei 2023</td>
                                <td>
                                    <div class="fw-bold">Andi Wijaya</div>
                                    <div class="text-muted small">Cetak Banner Promosi</div>
                                </td>
                                <td><span class="service-tag service-cetak"><i class="fas fa-print me-1"></i>Cetak</span></td>
                                <td>
                                    <div class="fw-bold">31 Mei 2023</div>
                                    <div class="text-warning small"><i class="fas fa-clock me-1"></i>Besok</div>
                                </td>
                                <td><span class="service-tag status-progress"><i class="fas fa-spinner me-1"></i>Dikerjakan</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation();">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Order 4 -->
                            <tr class="order-row" onclick="window.location='detail-order.html?id=PRT-2023-104'">
                                <td>PRT-2023-104</td>
                                <td>27 Mei 2023</td>
                                <td>
                                    <div class="fw-bold">Dewi Lestari</div>
                                    <div class="text-muted small">Paket Komplit (Desain+Cetak)</div>
                                </td>
                                <td>
                                    <span class="service-tag service-desain"><i class="fas fa-palette me-1"></i>Desain</span>
                                    <span class="service-tag service-cetak"><i class="fas fa-print me-1"></i>Cetak</span>
                                </td>
                                <td>
                                    <div class="fw-bold">2 Jun 2023</div>
                                    <div class="text-success small">3 hari lagi</div>
                                </td>
                                <td><span class="service-tag status-waiting"><i class="fas fa-clock me-1"></i>Menunggu</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation();">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Order 5 -->
                            <tr class="order-row" onclick="window.location='detail-order.html?id=PRT-2023-105'">
                                <td>PRT-2023-105</td>
                                <td>26 Mei 2023</td>
                                <td>
                                    <div class="fw-bold">Rina Melati</div>
                                    <div class="text-muted small">Ketik Laporan Keuangan</div>
                                </td>
                                <td><span class="service-tag service-ketik"><i class="fas fa-keyboard me-1"></i>Ketik</span></td>
                                <td>
                                    <div class="fw-bold">28 Mei 2023</div>
                                    <div class="text-muted small"><i class="fas fa-check-circle me-1"></i>Selesai</div>
                                </td>
                                <td><span class="service-tag status-completed"><i class="fas fa-check-circle me-1"></i>Selesai</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation();">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
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
</div>
@endsection