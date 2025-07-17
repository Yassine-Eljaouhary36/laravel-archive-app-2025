<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'toggle users']);

        // Create roles and assign permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(['manage users', 'view users', 'edit users', 'toggle users']);

        $controller = Role::create(['name' => 'controller']);
        $controller->givePermissionTo(['view users']);

        $user = Role::create(['name' => 'user']);
    }
}
