<?php

namespace Modules\OrgManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class OrgManagementDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

         $this->call(LicenseTypeTableSeeder::class);
         $this->call(LicenseTableSeeder::class);
         $this->call(OrganizationTableSeeder::class);
         $this->call(OrgLicenseTableSeeder::class);
         $this->call(OrgEmployeeStatusTableSeeder::class);
         $this->call(OrgEmployeeAddTableSeeder::class);
    }
}
