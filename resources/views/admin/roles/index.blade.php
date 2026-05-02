@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <h1>Roles</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow">
    <div class="card-header d-flex justify-content-between">
        <h4 class="mb-0">Role List</h4>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Role
        </a>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="bg-light">
                <tr>
                    <th>#</th>
                    <th>Role Name</th>
                    <th>Permissions</th>
                    <th width="20%">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($roles as $key => $role)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            @foreach($role->permissions as $perm)
                                <span class="badge badge-info">{{ $perm->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a class="btn btn-sm btn-info"
                               href="{{ route('admin.roles.edit', $role->id) }}">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            <form action="{{ route('admin.roles.destroy', $role->id) }}"
                                  class="d-inline"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure?')">
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
    </div>
</div>

@stop
