<?php

use Harristech\ACL\Model\Permission;
use Harristech\ACL\Model\Role;
use Illuminate\Database\Seeder;

class RoleAndPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // // make sure the roles table exist
        // if ( Schema::hasTable('roles') ) {
        //     \DB::table('permissions')->delete();
        //     \DB::table('roles')->delete();

        //     $defaultRoles = Role::defaultRoles();

        //     // create a record for each role
        //     $roleModel = new Role;
        //     foreach ( $defaultRoles as $role ) {
        //         // $this->command->info($role);
        //         $roleModel->create([ 'name' => $role ]);
        //     }
        // } else $this->command->error('roles table not found!');

        // // make sure the permissions table exist
        // if ( Schema::hasTable('permissions') ) {
        //     $defaultPermissions = Permission::defaultPermissions();

        //     // create a record for each permission
        //     $permissionModel = new Permission;
        //     foreach ( $defaultPermissions as $permission ) {
        //         // $this->command->info($permission);
        //         $permissionModel->create([ 'name' => $permission ]);
        //     }
        // } else $this->command->error('permissions table not found!');

        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // get the default permissions
        $permissions = Permission::defaultPermissions();
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }

        $this->command->info("Permissions created.");

        // get the default roles
        $roles = Role::defaultRoles();
        foreach ($roles as $role) {
            $createdRole = Role::updateOrCreate(['name' => $role]);

            if ($createdRole->name == 'ROOT') {
                $createdRole->syncPermissions(Permission::all());

                $this->command->info("ROOT permissions synced");
            }
        }

        $this->command->info("Roles created.");
    }
}
