<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanProvider;
use App\Models\LoanPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function __construct(){
        $this->middleware('permission:loan.management.view')->only(['index']);
        $this->middleware('permission:loan.management.create')->only(['create', 'store']);
    }
    
    public function index()
    {
        $totalLoans = Loan::sum('loan_amount');
        $totalPayable = Loan::sum('total_payable');
        $paid = LoanPayment::sum('paid_amount');
        $due = $totalPayable - $paid;        
        $loans = Loan::with('provider')->latest()->get();
        return view('admin.loan.index', compact('loans','totalLoans','totalPayable','paid','due'));
    }

    public function create()
    {
        $providers = LoanProvider::all();
        return view('admin.loan.create', compact('providers'));
    }
    public function show($id)
    {
        $loan = Loan::with('installments')->findOrFail($id);
        return view('admin.loan.show', compact('loan'));
    }
    
public function store(Request $request)
{
    // Validation
    $request->validate([
        'provider_id' => 'required|exists:loan_providers,id',
        'loan_title' => 'required|string|max:255',
        'loan_amount' => 'required|numeric',
        'interest_rate' => 'nullable|numeric',
        'installment_type' => 'required|in:monthly,weekly,daily',
        'installment_amount' => 'required|numeric',
        'start_date' => 'required|date',
        'note' => 'nullable|string',
    ]);

    // Calculate total payable
    $interest = $request->interest_rate ?? 0;
    $totalPayable = $request->loan_amount + ($request->loan_amount * $interest / 100);

    // Calculate installment count
    $installmentCount = ceil($totalPayable / $request->installment_amount);

    // Create Loan
    $loan = Loan::create([
        'provider_id'       => $request->provider_id,
        'loan_title'        => $request->loan_title,
        'loan_amount'       => $request->loan_amount,
        'interest_rate'     => $interest,
        'total_payable'     => $totalPayable,
        'installment_type'  => $request->installment_type,
        'installment_amount'=> $request->installment_amount,
        'installment_count' => $installmentCount,
        'start_date'        => $request->start_date,
        'note'              => $request->note,
    ]);

    // Generate Installments
    $date = \Carbon\Carbon::parse($loan->start_date);
    for ($i = 1; $i <= $installmentCount; $i++) {
        LoanInstallment::create([
            'loan_id'        => $loan->id,
            'installment_no' => $i,
            'amount_paid'    => 0,
            'paid_date'      => null,
            'payment_method' => null,
        ]);

        if ($loan->installment_type == 'monthly') {
            $date->addMonth();
        } elseif ($loan->installment_type == 'weekly') {
            $date->addWeek();
        } else {
            $date->addDay();
        }
    }

    return redirect()->route('loans.index')->with('toast_success', 'Loan created successfully!');
}

}
