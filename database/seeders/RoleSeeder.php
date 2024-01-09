<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{

    public function run(): void
    {
        $role_admin = Role::create(["name"=> "admin",]);
        $role_editor = Role::create(["name"=> "editor",]);
        $role_supervidor = Role::create(["name"=> "supervisor",]);
        $role_pdv = Role::create(["name"=> "pdv",]);

        $permission_create_role = Permission::create(["name"=> "create roles",]);
        $permission_read_role = Permission::create(["name"=> "read roles",]);
        $permission_update_role = Permission::create(["name"=> "update roles",]);
        $permission_delete_role = Permission::create(["name"=> "delete roles",]);

        $permission_create_pdv = Permission::create(["name"=> "create pdvs",]);
        $permission_delete_pdv = Permission::create(["name"=> "delete pdvs",]);
        $permission_update_pdv = Permission::create(["name"=> "update pdvs"]);
        $permission_read_pdv = Permission::create(["name"=> "read pdvs"]);
        $permission_asing_horary_pdv = Permission::create(["name"=> "asing horary pdvs"]);

        $permission_create_worker = Permission::create(["name"=> "create workers",]);
        $permission_delete_worker = Permission::create(["name"=> "delete workers",]);
        $permission_update_worker = Permission::create(["name"=> "update workers"]);
        $permission_read_worker = Permission::create(["name"=> "read workers"]);

        $permission_create_horary = Permission::create(["name"=> "create horaries",]);
        $permission_delete_horary = Permission::create(["name"=> "delete horaries",]);
        $permission_update_horary = Permission::create(["name"=> "update horaries"]);
        $permission_read_horary = Permission::create(["name"=> "read horaries"]);

        $permissions_admin = [
            $permission_create_role, $permission_read_role, $permission_update_role, $permission_delete_role,
            $permission_create_pdv, $permission_delete_pdv, $permission_update_pdv, $permission_read_pdv, $permission_asing_horary_pdv,
            $permission_create_worker, $permission_delete_worker, $permission_update_worker, $permission_read_worker,
            $permission_create_horary, $permission_delete_horary, $permission_update_horary, $permission_read_horary,
        ];

        $permissions_editor = [
            $permission_create_pdv, $permission_update_pdv, $permission_read_pdv, $permission_asing_horary_pdv,
            $permission_create_worker, $permission_update_worker, $permission_read_worker,
            $permission_create_horary, $permission_update_horary, $permission_read_horary,
        ];

        $role_admin->syncPermissions($permissions_admin);
        $role_editor->syncPermissions($permissions_editor);
    }
}
