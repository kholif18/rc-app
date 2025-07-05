@extends('partial.master')

@section('title', 'Tambah Template Pesan')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="{{ route('message-templates.index') }}">Template Pesan</a>
    </li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Template Pesan</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('message-templates.store') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nama (unik, contoh: order_ready)</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Judul (opsional)</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Isi Pesan</label>
                <textarea class="form-control" id="content" name="content" rows="5" required>{{ old('content') }}</textarea>
                <small class="text-muted d-block mt-2">Placeholder yang tersedia:  
                    <code>[name]</code>, 
                    <code>[order_number]</code>, 
                    <code>[services]</code>, 
                    <code>[deadline]</code>
                </small>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-1"></i> Simpan
            </button>
            <a href="{{ route('message-templates.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
