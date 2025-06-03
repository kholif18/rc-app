@extends('partial.master')

@section('title')
    Add Customers
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="{{ route('customers.index') }}">Customers</a>
    </li>
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Add Customers
        </a>
    </li>
@endsection

@section('content')
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

    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible" role="alert">
            {{ session('warning') }} 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-dismissible" role="alert">
            {{ session('info') }} 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    {{-- Menampilkan error validasi --}}
    @if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <div class="card mb-4">
        <h5 class="card-header">Tambah Pelanggan</h5>
        <div class="card-body">
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                <div class="form-floating py-1">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Ravaa Creative"
                        aria-describedby="floatingInputHelp" autofocus required value="{{ old('name') }}"
                    />
                    <label for="name">Nama Pelanggan</label>
                </div>
                <div class="form-floating py-1">
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="6281234xxxxxx"
                        aria-describedby="floatingInputHelp" value="{{ old('phone') }}"
                    />
                    <label for="phone">No HP</label>
                </div>
                <div class="form-floating py-1">
                    <input type="email" class="form-control" name="email" id="email" placeholder="mail@example.com"
                        aria-describedby="floatingInputHelp" value="{{ old('email') }}"
                    />
                    <label for="email">E-mail</label>
                </div>
                <div class="form-floating py-1">
                    <input type="text" class="form-control" name="address" id="address" placeholder="Ds. Ngluyu"
                        aria-describedby="floatingInputHelp" value="{{ old('address') }}"
                    />
                    <label for="address">Alamat</label>
                </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Batal</a>
                    <div id="floatingInputHelp" class="form-text">
                        We'll never share your details with anyone else.
                    </div>
            </form>   
        </div>
    </div>
@endsection