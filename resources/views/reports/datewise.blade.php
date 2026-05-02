@extends('adminlte::page')

@section('title', 'Month Wise Income & Expense Report')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Month Wise Income & Expense Report</h3>
    </div>
    <div class="card-body">

        {{-- Month Range Form --}}
        <form method="GET" action="{{ route('reports.datewise') }}" class="form-inline mb-3">
            <div class="form-group mr-2">
                <label>Start Month: </label>
                <input type="month" name="start_month" class="form-control ml-2" value="{{ request('start_month') }}">
            </div>
            <div class="form-group mr-2">
                <label>End Month: </label>
                <input type="month" name="end_month" class="form-control ml-2" value="{{ request('end_month') }}">
            </div>
            <button type="submit" class="btn btn-success">Search</button>
        </form>

        {{-- Report Table --}}
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Income</th>
                    <th>Expense</th>
                    <th>Net</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalIncome = 0;
                    $totalExpense = 0;
                    $totalNet = 0;
                @endphp
                @foreach($report as $row)
                    @php
                        $totalIncome += $row['income'];
                        $totalExpense += $row['expense'];
                        $totalNet += ($row['income'] - $row['expense']);
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $row['month'])->format('F, Y') }}</td>
                        <td>{{ number_format($row['income'], 2) }}</td>
                        <td>{{ number_format($row['expense'], 2) }}</td>
                        <td>{{ number_format($row['income'] - $row['expense'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <th>{{ number_format($totalIncome, 2) }}</th>
                    <th>{{ number_format($totalExpense, 2) }}</th>
                    <th>{{ number_format($totalNet, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@stop
