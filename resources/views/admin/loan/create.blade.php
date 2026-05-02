@extends('adminlte::page')

@section('content')

@section('content_header')
<h1>Add Loan</h1> 
@stop

<div class="card">
    <div class="card-header">Add Loan</div>

    <div class="card-body">
        <form action="{{ route('loans.store') }}" method="POST">
            @csrf 

            <div class="form-group">
                <label>Provider</label>
                <select name="provider_id" class="form-control">
                    @foreach($providers as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Loan Title</label>
                <input name="loan_title" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Loan Amount</label>
                <input name="loan_amount" type="number" class="form-control">
            </div>

            <div class="form-group">
                <label>Interest Rate (%)</label> 
                <input name="interest_rate" type="text" class="form-control">
            </div>

            <div class="form-group">
                <label>Total Payable</label>
                <input name="total_payable" type="number" class="form-control">
            </div>

            <div class="form-group">
                <label>Installment Amount</label>
                <input name="installment_amount" type="number" class="form-control">
            </div>

            <div class="form-group">
                <label>Installment Type</label>
                <select name="installment_type" class="form-control">
                    <option value="monthly">Monthly</option>
                    <option value="weekly">Weekly</option>
                    <option value="daily">Daily</option>
                </select>
            </div>

            <div class="form-group">
                <label>Start Date</label>
                <input name="start_date" type="date" class="form-control">
            </div>

            <button class="btn btn-success">Save</button>
        </form>
    </div>
</div>
@endsection
