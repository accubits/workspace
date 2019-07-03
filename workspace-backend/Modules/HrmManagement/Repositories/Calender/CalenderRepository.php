<?php
/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 26/09/18
 * Time: 12:55 PM
 */

namespace Modules\HrmManagement\Repositories\Calender;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\ResponseStatus;
use Modules\HrmManagement\Repositories\CalenderInterface;
use Modules\SocialModule\Entities\SocialEvent;
use Modules\SocialModule\Entities\SocialEventMember;
use Modules\TaskManagement\Entities\Task;
use Modules\TaskManagement\Entities\TaskParticipants;
use Nwidart\Modules\Facades\Module;

class CalenderRepository implements CalenderInterface
{

    protected $content;

    public function __construct()
    {
        $this->content = array();
    }

    public function fetchAllCalender(Request $request)
    {
        try {
            $loggedUser = Auth::user();
            //DB::enableQueryLog();

            $dayMonthArr = $this->getDayMonthArray($request);

            $mergeEventTasks = [];
            $event = $this->getCalenderEventQuery($request, $loggedUser, $dayMonthArr);
            $tasks = $this->getCalenderTaskQuery($request, $loggedUser, $dayMonthArr);

            $mergeEventTasks = array_merge_recursive(['event' => $event], ['task' => $tasks]);
//dd($mergeEventTasks);

            //dd(DB::getQueryLog());


            $maxEndDate = end($dayMonthArr)['date'];
            $maxEndDate = strtotime($maxEndDate);

            $maxStartDate = reset($dayMonthArr)['date'];
            $maxStartDate = strtotime($maxStartDate);
            //dd($mergeEventTasks);
            if ($request->type == 'month') {
                foreach ($mergeEventTasks as $keyTaskEvents => $taskEvents) {
                    foreach ($taskEvents as $taskEvent) {
                        $startDate  = $taskEvent->startDate;
                        $endDate    = $taskEvent->endDate;
                        if (!$startDate) $startDate = $endDate;

                        $startDate  = ($startDate < $maxStartDate)? $maxStartDate : $startDate;
                        $endDate    = ($endDate > $maxEndDate)? $maxEndDate : $endDate;

                        while( $startDate <= $endDate ) {
                            $dayMonthArr[date('Y-m-d', $startDate)][$keyTaskEvents][] = array(
                                'slug'      => $taskEvent->slug,
                                'title'     => $taskEvent->title,
                                'startDateStr' => $taskEvent->startDateStr,
                                'startDate' => $taskEvent->startDate,
                                'endDateStr'   => $taskEvent->endDateStr,
                                'endDate'   => $taskEvent->endDate,
                                'startDay'  => $taskEvent->startDay,
                                'endDay'    => $taskEvent->endDay,
                            );

                            //task start and end date is same
                            if (($taskEvent->startDay == $taskEvent->endDay) && $request->type == 'week') {
                                $dayMonthArr = $this->createTimingTaskEvents($dayMonthArr, date('Y-m-d', $startDate), $keyTaskEvents, $taskEvent);
                            }

                            $dayMonthArr[date('Y-m-d', $startDate)]['totalCount'] = $dayMonthArr[date('Y-m-d', $startDate)]['totalCount'] + 1;
                            $startDate = strtotime("+1 day", $startDate);
                        }
                    }
                }
            } else if ($request->type == 'week') {

                foreach ($mergeEventTasks as $keyTaskEvents => $taskEvents) {
                    foreach ($taskEvents as $taskEvent) {
                        $startDate  = $taskEvent->startDate;
                        $endDate    = $taskEvent->endDate;
                        if (!$startDate) $startDate = $endDate;

                        $startDate  = ($startDate < $maxStartDate)? $maxStartDate : $startDate;
                        $endDate    = ($endDate > $maxEndDate)? $maxEndDate : $endDate;

                        while( $startDate <= $endDate ) {

                            $dayMonthArr[date('Y-m-d', $startDate)]['overview'][$keyTaskEvents][] = array(
                                'slug'      => $taskEvent->slug,
                                'title'     => $taskEvent->title,
                                'startDateStr' => $taskEvent->startDateStr,
                                'startDate' => $taskEvent->startDate,
                                'endDateStr'   => $taskEvent->endDateStr,
                                'endDate'   => $taskEvent->endDate,
                                'startDay'  => $taskEvent->startDay,
                                'endDay'    => $taskEvent->endDay,
                            );

                            //task start and end date is same
                            if ($taskEvent->startDay == $taskEvent->endDay) {
                                $dayMonthArr = $this->createTimingTaskEvents($dayMonthArr, date('Y-m-d', $startDate), $keyTaskEvents, $taskEvent);
                            }

                            $dayMonthArr[date('Y-m-d', $startDate)]['overview']['totalCount'] = $dayMonthArr[date('Y-m-d', $startDate)]['overview']['totalCount'] + 1;
                            $startDate = strtotime("+1 day", $startDate);
                        }
                    }
                }


            } else if ($request->type == 'day') {
//dd($mergeEventTasks);
                foreach ($mergeEventTasks as $keyTaskEvents => $taskEvents) {
                    foreach ($taskEvents as $taskEvent) {
                        $startDate  = $taskEvent->startDate;
                        $endDate    = $taskEvent->endDate;
                        if (!$startDate) $startDate = $endDate;

                        $startDate  = ($startDate < $maxStartDate)? $maxStartDate : $startDate;
                        $endDate    = ($endDate > $maxEndDate)? $maxEndDate : $endDate;

                        $dayMonthArr[date('Y-m-d', $startDate)]['overview'][$keyTaskEvents][] = array(
                            'slug'      => $taskEvent->slug,
                            'title'     => $taskEvent->title,
                            'startDateStr' => $taskEvent->startDateStr,
                            'startDate' => $taskEvent->startDate,
                            'endDateStr'   => $taskEvent->endDateStr,
                            'endDate'   => $taskEvent->endDate,
                            'startDay'  => $taskEvent->startDay,
                            'endDay'    => $taskEvent->endDay,
                        );

                        //task start and end date is same
                        if ($taskEvent->startDay == $taskEvent->endDay) {
                            $dayMonthArr = $this->createTimingTaskEvents($dayMonthArr, date('Y-m-d', $startDate), $keyTaskEvents, $taskEvent);
                        }

                        $dayMonthArr[date('Y-m-d', $startDate)]['overview']['totalCount'] = $dayMonthArr[date('Y-m-d', $startDate)]['overview']['totalCount'] + 1;

                    }
                }


                /*foreach ($mergeEventTasks as $keyTaskEvents => $taskEvents) {
                    foreach ($taskEvents as $taskEvent) {
                        $startDate  = $taskEvent->startDate;
                        $endDate    = $taskEvent->endDate;
                        if ($endDate >= $maxEndDate) {
                            $dayMonthArr[date('Y-m-d', $endDate)]['overview'][$keyTaskEvents][] = array(
                                'slug'      => $taskEvent->slug,
                                'title'     => $taskEvent->title,
                                'startDateStr' => $taskEvent->startDateStr,
                                'startDate' => $taskEvent->startDate,
                                'endDateStr'   => $taskEvent->endDateStr,
                                'endDate'   => $taskEvent->endDate,
                                'startDay'  => $taskEvent->startDay,
                                'endDay'    => $taskEvent->endDay,
                            );

                            //task start and end date is same
                            if ($taskEvent->startDay == $taskEvent->endDay) {
                                $dayMonthArr = $this->createTimingTaskEvents($dayMonthArr, date('Y-m-d', $startDate), $keyTaskEvents, $taskEvent);
                            }
                        }
                    }
                }*/


            }

//dd($dayMonthArr);
            $response = array_values($dayMonthArr);

            return $this->content = array(
                'data'   => array('calender' => $response),
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );

        } catch (\Exception $e) {
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  $e->getCode();
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }


    }

    public function generateTimeSlots($dayMonthArr, $date)
    {
        if (empty($dayMonthArr[$date]['timing'])) {
            $range = range(0, 23);
            foreach ($range as $timeSlot) {
                $dayMonthArr[$date]['timing'][$timeSlot] = array(
                    'timeSlot' => $timeSlot,
                    'taskAndEvents' => ['task' => [], 'event' => []]
                );
            }
        }

        return  $dayMonthArr;
    }

    public function createTimingTaskEvents($dayMonthArr, $date, $taskEventKeys, $taskEvent)
    {
        $startHour   = $taskEvent->startDateHour;
        $endHour     = $taskEvent->endDateHour;
        $dayMonthArr[$date]['timing'][$startHour]['taskAndEvents'][$taskEventKeys][] = $taskEvent;
        return $dayMonthArr;
    }

    /**
     * get events
     * @param $request
     * @param $loggedUser
     * @param $dayMonthArr
     * @return array
     */
    public function getCalenderEventQuery($request, $loggedUser, $dayMonthArr)
    {
        $timeZoneOffset = $this->getCalenderTimezoneOffset($request->timezone);

        $rawStartDay = sprintf("day(CONVERT_TZ(%s, '+00:00', '%s')) as startDay", SocialEvent::table. '.' .SocialEvent::event_start_date, $timeZoneOffset);
        $rawEndDay   = sprintf("day(CONVERT_TZ(%s, '+00:00', '%s')) as endDay", SocialEvent::table. '.' .SocialEvent::event_end_date, $timeZoneOffset);

        $event = DB::table(SocialEvent::table)->leftjoin(SocialEventMember::table, SocialEvent::table. '.id', '=',
            SocialEventMember::table. '.' .SocialEventMember::social_event_id)
            ->where(function ($query) use ($loggedUser) {
                $query->orWhere(SocialEvent::table. '.' .SocialEvent::creator_user_id, $loggedUser->id)
                      ->orWhere(SocialEventMember::table. '.' .SocialEventMember::user_id, $loggedUser->id);
            })->select(
                SocialEvent::table. '.' .SocialEvent::event_slug. ' as slug',
                SocialEvent::table. '.' .SocialEvent::event_title. ' as title',
                DB::raw("CONVERT_TZ(" .SocialEvent::table. '.' .SocialEvent::event_start_date. ", '+00:00', '$timeZoneOffset') as startDateStr"),
                DB::raw("unix_timestamp(".SocialEvent::table . '.'.SocialEvent::event_start_date.") AS startDate"),
                DB::raw("CONVERT_TZ(" .SocialEvent::table. '.' .SocialEvent::event_end_date. ", '+00:00', '$timeZoneOffset') as endDateStr"),
                DB::raw("unix_timestamp(".SocialEvent::table . '.'.SocialEvent::event_end_date.") AS endDate"),
                DB::raw("hour(CONVERT_TZ(" .SocialEvent::table. '.' .SocialEvent::event_start_date. ", '+00:00', '$timeZoneOffset')) as startDateHour"),
                DB::raw("hour(CONVERT_TZ(" .SocialEvent::table. '.' .SocialEvent::event_end_date. ", '+00:00', '$timeZoneOffset')) as endDateHour"),
                DB::raw($rawStartDay),
                DB::raw($rawEndDay)
            )->groupBy(SocialEvent::table. '.' .SocialEvent::event_slug);

        $dateRange = $this->getDDMMYYRanges($request);


        $event->where(function ($eQuery) use ($dateRange) {
            $eQuery->whereDate(SocialEvent::table. '.' .SocialEvent::event_start_date, '>=', $dateRange['startDate'])
                ->whereDate(SocialEvent::table. '.' .SocialEvent::event_start_date, '<=', $dateRange['endDate'])
                ->orWhereDate(SocialEvent::table. '.' .SocialEvent::event_end_date, '>=', $dateRange['startDate'])
                ->whereDate(SocialEvent::table. '.' .SocialEvent::event_end_date, '<=', $dateRange['endDate']);
        });


/*        DB::enableQueryLog();
        $event->get();
        dd(DB::getQueryLog());*/

        return $event->get()->toArray();

    }

    public function getCalenderTimezoneOffset($timezone = 'Asia/Calcutta')
    {
        $timeZoneOffset = Carbon::now($timezone)->format('P');
        return $timeZoneOffset;
    }

    /**
     * @TODO start_date null case need to fix
     * calender task query
     * @param $request
     * @param $loggedUser
     * @param $dayMonthArr
     * @return array
     *
     */
    public function getCalenderTaskQuery($request, $loggedUser, $dayMonthArr)
    {

        $timezoneOffset = $this->getCalenderTimezoneOffset($request->timezone);


        $rawStartDay = sprintf("IFNULL(day(CONVERT_TZ(%s, '+00:00', '$timezoneOffset')), day(CONVERT_TZ(%s, '+00:00', '$timezoneOffset'))) as startDay", Task::table. '.' .Task::start_date, Task::table. '.' .Task::end_date);
        $rawStartDayHour = sprintf("IFNULL(hour(CONVERT_TZ(%s, '+00:00', '$timezoneOffset')), hour(CONVERT_TZ(%s, '+00:00', '$timezoneOffset'))) as startDateHour", Task::table. '.' .Task::start_date, Task::table. '.' .Task::end_date);
        $rawEndDay   = sprintf("day(CONVERT_TZ(%s, '+00:00', '$timezoneOffset')) as endDay", Task::table. '.' .Task::end_date);
        $rawStartOrCreatedAt   = sprintf("IFNULL(CONVERT_TZ(%s, '+00:00', '$timezoneOffset'), CONVERT_TZ(%s, '+00:00', '$timezoneOffset')) as %s", Task::table. '.' .Task::start_date, Task::table. '.created_at', Task::table. '.' .Task::start_date);
        $tasks = DB::table(Task::table)
            ->select(
                Task::table. '.' .Task::slug. ' as slug',
                Task::table. '.' .Task::title. ' as title',
                DB::raw("CONVERT_TZ(" .Task::table. '.' .Task::start_date. ", '+00:00', '$timezoneOffset') as startDateStr"),
                DB::raw("unix_timestamp(".Task::table . '.'.Task::start_date.") AS startDate"),
                DB::raw($rawStartDayHour),
                Task::table. '.created_at' ,
                DB::raw("CONVERT_TZ(" .Task::table. '.' .Task::end_date. ", '+00:00', '$timezoneOffset') as endDateStr"),
                DB::raw("unix_timestamp(".Task::table . '.'.Task::end_date.") AS endDate"),
                DB::raw('hour('.Task::table. '.' .Task::end_date. ') as endDateHour'),
                DB::raw($rawStartDay),
                DB::raw($rawEndDay)
            )
            ->leftJoin(TaskParticipants::table, TaskParticipants::table. '.' . TaskParticipants::task_id, '=',  Task::table. '.id')
            ->where(function ($query) use ($loggedUser) {
                $query->orWhere(Task::table . '.' .Task::approver_user_id, $loggedUser->id)
                    ->orWhere(Task::table . '.' .Task::responsible_person_id, $loggedUser->id)
                    ->orWhere(TaskParticipants::table . '.' .TaskParticipants::user_id, $loggedUser->id)
                    ->orWhere(Task::table. '.' .Task::is_to_allemployees, true);
            })->groupBy(Task::table. '.' .Task::slug);

            $dateRange = $this->getDDMMYYRanges($request);

        $tasks->where(function ($eQuery) use ($dateRange) {
            $eQuery->whereDate(Task::table. '.' .Task::end_date, '>=', $dateRange['startDate'])
                ->whereDate(Task::table. '.' .Task::end_date, '<=', $dateRange['endDate'])
                ->orWhereDate(Task::table. '.' .Task::end_date, '>=', $dateRange['startDate'])
                ->whereDate(Task::table. '.' .Task::end_date, '<=', $dateRange['endDate']);
        });



/*        DB::enableQueryLog();
        $tasks->get();
        dd(DB::getQueryLog());*/
        return $tasks->get()->toArray();
    }

    public function getDDMMYYRanges($request) {
        if ($request->type == 'week') {
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            Carbon::setWeekEndsAt(Carbon::SATURDAY);
            $num = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
            $day = (!$request->day) ? date('j', strtotime(now())) : $request->day;
            $startDate    = Carbon::createFromDate($request->year, $request->month, $day);
            $endDate    = Carbon::createFromDate($request->year, $request->month, $num);
            $startDate = $startDate->startOfWeek()->toDateString();
            $endDate = $endDate->endOfWeek()->toDateString();
        } else if ($request->type == 'day') {
            $day = (!$request->day) ? date('j', strtotime(now())) : $request->day;
            $startDate    = $endDate = Carbon::createFromDate($request->year, $request->month, $day);
            $startDate    = $startDate->toDateString();
            $endDate      = $endDate->toDateString();
        } else if ($request->type == 'month') {
            $num = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
            $startDate    = Carbon::createFromDate($request->year, $request->month, 1)->toDateString();
            $endDate    = Carbon::createFromDate($request->year, $request->month, $num)->toDateString();
        }

        return ['startDate' => $startDate, 'endDate' => $endDate];

    }

    public function weekRange($first, $last, $step = '+1 day') {
        $dates_month = array();
        $current = strtotime($first);
        $last = strtotime($last);
        $timeslots = $this->genTimeSlots();

        while( $current <= $last ) {
            $mktime = mktime(0, 0, 0, date('m', $current), date('d', $current), date('Y', $current));
            $date = date("Y-m-d", $mktime);
            $dates_month[date('Y-m-d', $current)] = ['dateStr' => $mktime, 'date' => $date, 'dayName' => date('D', $mktime)];
            $dates_month[date('Y-m-d', $current)]['overview'] = array('task' => array(), 'event' => array(), 'totalCount' => 0);
            $dates_month[date('Y-m-d', $current)]['timing'] = $timeslots;
            $current = strtotime($step, $current);
        }
        return $dates_month;
    }

    public function dayRange($first, $last, $step = '+1 day') {
        $dates_month = array();
        $current = strtotime($first);
        $last = strtotime($last);

        while( $current <= $last ) {
            $mktime = mktime(0, 0, 0, date('m', $current), date('d', $current), date('Y', $current));
            $date = date("Y-m-d", $mktime);
            $dates_month[date('Y-m-d', $current)] = ['dateStr' => $mktime, 'date' => $date, 'dayName' => date('D', $mktime), 'task' => [], 'event' => [], 'totalCount' => 0];
            $current = strtotime($step, $current);
        }
        return $dates_month;
    }

    public function getDayMonthArray($request) {
        $dates_month = array();
        if (empty($request->type)) {
            $now = Carbon::now();
            $request->type = 'month';
            $request->month = $now->month;
            $request->year = $now->year;
        }

        if ($request->type == 'month') {
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            Carbon::setWeekEndsAt(Carbon::SATURDAY);
            $num = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
            $startDate    = Carbon::createFromDate($request->year, $request->month, 1);
            $endDate    = Carbon::createFromDate($request->year, $request->month, $num);
            $startWeekDay = $startDate->startOfWeek()->toDateString();
            $endWeekDay = $endDate->endOfWeek()->toDateString();

            $weekRangeArray = $this->dayRange($startWeekDay, $endWeekDay);
            return $weekRangeArray;

/*            for ($i = 1; $i <= $num; $i++) {
                $mktime = mktime(0, 0, 0, $request->month, $i, $request->year);
                $date = date("Y-m-d", $mktime);
                $dates_month[$i] = ['date' => $date, 'dayName' => date('D', $mktime), 'task' => [], 'event' => []];
            }*/
        } else if ($request->type == 'week') {
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            $startDate    = Carbon::createFromDate($request->year, $request->month, $request->day);
            $startWeekDay = $startDate->startOfWeek()->toDateString();
            $endWeekDay   = $startDate->addDays(6)->toDateString();
            $weekRangeArray = $this->weekRange($startWeekDay, $endWeekDay);
            return $weekRangeArray;

        } else if ($request->type == 'day') {
            $mktime = mktime(0, 0, 0, $request->month, $request->day, $request->year);
            $date = date('Y-m-d', $mktime);
            $dates_month[$date] = [
                'dateStr' => $mktime, 'date' => $date, 'dayName' => date('D', $mktime)
            ];
            $dates_month[$date]['overview'] = array('task' => array(), 'event' => array(), 'totalCount' => 0);
            $dates_month[$date]['timing'] = $this->genTimeSlots();

        }

        return $dates_month;
    }

    public function genTimeSlots()
    {
        $timeSlotArr = [];
        $range = range(0, 23);
        foreach ($range as $timeSlot) {
            $timeSlotArr[] = array(
                'timeSlot' => $timeSlot,
                'taskAndEvents' => ['task' => [], 'event' => []]
            );
        }

        return $timeSlotArr;
    }
}