@extends('partial.master')

@section('title')
    Settings
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Settings
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
        <h5 class="card-header">Settings</h5>
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="app_name" class="form-label">App Name</label>
                            <input type="text" class="form-control" id="app_name" name="app_name" value="{{ old('app_name', $setting->app_name ?? 'Ravaa Crtv') }}" aria-describedby="defaultFormControlHelp"/>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email" value="{{ old('email', $setting->email ?? 'ravaacreative@gmail.com') }}" aria-describedby="defaultFormControlHelp"/>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">No Telp</label>
                            <input type="text" class="form-control" id="phone" name="phone"  value="{{ old('phone', $setting->phone ?? '0812-xxxx-xxxx') }}" aria-describedby="defaultFormControlHelp"/>
                        </div>
                        <div class="mb-3">
                            <label for="website" class="form-label">Website</label>
                            <input type="text" class="form-control" id="website" name="website" value="{{ old('website', $setting->website ?? 'ravaa.my.id') }}" aria-describedby="defaultFormControlHelp"/>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control" name="address" id="address" name="address" cols="30" rows="3" aria-describedby="defaultFormControlHelp">{{ old('address', $setting->address ?? '-') }}</textarea>
                        </div>
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">Logo Saat Ini</label><br>
                            @if(!empty($setting->logo))
                                <img src="{{ asset('storage/logo/' . $setting->logo) }}" alt="Logo" width="120">
                            @else
                                <img src="{{ asset('logo.png') }}" alt="Logo Default" width="120">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Upload Logo</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*" />
                                <label class="input-group-text" for="logo">Upload</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">Favicon Saat Ini</label><br>
                            @if(!empty($setting->favicon))
                                <img src="{{ asset('storage/favicon/' . $setting->favicon) }}" alt="favicon" width="60">
                            @else
                                <img src="{{ asset('favicon.png') }}" alt="favicon Default" width="60">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="favicon" class="form-label">Upload favicon</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="favicon" name="favicon" accept="image/*" />
                                <label class="input-group-text" for="favicon">Upload</label>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>   
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