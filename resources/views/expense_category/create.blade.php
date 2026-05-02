@extends('adminlte::page')

@section('title', 'Add Expense Category')

@section('content_header')
    <h1>Add Expense Category</h1>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-plus"></i> Add New Category</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('expense-category.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label><strong>Name</strong></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label><strong>Description</strong></label>
                        <textarea name="description" class="form-control" placeholder="Optional"></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('expense-category.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
