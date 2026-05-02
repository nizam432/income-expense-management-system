<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ExpenseCategory;
use App\Models\ExpenseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Session;
class ProductController extends Controller
{
    /**
     * Permission Middleware
     */
    public function __construct()
    {

      //  echo 'ddd3332222222';  dd(session('locale')); echo 'ddd333'; exit; 
        $this->middleware('permission:product.service.view')->only(['index']);
        $this->middleware('permission:product.service.create')->only(['create', 'store']);
        $this->middleware('permission:product.service.edit')->only(['edit', 'update']);
    }

    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
            
 
 
        $query = Product::with('category');

        // 🔍 Filter by search text
        if ($search = $request->search) {
            $query->where('name', 'like', "%{$search}%");
        }

        // 🔍 Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('id', 'desc')->paginate(10);
        $categories = ExpenseCategory::orderBy('name')->get();

        return view('product.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = ExpenseCategory::all();
        return view('product.create', compact('categories'));
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'name'        => 'required|unique:products,name|max:255',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path('products'), $imageName);
            $imagePath = 'products/' . $imageName;
        }

        Product::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'image'       => $imagePath,
        ]);

        return redirect()->route('product.index')
                         ->with('toast_success', 'Product created successfully!');
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = ExpenseCategory::all();
        return view('product.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'name'        => 'required|string|max:255|unique:products,name,' . $id,
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $product->category_id = $request->category_id;
        $product->name = $request->name;

        // 🔄 Handle Image Upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('products'), $imageName);
            $product->image = 'products/' . $imageName;
        }

        $product->save();

        return redirect()->route('product.index')
                         ->with('toast_success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // 🔒 Prevent deletion if used in expense details
        $hasExpense = ExpenseDetail::where('product_id', $product->id)->exists();

        if ($hasExpense) {
            return redirect()->back()
                             ->with('toast_error', '❌ This product cannot be deleted because it is used in expense details.');
        }

        $product->delete();

        return redirect()->back()
                         ->with('toast_success', '✅ Product deleted successfully!');
    }
}
