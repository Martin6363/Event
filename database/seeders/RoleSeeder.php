<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $supervisorRole = Role::firstOrCreate(['name' => 'supervisor', 'guard_name' => 'admin']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        // Create permissions
        $permissions = [
            'update',
            'delete',
            'approve_event',
            'publish',
            'unpublish',
        ];

        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'admin']);
            $supervisorRole->givePermissionTo($permission);
        }
    }
}
