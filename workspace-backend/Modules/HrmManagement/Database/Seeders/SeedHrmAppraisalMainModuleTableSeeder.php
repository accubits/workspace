<?php

namespace Modules\HrmManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\HrmManagement\Entities\HrmAppraisalMainModule;

class SeedHrmAppraisalMainModuleTableSeeder extends Seeder
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
                HrmAppraisalMainModule::module_name => 'timeAndReport ',
                HrmAppraisalMainModule::module_display_name => 'Time and Report',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                HrmAppraisalMainModule::module_name => 'taskScore',
                HrmAppraisalMainModule::module_display_name => 'Task score',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                HrmAppraisalMainModule::module_name => 'performanceIndicator',
                HrmAppraisalMainModule::module_display_name => 'PerformanceIndicator',
                'created_at' => date("Y-m-d H:i:s")
            ]];

        foreach ($data as $value) {
            $valueArray = $value;
            unset($value['created_at']);
            $mainKeysArray = $value;
            HrmAppraisalMainModule::updateOrCreate($mainKeysArray, $valueArray);
        }
    }
}
