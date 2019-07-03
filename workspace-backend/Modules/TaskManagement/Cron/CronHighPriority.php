<?php

/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 10/3/18
 * Time: 8:26 PM
 */

namespace Modules\TaskManagement\Cron;


use Carbon\Carbon;
use Modules\TaskManagement\Entities\Task;
use Modules\TaskManagement\Entities\TaskStatus;

class CronHighPriority
{
    public function __construct()
    {

    }

    public function process()
    {
        $statusArr = $this->getStatusArray();

        $tasks = Task::select(Task::table. '.id', Task::table. '.' .Task::end_date)
            ->where(Task::table. '.' .Task::end_date, '>=', Carbon::now()->subHours(12))
            ->where(Task::table. '.' .Task::end_date, '<=', Carbon::now())
            ->where(Task::table. '.' .Task::priority,  0)
            ->where(function ($query) use ($statusArr) {
                $query->Where(Task::table. '.' .Task::task_status_id, '<>', $statusArr[TaskStatus::overdue])
                    ->Where(Task::table. '.' .Task::task_status_id,  '<>', $statusArr[TaskStatus::completed_approved])
                    ->Where(Task::table. '.' .Task::task_status_id,  '<>', $statusArr[TaskStatus::completed_waiting_approval]);
            })
            ->update([Task::table. '.' .Task::priority => 1]);
    }

    /**
     * get status and return as array with status title as key
     * @return array
     */
    public function getStatusArray()
    {
        $taskStatusArr = collect();
        $taskStatuses = TaskStatus::select(TaskStatus::table. '.id', TaskStatus::table. '.' .TaskStatus::title)
            ->get();

        $taskStatuses->map(function ($status)  use ($taskStatusArr) {
            $taskStatusArr[$status->title] = $status->id;
        });

        return $taskStatusArr->toArray();
    }

}