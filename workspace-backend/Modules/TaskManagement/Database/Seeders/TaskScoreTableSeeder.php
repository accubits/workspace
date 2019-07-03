<?php

namespace Modules\TaskManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\TaskManagement\Entities\TaskScore;

class TaskScoreTableSeeder extends Seeder
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
                TaskScore::score_name          => 'excellence',
                TaskScore::score_display_name  => 'Excellence'
            ],
            [
                TaskScore::score_name          => 'positive',
                TaskScore::score_display_name  => 'Positive'
            ],
            [
                TaskScore::score_name          => 'negative',
                TaskScore::score_display_name  => 'Negative'
            ]
        ];

        foreach ($data as $value) {
            $valueArray = $value;
            unset($value['created_at']);
            unset($value['updated_at']);
            $mainKeysArray = $value;

            TaskScore::updateOrCreate($mainKeysArray, $valueArray);
        }
    }
}
