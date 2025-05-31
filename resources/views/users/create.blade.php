@extends('partial.master')

@section('title')
    Add User
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}">Users</a>
    </li>
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Add User
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
        <h5 class="card-header">Tambah User</h5>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="form-floating py-1">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Ravaa Creative"
                        aria-describedby="floatingInputHelp" required value="{{ old('name') }}"
                    />
                    <label for="name">Nama</label>
                </div>
                <div class="form-floating py-1">
                    <input type="text" class="form-control" name="username" id="username" placeholder="username"
                        aria-describedby="floatingInputHelp" required value="{{ old('username') }}"
                    />
                    <label for="username">Username</label>
                </div>
                <div class="form-floating py-1">
                    <input type="email" class="form-control" name="email" id="email" placeholder="mail@example.com"
                        aria-describedby="floatingInputHelp" required value="{{ old('email') }}"
                    />
                    <label for="email">Email</label>
                </div>
                <div class="py-1">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" class="form-control" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>
                <div class="form-floating py-1">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password"
                        aria-describedby="floatingInputHelp" required value="{{ old('password') }}"
                    />
                    <label for="password">Password</label>
                </div>
                <div class="form-floating py-1">
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password"
                        aria-describedby="floatingInputHelp" required value="{{ old('password') }}"
                    />
                    <label for="password">Confirm Password</label>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                <div id="floatingInputHelp" class="form-text">
                    We'll never share your details with anyone else.
                </div>
            </form>   
        </div>
    </div>
@endsection