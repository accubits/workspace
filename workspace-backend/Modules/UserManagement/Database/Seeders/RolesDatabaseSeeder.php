<?php

namespace Modules\UserManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\UserManagement\Entities\Roles;
use Spatie\Permission\Models\Role;

class RolesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Model::unguard();
        Role::updateOrCreate(['name' => 'SUPER_ADMIN'], ['name' => 'SUPER_ADMIN']);
        Role::updateOrCreate(['name' => 'SUB_ADMIN'], ['name' => 'SUB_ADMIN']);
        Role::updateOrCreate(['name' => Roles::PARTNER_MANAGER], ['name' => Roles::PARTNER_MANAGER]);
        Role::updateOrCreate(['name' => Roles::PARTNER], ['name' => Roles::PARTNER]);
        Role::updateOrCreate(['name' => Roles::ORG_EMPLOYEE], ['name' => Roles::ORG_EMPLOYEE]);
        Role::updateOrCreate(['name' => Roles::ORG_GROUP_EMPLOYEE], ['name' => Roles::ORG_GROUP_EMPLOYEE]);
        Role::updateOrCreate(['name' => Roles::ORG_ADMIN], ['name' => Roles::ORG_ADMIN]);
        Role::updateOrCreate(['name' => Roles::ORG_ADMIN], ['name' => Roles::ORG_ADMIN]);
        Role::updateOrCreate(['name' => Roles::COMMUNICATION_MANAGER], ['name' => Roles::COMMUNICATION_MANAGER]);
        Role::updateOrCreate(['name' => Roles::LICENSE_MANAGER], ['name' => Roles::LICENSE_MANAGER]);
        Role::updateOrCreate(['name' => Roles::WORKFLOW_MANAGER], ['name' => Roles::WORKFLOW_MANAGER]);
        Role::updateOrCreate(['name' => Roles::FORM_MANAGER], ['name' => Roles::FORM_MANAGER]);
        Role::updateOrCreate(['name' => Roles::PERMISSIONS_MANAGER], ['name' => Roles::PERMISSIONS_MANAGER]);
    }
}
