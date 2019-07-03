<?php

namespace Modules\HrmManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\Utilities;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgDesignation;

class AddOrgDesignationTableSeeder extends Seeder
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
        OrgDesignation::updateOrCreate(
            [OrgDesignation::name => 'Head Of Department'],
            [
                OrgDesignation::slug => Utilities::getUniqueId(),
                OrgDesignation::org_id => $org->id,
                OrgDesignation::name => 'Head Of Department',
                OrgDesignation::description => 'HOD (Head Of Department)'
            ]
            );

    }
}
