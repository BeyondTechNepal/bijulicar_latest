<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class AdminPermissionController extends Controller
{
    /**
     * Display a listing of the permissions for the admin guard.
     */
    public function index()
    {
        $permissions = Permission::where('guard_name', 'admin')
            ->withCount('roles') // counts roles using this permission
            ->orderBy('name')
            ->get();

        return view('admin.admin_permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create()
    {
        return view('admin.admin_permissions.create');
    }

    /**
     * Store a newly created permission in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:permissions,name'],
        ]);

        $name = strtolower(trim($request->name));
        Permission::create(['name' => $name, 'guard_name' => 'admin']);

        return redirect()
            ->route('admin.admin_permissions.index')
            ->with('success', "Permission '{$name}' created for admin guard.");
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission)
    {
        return view('admin.admin_permissions.edit', compact('permission'));
    }

    /**
     * Update the specified permission in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:permissions,name,' . $permission->id],
        ]);

        $name = strtolower(trim($request->name));
        $permission->update(['name' => $name]);

        return redirect()
            ->route('admin.admin_permissions.index')
            ->with('success', "Permission updated to '{$name}' for admin guard.");
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroy(Permission $permission)
    {
        $name = $permission->name;
        $permission->delete();

        return redirect()
            ->route('admin.admin_permissions.index')
            ->with('success', "Permission '{$name}' deleted from admin guard.");
    }
}
