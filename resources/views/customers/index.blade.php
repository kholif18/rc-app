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
    <div class="row">
        <div class="col-5">
            <h5 class="card-header">Daftar Pelanggan</h5>
        </div>
        <div class="col-4 mt-3">
            <form class="d-flex" method="GET" action="{{ route('customers.index') }}">
                <input class="form-control me-2" name="search" type="search" placeholder="Cari nama / No HP / alamat" aria-label="Search" value="{{ request('search') }}" />
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>
        </div>
        <div class="col-3 text-center">
            <a href="{{ route('customers.create') }}" class="btn btn-primary mt-3">Tambah Pelanggan</a>
        </div>
    </div>
    {{-- Alert dari session --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }} 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }} 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>No HP</th>
                <th>Alamat</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @forelse($customers as $index => $customer)
            <tr>
                <td>{{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->address }}</td>
                <td>
                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data pelanggan.</td>
                </tr>
            @endforelse
        </tbody>
        </table>
        @php
            $currentPage = $customers->currentPage();
            $lastPage = $customers->lastPage();
        @endphp

        @if ($lastPage > 1)
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                {{-- Tombol First --}}
                <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $customers->url(1) }}" aria-label="First"
                    ><i class="tf-icon bx bx-chevrons-left"></i
                    ></a>
                </li>
                {{-- Tombol Previous --}}
                <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $customers->url($currentPage - 1) }}" aria-label="Previous">
                        <i class="tf-icon bx bx-chevron-left"></i>
                    </a>
                </li>

                {{-- Nomor halaman --}}
                @for ($page = 1; $page <= $lastPage; $page++)
                    <li class="page-item {{ $currentPage == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $customers->url($page) }}">{{ $page }}</a>
                    </li>
                @endfor

                {{-- Tombol Next --}}
                <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $customers->url($currentPage + 1) }}" aria-label="Next">
                        <i class="tf-icon bx bx-chevron-right"></i>
                    </a>
                </li>
                {{-- Tombol Last --}}
                <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $customers->url($lastPage) }}" aria-label="Last"
                    ><i class="tf-icon bx bx-chevrons-right"></i
                    ></a>
                </li>
            </ul>
        </nav>
        @endif
    </div>
</div>
@endsection