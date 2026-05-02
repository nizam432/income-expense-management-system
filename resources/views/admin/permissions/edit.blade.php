@extends('adminlte::page')

@section('title', 'Edit Permission')

@section('content_header')
    <h1>Edit Permission</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Permission Name --}}
            <div class="form-group">
                <label>Permission Name</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $permission->name) }}" required>
            </div>

            {{-- Group Name --}}
            <div class="form-group mt-3">
                <label>Group Name</label>
                <input type="text" name="group_name" class="form-control"
                       value="{{ old('group_name', $permission->group_name) }}">
            </div>

            <button type="submit" class="btn btn-success mt-3">Update</button>
        </form>
    </div>
</div>
@stop
