<?php

namespace Modules\HrmManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\OrgManagement\Entities\OrgDepartment;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\OrgManagement\Entities\OrgEmployeeDepartment;

class AddOrgEmployeeDepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $departmentManagement = DB::table(OrgDepartment::table)->where(OrgDepartment::name, 'Management')->firstOrFail();
        $departmentUsers      = OrgEmployee::whereIn(OrgEmployee::name, [
            'Reshman', 'Robinson', 'Bhanu', 'Jyothish', 'Thuhin'
        ])->get();


        $departmentUsers->each(function ($orgUser) use ($departmentManagement) {
            OrgEmployeeDepartment::updateOrCreate(
                [
                    OrgEmployeeDepartment::org_employee_id => $orgUser->id,
                    OrgEmployeeDepartment::org_department_id => $departmentManagement->id
                ],
                [
                    OrgEmployeeDepartment::org_employee_id => $orgUser->id,
                    OrgEmployeeDepartment::org_department_id => $departmentManagement->id
                ]
            );
        });
    }
}
