@extends('adminlte::page')

@section('title', 'Edit Loan Provider')

@section('content_header')
<h1>Edit Loan Provider</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('loan-providers.update', $provider->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Important: PUT method for update -->

            <div class="form-group">
                <label for="name">Provider Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $provider->name) }}" required>
            </div>

            <div class="form-group">
                <label for="provider_type">Provider Type</label>
                <input type="text" name="provider_type" class="form-control" value="{{ old('provider_type', $provider->provider_type) }}" required>
            </div>

            <div class="form-group">
                <label for="account_number">Account Number</label>
                <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $provider->account_number) }}">
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('loan-providers.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@stop
