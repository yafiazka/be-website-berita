<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'view articles',
            'create articles',
            'edit articles',
            'edit own articles',
            'delete articles',
            'publish articles',
            'archive articles',
            'manage categories',
            'manage tags',
            'moderate comments',
            'manage users',
            'manage roles',
            'view stats',
            'view activity logs',
            'manage media',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Create Roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        $editorRole = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'web']);
        $editorRole->syncPermissions([
            'view articles',
            'create articles',
            'edit articles',
            'edit own articles',
            'delete articles',
            'publish articles',
            'archive articles',
            'manage categories',
            'manage tags',
            'moderate comments',
            'view stats',
            'manage media',
        ]);

        $penulisRole = Role::firstOrCreate(['name' => 'Penulis', 'guard_name' => 'web']);
        $penulisRole->syncPermissions([
            'view articles',
            'create articles',
            'edit own articles',
            'manage tags',
            'manage media',
        ]);

        $pembacaRole = Role::firstOrCreate(['name' => 'Pembaca', 'guard_name' => 'web']);
        $pembacaRole->syncPermissions([
            'view articles',
        ]);
    }
}
