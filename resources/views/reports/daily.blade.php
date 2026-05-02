@extends('adminlte::page')

@section('title', 'Daily Expense Report')

@section('content_header')
<h1><i class="fas fa-calendar-day text-primary"></i> Daily Expense Report</h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between">
        <span>Report for: {{ \Carbon\Carbon::parse($date)->format('d M, Y') }}</span>
        <a href="{{ route('reports.export', ['type'=>'pdf','report'=>'daily']) }}" class="btn btn-light btn-sm">
            <i class="fas fa-file-pdf text-danger"></i> Export PDF
        </a>
    </div>
    <div class="card-body">
        <table class="table table-bordered text-center">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Note</th>
                    <th>Total (৳)</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $index => $expense)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $expense->note }}</td>
                    <td>{{ number_format($expense->total_amount, 2) }}</td>
                    <td>{{ $expense->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
