<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Show all categories (READ)
     */
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }


    /**
     * Show create form (CREATE - FORM)
     */
    public function create()
    {
        return view('categories.create');
    }


    /**
     * Store new category (CREATE - SAVE)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'type' => 'required|in:income,expense',
        ]);

        Category::create([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return redirect()
            ->route('categories.index')
            ->with('success', 'ক্যাটাগরি সফলভাবে তৈরি হয়েছে।');
    }


    /**
     * Show edit form (EDIT - FORM)
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }


    /**
     * Update category (EDIT - UPDATE)
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'  => 'required|string|max:255|unique:categories,name,' . $category->id,
            'type'  => 'required|in:income,expense',
        ]);

        $category->update([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return redirect()
            ->route('categories.index')
            ->with('success', 'ক্যাটাগরি সফলভাবে আপডেট হয়েছে।');
    }


    /**
     * Delete category (DELETE)
     */
    public function destroy(Category $category)
    {
        // ভবিষ্যতে যদি ক্যাটাগরির সাথে ট্রানজ্যাকশন যুক্ত থাকে → আগে চেক করতে হবে
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'ক্যাটাগরি সফলভাবে ডিলিট হয়েছে।');
    }
}
