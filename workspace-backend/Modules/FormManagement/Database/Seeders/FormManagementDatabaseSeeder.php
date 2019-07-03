<?php

namespace Modules\FormManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class FormManagementDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(SeedFormAccessTypeTableSeeder::class);
        $this->call(SeedFormStatusTableSeeder::class);
        $this->call(SeedFormComponentTypeTableSeeder::class);
    }
}
