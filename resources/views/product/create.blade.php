@extends('adminlte::page')

@section('title', 'Add Product / Service')

@section('content_header')
    <h1>Add Product / Service</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title">New Product / Service</h3>
    </div>

    <div class="card-body">
        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- Category --}}
            <div class="form-group mb-3">
                <label for="category_id">Expense Category</label>
                <select name="category_id" id="category_id" class="form-control" >
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Product Name --}}
            <div class="form-group mb-3">
                <label for="name">Product / Service Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter product or service name" >
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{--
            <div class="form-group mb-3">
                <label for="unit">Unit</label>
                <select name="unit" id="unit" class="form-control" >
                    <option value="Pcs">Pieces (Pcs)</option>
                    <option value="Kilogram">Kilogram (KG)</option>
                    <option value="Gram">Gram (GM)</option>
                    <option value="Other">Other</option>
                </select>
            </div>
             --}}
             
            {{-- Image --}}
            <div class="form-group mb-3">
                <label for="image">Product Image (optional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                @error('image')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="form-group text-right">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Save Product
                </button>
                <a href="{{ route('product.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>
@stop
