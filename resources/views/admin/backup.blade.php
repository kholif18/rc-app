@extends('partial.master')

@section('title')
    Backup Database
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Backup Database
        </a>
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div style="color:red;">
                    <ul>
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div style="color:green;">
                    {{ session('success') }}
                </div>
            @endif
            <div class="row">
                <div class="col-6">
                    <h5>Export Database</h5>
                    <a href="{{ route('backup.export') }}" class="btn btn-success mt-2">Download Backup (.sql)</a>
                </div>
                <div class="col-6">
                    <h5>Import Database</h5>
                    <form action="{{ route('backup.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3 mt-2">
                            <input type="file" name="sql_file" class="form-control" accept=".sql,.txt" required>
                        </div>
                        <button class="btn btn-primary" type="submit" >Import</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection