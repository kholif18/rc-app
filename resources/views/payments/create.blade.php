@extends('partial.master')

@section('title')
    Pay Debt
@endsection

@section('breadcrumb')
    @parent
        <li class="breadcrumb-item">
        <a href="{{ route('debts.index') }}">Hutang</a>
    </li>
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Pay Debt
        </a>
    </li>
@endsection

@section('content')
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
        <div class="row">
            <div class="col-8">
                <h5 class="card-header">Bayar Hutang - {{ $debt->customer->name }}</h5>
            </div>
            <div class="col-4">
                <h4 class="card-header text-info text-end">
                    Rp {{ number_format($debt->customer->total_debt, 0, ',', '.') }} 
                </h4>
            </div>    
        </div>
        <div class="card-body">
            <form action="{{ route('payments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="debt_id" value="{{ $debt->id }}">
                
                <div class="mb-3">
                    <label for="amount" class="form-label">Jumlah Bayar</label>
                    <input type="number" name="amount" class="form-control" required min="1">
                </div>

                <div class="mb-3">
                    <label for="note" class="form-label">Catatan</label>
                    <textarea name="note" class="form-control" rows="3"></textarea>
                </div>
                <input type="datetime-local" name="payment_date" class="form-control mb-3" readonly required value="{{ date('Y-m-d\TH:i') }}">

                <button type="submit" class="btn btn-primary">Bayar</button>
            </form>   
        </div>
    </div>

    <div class="card mb-4">
        <h5 class="card-header">Riwayat Pembayaran untuk {{ $debt->customer->name }}</h5>

        <div class="table-responsive text-nowrap mt-3">
            <table class="table">
                <thead>
                    <tr>
                        <th>Transaksi</th>
                        <th>Pembayaran</th>
                        <th>Hutang</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($history as $index => $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->date)->format('d M Y H:i') }}</td>
                            <td class="text-success">
                                @if ($item->type === 'payment')
                                    Rp{{ number_format($item->amount, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-danger">
                                @if ($item->type === 'debt')
                                    Rp{{ number_format($item->amount, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $item->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
