@extends('adminlte::page')

@section('title', 'Add Income')

@section('content_header')
    <h1>Add New Income</h1>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Add Income</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('income.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label><strong>Title</strong></label>
                        <input type="text" name="title" class="form-control" placeholder="Enter income title" required>
                    </div>

                    <div class="form-group mb-3">
                        <label><strong>Amount</strong></label>
                        <input type="number" name="amount" class="form-control" placeholder="Enter amount" step="0.01" required>
                    </div>

                    <div class="form-group mb-3">
                        <label><strong>Date</strong></label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label><strong>Description</strong></label>
                        <textarea name="description" class="form-control" placeholder="Optional description..."></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('income.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Save Income
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
