@extends('adminlte::page')

@section('title', 'Income List')

@section('content_header')
    <h1>Income List</h1>
@stop

@section('content')
<div class="card shadow-sm">

    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="fas fa-list"></i> All Incomes</h4>
        <a href="{{ route('income.create') }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Add Income
        </a>
    </div>

    <div class="card-body">

        {{-- Filter Form --}}
        <form method="GET" class="row mb-3">

            {{-- Search --}}
            <div class="col-md-4">
                <label class="small text-muted">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="form-control" placeholder="Search income...">
            </div>

            {{-- From Month --}}
            <div class="col-md-3">
                <label class="small text-muted">From Month</label>
                <input type="month" name="from_month" value="{{ $from }}" class="form-control">
            </div>

            {{-- To Month --}}
            <div class="col-md-3">
                <label class="small text-muted">To Month</label>
                <input type="month" name="to_month" value="{{ $to }}" class="form-control">
            </div>

            {{-- Filter Btn --}}
            <div class="col-md-1 mt-4">
                <button class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            {{-- Reset Btn --}}
            <div class="col-md-1 mt-4">
                <a href="{{ route('income.index') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-undo"></i>
                </a>
            </div>

        </form>

        {{-- TOTALS --}}
        <div class="alert alert-info">
            <strong>Total Records:</strong> {{ $total_count }}
            &nbsp; | &nbsp;
            <strong>Total Amount:</strong> {{ number_format($total_amount, 2) }}
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif

        {{-- Table --}}
        <table class="table table-bordered table-hover">
            <thead class="bg-light">
                <tr>
                    <th width="5%">#</th>
                    <th>Title</th>
                    <th width="15%">Amount</th>
                    <th width="15%">Date</th>
                    <th>Description</th>
                    <th width="15%">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($incomes as $income)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $income->title }}</td>
                        <td>{{ number_format($income->amount, 2) }}</td>
                        <td>{{ $income->date }}</td>
                        <td>{{ $income->description }}</td>

                        <td>
                            <a href="{{ route('income.edit', $income->id) }}" 
                               class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('income.destroy', $income->id) }}" 
                                  method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No income records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            {{ $incomes->appends(request()->query())->links() }}
        </div>

    </div>
</div>
@stop
