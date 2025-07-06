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
                    <div class="form-group">
                        <label class="form-label" for="customer_search">Cari Pelanggan</label>
                        <input type="text" 
                            id="customer_search" 
                            list="customers_data" 
                            class="form-control" autofocus
                            placeholder="Ketik nama pelanggan..."
                            value="{{ old('customer_name') }}"
                            autocomplete="off"
                            required>
                        <datalist id="customers_data">
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->name }}" data-id="{{ $customer->id }}">
                            @endforeach
                        </datalist>
                        <input type="hidden" 
                            name="customer_id" 
                            id="customer_id" 
                            value="{{ old('customer_id') }}">
                    </div>
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
        // input datalist
        document.addEventListener('DOMContentLoaded', function() {
            const customerSearch = document.getElementById('customer_search');
            const customerIdInput = document.getElementById('customer_id');
            const datalist = document.getElementById('customers_data');
            
            // Handle ketika user memilih/menginput
            customerSearch.addEventListener('change', function() {
                const selectedName = this.value;
                const options = datalist.querySelectorAll('option');
                
                // Cari customer yang sesuai
                let found = false;
                for (let option of options) {
                    if (option.value === selectedName) {
                        customerIdInput.value = option.getAttribute('data-id');
                        found = true;
                        break;
                    }
                }
                
                // Jika tidak ditemukan, reset nilai
                if (!found) {
                    customerIdInput.value = '';
                }
            });
            
            // Validasi sebelum form submit
            document.querySelector('form').addEventListener('submit', function(e) {
                if (!customerIdInput.value) {
                    e.preventDefault();
                    alert('Silakan pilih pelanggan dari daftar yang tersedia');
                    customerSearch.focus();
                }
            });
        });
    </script>
@endpush