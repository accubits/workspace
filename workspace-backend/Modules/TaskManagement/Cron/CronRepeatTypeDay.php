<?php

/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 10/3/18
 * Time: 8:26 PM
 */

namespace Modules\TaskManagement\Cron;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\Utilities;
use Modules\TaskManagement\Entities\Task;
use Modules\TaskManagement\Entities\TaskRepeat;
use Modules\TaskManagement\Entities\TaskRepeatMap;
use Modules\TaskManagement\Entities\TaskRepeatRecurrence;
use Modules\TaskManagement\Entities\TaskRepeatType;
use Modules\TaskManagement\Entities\TaskStatus;

class CronRepeatTypeDay
{
    public function __construct()
    {

    }

    public function process()
    {
        $taskIds = [];
        //DB::enableQueryLog();
        $repeatTasks = DB::table(Task::table)
            ->select(
                Task::table. '.id',
                Task::table. '.' .Task::org_id,
                Task::table. '.' .Task::title,
                Task::table. '.' .Task::description,
                Task::table. '.' .Task::reminder,
                Task::table. '.' .Task::responsible_person_id,
                Task::table. '.' .Task::creator_user_id,
                Task::table. '.' .Task::approver_user_id,
                Task::table. '.' .Task::task_status_id,
                Task::table. '.' .Task::priority,
                Task::table. '.' .Task::favourite,
                Task::table. '.' .Task::is_to_allemployees,
                Task::table. '.' .Task::repeatCronStatus,
                Task::table. '.' .Task::start_date,
                Task::table. '.' .Task::end_date,
                Task::table. '.' .Task::CREATED_AT,
                TaskRepeat::table. '.' .TaskRepeat::repeat_every,
                TaskRepeat::table. '.' .TaskRepeat::ends_never,
                TaskRepeat::table. '.' .TaskRepeat::ends_on,
                TaskRepeat::table. '.' .TaskRepeat::ends_after,
                TaskRepeatType::table. '.' .TaskRepeatType::title. ' as taskRepeatType'
            )
            ->join(TaskRepeat::table, Task::table. '.id', '=', TaskRepeat::table. '.' .TaskRepeat::task_id)
            ->join(TaskRepeatType::table, TaskRepeatType::table. '.id', '=', TaskRepeat::table. '.' .TaskRepeat::task_repeat_type_id)
            ->where(Task::table. '.' .Task::repeat, true)
            ->whereDate(Task::table. '.' .Task::end_date, '>=', Carbon::now())
            ->whereNull(Task::table. '.' .Task::repeatCronStatus)
            ->get();
        DB::beginTransaction();

        foreach ($repeatTasks as $repeatTask) {
            array_push($taskIds, $repeatTask->id);
            if (in_array($repeatTask->taskRepeatType, ['day', 'month'])) {
                $this->repeatDayOrMonth($repeatTask);
            }
        }

        DB::table(Task::table)->whereIn('id', $taskIds)->update([Task::repeatCronStatus => Task::cronCompleted]);

        DB::commit();

        //dd(DB::getQueryLog());
        die("repeat cron");
    }

    /**
     * repeat Day and month basis
     * @param $repeatTask
     */
    public function repeatDayOrMonth($repeatTask)
    {
        $startDate = null;
        if ($repeatTask->{Task::start_date}) {
            $dateInterval = Carbon::parse($repeatTask->{Task::start_date})
                ->diffInDays($repeatTask->{Task::end_date});
            $startDate = Carbon::parse($repeatTask->{Task::start_date});
        } else {
            $dateInterval = Carbon::parse($repeatTask->{Task::CREATED_AT})
                ->diffInDays($repeatTask->{Task::end_date});
            $startDate = Carbon::parse($repeatTask->{Task::CREATED_AT});
        }
        $dateInterval = $dateInterval + 1;

        //ends on
        if ($repeatTask->{TaskRepeat::ends_on}) {
            $this->endsOn($repeatTask, $startDate, $dateInterval);
        } else if ($repeatTask->{TaskRepeat::ends_after}) {
            $this->endsAfter($repeatTask, $startDate, $dateInterval);
        }

    }

    /**
     * ends on specified date
     * @param $repeatTask
     * @param $startDate
     * @param $dateInterval
     */
    public function endsOn($repeatTask, $startDate, $dateInterval)
    {
        $endsOn = Carbon::parse($repeatTask->{TaskRepeat::ends_on});
        while($startDate->lte($endsOn)) {
            $newStartDate      = $startDate;
            if ($repeatTask->taskRepeatType == 'day') {
                $newTaskStartDate = $startDate->addDays($repeatTask->repeat_every);
            } else if ($repeatTask->taskRepeatType == 'month') {
                $newTaskStartDate = $startDate->addMonth($repeatTask->repeat_every);
                //endsOn date is greater than startDate
                if ($newTaskStartDate->greaterThan(Carbon::parse($repeatTask->{TaskRepeat::ends_on}))) continue;
            }

            $this->repeatTask($repeatTask, $newTaskStartDate, $dateInterval);
        }
    }

    /**
     * ends after n number of occurances
     * @param $repeatTask
     * @param $startDate
     * @param $dateInterval
     */
    public function endsAfter($repeatTask, $startDate, $dateInterval)
    {
        $initialOccurance = 1;
        $occurances       = $repeatTask->{TaskRepeat::ends_after};
        while($initialOccurance <= $occurances) {
            $newStartDate      = $startDate;
            if ($repeatTask->taskRepeatType == 'day') {
                $newTaskStartDate = $startDate->addDays($repeatTask->repeat_every);
            } else if ($repeatTask->taskRepeatType == 'month') {
                $newTaskStartDate = $startDate->addMonth($repeatTask->repeat_every);
            }

            $this->repeatTask($repeatTask, $startDate, $dateInterval);
            $initialOccurance++;
        }
    }

    public function repeatTask($repeatTask, $newTaskStartDate, $dateInterval)
    {
        $newtask = new Task;
        $newtask->{Task::slug} = Utilities::getUniqueId();

        $newtask->{Task::start_date} = $newTaskStartDate->timestamp;
        $newtask->{Task::end_date}   = $newTaskStartDate->copy()->addDays($dateInterval)->timestamp;

        $newtask->{Task::title} =$repeatTask->{Task::title};
        $newtask->{Task::org_id} =$repeatTask->{Task::org_id};
        $newtask->{Task::reminder} =$repeatTask->{Task::reminder};
        $newtask->{Task::responsible_person_id} =$repeatTask->{Task::responsible_person_id};
        $newtask->{Task::creator_user_id} =$repeatTask->{Task::creator_user_id};
        $newtask->{Task::approver_user_id} =$repeatTask->{Task::approver_user_id};
        $newtask->{Task::task_status_id} =$repeatTask->{Task::task_status_id};
        $newtask->{Task::priority} =$repeatTask->{Task::priority};
        $newtask->{Task::favourite} =$repeatTask->{Task::favourite};
        $newtask->{Task::is_to_allemployees} =$repeatTask->{Task::is_to_allemployees};
        //$newtask->{Task::repeatCronStatus} =  Task::cronCompleted;

        $newtask->save();

        $taskRepeatMap = new TaskRepeatMap;
        $taskRepeatMap->{TaskRepeatMap::origin_task_id} = $repeatTask->id;
        $taskRepeatMap->{TaskRepeatMap::task_id}        = $newtask->id;
        $taskRepeatMap->save();
    }


}