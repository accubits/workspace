<?php

namespace Modules\HrmManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Common\Utilities\Utilities;
use Modules\HrmManagement\Entities\HrmWorkReportScore;

class TaskScoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        foreach([HrmWorkReportScore::excellence,
                    HrmWorkReportScore::positive, HrmWorkReportScore::negative] as $status) {
            HrmWorkReportScore::updateOrCreate(
                [
                    HrmWorkReportScore::name => $status
                ],
                [
                    HrmWorkReportScore::slug => Utilities::getUniqueId(),
                    HrmWorkReportScore::name => $status,
                    HrmWorkReportScore::display_name => $status
                ]
            );
        }


    }
}
