<?php

namespace Modules\HrmManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\HrmManagement\Entities\HrmAppraisalCyclePeriod;

class SeedHrmAppraisalCyclePeriodTableSeeder extends Seeder
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
                HrmAppraisalCyclePeriod::period_type => 'fullYear',
                HrmAppraisalCyclePeriod::period_type_display_name => 'Full year',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                HrmAppraisalCyclePeriod::period_type => 'halfYear',
                HrmAppraisalCyclePeriod::period_type_display_name => 'Half year',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                HrmAppraisalCyclePeriod::period_type => 'custom',
                HrmAppraisalCyclePeriod::period_type_display_name => 'Custom',
                'created_at' => date("Y-m-d H:i:s")
            ]];

        foreach ($data as $value) {
            $valueArray = $value;
            unset($value['created_at']);
            $mainKeysArray = $value;
            HrmAppraisalCyclePeriod::updateOrCreate($mainKeysArray, $valueArray);
        }
    }
}
