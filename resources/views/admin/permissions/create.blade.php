@extends('adminlte::page')

@section('title', 'Create Permission')

@section('content_header')
    <h1>Create Permission</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.permissions.store') }}" method="POST">
            @csrf

            {{-- Permission Name --}}
            <div class="form-group">
                <label>Permission Name</label>
                <input type="text" name="name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name') }}" required>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Group Name --}}
            <div class="form-group mt-3">
                <label>Group Name</label>
                <input type="text" name="group_name"
                    class="form-control @error('group_name') is-invalid @enderror"
                    value="{{ old('group_name') }}">
                @error('group_name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary mt-3">Create Permission</button>
        </form>
    </div>
</div>
@stop
