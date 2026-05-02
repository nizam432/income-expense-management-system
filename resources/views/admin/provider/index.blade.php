@extends('adminlte::page')

@section('title', 'Loan Providers')

@section('content_header')
<h1>Loan Providers</h1>
@stop

@section('content')



<div class="card">
    <div class="card-header">
        <a href="{{ route('loan-providers.create') }}" class="btn btn-primary">Add Provider</a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Account</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($providers as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->provider_type }}</td>
                    <td>{{ $p->account_number }}</td>
                    <td>
                        <a href="{{ route('loan-providers.edit', $p->id) }}" class="btn btn-info btn-sm">Edit</a>
              
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
