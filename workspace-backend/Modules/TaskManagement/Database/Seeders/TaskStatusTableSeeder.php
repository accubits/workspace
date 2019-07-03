<?php

namespace Modules\TaskManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\Utilities;
use Modules\TaskManagement\Entities\TaskStatus;

class TaskStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*$statuses = [TaskStatus::active, TaskStatus::overdue, TaskStatus::ongoing, TaskStatus::pause,
            TaskStatus::completed_waiting_approval, TaskStatus::completed_approved];

        $taskStatusArr = [];
        foreach($statuses as $status) {
            if (DB::table(TaskStatus::table)->where(TaskStatus::title, $status)->doesntExist()) {
                if ($status == TaskStatus::active) {
                    $displayName = 'Active';
                } else if ($status == TaskStatus::overdue) {
                    $displayName = 'Overdue';
                } else if ($status == TaskStatus::ongoing) {
                    $displayName = 'Ongoing';
                } else if ($status == TaskStatus::pause) {
                    $displayName = 'Pause';
                } else if ($status == TaskStatus::completed_waiting_approval) {
                    $displayName = 'Awaiting Approval';
                } else if ($status == TaskStatus::completed_approved) {
                    $displayName = 'Completed';
                }
                $taskStatusArr[] =  [
                    TaskStatus::slug => Utilities::getUniqueId(),
                    TaskStatus::title => $status,
                    TaskStatus::display_name => $displayName
                ];
            }
        }

        if (!empty($taskStatusArr)) {
            DB::table(TaskStatus::table)->insert($taskStatusArr);
        }*/

        $data = [
            [
                TaskStatus::slug      => Utilities::getUniqueId(),
                TaskStatus::title     => TaskStatus::active,
                TaskStatus::display_name => 'Active',
                'created_at'          => now(),
                'updated_at'          => now()
            ],
            [
                TaskStatus::slug      => Utilities::getUniqueId(),
                TaskStatus::title     => TaskStatus::overdue,
                TaskStatus::display_name => 'Overdue',
                'created_at'          => now(),
                'updated_at'          => now()
            ],
            [
                TaskStatus::slug      => Utilities::getUniqueId(),
                TaskStatus::title     => TaskStatus::ongoing,
                TaskStatus::display_name => 'Ongoing',
                'created_at'          => now(),
                'updated_at'          => now()
            ],
            [
                TaskStatus::slug      => Utilities::getUniqueId(),
                TaskStatus::title     => TaskStatus::completed_waiting_approval,
                TaskStatus::display_name => 'Awaiting Approval',
                'created_at'          => now(),
                'updated_at'          => now()
            ],
            [
                TaskStatus::slug      => Utilities::getUniqueId(),
                TaskStatus::title     => TaskStatus::completed_approved,
                TaskStatus::display_name => 'Completed',
                'created_at'          => now(),
                'updated_at'          => now()
            ],

            [
                TaskStatus::slug      => Utilities::getUniqueId(),
                TaskStatus::title     => TaskStatus::pause,
                TaskStatus::display_name => 'Pause',
                'created_at'          => now(),
                'updated_at'          => now()
            ]
        ];

        foreach ($data as $value) {
            $taskStatus = TaskStatus::where(TaskStatus::title,'=',$value[TaskStatus::title])->first();
            if(empty($taskStatus)){
                TaskStatus::create($value);
            }
        }
    }
}
