<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionsData = [
            'User Management' => ['show-users', 'create-users', 'edit-users', 'delete-users'],
            'Role Management' => ['show-roles', 'create-roles', 'edit-roles', 'delete-roles']
        ];

        $permissionsToUpsert = [];
        foreach ($permissionsData as $parent_name => $permissions) {
            foreach ($permissions as $permission) {
                $permissionsToUpsert[] = [
                    'parent_name' => $parent_name,
                    'name' => $permission,
                    'guard_name' => 'web',
                ];
            }
        }

        // Upsert permissions based on parent_name, name, and guard_name
        Permission::upsert(
            $permissionsToUpsert,
            ['parent_name', 'name', 'guard_name'],
            ['parent_name', 'name', 'guard_name']
        );
    }
}
