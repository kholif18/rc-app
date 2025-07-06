@extends('partial.master')

@section('title')
    Bahan Cetak
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Bahan Cetak
        </a>
    </li>
@endsection

@section('content')
    <div class="bs-toast toast toast-placement-ex top-0 end-0 m-2">
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
                    {!! session('error') !!}
                </div>
            </div>
        @endif
    </div>
    <div class="card">
        <div class="row">
            <div class="col-3">
                <h5 class="card-header">Bahan Cetak</h5>
            </div>
            <div class="col-5 mt-3">
                <form method="GET" action="{{ route('bahan-cetak.index') }}">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari bahan..." name="search" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-2 mt-3">
                <select class="form-select" id="filterJenis" onchange="filterByJenis()">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisBahan as $jenis)
                        <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-2 mt-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="btnTambahBahan" data-bs-target="#tambahBahanModal">Tambah Bahan</button>
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
                                <button type="button" class="btn btn-sm btn-danger btn-delete"><i class="fas fa-trash"></i></button>
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
    <!-- Modal Tambah Bahan -->
    @include('bahan-cetak.modal-tambah')
    <!-- Modal Edit Bahan -->
    @include('bahan-cetak.modal-edit')

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

            const modal = document.getElementById('tambahBahanModal');
            const trigger = document.getElementById('btnTambahBahan');

            modal.addEventListener('shown.bs.modal', function () {
                document.getElementById('nama_bahan').focus();
            });

            modal.addEventListener('hidden.bs.modal', function () {
                // Fokus kembali ke tombol pembuka modal
                if (trigger) trigger.focus();
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-delete');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data yang dihapus tidak bisa dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection