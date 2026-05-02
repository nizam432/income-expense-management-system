@extends('adminlte::page')

@section('title', 'Category Wise Expense Report')

@section('content_header')
    <h1><i class="fas fa-chart-pie"></i> Category Wise Expense Report</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Filter Report</h4>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.category') }}">
            <div class="row">
                {{-- From Date --}}
                <div class="col-md-3">
                    <label>From Date</label>
                    <input type="date" name="from" value="{{ $from ?? date('Y-m-01') }}" class="form-control" required>
                </div>
                {{-- To Date --}}
                <div class="col-md-3">
                    <label>To Date</label>
                    <input type="date" name="to" value="{{ $to ?? date('Y-m-d') }}" class="form-control" required>
                </div>
                {{-- Category Select --}}
                <div class="col-md-3">
                    <label>Category</label>
                    <select name="categories[]" class="form-control" multiple>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                @if(isset($selectedCategories) && in_array($category->id, $selectedCategories)) selected @endif>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hold Ctrl (Windows) / Cmd (Mac) to select multiple</small>
                </div>
                {{-- Submit Button --}}
                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-search"></i> Generate
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Report Table --}}
@if(isset($report) && count($report) > 0)
<div class="card mt-3">
    <div class="card-header bg-info text-white">
        <h4 class="mb-0">Result Summary</h4>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th style="width: 10%">SL</th>
                    <th>Category Name</th>
                    <th style="width: 25%">Total Expense (৳)</th>
                </tr>
            </thead>
            <tbody>
                @php $grand_total = 0; @endphp
                @foreach($report as $key => $row)
                    @php $grand_total += $row->total_amount; @endphp
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $row->category_name }}</td>
                        <td>{{ number_format($row->total_amount, 2) }}</td>
                    </tr>
                @endforeach
                <tr class="bg-light fw-bold">
                    <td colspan="2" class="text-right">Grand Total</td>
                    <td>{{ number_format($grand_total, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@else
    <div class="alert alert-warning mt-3">
        <i class="fas fa-exclamation-circle"></i> No data found for the selected date range or category.
    </div>
@endif
@stop

@section('css')
<style>
.card-header h4 {
    font-size: 1.1rem;
    font-weight: 600;
}
.table th, .table td {
    vertical-align: middle;
}
</style>
@stop
