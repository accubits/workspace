<?php

namespace Modules\DriveManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DriveManagementDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

         $this->call(DriveTypesTableSeeder::class);
         $this->call(DriveTableSeeder::class);
         $this->call(DrivePermissionsTableSeeder::class);
    }
}
