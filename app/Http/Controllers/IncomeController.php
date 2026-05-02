<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    /**
     * Permission Middleware
     */
    public function __construct()
    {
        $this->middleware('permission:income.view')->only(['index']);
        $this->middleware('permission:income.create')->only(['create', 'store']);
        $this->middleware('permission:income.edit')->only(['edit', 'update']);
        $this->middleware('permission:income.delete')->only(['destroy']);
    }


    /**
     * Display a listing of incomes (READ)
     */
public function index(Request $request)
{
    $query = Income::query();

    // Search
    if ($request->search) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    // Month Range Filter
    $from = $request->from_month ?? now()->format('Y-m');
    $to   = $request->to_month ?? now()->format('Y-m');

    if ($from && $to) {
        $query->whereRaw("DATE_FORMAT(date, '%Y-%m') BETWEEN ? AND ?", [$from, $to]);
    }

    // Clone query BEFORE paginate (very important)
    $clone = clone $query;

    // Total Count (Search + month filter wise)
    $total_count = $clone->count();

    // Total Amount (Search wise + month filter wise)
    $total_amount = $clone->sum('amount');

    // Pagination
    $incomes = $query->orderBy('date', 'desc')->paginate(10);

    return view('income.index',
        compact('incomes', 'from', 'to', 'total_count', 'total_amount')
    );
}

    /**
     * Show the form for creating a new income (CREATE FORM)
     */
    public function create()
    {
        return view('income.create');
    }


    /**
     * Store a newly created income (CREATE - SAVE)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date'   => 'required|date',
        ]);

        Income::create($request->all());

        return redirect()
            ->route('income.index')
            ->with('success', 'Income added successfully!');
    }


    /**
     * Show the form for editing an income (EDIT FORM)
     */
    public function edit(Income $income)
    {
        return view('income.edit', compact('income'));
    }


    /**
     * Update the specified income (UPDATE)
     */
    public function update(Request $request, Income $income)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date'   => 'required|date',
        ]);

        $income->update($request->all());

        return redirect()
            ->route('income.index')
            ->with('success', 'Income updated successfully!');
    }


    /**
     * Remove the specified income (DELETE)
     */
    public function destroy(Income $income)
    {
        $income->delete();

        return redirect()
            ->route('income.index')
            ->with('success', 'Income deleted successfully!');
    }
}
