<?php

namespace Modules\UserManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\UserManagement\Entities\Permissions;
use Modules\UserManagement\Entities\Roles;
use Modules\UserManagement\Entities\User;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Model::unguard();
        /** @var SUPERADMIN PERMISSIONS $role */
        $role = Role::findByName(Roles::SUPER_ADMIN);
        if (!$role->hasPermissionTo(Permissions::FULL_PERMISSION)) {
            $role->syncPermissions(Permissions::FULL_PERMISSION);
        }

        /** @var SUBADMIN PERMISSIONS $role */
        $role = Role::findByName(Roles::SUB_ADMIN);
        if (!$role->hasPermissionTo(Permissions::SUBADMIN_PERMISSION)) {
            $role->givePermissionTo(Permissions::SUBADMIN_PERMISSION);
        }


        /** @var PARTNER $role */
        $role = Role::findByName(Roles::PARTNER);

        if (!$role->hasPermissionTo(Permissions::PARTNER_PERMISSION)) {
            $role->givePermissionTo(Permissions::PARTNER_PERMISSION);
        }

        if (!$role->hasPermissionTo(Permissions::FULL_PERMISSION)) {
            $role->givePermissionTo(Permissions::FULL_PERMISSION);
        }

        /** @var PARTNER MANAGER $role */
        $role = Role::findByName(Roles::PARTNER_MANAGER);

        if (!$role->hasPermissionTo(Permissions::PARTNER_MANAGER_PERMISSION)) {
            $role->givePermissionTo(Permissions::PARTNER_MANAGER_PERMISSION);
        }

        /** @var ORG GROUP EMPLOYEE  $role */
        $role = Role::findByName(Roles::ORG_GROUP_EMPLOYEE);

        if (!$role->hasPermissionTo(Permissions::ORG_EMPLOYEE_PERMISSION)) {
            $role->givePermissionTo(Permissions::ORG_EMPLOYEE_PERMISSION);
        }

        if (!$role->hasPermissionTo(Permissions::ORG_EMPLOYEE_PERMISSION_CREATE)) {
            $role->givePermissionTo(Permissions::ORG_EMPLOYEE_PERMISSION_CREATE);
        }

        if (!$role->hasPermissionTo(Permissions::ORG_EMPLOYEE_PERMISSION_EDIT)) {
            $role->givePermissionTo(Permissions::ORG_EMPLOYEE_PERMISSION_EDIT);
        }

        if (!$role->hasPermissionTo(Permissions::ORG_EMPLOYEE_PERMISSION_DELETE)) {
            $role->givePermissionTo(Permissions::ORG_EMPLOYEE_PERMISSION_DELETE);
        }

        /** @var ORG EMPLOYEE  $role */
        $role = Role::findByName(Roles::ORG_EMPLOYEE);

        if (!$role->hasPermissionTo(Permissions::ORG_EMPLOYEE_PERMISSION)) {
            $role->givePermissionTo(Permissions::ORG_EMPLOYEE_PERMISSION);
        }
    }
}
