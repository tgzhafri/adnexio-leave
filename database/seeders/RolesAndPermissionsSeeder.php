<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'view request']);
        Permission::create(['name' => 'create request']);
        Permission::create(['name' => 'delete request']);
        Permission::create(['name' => 'approve request']);
        Permission::create(['name' => 'reject request']);
        Permission::create(['name' => 'view policy']);
        Permission::create(['name' => 'create policy']);
        Permission::create(['name' => 'edit policy']);
        Permission::create(['name' => 'delete policy']);
        Permission::create(['name' => 'create admin']);
        Permission::create(['name' => 'delete admin']);

        // create roles and assign existing permissions
        $superadmin = Role::create(['name' => 'superadmin']);
        $superadmin->givePermissionTo(Permission::all());
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo('view request');
        $admin->givePermissionTo('create request');
        $admin->givePermissionTo('delete request');
        $admin->givePermissionTo('approve request');
        $admin->givePermissionTo('reject request');
        $admin->givePermissionTo('view policy');
        $admin->givePermissionTo('create policy');
        $admin->givePermissionTo('edit policy');
        $admin->givePermissionTo('delete policy');

        $supervisor = Role::create(['name' => 'supervisor']);
        $supervisor->givePermissionTo('view request');
        $supervisor->givePermissionTo('create request');
        $supervisor->givePermissionTo('delete request');
        $supervisor->givePermissionTo('approve request');
        $supervisor->givePermissionTo('reject request');

        $staff = Role::create(['name' => 'staff']);
        $staff->givePermissionTo('view request');
        $staff->givePermissionTo('create request');
        $staff->givePermissionTo('delete request');
    }
}
