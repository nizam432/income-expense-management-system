@extends('adminlte::page')

@section('title', 'Monthly Expense Report')

@section('content_header')
    <h1><i class="fas fa-calendar-alt text-success"></i> Monthly Expense Report</h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-header bg-success text-white d-flex justify-content-between">
        <span><i class="fas fa-filter"></i> Select Month</span>

    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('reports.monthly') }}" class="row g-2 mb-3">
            <div class="col-md-4">
                <label>Month</label>
                <input type="month" name="month" value="{{ request('month', date('Y-m')) }}" class="form-control">
            </div>
            <div class="col-md-2 align-self-end">
                <button class="btn btn-success w-100"><i class="fas fa-search"></i> Filter</button>
            </div>
        </form>

        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="bg-success text-white">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Note</th>
                    <th>Total (৳)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $exp)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $exp->expense_date }}</td>
                    <td>{{ $exp->note }}</td>
                    <td class="fw-bold text-success">৳ {{ number_format($exp->total_amount, 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-muted">No records found</td></tr>
                @endforelse
            </tbody>
            @if($expenses->count() > 0)
            <tfoot class="fw-bold">
                <tr>
                    <td colspan="3" class="text-end">Total:</td>
                    <th>৳ {{ number_format($expenses->sum('total_amount'), 2) }}</th>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@stop
