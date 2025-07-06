@extends('partial.master')

@section('title', 'Template Pesan')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Template Pesan</li>
@endsection

@section('content')
<div class="card">
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
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Template Pesan</h5>
        <a href="{{ route('message-templates.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Tambah Template
        </a>
    </div>

    <div class="card-body">
        @if ($templates->count())
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Judul</th>
                            <th>Isi</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($templates as $template)
                            <tr>
                                <td><code>{{ $template->name }}</code></td>
                                <td>{{ $template->title ?? '-' }}</td>
                                <td>{{ Str::limit($template->content, 60) }}</td>
                                <td>
                                    <a href="{{ route('message-templates.edit', $template) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('message-templates.destroy', $template) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Hapus template ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">Belum ada template.</p>
        @endif
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
