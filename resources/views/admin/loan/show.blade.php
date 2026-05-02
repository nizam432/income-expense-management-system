@extends('adminlte::page')

@section('title', 'Loan Details')

@section('content_header')
<h1>Loan Details</h1>
@stop

@section('content')

<div class="card">
    <div class="card-header">
        <a href="{{ route('loans.index') }}" class="btn btn-secondary btn-sm">Back to Loans</a>
    </div>

    <div class="card-body">
        <!-- Loan Info -->
        <h4>Loan Information</h4>
        <table class="table table-bordered">
            <tr>
                <th>Title</th>
                <td>{{ $loan->loan_title }}</td>
            </tr>
            <tr>
                <th>Provider</th>
                <td>{{ $loan->provider->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Loan Amount</th>
                <td>{{ number_format($loan->loan_amount,2) }}</td>
            </tr>
            <tr>
                <th>Interest Rate</th>
                <td>{{ $loan->interest_rate }}%</td>
            </tr>
            <tr>
                <th>Total Payable</th>
                <td>{{ number_format($loan->total_payable,2) }}</td>
            </tr>
            <tr>
                <th>Installment Type</th>
                <td>{{ ucfirst($loan->installment_type) }}</td>
            </tr>
            <tr>
                <th>Installment Amount</th>
                <td>{{ number_format($loan->installment_amount,2) }}</td>
            </tr>
            <tr>
                <th>Total Installments</th>
                <td>{{ $loan->installment_count }}</td>
            </tr>
            <tr>
                <th>Start Date</th>
                <td>{{ $loan->start_date }}</td>
            </tr>
            <tr>
                <th>Note</th>
                <td>{{ $loan->note ?? '-' }}</td>
            </tr>
        </table>

        <!-- Installments Table -->
        <h4 class="mt-4">Installments</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Amount Paid</th>
                    <th>Paid Date</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
               @foreach($loan->installments as $inst)
        <tr>
            <td>{{ $inst->installment_no }}</td>
            <td>{{ number_format($inst->amount_paid,2) }}</td>
            <td>{{ $inst->paid_date ?? '-' }}</td>
            <td>{{ $inst->payment_method ?? '-' }}</td>
            <td>{{ number_format($inst->amount_paid,2) }} / {{ number_format($inst->loan->installment_amount,2) }}</td>
            <td>
                @if($inst->amount_paid >= $inst->loan->installment_amount)
                    <span class="badge bg-success">Paid</span>
                @else
                                   <a href="{{ route('loan.installment.form', $inst->id) }}" class="btn btn-sm btn-primary">
                    Pay
                </a>
                @endif
            </td>
        </tr>
@endforeach
            </tbody>
        </table>
    </div>
</div>

@stop
