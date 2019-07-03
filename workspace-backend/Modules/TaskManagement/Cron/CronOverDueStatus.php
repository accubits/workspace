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
use Modules\TaskManagement\Entities\TaskStatusLog;
use Modules\UserManagement\Entities\User;

class CronOverDueStatus
{
    public function __construct()
    {

    }

    public function process()
    {
        $statusArr = $this->getStatusArray();

        $tasks = Task::select(Task::table. '.id', Task::table. '.' .Task::end_date)
            ->where(Task::table. '.' .Task::end_date, '<', Carbon::now())
            ->where(function ($query) use ($statusArr) {
                $query->Where(Task::table. '.' .Task::task_status_id, '<>', $statusArr[TaskStatus::overdue])
                    ->Where(Task::table. '.' .Task::task_status_id,  '<>', $statusArr[TaskStatus::completed_approved])
                    ->Where(Task::table. '.' .Task::task_status_id,  '<>', $statusArr[TaskStatus::completed_waiting_approval]);
            })
            ->get();


        $user = User::where(User::table. '.' .User::email, 'system@mailinator.com')->first();

        $tasks->map(function ($task) use ($user, $statusArr) {
            $task->{Task::task_status_id} = $statusArr[TaskStatus::overdue];
            $task->{Task::priority}       = false;
            $task->save();
            $this->addTaskStatusLog($task, $user);
        });
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

    /**
     * @param $task
     * @param $user
     */
    public function addTaskStatusLog($task, $user)
    {
        $taskStatusLog = TaskStatusLog::where(TaskStatusLog::task_id, $task->id)
            ->latest()->first();

        if ((!$taskStatusLog) ||
            ($taskStatusLog->{TaskStatusLog::current_status_id} != $task->{Task::task_status_id})) {
            TaskStatusLog::create([
                TaskStatusLog::task_id => $task->id,
                TaskStatusLog::user_id => $user->id,
                TaskStatusLog::previous_status_id => ($taskStatusLog)? $taskStatusLog->{TaskStatusLog::current_status_id} : NULL,
                TaskStatusLog::status_log_time    => Carbon::now(),
                TaskStatusLog::current_status_id  => $task->{Task::task_status_id}
            ]);
        }

        return;
    }

}