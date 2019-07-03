<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\SubadminRoles;
use Modules\UserManagement\Entities\Roles;

class SubadminRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $roles = [
            SubadminRoles::PARTNER_MANAGER => Roles::PARTNER_MANAGER,
            SubadminRoles::COMMUNICATION_MANAGER => Roles::COMMUNICATION_MANAGER,
            SubadminRoles::LICENSE_MANAGER => Roles::LICENSE_MANAGER,
            SubadminRoles::WORKFLOW_MANAGER => Roles::WORKFLOW_MANAGER,
            SubadminRoles::FORM_MANAGER => Roles::FORM_MANAGER,
            SubadminRoles::PERMISSIONS_MANAGER => Roles::PERMISSIONS_MANAGER
        ];

        foreach ($roles as $key => $value) {
            $role = Roles::where(Roles::name,'=',$value)->first();

            if(!empty($role)){
                $subadmin = SubadminRoles::where(SubadminRoles::role_id, $role->id)->first();
                if (empty($subadmin)) {
                    SubadminRoles::create(
                        [
                            SubadminRoles::role_id => $role->id,
                            SubadminRoles::name    => $key
                        ]
                    );
                }

            }
        }

    }
}
