<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\Admin;

class NewsAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear permission cache (still required by Spatie)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── CREATE NEWS ADMIN ROLE (ADMIN GUARD) ─────────────────────
        $newsAdmin = Role::firstOrCreate([
            'name' => 'newsadmin',
            'guard_name' => 'admin',
        ]);

        // ── OPTIONAL: CREATE DEMO USER ───────────────────────────────
        $admin = Admin::firstOrCreate(
            ['email' => 'newsadmin@test.com'],
            [
                'name' => 'News Admin',
                'password' => bcrypt('password'),
            ],
        );

        // Assign role ONLY (no permissions)
        $admin->assignRole($newsAdmin);
    }
}
