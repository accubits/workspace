<?php

namespace Modules\HrmManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Common\Utilities\Utilities;
use Modules\HrmManagement\Entities\HrmLeaveTypeCategory;

class LeaveTypeCategoryTableSeeder extends Seeder
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
                HrmLeaveTypeCategory::slug      => Utilities::getUniqueId(),
                HrmLeaveTypeCategory::category_display_name     => 'Paid',
                HrmLeaveTypeCategory::category_name     => 'paid',
                HrmLeaveTypeCategory::is_active     => true,
                'created_at'          => now(),
                'updated_at'          => now()
            ],
            [
                HrmLeaveTypeCategory::slug      => Utilities::getUniqueId(),
                HrmLeaveTypeCategory::category_display_name     => 'Unpaid',
                HrmLeaveTypeCategory::category_name     => 'unpaid',
                HrmLeaveTypeCategory::is_active     => true,
                'created_at'          => now(),
                'updated_at'          => now()
            ],
            [
                HrmLeaveTypeCategory::slug      => Utilities::getUniqueId(),
                HrmLeaveTypeCategory::category_display_name     => 'Onduty',
                HrmLeaveTypeCategory::category_name     => 'onduty',
                HrmLeaveTypeCategory::is_active     => true,
                'created_at'          => now(),
                'updated_at'          => now()
            ]
        ];


        foreach ($data as $value) {
            $leaveTypeCat = HrmLeaveTypeCategory::where(HrmLeaveTypeCategory::category_name,'=',$value[HrmLeaveTypeCategory::category_name])->first();
            if(empty($leaveTypeCat)){
                HrmLeaveTypeCategory::create($value);
            }
        }
    }
}
