<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use app\Models\User;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::where('guard_name', 'web')->with('permissions')->withCount('users')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::where('guard_name', 'web')->orderBy('name')->get();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role = Role::create(['name' => strtolower(trim($request->name)), 'guard_name' => 'web']);

        if ($request->filled('permissions')) {
            $role->syncPermissions(Permission::whereIn('id', $request->permissions)->get());
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', "Role '{$role->name}' created.");
    }

    public function edit(Role $role)
    {
        $permissions = Permission::where('guard_name', 'web')->orderBy('name')->get();
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissionIds'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name,' . $role->id],
        ]);
        $role->update(['name' => strtolower(trim($request->name))]);
        return redirect()->route('admin.roles.index')->with('success', 'Role updated.');
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role->syncPermissions(Permission::whereIn('id', $request->permissions ?? [])->get());

        return redirect()
            ->route('admin.roles.index')
            ->with('success', "Permissions updated for '{$role->name}'.");
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['buyer', 'seller', 'business'])) {
            return redirect()
                ->route('admin.roles.index')
                ->with('error', "Cannot delete core role '{$role->name}'.");
        }
        $name = $role->name;
        $role->delete();
        return redirect()
            ->route('admin.roles.index')
            ->with('success', "Role '{$name}' deleted.");
    }
}
