<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions grouped by group_name
     */
    public function index()
    {
        $permissions = Permission::orderBy('group_name')
                        ->orderBy('name')
                        ->get()
                        ->groupBy('group_name');

        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created permission in database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|unique:permissions,name',
            'group_name' => 'nullable|string|max:255',
        ]);

        Permission::create([
            'name'       => $request->name,
            'group_name' => $request->group_name,
        ]);

        return redirect()->route('admin.permissions.index')
                         ->with('success', 'Permission created successfully.');
    }

    /**
     * Show the form for editing the specified permission
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);

        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified permission in database
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name'       => 'required|unique:permissions,name,' . $permission->id,
            'group_name' => 'nullable|string|max:255',
        ]);

        $permission->update([
            'name'       => $request->name,
            'group_name' => $request->group_name,
        ]);

        return redirect()->route('admin.permissions.index')
                         ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission from the database
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('admin.permissions.index')
                         ->with('success', 'Permission deleted successfully.');
    }
}
