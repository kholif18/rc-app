@extends('partial.master')

@section('title')
    Laporan Pendapatan
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Laporan Pendapatan
        </a>
    </li>
@endsection

@section('content')
    <div class="card">
        <h2>Laporan Pendapatan</h2>
    
        <div class="card mb-4">
            <div class="card-header">
                <form method="GET" class="row">
                    <div class="col-md-4">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" 
                            value="{{ request('start_date') ?? now()->startOfMonth()->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label>Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control" 
                            value="{{ request('end_date') ?? now()->endOfMonth()->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4 align-self-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-header">Jasa Ketik</div>
                            <div class="card-body">
                                <h4 class="card-title">Rp {{ number_format($servicesIncome->ketik_income, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-info mb-3">
                            <div class="card-header">Jasa Desain</div>
                            <div class="card-body">
                                <h4 class="card-title">Rp {{ number_format($servicesIncome->desain_income, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-header">Jasa Cetak</div>
                            <div class="card-body">
                                <h4 class="card-title">Rp {{ number_format($servicesIncome->cetak_income, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-header">Total Pendapatan</div>
                            <div class="card-body">
                                <h3 class="card-title">Rp {{ number_format($servicesIncome->total_income, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h4 class="mt-4">Detail Pembayaran</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                            <td>#{{ $payment->order->id }}</td>
                            <td>{{ $payment->order->customer->name }}</td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>{{ $payment->payment_method }}</td>
                            <td>{{ $payment->notes }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection