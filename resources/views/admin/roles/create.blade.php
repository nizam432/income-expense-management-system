@extends('adminlte::page')

@section('title', 'Create Role')

@section('content_header')
    <h1>Create Role</h1>
@stop

@section('content')

<div class="card shadow">
    <div class="card-body">

        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Role Name</label>
                <input type="text" name="name"
                       class="form-control" required>
            </div>

            <hr>

            <div class="form-group">
                <input type="checkbox" id="checkAll">
                <label for="checkAll">All Permissions</label>
            </div>

            <div class="row">

                @foreach($permissions as $group => $perms)
                    <div class="col-md-4">
                        <div class="border rounded p-3 mb-3">

                            <input type="checkbox" class="group-check"
                                   data-group="{{ $group }}">
                            <strong>{{ $group }}</strong>

                            @foreach($perms as $perm)
                                <div class="ml-4 mt-1">
                                    <label>
                                        <input type="checkbox"
                                               name="permissions[]"
                                               class="permission-checkbox permission-{{ $group }}"
                                               value="{{ $perm->name }}">
                                        {{ $perm->name }}
                                    </label>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endforeach

            </div>

            <button type="submit" class="btn btn-primary mt-3">
                Save Role
            </button>

        </form>

    </div>
</div>

@stop


@section('js')
<script>
    $('#checkAll').change(function() {
        $('input[type=checkbox]').prop('checked', $(this).is(':checked'));
    });

    $('.group-check').change(function() {
        let group = $(this).data('group');
        $(".permission-" + group).prop('checked', $(this).is(':checked'));
    });
</script>
@stop
