<?php

namespace Modules\HrmManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\HrmManagement\Entities\HrmAppraisalCycleApplicable;

class SeedHrmAppraisalCycleApplicableTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $data = [
            [
                HrmAppraisalCycleApplicable::applicable_type => "department",
                HrmAppraisalCycleApplicable::applicable_type_display_name => "Department",
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                HrmAppraisalCycleApplicable::applicable_type => "employee",
                HrmAppraisalCycleApplicable::applicable_type_display_name => "Employee",
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                HrmAppraisalCycleApplicable::applicable_type => "wholeOrganization",
                HrmAppraisalCycleApplicable::applicable_type_display_name => "Whole Organization",
                'created_at' => date("Y-m-d H:i:s")
            ]];

        foreach ($data as $value) {
            $valueArray = $value;
            unset($value['created_at']);
            $mainKeysArray = $value;
            HrmAppraisalCycleApplicable::updateOrCreate($mainKeysArray, $valueArray);
        }
    }
}
