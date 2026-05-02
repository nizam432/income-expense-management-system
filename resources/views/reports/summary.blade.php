@extends('adminlte::page')

@section('title', 'Expense Summary Report')

@section('content_header')
    <h1><i class="fas fa-chart-pie text-primary"></i> Expense Summary Report</h1>
@stop

@section('content')
<div class="card shadow-lg">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <span><i class="fas fa-filter"></i> Summary Filter</span>
        <div>
            <a href="{{ route('reports.summary.export', ['type' => 'excel']) }}" class="btn btn-light btn-sm">
                <i class="fas fa-file-excel text-success"></i> Excel
            </a>
            <a href="{{ route('reports.summary.export', ['type' => 'pdf']) }}" class="btn btn-light btn-sm">
                <i class="fas fa-file-pdf text-danger"></i> PDF
            </a>
        </div>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('reports.summary') }}" class="row g-2 mb-4">
            <div class="col-md-3">
                <label>From Date</label>
                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
            </div>
            <div class="col-md-3">
                <label>To Date</label>
                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="col-md-3">
                <label>Category</label>
                <select name="category_id" class="form-control">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id')==$cat->id?'selected':'' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 align-self-end">
                <button class="btn btn-primary w-100"><i class="fas fa-search"></i> Filter</button>
            </div>
        </form>

        <div class="row">
            <div class="col-md-6">
                <canvas id="summaryChart"></canvas>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered align-middle text-center">
                    <thead class="bg-light">
                        <tr>
                            <th>Category</th>
                            <th>Total (৳)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($summary as $item)
                        <tr>
                            <td>{{ $item->category->name ?? '-' }}</td>
                            <td class="fw-bold text-success">৳ {{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('summaryChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: {!! json_encode($chartData['labels'] ?? []) !!},
        datasets: [{
            data: {!! json_encode($chartData['values'] ?? []) !!},
            backgroundColor: ['#007bff','#28a745','#ffc107','#dc3545','#17a2b8']
        }]
    }
});
</script>
@stop
