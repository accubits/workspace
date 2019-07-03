<?php

namespace Modules\UserManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\UserManagement\Entities\Permissions;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Model::unguard();
        Permission::updateOrCreate(['name' => Permissions::FULL_PERMISSION], ['name' => Permissions::FULL_PERMISSION]);
        Permission::updateOrCreate(['name' => Permissions::SUBADMIN_PERMISSION], ['name' => Permissions::SUBADMIN_PERMISSION]);
        Permission::updateOrCreate(['name' => Permissions::PARTNER_MANAGER_PERMISSION], ['name' => Permissions::PARTNER_MANAGER_PERMISSION]);
        Permission::updateOrCreate(['name' => Permissions::PARTNER_PERMISSION], ['name' => Permissions::PARTNER_PERMISSION]);
        Permission::updateOrCreate(['name' => Permissions::ORG_EMPLOYEE_PERMISSION], ['name' => Permissions::ORG_EMPLOYEE_PERMISSION]);
        Permission::updateOrCreate(['name' => Permissions::ORG_EMPLOYEE_PERMISSION_CREATE], ['name' => Permissions::ORG_EMPLOYEE_PERMISSION_CREATE]);
        Permission::updateOrCreate(['name' => Permissions::ORG_EMPLOYEE_PERMISSION_EDIT], ['name' => Permissions::ORG_EMPLOYEE_PERMISSION_EDIT]);
        Permission::updateOrCreate(['name' => Permissions::ORG_EMPLOYEE_PERMISSION_DELETE], ['name' => Permissions::ORG_EMPLOYEE_PERMISSION_DELETE]);

        //SUBADMIN COMMUNICATION WLPOST
        Permission::updateOrCreate(['name' => Permissions::SUBADMIN_COMM_WLPOSTS_CREATE], ['name' => Permissions::SUBADMIN_COMM_WLPOSTS_CREATE]);
        Permission::updateOrCreate(['name' => Permissions::SUBADMIN_COMM_WLPOSTS_READ], ['name' => Permissions::SUBADMIN_COMM_WLPOSTS_READ]);
        Permission::updateOrCreate(['name' => Permissions::SUBADMIN_COMM_WLPOSTS_EDIT], ['name' => Permissions::SUBADMIN_COMM_WLPOSTS_EDIT]);
        Permission::updateOrCreate(['name' => Permissions::SUBADMIN_COMM_WLPOSTS_DELETE], ['name' => Permissions::SUBADMIN_COMM_WLPOSTS_DELETE]);

        //SUBADMIN COMMUNICATION VLPOST
        Permission::updateOrCreate(['name' => Permissions::SUBADMIN_COMM_VLPOSTS_CREATE], ['name' => Permissions::SUBADMIN_COMM_VLPOSTS_CREATE]);
        Permission::updateOrCreate(['name' => Permissions::SUBADMIN_COMM_VLPOSTS_READ], ['name' => Permissions::SUBADMIN_COMM_VLPOSTS_READ]);
        Permission::updateOrCreate(['name' => Permissions::SUBADMIN_COMM_VLPOSTS_EDIT], ['name' => Permissions::SUBADMIN_COMM_VLPOSTS_EDIT]);
        Permission::updateOrCreate(['name' => Permissions::SUBADMIN_COMM_VLPOSTS_DELETE], ['name' => Permissions::SUBADMIN_COMM_VLPOSTS_DELETE]);

    }
}
