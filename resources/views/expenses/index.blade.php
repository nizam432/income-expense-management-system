@extends('adminlte::page')

@section('title', 'Expense Dashboard')

@section('content_header')
    <h1 class="m-0 text-dark"><i class="fas fa-wallet text-primary"></i> Expense Dashboard</h1>
@stop

@section('content')
<div class="card border-0 shadow-lg rounded-4">
    <div class="card-header bg-gradient-primary d-flex justify-content-between align-items-center rounded-top-4 py-3 px-4">
        <h3 class="card-title mb-0 text-white fw-bold"><i class="fas fa-chart-line"></i> Expense Overview</h3>
        <div>
            <a href="{{ route('expenses.create') }}" class="btn btn-light btn-sm fw-semibold shadow-sm me-2">
                <i class="fas fa-plus-circle"></i> Add Expense
            </a>
            <a href="{{ route('expenses.export', ['type' => 'excel']) }}" class="btn btn-success btn-sm shadow-sm me-2">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('expenses.export', ['type' => 'pdf']) }}" class="btn btn-danger btn-sm shadow-sm">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
        </div>
    </div>

    <div class="card-body bg-light">
        {{-- 🔍 Filter Form --}}
        <form action="{{ route('expenses.index') }}" method="GET" class="mb-4">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-muted fw-semibold">Search by Note</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control shadow-sm"
                           placeholder="Type something...">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted fw-semibold">From</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="form-control shadow-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted fw-semibold">To</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="form-control shadow-sm">
                </div>
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-primary w-100 shadow-sm"><i class="fas fa-search"></i> Filter</button>
                </div>
            </div>
        </form>

        {{-- 📋 Expense Table --}}
        <div class="table-responsive mt-3">
            <table class="table table-hover align-middle table-bordered text-center shadow-sm rounded-3 overflow-hidden">
                <thead class="bg-primary text-white sticky-top">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Note</th>
                        <th>Total (৳)</th>
                        <th width="180">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $expense)
                    
        
                        <tr>
                        
                            <td>{{ $loop->iteration + ($expenses->currentPage() - 1) * $expenses->perPage() }}</td>
                            <td><span class="badge bg-info">{{ \Carbon\Carbon::parse($expense->expense_date)->format('D d M Y') }}</span></td> 
                            <td class="text-start">{{ Str::limit($expense->note, 50) }}</td>
                            <td class="fw-bold text-success">৳ {{ number_format($expense->total_amount, 2) }}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm me-1 shadow-sm show-expense" 
                                    data-id="{{ $expense->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-warning btn-sm me-1 shadow-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @can('expense.delete')
                                <button type="button" class="btn btn-danger btn-sm shadow-sm delete-btn" data-id="{{ $expense->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                
                                <form id="delete-form-{{ $expense->id }}" action="{{ route('expenses.destroy', $expense->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted py-4"><i class="fas fa-info-circle"></i> No expenses found.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($expenses->count() > 0)
                <tfoot class="table-secondary fw-bold">
                    <tr>
                        <td colspan="4" class="text-end text-dark">Total:</td>
                        <td colspan="2" class="text-success">৳ {{ number_format($expenses->sum('total_amount'), 2) }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-end mt-3">
            {{ $expenses->links('pagination::bootstrap-5') }}
        </div>
        
        {{-- 📊 Expense Summary Graph --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold"><i class="fas fa-chart-bar text-primary"></i> Expense Summary (Last 30 Days)</div>
            <div class="card-body">
                <canvas id="expenseChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- 🔹 Expense Details Modal --}}
<div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="expenseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title fw-semibold" id="expenseModalLabel"><i class="fas fa-eye"></i> Expense Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
      </div>
      <div class="modal-body" id="expense-details-content" style="min-height:150px;">
        <div class="text-center text-muted">
            <i class="fas fa-spinner fa-spin"></i> Loading details...
        </div>
      </div>
    </div>
  </div>
</div>
@stop


@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 🗑️ Delete confirmation
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This expense will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    });

    // 👁 Show expense modal
    $(document).on('click', '.show-expense', function() {
        const id = $(this).data('id');
        $('#expenseModal').modal('show');
        $('#expense-details-content').html('<div class="text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
        $.get("{{ url('expenses') }}/" + id, function(data) {
            $('#expense-details-content').html(data);
        }).fail(() => {
            $('#expense-details-content').html('<div class="text-danger text-center py-3">❌ Failed to load expense details.</div>');
        });
    });

    // 📊 Expense Chart
    const ctx = document.getElementById('expenseChart').getContext('2d');
    const expenseData = @json($chartData ?? []);
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: expenseData.labels,
            datasets: [{
                label: 'Total Expense (৳)',
                data: expenseData.values,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
</script>
@stop
