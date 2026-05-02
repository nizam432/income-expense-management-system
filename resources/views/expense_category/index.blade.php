@extends('adminlte::page')

@section('title', 'Expense Categories')

@section('content_header')
    <h1 class="d-flex justify-content-between">
        <span>Expense Categories</span>
        <a href="{{ route('expense-category.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus-circle"></i> Add New
        </a>
    </h1>
@stop

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-info">
        <h3 class="card-title text-white"><i class="fas fa-list"></i> Category List</h3>
    </div>

    <div class="card-body">
        <table id="categoryTable" class="table table-bordered table-striped table-hover">
            <thead class="bg-secondary text-white">
                <tr>
                    <th width="5%">#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th width="20%">Created At</th>
                    <th width="15%">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@stop

@section('js')
{{-- DataTables CDN --}}
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js"></script>
<script>
$(document).ready(function () {
    $('#categoryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('expense-category.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'description', name: 'description', defaultContent: '' },

            {
                data: 'created_at',
                name: 'created_at',
                render: function (data) {
                    return moment(data).format('DD MMMM YYYY hh:mm A');
                }
            },

            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[3, "desc"]],
        pageLength: 25,
    });
});
</script>
@stop
