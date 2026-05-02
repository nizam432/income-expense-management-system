@extends('adminlte::page')

@section('title', 'Edit Income')

@section('content_header')
    <h1>Edit Income</h1>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Income</h4>
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

                <form action="{{ route('income.update', $income->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label><strong>Title</strong></label>
                        <input type="text" name="title" class="form-control" value="{{ $income->title }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label><strong>Amount</strong></label>
                        <input type="number" name="amount" class="form-control" step="0.01" value="{{ $income->amount }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label><strong>Date</strong></label>
                        <input type="date" name="date" class="form-control" value="{{ $income->date }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label><strong>Description</strong></label>
                        <textarea name="description" class="form-control">{{ $income->description }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('income.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
