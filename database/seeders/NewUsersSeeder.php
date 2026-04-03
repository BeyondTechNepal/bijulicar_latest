<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Admin;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;

class NewUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── 2. DEFINE PERMISSIONS ─────────────────────────────────────

        // Web Guard Permissions (Standard Users + New Specialties)
        $webPermissions = [
            'browse listings',
            'purchase vehicle',
            'write reviews',
            'manage own orders',
            'list vehicles',
            'manage own listings',
            'view seller analytics',
            'create advertisements',
            'bulk operations',
            'view business analytics',
            'manage garage services', // Added for Garage
            'manage charging stations', // Added for EV Station
            'view technical analytics', // Added for Technical Roles
        ];

        // Admin Guard Permissions (Staff + Management)
        $adminPermissions = [
            'manage users',
            'manage listings',
            'view reports',
            'manage admins',
            'manage site settings',
            'manage news articles', // Added for NewsAdmin
        ];

        foreach ($webPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        foreach ($adminPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'admin']);
        }

        // ── 3. CREATE ROLES & ASSIGN PERMISSIONS ──────────────────────

        // --- Web Guard Roles ---

        $buyer = Role::firstOrCreate(['name' => 'buyer', 'guard_name' => 'web']);
        $buyer->syncPermissions(['browse listings', 'purchase vehicle', 'write reviews', 'manage own orders']);

        $seller = Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'web']);
        $seller->syncPermissions(['browse listings', 'list vehicles', 'manage own listings', 'view seller analytics']);

        $business = Role::firstOrCreate(['name' => 'business', 'guard_name' => 'web']);
        $business->syncPermissions(['browse listings', 'list vehicles', 'view seller analytics', 'create advertisements', 'bulk operations', 'view business analytics']);

        $evStation = Role::firstOrCreate(['name' => 'ev-station', 'guard_name' => 'web']);
        $evStation->syncPermissions(['browse listings', 'manage charging stations', 'view technical analytics']);

        $garage = Role::firstOrCreate(['name' => 'garage', 'guard_name' => 'web']);
        $garage->syncPermissions(['browse listings', 'manage garage services', 'view technical analytics']);

        // --- Admin Guard Roles ---

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin']);
        $adminRole->syncPermissions(['manage users', 'manage listings', 'view reports']);

        $newsAdmin = Role::firstOrCreate(['name' => 'newsadmin', 'guard_name' => 'admin']);
        $newsAdmin->syncPermissions(['manage news articles', 'view reports']);

        $superAdmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'admin']);
        // Superadmin gets everything in the admin guard
        $superAdmin->syncPermissions(Permission::where('guard_name', 'admin')->pluck('name')->toArray());

        // ── 4. CREATE DEMO USERS (Safely) ─────────────────────────────

        // Web Guard Users
        $users = [['name' => 'Alice Buyer', 'email' => 'buyer@test.com', 'role' => 'buyer'], ['name' => 'Bob Seller', 'email' => 'seller@test.com', 'role' => 'seller'], ['name' => 'Acme Business', 'email' => 'business@test.com', 'role' => 'business'], ['name' => 'PowerVolt Station', 'email' => 'station@test.com', 'role' => 'ev-station'], ['name' => 'QuickFix Garage', 'email' => 'garage@test.com', 'role' => 'garage']];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(['email' => $userData['email']], ['name' => $userData['name'], 'password' => bcrypt('password')]);
            $user->syncRoles([$userData['role']]);
        }

        // Admin Guard Users
        $admins = [['name' => 'Carol Admin', 'email' => 'admin@test.com', 'role' => 'admin'], ['name' => 'Dave Super', 'email' => 'super@test.com', 'role' => 'superadmin'], ['name' => 'Nathan News', 'email' => 'news@test.com', 'role' => 'newsadmin']];

        foreach ($admins as $adminData) {
            $admin = Admin::updateOrCreate(['email' => $adminData['email']], ['name' => $adminData['name'], 'password' => bcrypt('password')]);
            $admin->syncRoles([$adminData['role']]);
        }
    }
}
