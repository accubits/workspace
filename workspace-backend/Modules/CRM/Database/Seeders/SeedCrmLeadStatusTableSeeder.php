<?php

namespace Modules\CRM\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\CRM\Entities\CRMLeadStatus;
use Illuminate\Support\Facades\DB;

class SeedCrmLeadStatusTableSeeder extends Seeder
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
                CRMLeadStatus::status_name => 'unassiged',
                CRMLeadStatus::status_displayname => 'unassiged',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                CRMLeadStatus::status_name => 'responsibleAssigned',
                CRMLeadStatus::status_displayname => 'Responsible Assigned',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                CRMLeadStatus::status_name => 'waitingForDetails',
                CRMLeadStatus::status_displayname => 'Waiting for details',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                CRMLeadStatus::status_name => 'cannotContact',
                CRMLeadStatus::status_displayname => 'Cannot contact',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                CRMLeadStatus::status_name => 'Inprogress',
                CRMLeadStatus::status_displayname => 'In progress',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                CRMLeadStatus::status_name => 'onHold',
                CRMLeadStatus::status_displayname => 'On Hold',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                CRMLeadStatus::status_name => 'customer',
                CRMLeadStatus::status_displayname => 'Customer',
                'created_at' => date("Y-m-d H:i:s")
            ]
        ];

        foreach ($data as $value) {
            $valueArray = $value;
            unset($value['created_at']);
            $mainKeysArray = $value;
            CRMLeadStatus::updateOrCreate($mainKeysArray, $valueArray);
        }
    }
}
