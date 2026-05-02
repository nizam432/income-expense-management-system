<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseDetail;
use App\Models\ExpenseCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExpensesExport;

class ExpenseController extends Controller
{
    public function __construct(){
        $this->middleware('permission:expense.view')->only(['index']);
        $this->middleware('permission:expense.create')->only(['create','store']);
        $this->middleware('permission:expense.edit')->only(['edit','update']);
        $this->middleware('permission:expense.delete')->only(['destroy']);
    }    
    
    /**
     * ============================
     * 🔹 Export Excel / PDF
     * ============================
     */
    public function export(Request $request, $type)
    {
        $from = $request->from;
        $to   = $request->to;

        // Excel Export
        if ($type === 'excel') {
            return Excel::download(new ExpensesExport($from, $to), 'expenses.xlsx'); 
        } 
        // PDF Export
        elseif ($type === 'pdf') {
            $expenses = (new ExpensesExport($from, $to))->view()->getData()['expenses'];
            $pdf = Pdf::loadView('exports.expenses', compact('expenses'));
            return $pdf->download('expenses.pdf');
        }

        // Invalid Type
        return redirect()->back()->with('toast_error', 'Invalid export type!');
    }


    /**
     * ============================
     * 🔹 Category অনুযায়ী Product list আনবে (Ajax)
     * ============================
     */
    public function getProductsByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($products);
    }    


    /**
     * ============================
     * 🔹 Expense details দেখাবে (Modal / Page)
     * ============================
     */
    public function show($id)
    {
        $expense = Expense::with(['details.category', 'details.product'])->findOrFail($id);
        return view('expenses.partials.show', compact('expense'));
    }


    /**
     * ============================
     * 🔹 Expense Edit Page
     * ============================
     */
    public function edit($id)
    {
        $expense = Expense::with('details')->findOrFail($id);
        $categories = ExpenseCategory::all();
        $products = Product::all(); 

        return view('expenses.edit', compact('expense', 'categories', 'products'));
    }


    /**
     * ============================
     * 🔹 Expense Update Function
     * ============================
     */
    public function update(Request $request, $id)
    {
        // ✅ Validation
        $request->validate([
            'expense_date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        // ✅ Update main expense
        $expense = Expense::findOrFail($id);
        $expense->expense_date = $request->expense_date;
        $expense->note = $request->note ?? '';
        $expense->total_amount = collect($request->products)->sum(function($p) {
            return ($p['unit'] === 'Gram') ? $p['price'] : $p['quantity'] * $p['price'];
        });
        $expense->save();

        // 🔁 পুরনো details ডিলিট করে নতুনগুলো ইনসার্ট করবে
        $expense->details()->delete();

        foreach ($request->products as $item) {
            $expense->details()->create([
                'category_id' => $item['category_id'],
                'product_id'  => $item['product_id'],
                'quantity'    => $item['quantity'],
                'unit'        => $item['unit'],
                'price'       => $item['price'],
                'total'       => ($item['unit'] === 'Gram') ? $item['price'] : $item['quantity'] * $item['price'],
            ]);
        }

        return redirect()->route('expenses.index')->with('toast_success', 'Expense updated successfully!');
    }


    /**
     * ============================
     * 🔹 Expense List (Index Page)
     * ============================
     */
    public function index(Request $request)
    {
        $query = Expense::where('id', '!=', NULL);

        // Search by note
        if ($request->filled('search')) {
            $query->where('note', 'like', '%' . $request->search . '%');
        }

        // Date filter
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        // Pagination
        $expenses = $query->orderBy('expense_date', 'desc')->paginate(30);
        $categories = ExpenseCategory::all();

        // 🔸 Chart Data (Last 30 Days)
        $chartData = [
            'labels' => Expense::whereBetween('created_at', [now()->subDays(30), now()])
                ->selectRaw('DATE(created_at) as date')
                ->groupBy('date')
                ->pluck('date'),
            'values' => Expense::whereBetween('created_at', [now()->subDays(30), now()])
                ->selectRaw('SUM(total_amount) as total')
                ->groupByRaw('DATE(created_at)')
                ->pluck('total'),
        ];

        return view('expenses.index', compact('expenses', 'categories', 'chartData'));
    }


    /**
     * ============================
     * 🔹 Expense Create Page
     * ============================
     */
    public function create()
    {
        $categories = ExpenseCategory::all();
        $products = Product::all();
        $units = ['Pieces', 'Kilogram (KG)', 'Gram (GM)', 'Other'];

        return view('expenses.create', compact('categories', 'products', 'units'));
    }


    /**
     * ============================
     * 🔹 Store Expense (Save new)
     * ============================
     */
    public function store(Request $request)
    {
        // Total amount calculate করবে
        $totalAmount = 0;
        foreach ($request->products as $p) {
            if (strtolower($p['unit']) === 'gram' || strtolower($p['unit']) === 'gm') {
                $totalAmount += $p['price'];
            } else {
                $totalAmount += ($p['quantity'] * $p['price']);
            }
        }          

        // ✅ Step 1: Save main expense
        $expense = Expense::create([
            'expense_date' => $request->expense_date,
            'note' => $request->note,
            'total_amount' =>  $totalAmount,
        ]);

        // ✅ Step 2: Save expense details
        if ($request->has('products')) {
            foreach ($request->products as $item) {
                if (!empty($item['product_id'])) {
                    ExpenseDetail::create([
                        'expense_id' => $expense->id,
                        'category_id' => $item['category_id'],
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit' => $item['unit'],
                        'price' => $item['price'],
                        'total' => ($item['unit'] === 'Gram') 
                            ? $item['price'] 
                            : $item['quantity'] * $item['price'],
                    ]);
                }
            }
        }

        return redirect()->route('expenses.index')->with('toast_success', 'Expense created successfully.');
    }
    

    /**
     * ============================
     * 🔹 Delete Expense + Details
     * ============================
     */
    public function destroy($id)
    {
        try {
            $expense = Expense::findOrFail($id);
            $expense->delete();

            return redirect()->route('expenses.index')
                ->with('toast_success', 'Expense and its details deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('expenses.index')
                ->with('toast_error', 'Error: ' . $e->getMessage());
        }
    }   

}
