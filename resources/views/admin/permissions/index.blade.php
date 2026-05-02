@extends('adminlte::page')

@section('title', 'Permissions')

@section('content_header')
    <h1>Permissions List</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">All Permissions</h4>
        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Permission
        </a>
    </div>

    <div class="card-body">

        @foreach($permissions as $group => $perms)
            <h5 class="mt-4 mb-2 text-primary">
                <i class="fas fa-folder-open"></i> {{ $group ?? 'Ungrouped' }}
            </h5>

            <table class="table table-bordered table-striped">
                <thead class="bg-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Name</th>
                        <th width="20%">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($perms as $key => $perm)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $perm->name }}</td>

                            <td>
                                <a href="{{ route('admin.permissions.edit', $perm->id) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <form action="{{ route('admin.permissions.destroy', $perm->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure to delete this permission?');">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        @endforeach

    </div>
</div>

@stop
