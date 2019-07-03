<?php

namespace Modules\HrmManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Common\Utilities\Utilities;
use Modules\HrmManagement\Entities\HrmShiftTimings;

class HrmTimingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        HrmShiftTimings::updateOrCreate(
            [
                HrmShiftTimings::start_time => date('H:i:s', strtotime("04:30:00")),
                HrmShiftTimings::end_time   => date('H:i:s', strtotime("12:30:00"))
            ],
            [
                HrmShiftTimings::slug => Utilities::getUniqueId(),
                HrmShiftTimings::start_time => date('H:i:s', strtotime("04:30:00")),
                HrmShiftTimings::end_time   => date('H:i:s', strtotime("12:30:00"))
            ]
        );
    }
}
