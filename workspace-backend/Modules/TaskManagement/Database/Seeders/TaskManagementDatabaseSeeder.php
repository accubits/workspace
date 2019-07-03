<?php

namespace Modules\TaskManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TaskManagementDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

         $this->call(TaskStatusTableSeeder::class);
         $this->call(TaskRepeatTypeTableSeeder::class);
         $this->call(TaskScoreTableSeeder::class);
         $this->call(DefaultTaskScoreTableSeeder::class);
    }
}
