@extends('partial.master')

@section('title')
    Profile Details
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}">Users</a>
    </li>
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Profile Details
        </a>
    </li>
@endsection

@section('content')
    <div class="card mb-4">
        <h5 class="card-header">Profile Details</h5>
        @if(auth()->user()->can('update', $user))
            <!-- Account -->
            <form id="formAccountSettings" method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                    <img id="uploadedAvatar" src="{{ $user->avatar && $user->avatar !== 'avatar.png' ? asset('storage/avatars/' . $user->avatar) : asset('avatar.png') }}"
                        alt="user-avatar"
                        class="d-block rounded"
                        height="100"
                        width="100"
                        id="uploadedAvatar"
                    />
                    <div class="button-wrapper">
                        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                        <span class="d-none d-sm-block">Upload new photo</span>
                        <i class="bx bx-upload d-block d-sm-none"></i>
                        <input
                            type="file"
                            id="upload"
                            name="avatar"
                            class="account-file-input"
                            hidden
                            accept="image/png, image/jpeg"
                        />
                        </label>
                        <button type="button" onclick="resetAvatar()" class="btn btn-outline-secondary account-image-reset mb-4">
                        <i class="bx bx-reset d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Reset</span>
                        </button>
                        <input type="hidden" name="reset_avatar" id="reset_avatar" value="0">

                        <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                    </div>
                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <div class="row">
                        
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Nama</label>
                            <input class="form-control" type="text" id="name" name="name" required value="{{ old('name', $user->name) }}" autofocus placeholder="Nama"/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input class="form-control" type="text" id="username" name="username" required value="{{ old('username', $user->username) }}" placeholder="Username"/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <input class="form-control" type="text" id="email" name="email" required value="{{ old('email', $user->email) }}" placeholder="john.doe@example.com"/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="role" class="form-label">Role</label>
                                @if(auth()->user()->role === 'superadmin')
                                    @if(auth()->id() === $user->id && $user->role === 'superadmin')
                                        {{-- Superadmin mengedit dirinya sendiri: tampilkan role sebagai readonly --}}
                                        <input type="hidden" name="role" value="{{ $user->role }}">
                                        <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly>
                                    @else
                                        {{-- Superadmin mengedit user lain --}}
                                        <select name="role" class="form-control" required>
                                            <option value="">-- Pilih Role --</option>
                                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                        </select>
                                    @endif
                                @else
                                    {{-- Bukan superadmin, tidak bisa mengubah role --}}
                                    <input type="hidden" name="role" value="{{ $user->role }}">
                                    <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly>
                                @endif
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="password" class="form-label">Password <small class="text-muted">(kosongkan jika tidak ingin ganti)</small></label>
                            <input class="form-control" type="password" id="password" name="password" placeholder="Password"/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Passsword"/>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </form>
            <!-- /Account -->
            @else
            <div class="card-body">
                <div class="alert alert-danger">
                    <i class="fas fa-ban me-2"></i>
                    Akses Ditolak: Anda tidak memiliki izin untuk mengedit data pengguna ini
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function resetAvatar() {
            // Set input reset_avatar ke 1
            document.getElementById('reset_avatar').value = 1;

            // Reset tampilan preview avatar (opsional)
            document.getElementById('uploadedAvatar').src = '{{ asset("avatar.png") }}';

            // Kosongkan input file
            document.getElementById('upload').value = '';
        }
    </script>
@endpush