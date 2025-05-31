@extends('partial.master')

@section('title')
    User
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            User
        </a>
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="row">
            <div class="col-9">
                <h5 class="card-header">Daftar User</h5>
            </div>
            <div class="col-3 text-center">
                <a href="{{ route('users.create') }}" class="btn btn-primary mt-3">+ Tambah User</a>
            </div>
        </div>
        @if ($errors->has('avatar'))
            <div class="text-danger">{{ $errors->first('avatar') }}</div>
        @endif
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                {{ $user->name }}
                                @if(auth()->id() === $user->id)
                                    <span class="badge bg-success">Online</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                @can('delete', $user)
                                    @if($user->role !== 'superadmin')
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    @endif
                                @else
                                    <button class="btn btn-danger btn-sm" disabled title="Anda tidak memiliki izin">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada user</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection