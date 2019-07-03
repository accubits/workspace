<?php

/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 17/10/18
 * Time: 06:46 PM
 */

namespace Modules\TaskManagement\Cron;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Common\Entities\Country;
use Modules\OrgManagement\Entities\Organization;
use Modules\TaskManagement\Entities\Task;
use Modules\TaskManagement\Entities\TaskParticipants;
use Modules\TaskManagement\Jobs\TaskReminderJob;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;

class CronTaskReminder
{
    public function __construct()
    {

    }

    /**
     * @TODO All employees email sending, uncomment dispatch
     */
    public function process()
    {
        $currentDateStr = Carbon::now()->toDateString();
        $reminderTasksQuery = DB::table(Task::table)->select(
                Task::table. '.id',
                Task::table. '.' .Task::slug,
                User::table. '.' .User::email,
                User::table. '.' .User::name,
                Task::table. '.' .Task::title,
                Task::table. '.' .Task::end_date,
                Task::table. '.' .Task::is_to_allemployees,
                Organization::table. '.' .Organization::timezone. ' as orgTimezone',
                UserProfile::table. '.' .UserProfile::timezone. ' as userTimezone'
            )
            ->join(User::table, User::table. '.id', '=', Task::table. '.' .Task::responsible_person_id)
            ->leftjoin(UserProfile::table, User::table. '.id', '=', UserProfile::table. '.' .UserProfile::user_id)
            ->join(Organization::table, Organization::table. '.id', '=', Task::table. '.' .Task::org_id)
            ->whereDate(Task::table. '.' .Task::end_date, '>=', $currentDateStr)
            ->whereDate(Task::table. '.' .Task::reminder, $currentDateStr)
            ->where(Task::table. '.' .Task::is_reminder_sent, false)
            ->where(Task::table. '.' .Task::is_template, false)
            ->whereNotNull(Task::table. '.' .Task::reminder);

            $reminderTasks = $reminderTasksQuery->get();

        foreach ($reminderTasks as $reminderTask) {
            dispatch(new TaskReminderJob($reminderTask));
        }

        $taskIdArr = $reminderTasksQuery->groupBy(Task::table. '.id')->pluck('id')->toArray();

        Task::whereIn(Task::table. '.id', $taskIdArr)->update([Task::table. '.' .Task::is_reminder_sent => true]);
//*******************start reminder send to participants code ************/
        /*$emailArr = [];
        foreach ($reminderTasks as $reminderTask) {
            if (!$reminderTask->is_to_allemployees) {
                $emailArr = $this->getParticipants($reminderTask->id);
                if (!in_array($reminderTask->email, $emailArr))
                    array_push($emailArr, $reminderTask->email);

                //dispatch(new TaskReminderJob($emailArr));
            }
        }

        $taskIdArr = $reminderTasksQuery->groupBy(Task::table. '.id')->pluck('id')->toArray();

        Task::whereIn(Task::table. '.id', $taskIdArr)->update([Task::table. '.' .Task::is_reminder_sent => true]);*/
//*******************end reminder send to participants code ************/

    }

    public function getParticipants($taskId)
    {
        return DB::table(TaskParticipants::table)
            ->select(User::table. '.' .User::email)
            ->join(User::table, User::table. '.id', '=', TaskParticipants::table. '.' .TaskParticipants::user_id)
            ->where(TaskParticipants::task_id, $taskId)
            ->get()
            ->pluck(User::email)->toArray();
    }


}