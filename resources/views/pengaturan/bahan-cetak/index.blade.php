@extends('partial.master')

@section('title')
    Bahan Cetak
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="#">Pengaturan</a>
    </li>
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Bahan Cetak
        </a>
    </li>
@endsection

@section('content')
<div class="bs-toast toast position-fixed m-2 toast-container top-0 end-0">
    @if(session('success'))
        <div
            class="bs-toast toast fade show bg-success"
            role="alert"
            aria-live="assertive"
            aria-atomic="true"
        >
            <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto fw-semibold">Sukses</div>
                <small>Baru saja</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div
            class="bs-toast toast fade show bg-danger"
            role="alert"
            aria-live="assertive"
            aria-atomic="true"
        >
            <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto fw-semibold">Gagal</div>
                <small>Baru saja</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    @endif
</div>

    <div class="card">
        <h4 class="card-header">Pengaturan Layanan</h4>

        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link" href="#">Jenis Layanan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Bahan Cetak</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Finishing</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Harga</a>
            </li>
        </ul>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Daftar Bahan Cetak</span>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahBahanModal">
                    <i class="fas fa-plus"></i> Tambah Bahan
                </button>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('bahan-cetak.index') }}">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari bahan..." name="search" value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterJenis" onchange="filterByJenis()">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisBahan as $jenis)
                                <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Bahan</th>
                                <th>Jenis</th>
                                <th>Gramatur</th>
                                <th>Ukuran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bahanCetak as $index => $bahan)
                            <tr>
                                <td>{{ $index + $bahanCetak->firstItem() }}</td>
                                <td>{{ $bahan->nama_bahan }}</td>
                                <td>{{ $bahan->jenis_bahan }}</td>
                                <td>{{ $bahan->gramatur ?? '-' }}</td>
                                <td>{{ $bahan->ukuran }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-1" title="Edit" 
                                        data-bs-toggle="modal" data-bs-target="#editBahanModal"
                                        data-id="{{ $bahan->id }}"
                                        data-nama="{{ $bahan->nama_bahan }}"
                                        data-jenis="{{ $bahan->jenis_bahan }}"
                                        data-gramatur="{{ $bahan->gramatur }}"
                                        data-ukuran="{{ $bahan->ukuran }}">
                                        <i class="fas fa-pencil"></i>
                                    </button>
                                    <form action="{{ route('bahan-cetak.destroy', $bahan->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus bahan ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mb-0">
                            {{-- Tombol Previous --}}
                            <li class="page-item {{ $bahanCetak->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $bahanCetak->previousPageUrl() ?? '#' }}" tabindex="-1">Previous</a>
                            </li>

                            {{-- Tombol Angka Halaman --}}
                            @for ($i = 1; $i <= $bahanCetak->lastPage(); $i++)
                                <li class="page-item {{ $bahanCetak->currentPage() == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $bahanCetak->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- Tombol Next --}}
                            <li class="page-item {{ $bahanCetak->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $bahanCetak->nextPageUrl() ?? '#' }}">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Tambah Bahan -->
    @include('pengaturan.bahan-cetak.modal-tambah')
    <!-- Modal Edit Bahan -->
    @include('pengaturan.bahan-cetak.modal-edit')

    <script>
        function filterByJenis() {
            const jenis = document.getElementById('filterJenis').value;
            const url = new URL(window.location.href);
            url.searchParams.set('jenis', jenis);
            window.location.href = url.toString();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const toastElements = document.querySelectorAll('.toast');
            toastElements.forEach(function (toastEl) {
                const toast = new bootstrap.Toast(toastEl, { delay: 4000 }); // 4 detik
                toast.show();
            });
        });

    </script>
@endsection