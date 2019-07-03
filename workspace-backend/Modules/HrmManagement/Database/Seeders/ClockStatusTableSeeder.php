<?php

namespace Modules\HrmManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\Utilities;
use Modules\HrmManagement\Entities\HrmClockStatus;

class ClockStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        if (DB::table(HrmClockStatus::table)->whereIn(HrmClockStatus::name,
            [HrmClockStatus::clockin,
                HrmClockStatus::clockout,HrmClockStatus::clockContinue,
                HrmClockStatus::pause])->doesntExist()) {

            $clockStatus = [
                [
                    HrmClockStatus::slug => Utilities::getUniqueId(),
                    HrmClockStatus::display_name => 'CLOCK IN',
                    HrmClockStatus::name => HrmClockStatus::clockin
                ],
                [
                    HrmClockStatus::slug => Utilities::getUniqueId(),
                    HrmClockStatus::display_name => 'PAUSE',
                    HrmClockStatus::name => HrmClockStatus::pause
                ],
                [
                    HrmClockStatus::slug => Utilities::getUniqueId(),
                    HrmClockStatus::display_name => 'CONTINUE',
                    HrmClockStatus::name => HrmClockStatus::clockContinue
                ],
                [
                    HrmClockStatus::slug => Utilities::getUniqueId(),
                    HrmClockStatus::display_name => 'CLOCK OUT',
                    HrmClockStatus::name => HrmClockStatus::clockout
                ],
                [
                    HrmClockStatus::slug => Utilities::getUniqueId(),
                    HrmClockStatus::display_name => 'EARLY CLOCKOUT',
                    HrmClockStatus::name => HrmClockStatus::earlyClockout
                ],
            ];

            DB::table(HrmClockStatus::table)->insert($clockStatus);

        }
    }
}
