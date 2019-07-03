<?php

namespace Modules\TaskManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\TaskManagement\Entities\TaskRepeatType;

class TaskRepeatTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            [
                TaskRepeatType::title => 'day',
                'created_at'          => now(),
                'updated_at'          => now()
            ],
            [
                TaskRepeatType::title => 'week',
                'created_at'          => now(),
                'updated_at'          => now()
            ],
            [
                TaskRepeatType::title => 'month',
                'created_at'          => now(),
                'updated_at'          => now()
            ],
            [
                TaskRepeatType::title => 'year',
                'created_at'          => now(),
                'updated_at'          => now()
            ]
        ];

        foreach ($data as $value) {
            $valueArray = $value;
            unset($value['created_at']);
            unset($value['updated_at']);
            $mainKeysArray = $value;

            TaskRepeatType::updateOrCreate($mainKeysArray, $valueArray);
        }
    }
}
