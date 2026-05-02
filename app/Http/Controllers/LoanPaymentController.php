<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoanInstallment;
use App\Models\LoanPayment;
use Carbon\Carbon;

class LoanPaymentController extends Controller
{
    public function __construct(){
        $this->middleware('permission:loan.management.payment')->only(['paymentForm','pay']);

    }
    // Show payment form
    public function paymentForm($installment_id)
    {
        $installment = \App\Models\LoanInstallment::with('loan')->findOrFail($installment_id);
        return view('admin.loan.payment', compact('installment'));
    }
    
    public function pay(Request $request, $installment_id)
    {
        $request->validate([
            'paid_amount'=>'required|numeric|min:0.01',
            'payment_method'=>'required|string|max:50',
            'paid_date'=>'required|date',
        ]);

        $installment = \App\Models\LoanInstallment::findOrFail($installment_id);

        $remaining = $installment->loan->installment_amount - $installment->amount_paid;

        if($request->paid_amount > $remaining){
            return back()->with('toast_error','Paid amount cannot exceed remaining installment amount.');
        }

        $installment->amount_paid += $request->paid_amount; // partial payment
        $installment->payment_method = $request->payment_method;
        $installment->paid_date = $request->paid_date;
        $installment->save();
        
      // Create LoanPayment entry
        LoanPayment::create([
            'loan_id'        => $installment->loan_id,
            'installment_id' => $installment->id,
            'paid_amount'    => $request->paid_amount,
            'paid_date'      => Carbon::now(),
            'payment_method' => $request->payment_method ?? 'cash',
            'transaction_id' => $request->transaction_id ?? null,
        ]);
        
        return redirect()->route('loans.show', $installment->loan_id)
                         ->with('toast_success','Installment Paid Successfully!');
    }
}
