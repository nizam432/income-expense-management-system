<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct(){

        //$this->middleware('permission:dashboard.view')->only(['index','filter','loadData']);
    }    
        
    public function index()
    {

        // Default: this month
        $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate   = Carbon::now()->endOfMonth()->format('Y-m-d');

        return $this->loadData($startDate, $endDate);
    }

    // AJAX filter
    public function filter(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate   = $request->end_date   ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        return $this->loadData($startDate, $endDate);
    }

    private function loadData($startDate, $endDate)
    {
        // মোট Income
        $totalIncome = DB::table('incomes')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        // মোট Expense
        $totalExpense = DB::table('expense_details')
            ->join('expenses', 'expense_details.expense_id', '=', 'expenses.id')
            ->whereBetween('expenses.expense_date', [$startDate, $endDate])
            ->sum('expense_details.total');

        $currentBalance = $totalIncome - $totalExpense;

        // Daily Expense Chart
        $dailyExpenses = DB::table('expense_details')
            ->join('expenses', 'expense_details.expense_id', '=', 'expenses.id')
            ->select(
                DB::raw('DATE(expenses.expense_date) as date'),
                DB::raw('SUM(expense_details.total) as total')
            )
            ->whereBetween('expenses.expense_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates  = $dailyExpenses->pluck('date');
        $totals = $dailyExpenses->pluck('total');

        // Category-wise Expense Chart
        $categoryWise = DB::table('expense_details')
            ->join('expenses', 'expense_details.expense_id', '=', 'expenses.id')
            ->join('expense_categories', 'expense_details.category_id', '=', 'expense_categories.id')
            ->select(
                'expense_categories.name as category_name',
                DB::raw('SUM(expense_details.total) as total')
            )
            ->whereBetween('expenses.expense_date', [$startDate, $endDate])
            ->groupBy('expense_categories.id', 'expense_categories.name')
            ->get();

        $categoryLabels = $categoryWise->pluck('category_name');
        $categoryTotals = $categoryWise->pluck('total');

        $data = [
            'totalIncome'     => $totalIncome,
            'totalExpense'    => $totalExpense,
            'currentBalance'  => $currentBalance,
            'dates'           => $dates,
            'totals'          => $totals,
            'categoryLabels'  => $categoryLabels,
            'categoryTotals'  => $categoryTotals,
        ];

        // If AJAX request, return JSON
        if(request()->ajax()){
            return response()->json($data);
        }

        // Otherwise, return view
        return view('dashboard', $data);
    }
}
