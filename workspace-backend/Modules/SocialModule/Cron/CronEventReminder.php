<?php

/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 18/03/19
 * Time: 06:46 PM
 */

namespace Modules\SocialModule\Cron;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\SocialModule\Entities\SocialEvent;
use Modules\SocialModule\Entities\SocialEventMember;
use Modules\SocialModule\Entities\SocialLookup;
use Modules\SocialModule\Jobs\EventReminderJob;
use Modules\UserManagement\Entities\User;

class CronEventReminder
{
    public function __construct()
    {

    }

    /**
     * @TODO - send to all participants is pending
     */
    public function process()
    {

        try {
            $currentDate = Carbon::now();

            $reminderEventsQuery = DB::table(SocialEvent::table)
                ->select(
                    SocialEvent::table. '.id as eventId',
                    SocialEvent::table. '.' .SocialEvent::event_title. ' as eventTitle',
                    SocialEvent::table. '.' .SocialEvent::event_start_date,
                    SocialEvent::table. '.' .SocialEvent::reminder_count,
                    SocialLookup::table. '.' .SocialLookup::value,
                    //DB::raw("GROUP_CONCAT(COALESCE(".User::table. '.' .User::email . ',', ''.")) AS members"),
                    DB::raw('GROUP_CONCAT((' .User::table. '.' .User::email.')) as memberEmail'),
                    DB::raw('GROUP_CONCAT((' .User::table. '.' .User::name.')) as memberName')
                )
                ->join(SocialLookup::table, SocialEvent::table. '.' .SocialEvent::reminder_type_id, '=', SocialLookup::table. '.id')
                ->join(SocialEventMember::table, SocialEventMember::table. '.' .SocialEventMember::social_event_id, '=', SocialEvent::table. '.id')
                ->join(User::table, SocialEventMember::table. '.' .SocialEventMember::user_id, '=', User::table. '.id')
                ->where(SocialEvent::table. '.' .SocialEvent::event_start_date, '>=', $currentDate)
                ->whereNotNull(SocialEvent::table. '.' .SocialEvent::reminder_count)
                ->where(SocialLookup::table. '.' .SocialLookup::attribute, 'reminder');

            $reminderEvents = $reminderEventsQuery->get();

            foreach ($reminderEvents as &$reminderEvent) {
                $startDt     = $reminderEvent->{SocialEvent::event_start_date};
                $reminderCnt = $reminderEvent->{SocialEvent::reminder_count};
                $val         = $reminderEvent->{SocialLookup::value};

                $calReminderTime = $this->calculateReminderTime($val, $startDt, $reminderCnt);

                if ($currentDate >= $calReminderTime) {
                    $reminderEvent->members = explode(',', $reminderEvent->memberEmail);
                    $reminderEvent->memberNames = explode(',', $reminderEvent->memberName);
                    dispatch(new EventReminderJob($reminderEvent));
                }

            }

            $eventIdArr = $reminderEventsQuery->groupBy(SocialEvent::table . '.id')->pluck('eventId')->toArray();
            SocialEvent::whereIn(SocialEvent::table . '.id', $eventIdArr)->update([SocialEvent::table . '.' . SocialEvent::is_reminder_sent => true]);

        } catch (\Exception $e) {

        }

        dd("sd");
/*        $currentDateStr = Carbon::now()->toDateString();
        $reminderTasksQuery = DB::table(Task::table)->select(
            Task::table . '.id',
            Task::table . '.' . Task::slug,
            User::table . '.' . User::email,
            User::table . '.' . User::name,
            Task::table . '.' . Task::title,
            Task::table . '.' . Task::end_date,
            Task::table . '.' . Task::is_to_allemployees,
            Organization::table . '.' . Organization::timezone . ' as orgTimezone',
            UserProfile::table . '.' . UserProfile::timezone . ' as userTimezone'
        )
            ->join(User::table, User::table . '.id', '=', Task::table . '.' . Task::responsible_person_id)
            ->leftjoin(UserProfile::table, User::table . '.id', '=', UserProfile::table . '.' . UserProfile::user_id)
            ->join(Organization::table, Organization::table . '.id', '=', Task::table . '.' . Task::org_id)
            ->whereDate(Task::table . '.' . Task::end_date, '>=', $currentDateStr)
            ->whereDate(Task::table . '.' . Task::reminder, $currentDateStr)
            ->where(Task::table . '.' . Task::is_reminder_sent, false)
            ->where(Task::table . '.' . Task::is_template, false)
            ->whereNotNull(Task::table . '.' . Task::reminder);

        $reminderTasks = $reminderTasksQuery->get();

        foreach ($reminderTasks as $reminderTask) {
            dispatch(new TaskReminderJob($reminderTask));
        }

        $taskIdArr = $reminderTasksQuery->groupBy(Task::table . '.id')->pluck('id')->toArray();

        Task::whereIn(Task::table . '.id', $taskIdArr)->update([Task::table . '.' . Task::is_reminder_sent => true]);*/
    }

    public function calculateReminderTime($type, $startDate, $val)
    {
        $subDt = null;
        if ($type == 'Minutes') {
            $subDt = Carbon::parse($startDate)->subMinutes($val);
        } else if ($type == 'Hours') {
            $subDt = Carbon::parse($startDate)->subHours($val);
        } else if ($type == 'Days') {
            $subDt = Carbon::parse($startDate)->subDays($val);
        }
        return $subDt;
    }


}