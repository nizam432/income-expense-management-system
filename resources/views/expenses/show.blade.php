@extends('adminlte::page')

@section('title', 'Expense Details')

@section('content_header')
    <h1>Expense Details</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Expense #{{ $expense->id }}</h5>
    </div>

    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <strong>Category:</strong> {{ $expense->category->name ?? 'N/A' }}
            </div>
            <div class="col-md-4">
                <strong>Date:</strong> {{ \Carbon\Carbon::parse($expense->expense_date)->format('d M, Y') }}
            </div>
            <div class="col-md-4">
                <strong>Description:</strong> {{ $expense->description ?? '-' }}
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expense->details as $detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $detail->product->name ?? 'N/A' }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ $detail->unit }}</td>
                        <td>{{ number_format($detail->price, 2) }}</td>
                        <td>@if($detail->unit=='Gram' )
                            {{ number_format($detail->quantity * $detail->price, 2) }}
                            @else
                            {{$detail->price}}
                            @endif
                                </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">Total Amount:</th>
                    <th>{{ number_format($totalAmount, 2) }}</th>
                </tr>
            </tfoot>
        </table>

        <div class="mt-3">
            <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
                ← Back to List
            </a>
            <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>
</div>
@stop
