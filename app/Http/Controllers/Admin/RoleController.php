<?php

namespace App\Http\Controllers\Admin;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('group_name')
                            ->orderBy('name')
                            ->get()
                            ->groupBy('group_name');

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array|nullable',
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')
                         ->with('success', 'Role created successfully.');
    }

 // Show the edit form for a role
    public function edit($id)
    {
        $role = Role::findOrFail($id);

        // Group permissions by module (or group)
        $permissions = Permission::all()->groupBy(function($perm) {
            return explode('.', $perm->name)[0]; // e.g., 'user.create' => 'user'
        });

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    // Update the role
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        // Update role name
        $role->name = $request->name;
        $role->save();

        // Sync permissions
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]); // remove all if none selected
        }

        return redirect()->route('admin.roles.index')
                         ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.roles.index')
                         ->with('success', 'Role deleted successfully.');
    }
}
