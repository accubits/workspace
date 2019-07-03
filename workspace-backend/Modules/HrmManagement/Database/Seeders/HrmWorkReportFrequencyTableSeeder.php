<?php

namespace Modules\HrmManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Common\Utilities\Utilities;
use Modules\HrmManagement\Entities\HrmWorkReportFrequency;

class HrmWorkReportFrequencyTableSeeder extends Seeder
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
                HrmWorkReportFrequency::slug      => Utilities::getUniqueId(),
                HrmWorkReportFrequency::frequency_display_name     => 'Daily',
                HrmWorkReportFrequency::frequency_name            => 'daily'
            ],
            [
                HrmWorkReportFrequency::slug      => Utilities::getUniqueId(),
                HrmWorkReportFrequency::frequency_display_name     => 'Monthly',
                HrmWorkReportFrequency::frequency_name            => 'monthly'
            ],
            [
                HrmWorkReportFrequency::slug      => Utilities::getUniqueId(),
                HrmWorkReportFrequency::frequency_display_name     => 'Weekly',
                HrmWorkReportFrequency::frequency_name            => 'weekly'
            ]
        ];


        foreach ($data as $value) {
            $frequency = HrmWorkReportFrequency::where(HrmWorkReportFrequency::frequency_name,'=',$value[HrmWorkReportFrequency::frequency_name])->first();
            if(empty($frequency)){
                HrmWorkReportFrequency::create($value);
            }
        }
    }
}
