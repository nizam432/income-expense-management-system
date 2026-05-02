@extends('adminlte::page')

@section('title', 'Product / Service List')

@section('content_header')
    <h1>{{ __('messages.product_or_services_list') }}</h1>
@stop

@section('content')
<div class="card shadow-sm">
    {{-- ✅ Header --}}
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color:#007bff; color:white;">
        <h3 class="card-title mb-0"><i class="fas fa-boxes"></i> All Products / Services</h3>
        <a href="{{ route('product.create') }}" class="btn btn-light btn-sm fw-bold">
            <i class="fas fa-plus"></i> Add New
        </a>
    </div>

    <div class="card-body">
        {{-- ✅ Search + Category Filter --}}
        <form method="GET" action="{{ route('product.index') }}" class="row mb-3 g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control"
                    placeholder="Search by name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="category_id" class="form-control">
                    <option value="">-- Filter by Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('product.index') }}" class="btn btn-secondary">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </div>
        </form>

        {{-- ✅ Table --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Product / Service</th>
                        <th>Category</th>
                        <th style="width: 130px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $key => $product)
                        <tr>
                            <td class="text-center">{{ $products->firstItem() + $key }}</td>
                            <td class="text-center">
                                @if($product->image)
                                    <img src="{{ asset('products/'.$product->image) }}" alt="Product" width="50" height="50" class="rounded">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" width="50" height="50" class="rounded" alt="No image">
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category?->name ?? '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                 <form action="{{ route('product.destroy', $product->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger delete-btn" onclick="return confirm('Are you sure?')" >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No products found!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ✅ Pagination --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@stop
 
