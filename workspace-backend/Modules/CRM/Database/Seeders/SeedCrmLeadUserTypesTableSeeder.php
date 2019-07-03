<?php

namespace Modules\CRM\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\CRM\Entities\CRMLeadUserType;
use Illuminate\Support\Facades\DB;

class SeedCrmLeadUserTypesTableSeeder extends Seeder
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
                CRMLeadUserType::type_name => 'student',
                CRMLeadUserType::type_displayname => 'Student',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                CRMLeadUserType::type_name => 'agent',
                CRMLeadUserType::type_displayname => 'Agent',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                CRMLeadUserType::type_name => 'other',
                CRMLeadUserType::type_displayname => 'Other',
                'created_at' => date("Y-m-d H:i:s")
            ]
        ];

        foreach ($data as $value) {
            $valueArray = $value;
            unset($value['created_at']);
            $mainKeysArray = $value;
            CRMLeadUserType::updateOrCreate($mainKeysArray, $valueArray);
        }
    }
}
