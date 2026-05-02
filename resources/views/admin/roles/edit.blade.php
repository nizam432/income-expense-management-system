@extends('adminlte::page')

@section('title', 'Edit Role')

@section('content_header')
    <h1>Edit Role</h1>
@stop

@section('content')

<div class="card shadow">
    <div class="card-body">

        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Role Name</label>
                <input type="text" name="name"
                       class="form-control" required
                       value="{{ old('name', $role->name) }}">
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
                            <strong>{{ ucfirst($group) }}</strong>

                            @foreach($perms as $perm)
                                <div class="ml-4 mt-1">
                                    <label>
                                        <input type="checkbox"
                                               name="permissions[]"
                                               class="permission-checkbox permission-{{ $group }}"
                                               value="{{ $perm->name }}"
                                               {{$role->hasPermissionTo($perm->name) ? 'checked' : '' }}>
                                        {{ $perm->name }}
                                    </label>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endforeach

            </div>

            <button type="submit" class="btn btn-primary mt-3">
                Update Role
            </button>

        </form>

    </div>
</div>

@stop

@section('js')
<script>
    // Check/uncheck all
    $('#checkAll').change(function() {
        $('input[type=checkbox]').prop('checked', $(this).is(':checked'));
    });

    // Check/uncheck group
    $('.group-check').change(function() {
        let group = $(this).data('group');
        $(".permission-" + group).prop('checked', $(this).is(':checked'));
    });

    // Optional: auto-check group if all child permissions are checked
    $('.permission-checkbox').change(function() {
        let classes = $(this).attr('class').split(/\s+/);
        classes.forEach(function(cls){
            if(cls.startsWith('permission-')){
                let group = cls.replace('permission-', '');
                let allChecked = $(".permission-" + group).length === $(".permission-" + group + ":checked").length;
                $(".group-check[data-group='"+group+"']").prop('checked', allChecked);
            }
        });

        // Check 'All Permissions' if all checkboxes are checked
        let allChecked = $('input.permission-checkbox').length === $('input.permission-checkbox:checked').length;
        $('#checkAll').prop('checked', allChecked);
    });

    // Trigger change on page load to update group/all checkboxes
    $(document).ready(function(){
        $('.permission-checkbox').trigger('change');
    });
</script>
@stop
