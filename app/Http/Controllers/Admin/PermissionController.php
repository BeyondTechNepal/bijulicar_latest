<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::where('guard_name', 'web')->withCount('roles')->orderBy('name')->get();
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:permissions,name'],
        ]);

        $name = strtolower(trim($request->name));
        Permission::create(['name' => $name, 'guard_name' => 'web']);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', "Permission '{$name}' created.");
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:permissions,name,' . $permission->id],
        ]);

        $name = strtolower(trim($request->name));
        $permission->update(['name' => $name]);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', "Permission updated to '{$name}'.");
    }

    public function destroy(Permission $permission)
    {
        $name = $permission->name;
        $permission->delete();
        return redirect()
            ->route('admin.permissions.index')
            ->with('success', "Permission '{$name}' deleted.");
    }
}
