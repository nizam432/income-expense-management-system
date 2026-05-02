@extends('adminlte::page')

@section('content')
@section('content_header')
<h1>Loan List</h1>
@stop
<div class="row">

    <div class="col-md-3">
        <div class="small-box bg-info">
            <div class="inner"><h4>{{ $totalLoans }}</h4><p>Total Loan</p></div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="small-box bg-warning">
            <div class="inner"><h4>{{ $totalPayable }}</h4><p>Total Payable</p></div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="small-box bg-success">
            <div class="inner"><h4>{{ $paid }}</h4><p>Total Paid</p></div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="small-box bg-danger">
            <div class="inner"><h4>{{ $due }}</h4><p>Due</p></div>
        </div>
    </div>

</div>
<div class="card">
    <div class="card-header">
        <a href="{{ route('loans.create') }}" class="btn btn-primary">Add Loan</a>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Title</th>
                <th>Provider</th>
                <th>Loan Amount</th>
                <th>Total Payable</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            @foreach($loans as $loan)
            <tr>
                <td>{{ $loan->loan_title }}</td>
                <td>{{ $loan->provider->name }}</td>
                <td>{{ $loan->loan_amount }}</td>
                <td>{{ $loan->total_payable }}</td>
                <td>{{ $loan->status }}</td>
                <td>
                    <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-info btn-sm">View</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection
