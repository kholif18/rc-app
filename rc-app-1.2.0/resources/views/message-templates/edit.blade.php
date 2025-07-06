@extends('partial.master')

@section('title', 'Edit Template Pesan')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="{{ route('message-templates.index') }}">Template Pesan</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Template Pesan</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('message-templates.update', $messageTemplate) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama (unik)</label>
                <input type="text" class="form-control" id="name" name="name"
                        value="{{ old('name', $messageTemplate->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Judul (opsional)</label>
                <input type="text" class="form-control" id="title" name="title"
                        value="{{ old('title', $messageTemplate->title) }}">
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Isi Pesan</label>
                <textarea class="form-control" id="content" name="content" rows="5" required>{{ old('content', $messageTemplate->content) }}</textarea>
                <small class="text-muted d-block mt-2">Gunakan placeholder seperti  
                    <code>[name]</code>, 
                    <code>[order_number]</code>, 
                    <code>[services]</code>, 
                    <code>[deadline]</code>, dll.
                </small>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Update
            </button>
            <a href="{{ route('message-templates.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
