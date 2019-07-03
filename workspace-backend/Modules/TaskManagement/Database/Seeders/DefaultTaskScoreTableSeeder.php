<?php

namespace Modules\TaskManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\TaskManagement\Entities\Task;
use Modules\TaskManagement\Entities\TaskScore;

class DefaultTaskScoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $taskScore = DB::table(TaskScore::table)
            ->select('id')
            ->where(TaskScore::score_name, 'positive')->first();

        if (!empty($taskScore)) {
            Task::whereNull(Task::task_score_id)
                ->update([Task::task_score_id => $taskScore->id]);
        }

    }
}
