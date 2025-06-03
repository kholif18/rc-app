@extends('partial.master')

@section('title')
    Add Debt
@endsection

@section('breadcrumb')
    @parent
        <li class="breadcrumb-item">
        <a href="{{ route('debts.index') }}">Hutang</a>
    </li>
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Add Debt
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
        <h5 class="card-header">Catat Hutang Baru</h5>
        <div class="card-body">
            <form action="{{ route('debts.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="customer_id" class="form-label">Pelanggan</label>
                    <select name="customer_id" id="customer_id" class="form-select select2" required>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Jumlah Hutang</label>
                    <input type="number" id="amount" name="amount" class="form-control" required step="0.01">
                </div>
                <div class="mb-3">
                    <label for="note" class="form-label">Catatan (opsional)</label>
                    <textarea name="note" id="note" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Hutang</button>
            </form>   
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#customer_id').select2({
                placeholder: 'Pilih pelanggan',
                allowClear: true
            });
        });
    </script>
@endpush