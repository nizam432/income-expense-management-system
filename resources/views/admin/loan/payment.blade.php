@extends('adminlte::page')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h4>Loan Installment Payment</h4>
    </div>

    <div class="card-body">

        <p><strong>Loan Title:</strong> {{ $installment->loan->loan_title }}</p>
        <p><strong>Installment Amount:</strong> {{ $installment->amount }}</p>
        <p><strong>Status:</strong> {{ ucfirst($installment->status) }}</p>

        <form action="{{ route('loan.installment.pay', $installment->id) }}" method="POST">
            @csrf

            <!-- Amount -->
            <label>Paid Amount</label>
            <input type="number" name="paid_amount" value="{{ $installment->amount }}" required class="form-control mb-2">

            <!-- Method -->
            <label>Payment Method</label>
            <select name="payment_method" class="form-control mb-2" required>
                <option value="">Select Method</option>
                <option value="cash">Cash</option>
                <option value="bank">Bank</option>
                <option value="bkash">Bkash</option>
                <option value="nagad">Nagad</option>
            </select>

            <!-- Date -->
            <label>Payment Date</label>
            <input type="date" name="paid_date" class="form-control mb-2" required>

            <!-- Notes -->
            <label>Notes</label>
            <textarea name="notes" class="form-control mb-2" rows="2"></textarea>

            <button class="btn btn-success w-100">Submit Payment</button>
        </form>
    </div>
</div>
@endsection
