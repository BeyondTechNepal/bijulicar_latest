<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Admin;
use App\Models\User;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    /*
|--------------------------------------------------------------------------
| PERMISSION MATRIX
|--------------------------------------------------------------------------
|
|  Permission                | buyer | seller | business
|  ─────────────────────────────────────────────────────
|  browse listings           |   ✓   |   ✓    |    ✓
|  purchase vehicle          |   ✓   |        |
|  write reviews             |   ✓   |        |
|  manage own orders         |   ✓   |        |
|  list vehicles             |       |   ✓    |    ✓
|  manage own listings       |       |   ✓    |
|  view seller analytics     |       |   ✓    |    ✓
|  create advertisements     |       |        |    ✓
|  bulk operations           |       |        |    ✓
|  view business analytics   |       |        |    ✓
|
|  Permission                | admin | superadmin
|  ──────────────────────────────────────────────
|  manage users              |   ✓   |     ✓
|  manage listings           |   ✓   |     ✓
|  view reports              |   ✓   |     ✓
|  manage admins             |       |     ✓
|  manage site settings      |       |     ✓
|
*/
    public function run(): void
    {
        // Always clear cache first
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── WEB GUARD PERMISSIONS ─────────────────────────────────────
        $webPermissions = ['browse listings', 'purchase vehicle', 'write reviews', 'manage own orders', 'list vehicles', 'manage own listings', 'view seller analytics', 'create advertisements', 'bulk operations', 'view business analytics'];

        foreach ($webPermissions as $perm) {
            Permission::create(['name' => $perm, 'guard_name' => 'web']);
        }

        // ── ADMIN GUARD PERMISSIONS ───────────────────────────────────
        $adminPermissions = ['manage users', 'manage listings', 'view reports', 'manage admins', 'manage site settings'];

        foreach ($adminPermissions as $perm) {
            Permission::create(['name' => $perm, 'guard_name' => 'admin']);
        }

        // ── WEB GUARD ROLES ───────────────────────────────────────────
        $buyer = Role::create(['name' => 'buyer', 'guard_name' => 'web']);
        $buyer->givePermissionTo(['browse listings', 'purchase vehicle', 'write reviews', 'manage own orders']);

        $seller = Role::create(['name' => 'seller', 'guard_name' => 'web']);
        $seller->givePermissionTo(['browse listings', 'list vehicles', 'manage own listings', 'view seller analytics']);

        $business = Role::create(['name' => 'business', 'guard_name' => 'web']);
        $business->givePermissionTo(['browse listings', 'list vehicles', 'view seller analytics', 'create advertisements', 'bulk operations', 'view business analytics']);

        // ── ADMIN GUARD ROLES ─────────────────────────────────────────
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'admin']);
        $admin->givePermissionTo(['manage users', 'manage listings', 'view reports']);

        $superadmin = Role::create(['name' => 'superadmin', 'guard_name' => 'admin']);
        $superadmin->givePermissionTo(Permission::where('guard_name', 'admin')->pluck('name')->toArray());

        // ── DEMO USERS ────────────────────────────────────────────────
        User::create(['name' => 'Alice Buyer', 'email' => 'buyer@test.com', 'password' => bcrypt('password')])->assignRole('buyer');
        User::create(['name' => 'Bob Seller', 'email' => 'seller@test.com', 'password' => bcrypt('password')])->assignRole('seller');
        User::create(['name' => 'Acme Business', 'email' => 'business@test.com', 'password' => bcrypt('password')])->assignRole('business');

        Admin::create(['name' => 'Carol Admin', 'email' => 'admin@test.com', 'password' => bcrypt('password')])->assignRole('admin');
        Admin::create(['name' => 'Dave Super', 'email' => 'super@test.com', 'password' => bcrypt('password')])->assignRole('superadmin');
    }
}
