@extends('partial.master')

@section('title')
    API Setting
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            API Setting
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
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">API Setting</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('api.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="client_name">App Name</label>
                        <input type="text" class="form-control" name="client_name" id="client_name" value="{{ old('client_name', $client_name) }}" placeholder="Paste dari r-gateway" />
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label" for="api_token">API Token</label>
                        <input type="text" class="form-control" name="api_token" id="api_token" value="{{ old('api_token', $api_token) }}" placeholder="Paste dari r-gateway" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="gateway_url" class="form-label">URL r-Gateway</label>
                        <input type="text" name="gateway_url" id="gateway_url" class="form-control"
                            value="{{ old('gateway_url', $gateway_url) }}"
                            placeholder="Contoh: http://r-gateway.local">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <button type="button" class="btn btn-info" id="test-connection">Test Koneksi</button>
            <div id="connection-result" class="mt-2"></div>
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

        document.getElementById('test-connection').addEventListener('click', function () {
            const clientName = document.getElementById('client_name').value;
            const apiToken = document.getElementById('api_token').value;
            const resultDiv = document.getElementById('connection-result');

            resultDiv.innerHTML = '⏳ Menguji koneksi...';

            fetch("{{ route('api.test-connection') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    client_name: clientName,
                    api_token: apiToken
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.innerHTML = `<span class="text-success">✅ ${data.message}</span>`;
                } else {
                    resultDiv.innerHTML = `<span class="text-danger">❌ ${data.message}</span>`;
                }
            })
            .catch(error => {
                console.error(error);
                resultDiv.innerHTML = `<span class="text-danger">❌ Gagal terhubung ke server</span>`;
            });
        });
    </script>
@endsection