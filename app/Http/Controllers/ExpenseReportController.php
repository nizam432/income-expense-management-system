<?php 

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseDetail;
use App\Models\ExpenseCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericReportExport;

class ExpenseReportController extends Controller
{
    /**
     * 1. Datewise Income & Expense Report
     */
    public function datewiseReport(Request $request)
    {
        $startMonth = $request->start_month ?? date('Y-m'); // default: this month
        $endMonth   = $request->end_month ?? date('Y-m');

        $startDate = Carbon::createFromFormat('Y-m', $startMonth)->startOfMonth()->toDateString();
        $endDate   = Carbon::createFromFormat('Y-m', $endMonth)->endOfMonth()->toDateString();

        // Expenses
        $expenses = DB::table('expenses')
            ->select(DB::raw('DATE_FORMAT(expense_date, "%Y-%m") as month'), DB::raw('SUM(total_amount) as total_expense'))
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Income
        $incomes = DB::table('incomes')
            ->select(DB::raw('DATE_FORMAT(date, "%Y-%m") as month'), DB::raw('SUM(amount) as total_income'))
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Merge months
        $months = $incomes->pluck('month')->merge($expenses->pluck('month'))->unique()->sort();

        $report = [];
        foreach ($months as $month) {
            $report[] = [
                'month'   => $month,
                'income'  => $incomes->firstWhere('month', $month)->total_income ?? 0,
                'expense' => $expenses->firstWhere('month', $month)->total_expense ?? 0,
            ];
        }

        return view('reports.datewise', compact('report'));
    }


    /**
     * 2. Daily Expense Report
     */
    public function daily(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        $expenses = Expense::whereDate('created_at', $date)->get();

        return view('reports.daily', compact('expenses', 'date'));
    }


    /**
     * 3. Monthly Expense Report
     */
    public function monthly(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        $expenses = Expense::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$month])->get();

        return view('reports.monthly', compact('expenses', 'month'));
    }


    /**
     * 4. Category Wise Report
     */
    public function categoryWise(Request $request)
    {
        $from = $request->from ?? date('Y-m-01');
        $to   = $request->to ?? date('Y-m-d');
        $selectedCategories = $request->categories ?? [];

        $categories = ExpenseCategory::all();

        $query = DB::table('expense_details')
            ->join('expenses', 'expense_details.expense_id', '=', 'expenses.id')
            ->join('expense_categories', 'expense_details.category_id', '=', 'expense_categories.id')
            ->select('expense_categories.id', 'expense_categories.name as category_name', DB::raw('SUM(expense_details.total) as total_amount'))
            ->whereBetween('expenses.expense_date', [$from, $to]);

        if (!empty($selectedCategories)) {
            $query->whereIn('expense_categories.id', $selectedCategories);
        }

        $report = $query->groupBy('expense_categories.id', 'expense_categories.name')
                        ->orderBy('expense_categories.name')
                        ->get();

        $grand_total = $report->sum('total_amount');

        return view('reports.category', compact('report', 'categories', 'from', 'to', 'selectedCategories', 'grand_total'));
    }


    /**
     * 5. Product Wise Report
     */
    public function productWise(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to ?? now()->endOfMonth()->toDateString();

        $query = DB::table('expense_details')
            ->join('products', 'expense_details.product_id', '=', 'products.id')
            ->join('expenses', 'expenses.id', '=', 'expense_details.expense_id')
            ->join('expense_categories', 'products.category_id', '=', 'expense_categories.id')
            ->select(
                'products.name',
                'expense_categories.name as category_name',
                'expense_details.quantity as total_qty',
                'expense_details.total as total_amount'
            )
            ->whereBetween('expenses.expense_date', [$from, $to]);

        if ($request->category_id) {
            $query->where('expense_details.category_id', $request->category_id);
        }

        $productSummary = $query->get();
        $totalQty = $productSummary->sum('total_qty');
        $totalAmount = $productSummary->sum('total_amount');
        $categories = ExpenseCategory::all();

        return view('reports.product_wise', compact('productSummary', 'totalQty', 'totalAmount', 'categories', 'from', 'to'));
    }


    /**
     * 6. User Wise Report (if multi-user system)
     */
    public function userWise()
    {
        $data = Expense::with('user')
            ->select('user_id', DB::raw('SUM(total_amount) as total'))
            ->groupBy('user_id')
            ->get();

        return view('reports.user', compact('data'));
    }


    /**
     * 7. Income vs Expense Report
     */
    public function incomeExpenseReport(Request $request)
    {
        $from = $request->from ?? date('Y-m-01');
        $to   = $request->to ?? date('Y-m-t');

        $incomes  = \App\Models\Income::whereBetween('date', [$from, $to])->get();
        $expenses = Expense::whereBetween('expense_date', [$from, $to])->get();

        $records = [];

        foreach ($incomes as $inc) {
            $records[] = [
                'date'   => $inc->date,
                'type'   => 'income',
                'note'   => $inc->note,
                'amount' => $inc->amount,
            ];
        }

        foreach ($expenses as $exp) {
            $records[] = [
                'date'   => $exp->expense_date,
                'type'   => 'expense',
                'note'   => $exp->note,
                'amount' => $exp->total_amount,
            ];
        }

        usort($records, fn($a, $b) => strcmp($a['date'], $b['date']));

        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('total_amount');
        $netBalance = $totalIncome - $totalExpense;

        return view('reports.income_expense', compact('records', 'totalIncome', 'totalExpense', 'netBalance', 'from', 'to'));
    }


    /**
     * 8. Custom Date Range Expense Report
     */
    public function custom(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to ?? now()->toDateString();

        $expenses = Expense::whereBetween('created_at', [$from, $to])->get();
        return view('reports.custom', compact('expenses', 'from', 'to'));
    }


    /**
     * 9. Top Categories Report
     */
    public function topCategories()
    {
        $data = ExpenseDetail::select('category_id', DB::raw('SUM(total) as total'))
            ->with('category')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('reports.top_categories', compact('data'));
    }


    /**
     * 10. Export Reports (Excel / PDF)
     */
    public function export($type, $report)
    {
        $filename = ucfirst($report) . '_Report_' . now()->format('d_m_Y');
        $data = [];

        switch ($report) {
            case 'daily':
                $data = Expense::whereDate('created_at', now())->get();
                break;
            case 'monthly':
                $data = Expense::whereMonth('created_at', now()->month)->get();
                break;
            case 'category':
                $data = ExpenseDetail::with('category')->get();
                break;
        }

        if ($type == 'excel') {
            return Excel::download(new GenericReportExport($data), "$filename.xlsx");
        } elseif ($type == 'pdf') {
            $pdf = PDF::loadView('exports.report_pdf', compact('data', 'report'));
            return $pdf->download("$filename.pdf");
        }
    }
}
