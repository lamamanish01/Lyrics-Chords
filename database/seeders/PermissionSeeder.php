<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [

            // ================= DASHBOARD =================
            'view dashboard',

            // ================= SONGS =================
            'view songs',
            'create songs',
            'edit songs',
            'delete songs',

            // ================= LYRICS =================
            'view lyrics',
            'create lyrics',
            'edit lyrics',
            'delete lyrics',

            // ================= COLLECTIONS =================
            'view collections',
            'create collections',
            'edit collections',
            'delete collections',

            // ================= USERS =================
            'view users',
            'create users',
            'edit users',
            'delete users',

            // ================= ROLES =================
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',

            // ================= PERMISSIONS =================
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        $this->command->info('✅ Permissions seeded: ' . count($permissions) . ' permissions created.');
    }
}
