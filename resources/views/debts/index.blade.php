@extends('partial.master')

@section('title')
    Hutang
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Hutang
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
                {{ session('error') }}
            </div>
        </div>
    @endif
</div>
<div class="card">
    <div class="row">
        <div class="col-5">
            <h5 class="card-header">Daftar Hutang Pelanggan</h5>
        </div>
        <div class="col-4 mt-3">
            <form class="d-flex" method="GET" action="{{ route('debts.index') }}">
                <input class="form-control me-2" name="search" type="search" placeholder="Cari nama / No HP / alamat" aria-label="Search" value="{{ request('search') }}" />
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>
        </div>
        <div class="col-3 text-center">
            <a href="{{ route('debts.create') }}" class="btn btn-primary mt-3">Catat Hutang Baru</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Jumlah</th>
                    <th>Catatan</th>
                    <th>Tanggal</th>
                    <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($customers as $index => $customer)
                        <tr>
                            <td>{{ ($customers->firstItem() ?? 0) + $index }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>Rp {{ number_format($customer->total_debt, 0, ',', '.') }}</td>
                            <td>{{ $customer->last_debt_note ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($customer->last_debt_date)->format('d M Y H:i') ?? '-' }}</td>
                            <td>
                                <a href="{{ route('payments.create', ['debt_id' => $customer->last_debt_id]) }}" class="btn btn-sm btn-warning">Bayar Hutang</a>
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data transaksi.</td>
                    </tr>
                    @endforelse
                    {{-- Baris total sisa hutang --}}
                    @if ($customers->count() > 0)
                        <tr class="fw-bold">
                            <td colspan="2">Total Sisa Hutang</td>
                            <td colspan="4" class="text-danger">
                                Rp {{ number_format($totalRemainingDebt, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mb-0">
                    {{-- Tombol Previous --}}
                    <li class="page-item {{ $customers->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $customers->previousPageUrl() ?? '#' }}" tabindex="-1">Previous</a>
                    </li>

                    {{-- Tombol Angka Halaman --}}
                    @for ($i = 1; $i <= $customers->lastPage(); $i++)
                        <li class="page-item {{ $customers->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $customers->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    {{-- Tombol Next --}}
                    <li class="page-item {{ $customers->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $customers->nextPageUrl() ?? '#' }}">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toastElements = document.querySelectorAll('.toast');
        toastElements.forEach(function (toastEl) {
            const toast = new bootstrap.Toast(toastEl, { delay: 4000 }); // 4 detik
            toast.show();
        });
    });
</script>
@endsection