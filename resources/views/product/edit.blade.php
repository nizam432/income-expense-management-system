@extends('adminlte::page')

@section('title', 'Edit Product / Service')

@section('content_header')
    <h1 class="text-primary"><i class="fas fa-edit"></i> Edit Product / Service</h1>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow border-0">
            
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-box"></i> Update Product / Service</span>

            </div>

            <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">

                    {{-- Expense Category --}}
                    <div class="form-group mb-3">
                        <label for="category_id" class="fw-bold">Expense Category</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">-- Select Category --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Product / Service Name --}}
                    <div class="form-group mb-3">
                        <label for="name" class="fw-bold">Product / Service Name</label>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name', $product->name) }}" 
                               class="form-control" placeholder="Enter product or service name" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Product Image --}}
                    <div class="form-group mb-3">
                        <label for="image" class="fw-bold">Product Image (optional)</label>
                        <input type="file" name="image" id="image" class="form-control">
                        @if($product->image)
                            <div class="mt-2">
                                <img src="{{ asset('products/' . $product->image) }}" 
                                     alt="Product Image" width="100" class="rounded shadow border">
                            </div>
                        @endif
                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                <div class="card-footer bg-light text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                <a href="{{ route('product.index') }}" class="btn btn-default ">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>                    
                </div>

            </form>
        </div>
    </div>
</div>
@stop
