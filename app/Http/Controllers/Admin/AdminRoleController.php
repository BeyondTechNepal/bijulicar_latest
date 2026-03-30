<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminRoleController extends Controller
{
    // List all admin roles
    public function index()
    {
        $roles = Role::where('guard_name', 'admin')
            ->with('permissions')
            ->withCount('users') // counts how many admins have this role
            ->get();

        return view('admin.admin_roles.index', compact('roles'));
    }

    // Show form to create a new admin role
    public function create()
    {
        $permissions = Permission::where('guard_name', 'admin')->orderBy('name')->get();

        return view('admin.admin_roles.create', compact('permissions'));
    }

    // Store a new admin role
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role = Role::create([
            'name' => strtolower(trim($request->name)),
            'guard_name' => 'admin',
        ]);

        if ($request->filled('permissions')) {
            $role->syncPermissions(Permission::whereIn('id', $request->permissions)->get());
        }

        return redirect()
            ->route('admin.admin_roles.index')
            ->with('success', "Admin role '{$role->name}' created.");
    }

    // Show form to edit an existing admin role
    public function edit(Role $role)
    {
        $permissions = Permission::where('guard_name', 'admin')->orderBy('name')->get();

        $rolePermissionIds = $role->permissions->pluck('id')->toArray();

        return view('admin.admin_roles.edit', compact('role', 'permissions', 'rolePermissionIds'));
    }

    // Update the role name only
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name,' . $role->id],
        ]);

        $role->update(['name' => strtolower(trim($request->name))]);

        return redirect()->route('admin.admin_roles.index')->with('success', 'Admin role updated.');
    }

    // Update permissions for the role
    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role->syncPermissions(Permission::whereIn('id', $request->permissions ?? [])->get());

        return redirect()
            ->route('admin.admin_roles.index')
            ->with('success', "Permissions updated for '{$role->name}'.");
    }

    // Delete an admin role
    public function destroy(Role $role)
    {
        // Prevent deletion of core admin roles
        if (in_array($role->name, ['admin', 'superadmin', 'newsadmin'])) {
            return redirect()
                ->route('admin.admin_roles.index')
                ->with('error', "Cannot delete core admin role '{$role->name}'.");
        }

        $name = $role->name;
        $role->delete();

        return redirect()
            ->route('admin.admin_roles.index')
            ->with('success', "Admin role '{$name}' deleted.");
    }
}
