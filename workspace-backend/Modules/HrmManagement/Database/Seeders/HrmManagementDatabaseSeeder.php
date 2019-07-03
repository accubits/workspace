<?php

namespace Modules\HrmManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class HrmManagementDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

         $this->call(ClockStatusTableSeeder::class);
         $this->call(AddOrgDesignationTableSeeder::class);
         $this->call(AddOrgEmployeeDesignationTableSeeder::class);
         $this->call(AddOrgDepartmentTableSeeder::class);
         $this->call(AddOrgEmployeeDepartmentTableSeeder::class);
         $this->call(TaskScoresTableSeeder::class);
         $this->call(HrmTimingsTableSeeder::class);
         $this->call(LeaveTypeCategoryTableSeeder::class);
         $this->call(HrmWorkReportFrequencyTableSeeder::class);
         $this->call(SeedKraQuestionTypesTableSeeder::class);
         $this->call(SeedHrmTrainingStatusTableSeeder::class);
         $this->call(SeedHrmAppraisalCyclePeriodTableSeeder::class);
         $this->call(SeedHrmAppraisalCycleApplicableTableSeeder::class);
         $this->call(SeedHrmAppraisalMainModuleTableSeeder::class);

    }
}
