<?php

namespace Modules\HrmManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\Utilities;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgDepartment;

class AddOrgDepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $org = DB::table(Organization::table)->where(Organization::name, 'Icosys')->firstOrFail();

        collect(['Management', 'Sales'])->each(function ($designation) use ($org) {
            OrgDepartment::updateOrCreate(
                [OrgDepartment::name => $designation],
                [
                    OrgDepartment::slug   => Utilities::getUniqueId(),
                    OrgDepartment::org_id => $org->id,
                    OrgDepartment::name   => $designation
                ]
            );
        });

    }
}
