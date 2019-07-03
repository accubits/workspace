<?php

namespace Modules\HrmManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgDesignation;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\OrgManagement\Entities\OrgEmployeeDesignation;

class AddOrgEmployeeDesignationTableSeeder extends Seeder
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
        $orgEmployee = DB::table(OrgEmployee::table)->select('id')->where(OrgEmployee::name, 'Bhanu')->firstOrFail();
        $orgDesignation = DB::table(OrgDesignation::table)->select('id')->where(OrgDesignation::name, 'Head Of Department')->firstOrFail();


        OrgEmployeeDesignation::updateOrCreate(
            [
                OrgEmployeeDesignation::org_employee_id    => $orgEmployee->id,
                OrgEmployeeDesignation::org_designation_id => $orgDesignation->id,
                OrgEmployeeDesignation::org_id             => $org->id
            ],
            [
                OrgEmployeeDesignation::org_employee_id    => $orgEmployee->id,
                OrgEmployeeDesignation::org_designation_id => $orgDesignation->id,
                OrgEmployeeDesignation::org_id             => $org->id,
                OrgEmployeeDesignation::is_active => true
            ]
        );
    }
}
