@extends('adminlte::page')

@section('title', 'Product Wise Report')

@section('content_header')
    <h1 class="text-primary fw-bold">
        <i class="fas fa-boxes"></i> Product Wise Report
    </h1>
@stop

@section('content')
<div class="card shadow-lg border-0 rounded-4">
    <div class="card-body">

        {{-- 🔍 Filter Form --}}
        <form method="GET" action="{{ route('reports.product') }}" class="row gy-2 gx-3 align-items-end mb-4">
            <div class="col-md-2">
                <label class="form-label fw-semibold text-secondary">From Date</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control rounded-3 shadow-sm">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold text-secondary">To Date</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control rounded-3 shadow-sm">
            </div>
            {{-- 🏷️ Category Filter --}}
            <div class="col-md-2">
                <label class="form-label fw-semibold text-secondary">Category</label>
                <select name="category_id" class="form-control  select2 rounded-3 shadow-sm">
                    <option value="">-- All Categories --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>            
            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary rounded-3 me-2 shadow-sm">
                    <i class="fas fa-search"></i> Filter
                </button>
                {{-- 
                <a href="{{ route('reports.productWise.export', ['from' => request('from'), 'to' => request('to')]) }}" 
                   class="btn btn-success rounded-3 shadow-sm">
                    <i class="fas fa-file-excel"></i> Export
                </a>
                --}}
            </div>
        </form>

        {{-- 📊 Summary --}}
        <div class="row mb-4 g-3">
            <div class="col-md-4">
                <div class="card border-0 shadow text-center p-4" style="background: linear-gradient(135deg, #007bff, #42a5f5); color:white;">
                    <h5 class="fw-semibold">Total Products</h5>
                    <h2 class="fw-bold mt-2">{{ count($productSummary ?? []) }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow text-center p-4" style="background: linear-gradient(135deg, #28a745, #5dd35d); color:white;">
                    <h5 class="fw-semibold">Total Quantity </h5>
                    <h2 class="fw-bold mt-2">{{ number_format($totalQty ?? 0) }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow text-center p-4" style="background: linear-gradient(135deg, #ffc107, #ffd54f); color:white;">
                    <h5 class="fw-semibold">Total Price (৳)</h5>
                    <h2 class="fw-bold mt-2">৳ {{ number_format($totalAmount ?? 0, 2) }}</h2>
                </div>
            </div>
        </div>

        {{-- 📋 Product Report Table --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle text-center rounded-3 overflow-hidden shadow-sm">
                <thead class="table-primary text-dark">
                    <tr>
                        <th>SL</th>
                        <th>Product Name</th>
                        <th>Quantity Sold</th>
                        <th>Total Amount (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productSummary as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-semibold text-secondary">{{ $product->name }}</td> 
                            <td class="fw-bold text-success">{{ $product->total_qty }}</td>
                            <td class="fw-bold text-primary">৳ {{ number_format($product->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted py-3">No records found for selected period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@stop
