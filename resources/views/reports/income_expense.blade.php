@extends('adminlte::page')

@section('title', 'Income vs Expense Report')

@section('content_header')
    <h1 class="text-primary fw-bold">
        <i class="fas fa-chart-pie"></i> Income vs Expense Report
    </h1>
@stop

@section('content')
<div class="card shadow-lg border-0 rounded-4">
    <div class="card-body">

        {{-- 🔍 Filter Form --}}
        <form method="GET" action="{{ route('reports.incomeExpenseReport') }}" class="row gy-2 gx-3 align-items-end mb-4">
            <div class="col-md-3">
                <label class="form-label fw-semibold text-secondary">From Date</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control rounded-3 shadow-sm">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold text-secondary">To Date</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control rounded-3 shadow-sm">
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button class="btn btn-primary rounded-3 me-2 shadow-sm">
                    <i class="fas fa-search"></i> Filter
                </button>
                {{-- 
                <a href="{{ route('reports.income_expense.export', ['from' => request('from'), 'to' => request('to')]) }}" class="btn btn-success rounded-3 shadow-sm">
                    <i class="fas fa-file-excel"></i> Export
                </a>
                --}}
            </div>
        </form>

        {{-- 📊 Summary --}}
        <div class="row mb-4 g-3">
            <div class="col-md-4">
                <div class="card border-0 shadow text-center p-4" style="background: linear-gradient(135deg, #28a745, #5dd35d); color:white;">
                    <h5 class="fw-semibold">Total Income</h5>
                    <h2 class="fw-bold mt-2">৳ {{ number_format($totalIncome, 2) }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow text-center p-4" style="background: linear-gradient(135deg, #dc3545, #f87171); color:white;">
                    <h5 class="fw-semibold">Total Expense</h5>
                    <h2 class="fw-bold mt-2">৳ {{ number_format($totalExpense, 2) }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow text-center p-4" style="background: linear-gradient(135deg, #17a2b8, #3ed3e9); color:white;">
                    <h5 class="fw-semibold">Net Balance</h5>
                    <h2 class="fw-bold mt-2">৳ {{ number_format($netBalance, 2) }}</h2>
                </div>
            </div>
        </div>

        {{-- 📋 Detailed Table --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle text-center rounded-3 overflow-hidden shadow-sm">
                <thead class="table-primary text-dark">
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Note</th>
                        <th>Amount (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            <td class="fw-semibold text-secondary">{{ $record['date'] }}</td>
                            <td>
                                @if($record['type'] == 'income')
                                    <span class="badge bg-success px-3 py-2">Income</span>
                                @else
                                    <span class="badge bg-danger px-3 py-2">Expense</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $record['note'] }}</td>
                            <td class="fw-bold {{ $record['type'] == 'income' ? 'text-success' : 'text-danger' }}">
                                {{ number_format($record['amount'], 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted py-3">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@stop
