<?php

namespace Modules\OrgManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\OrgManagement\Entities\OrgEmployeeStatus;

class OrgEmployeeStatusTableSeeder extends Seeder
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
                OrgEmployeeStatus::name => OrgEmployeeStatus::WORKING,
                'created_at'          => now(),
                'updated_at'          => now()
            ],
            [
                OrgEmployeeStatus::name => OrgEmployeeStatus::EX_EMPLOYEE,
                'created_at'          => now(),
                'updated_at'          => now()
            ],
            [
                OrgEmployeeStatus::name => OrgEmployeeStatus::ON_LEAVE,
                'created_at'          => now(),
                'updated_at'          => now()
            ]
        ];

        foreach ($data as $value) {
            $valueArray = $value;
            unset($value['created_at']);
            unset($value['updated_at']);
            $mainKeysArray = $value;

            OrgEmployeeStatus::updateOrCreate($mainKeysArray, $valueArray);
        }
    }
}
