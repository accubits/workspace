<?php

namespace Modules\PartnerManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PartnerManagementDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

         $this->call(PartnerTableSeeder::class);
         $this->call(PartnerManagerTableSeeder::class);
    }
}
