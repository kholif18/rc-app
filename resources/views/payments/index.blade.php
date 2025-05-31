@extends('partial.master')

@section('title')
    Lunas
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Lunas
        </a>
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">Daftar Pelanggan Lunas</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>Total Hutang</th>
                        <th>Total Pembayaran</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paginatedCustomers as $customer)
                        @php
                            $totalDebt = $customer->debts->sum('amount');
                            $totalPaid = $customer->debts->flatMap->payments->sum('amount');
                        @endphp
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>Rp {{ number_format($totalDebt, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($totalPaid, 0, ',', '.') }}</td>
                            <td><span class="badge bg-success">Lunas</span></td>
                            <td>
                                <a href="{{ route('payments.detail', ['debt_id' => $customer->debts->last()?->id]) }}" class="btn btn-sm btn-info">View Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada pelanggan yang lunas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @php
            $currentPage = $paginatedCustomers->currentPage();
            $lastPage = $paginatedCustomers->lastPage();
        @endphp

        @if ($lastPage > 1)
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $paginatedCustomers->url($currentPage - 1) }}">
                            <i class="tf-icon bx bx-chevron-left"></i>
                        </a>
                    </li>
                    @for ($page = 1; $page <= $lastPage; $page++)
                        <li class="page-item {{ $currentPage == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $paginatedCustomers->url($page) }}">{{ $page }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $paginatedCustomers->url($currentPage + 1) }}">
                            <i class="tf-icon bx bx-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        @endif
    </div>
@endsection