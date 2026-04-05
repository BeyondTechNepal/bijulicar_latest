<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        $stats = [
            'total_users' => User::count(),
            'buyers' => User::role('buyer')->count(),
            'sellers' => User::role('seller')->count(),
            'businesses' => User::role('business')->count(),
            'ev-stations' => User::role('ev-station')->count(), // Added
            'garages' => User::role('garage')->count(), // Added
            'total_admins' => Admin::count(),
        ];
        return view('admin.dashboard', compact('admin', 'stats'));
    }

    public function users()
    {
        $users = User::with('roles')->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(['buyer', 'seller', 'business', 'ev-station', 'garage'])],
        ]);
        $user->syncRoles([$data['role']]);
        return back()->with('success', "Role updated for {$user->name}.");
    }

    public function destroy($user)
    {
        $user = User::findOrFail($user);

        $user->delete();

        return back()->with('success', 'User deleted successfully');
    }
}
