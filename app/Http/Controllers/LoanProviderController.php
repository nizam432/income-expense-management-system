<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoanProvider;

class LoanProviderController extends Controller
{
    public function __construct(){
        $this->middleware('permission:loan.provider.view')->only(['index']);
        $this->middleware('permission:loan.provider.create')->only(['create', 'store']);
        $this->middleware('permission:loan.provider.edit')->only(['edit', 'update']);
    }
    
    /**
     * Show all providers
     */
    public function index()
    {
        $providers = LoanProvider::all();
        return view('admin.provider.index', compact('providers'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.provider.create');
    }

    /**
     * Store new provider
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'provider_type' => 'required|string|max:100',
            'account_number' => 'nullable|string|max:50',
        ]);

        LoanProvider::create($request->all());

        return redirect()->route('loan-providers.index')->with('success', 'Provider Added Successfully!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $provider = LoanProvider::findOrFail($id);
        return view('admin.provider.edit', compact('provider'));
    }

    /**
     * Update provider
     */
    public function update(Request $request, $id)
    {
        $provider = LoanProvider::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'provider_type' => 'required|string|max:100',
            'account_number' => 'nullable|string|max:50',
        ]);

        $provider->update($request->all());

        return redirect()->route('loan-providers.index')->with('success', 'Provider Updated Successfully!');
    }

    /**
     * Delete provider
     */
    public function destroy($id)
    {
        LoanProvider::destroy($id);
        return redirect()->route('loan-providers.index')->with('success', 'Provider Deleted Successfully!');
    }
}
