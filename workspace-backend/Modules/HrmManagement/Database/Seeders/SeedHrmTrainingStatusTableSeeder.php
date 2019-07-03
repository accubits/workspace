<?php

namespace Modules\HrmManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\HrmManagement\Entities\HrmTrainingStatus;

class SeedHrmTrainingStatusTableSeeder extends Seeder
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
                HrmTrainingStatus::name => 'Awaiting Approval',
                HrmTrainingStatus::value => 'awaitingApproval',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                HrmTrainingStatus::name => 'Approved',
                HrmTrainingStatus::value => 'approved',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                HrmTrainingStatus::name => 'Rejected',
                HrmTrainingStatus::value => 'rejected',
                'created_at' => date("Y-m-d H:i:s")
            ]];

        foreach ($data as $value) {
            $valueArray = $value;
            unset($value['created_at']);
            $mainKeysArray = $value;
            HrmTrainingStatus::updateOrCreate($mainKeysArray, $valueArray);
        }
    }
}
