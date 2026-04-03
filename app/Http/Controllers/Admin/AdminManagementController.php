<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class AdminManagementController extends Controller
{
    public function index()
    {
        $currentAdmin = Auth::guard('admin')->user();
        $admins = Admin::with('roles')->latest()->get();
        return view('admin.admins.index', compact('admins', 'currentAdmin'));
    }

    public function create()
    {
        $roles = Role::where('guard_name', 'admin')->get();

        return view('admin.admins.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:admins,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'role' => ['required', Rule::in(['admin', 'superadmin', 'newsadmin', 'garageadmin', 'evstationadmin'])],
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        
        $admin->assignRole($request->role);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', "Admin '{$admin->name}' created.");
    }

    public function edit(Admin $admin)
    {
        $currentAdmin = Auth::guard('admin')->user();

        $roles = Role::where('guard_name', 'admin')->get();

        return view('admin.admins.edit', compact('admin', 'currentAdmin', 'roles'));
    }

    public function update(Request $request, Admin $admin)
    {
        $currentAdmin = Auth::guard('admin')->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:admins,email,' . $admin->id],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'role' => ['required', Rule::in(['admin', 'superadmin', 'newsadmin', 'garageadmin', 'evstationadmin'])],
        ]);

        $data = ['name' => $request->name, 'email' => $request->email];
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
        $admin->update($data);

        if ($admin->id !== $currentAdmin->id) {
            $admin->syncRoles([$request->role]);
        }

        return redirect()->route('admin.admins.index')->with('success', 'Admin updated.');
    }

    public function destroy(Admin $admin)
    {
        $currentAdmin = Auth::guard('admin')->user();

        if ($admin->id === $currentAdmin->id) {
            return redirect()->route('admin.admins.index')->with('error', 'Cannot delete yourself.');
        }

        if ($admin->hasRole('superadmin') && Admin::role('superadmin')->count() <= 1) {
            return redirect()->route('admin.admins.index')->with('error', 'Cannot delete the last superadmin.');
        }

        $name = $admin->name;
        $admin->delete();
        return redirect()
            ->route('admin.admins.index')
            ->with('success', "Admin '{$name}' deleted.");
    }
}
