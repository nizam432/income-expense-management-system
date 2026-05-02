<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ExpenseCategoryController extends Controller
{
    /**
     * Permission Middleware
     */
    public function __construct()
    {
        $this->middleware('permission:expense.category.view')->only(['index']);
        $this->middleware('permission:expense.category.create')->only(['create', 'store']);
        $this->middleware('permission:expense.category.edit')->only(['edit', 'update']);
        $this->middleware('permission:expense.category.delete')->only(['destroy']);
    }


    /**
     * Display category list (Supports DataTable AJAX)
     */
    public function index(Request $request)
    {
        // If DataTable Ajax request → return JSON
        if ($request->ajax()) {
            $query = ExpenseCategory::select(['id', 'name', 'description', 'created_at']);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                        <a href="' . route('expense-category.edit', $row->id) . '" 
                           class="btn btn-warning btn-sm">Edit</a>

                        <form action="' . route('expense-category.destroy', $row->id) . '" 
                              method="POST" style="display:inline-block;">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button class="btn btn-danger btn-sm" 
                                onclick="return confirm(\'Are you sure?\')">
                                Delete
                            </button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('expense_category.index');
    }


    /**
     * Show create form
     */
    public function create()
    {
        return view('expense_category.create');
    }


    /**
     * Store new category
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        ExpenseCategory::create($request->only(['name', 'description']));

        return redirect()->route('expense-category.index')
            ->with('toast_success', 'Expense category added successfully!');
    }


    /**
     * Show Edit form
     */
    public function edit(ExpenseCategory $expense_category)
    {
        return view('expense_category.edit', compact('expense_category'));
    }


    /**
     * Update category
     */
    public function update(Request $request, ExpenseCategory $expense_category)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $expense_category->update($request->only(['name', 'description']));

        return redirect()->route('expense-category.index')
            ->with('toast_success', 'Expense category updated successfully!');
    }


    /**
     * Delete category if no product exists under it
     */
    public function destroy(ExpenseCategory $expense_category)
    {
        // Safety check → Prevent delete if category has products
        if ($expense_category->products()->exists()) {
            return redirect()->route('expense-category.index')
                ->with('toast_error', 'Cannot delete this category because products exist under it.');
        }

        $expense_category->delete();

        return redirect()->route('expense-category.index')
            ->with('toast_success', 'Expense category deleted successfully!');
    }

}
