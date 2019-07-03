<?php
/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 20/14/18
 * Time: 04:10 PM
 */

namespace Modules\HrmManagement\Repositories\Time;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\ResponseStatus;
use Modules\Common\Utilities\Utilities;
use Modules\HrmManagement\Entities\HrmClock;
use Modules\HrmManagement\Entities\HrmClockEditHistory;
use Modules\HrmManagement\Entities\HrmClockMaster;
use Modules\HrmManagement\Entities\HrmClockoutReason;
use Modules\HrmManagement\Entities\HrmClockoutStatus;
use Modules\HrmManagement\Entities\HrmClockStatus;
use Modules\HrmManagement\Entities\HrmShiftTimings;
use Modules\HrmManagement\Entities\HrmWorkReportSettings;
use Modules\HrmManagement\Repositories\CommonRepositoryInterface;
use Modules\HrmManagement\Repositories\TimeInterface;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;

class TimeRepository implements TimeInterface
{

    protected $content;
    protected $statusArray;
    protected $commonRepository;

    public function __construct(CommonRepositoryInterface $commonRepository)
    {
        $this->content = array();
        $this->statusArray = array();
        $this->commonRepository = $commonRepository;
    }

    /**
     * ClockIn ClockOut Pause, Continue
     * @param Request $request
     * @return array
     */
    public function logClockStatus(Request $request)
    {
        $user  = Auth::user();
        $org   = $this->getOrganization($request->orgSlug);
        $data  = [];

        try {
            DB::beginTransaction();
            $this->statusArray = $this->getClockStatusArray();

            //clock master
            $clockMaster = $this->checkUserLogInClockMaster($user, $org, $request)
                ->addSelect(HrmClockMaster::start_date, HrmClockMaster::total_break_time,
                    HrmClockMaster::total_working_time, HrmClockMaster::stop_date)
                ->first();

            $clock = NULL;
            $previousClockOut = false;
            if ($clockMaster) {
                $clock    = $this->checkUserLogInClock($user, $org, $clockMaster)->first();
                $previousClockOut = $clockMaster->is_previous_clock_out;
            }

            //previous day not clockout response
            if (($previousClockOut) && (!in_array($request->status, [HrmClockStatus::clockin, HrmClockStatus::clockout]))
                && is_null($clockMaster->{HrmClockMaster::stop_date})) {
                $data = [
                    'startTime' => Carbon::parse($clockMaster->{HrmClockMaster::start_date})->timestamp,
                    'clockStatusButtons' => [],
                    'currentStatusName'  => NULL,
                    'currentStatusId'    => NULL,
                    "totalWorkingTime"   => NULL,
                    "totalBreakTime"     => NULL,
                    'lastRecordedTime' => NULL,
                    'isPreviousDayClockout' => false
                ];
                return $this->content = array(
                    'data'   => $data,
                    'code'   => Response::HTTP_OK,
                    'status' => ResponseStatus::OK
                );
            }

            if (in_array($request->status, [HrmClockStatus::clockin, HrmClockStatus::clockout])) {
                $clockMaster = ($clockMaster) ? $this->clockOut($clockMaster, $request, $clock) : $this->clockIn($user, $org, $request);
                $clockMaster->save();
            }

            if (!$clockMaster) {
                throw new \Exception("Please Clock In First!", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $statusId = $this->statusArray[$request->status];

            if ($clock && ($clock->{HrmClock::clock_status_id} == $statusId)) {
                throw new \Exception("Same Status Not Allowed", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            //saving breaktime
            if ($request->status == HrmClockStatus::clockContinue) {
                $breakSeconds      = $request->currentTime - strtotime($clock->{HrmClock::clock_datetime});
                $previousBreakTime = $clockMaster->{HrmClockMaster::total_break_time};
                $totalBreakTime    = date("H:i:s",strtotime($previousBreakTime)+$breakSeconds);
                $clockMaster->{HrmClockMaster::total_break_time} = $totalBreakTime;
                $clockMaster->{HrmClockMaster::stop_date} = NULL;

                if ($request->lastRecordedTime)
                    $clockMaster->{HrmClockMaster::last_recorded_time} = $request->lastRecordedTime;

                $clockMaster->save();
            }

            //saving breaktime
            if ($request->status == HrmClockStatus::pause) {
                $workingTimeSecs      = $request->currentTime - strtotime($clock->{HrmClock::clock_datetime});

                $previousWorkedTime = $clockMaster->{HrmClockMaster::total_working_time};
                $totalWorkedTime    = date("H:i:s",strtotime($previousWorkedTime)+$workingTimeSecs);

                $clockMaster->{HrmClockMaster::total_working_time} = $totalWorkedTime;
                $clockMaster->{HrmClockMaster::stop_date} = NULL;

                if ($request->lastRecordedTime)
                    $clockMaster->{HrmClockMaster::last_recorded_time} = $request->lastRecordedTime;

                $clockMaster->save();
            }

            //is logout action happened in working time
            $isWorkingTime = false;
            $reportPrompt  = false;
            if ($request->status == HrmClockStatus::clockout) {
                $startDate     = $clockMaster->{HrmClockMaster::start_date};
                $isWorkingTime = $this->isCurrentTimeWorking($request->currentTime, $startDate);

                //is user has set any work report frequency
                //$reportPrompt  = $this->isWorkReportPrompt($org, $user);
                $reportPrompt  = false;
            }

            $clock = new HrmClock;
            $clock->{HrmClock::slug}           = Utilities::getUniqueId();
            $clock->{HrmClock::org_id}         = $org->id;
            $clock->{HrmClock::user_id}        = $user->id;
            $clock->{HrmClock::clock_datetime}  = $request->currentTime;
            $clock->{HrmClock::clock_master_id} = $clockMaster->id;
            $clock->{HrmClock::clock_status_id} = ($isWorkingTime) ? $this->statusArray[HrmClockStatus::earlyClockout] : $statusId;

            $clock->save();
            $clockStatus = $clock->{HrmClock::clock_status_id};

            // report prompt
            $reportPrompt    = false;
            $request->action = 'reportPrompt';
            $workReportDates = $this->commonRepository->getWorkReportDates($request, $user, $org);
            $endDate = Utilities::createDateTimeFromUtc($workReportDates['endDate']);
            if (Carbon::today()->greaterThan($endDate)) {
                $reportPrompt = true;
            }


            $data = [
                'startTime' => Carbon::parse($clockMaster->{HrmClockMaster::start_date})->timestamp,
                'clockStatusButtons' => $this->getClockStatusButtons($clock),
                'currentStatusName'  => array_flip($this->statusArray)[$clockStatus],
                'currentStatusId'    => $clockStatus,
                //"totalWorkingTime"   => $this->totalWorkingTimeTimestamp($clockMaster->{HrmClockMaster::start_date}, $clockMaster->{HrmClockMaster::total_working_time}),
                "totalWorkingTime"   => $clockMaster->{HrmClockMaster::total_working_time},
                "totalBreakTime"     => $clockMaster->{HrmClockMaster::total_break_time},
                "lastRecordedTime"     => $clockMaster->{HrmClockMaster::last_recorded_time},
                'isPreviousDayClockout' => true,
                "isWorkReportPrompt" => $reportPrompt
            ];

            DB::commit();

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

        return $this->content = array(
            'data'   => $data,
            'code'   => Response::HTTP_CREATED,
            'status' => ResponseStatus::OK
        );

    }

    public function isWorkReportPrompt($org, $loggedUser)
    {
        $today = now()->toDateString();
        $reportSettings = DB::table(HrmWorkReportSettings::table)->where(HrmWorkReportSettings::org_id, $org->id)
            ->where(HrmWorkReportSettings::user_id, $loggedUser->id)
            ->whereRaw('"'.$today.'" between '.HrmWorkReportSettings::start_date.' and '.HrmWorkReportSettings::end_date)
            ->exists();
        return $reportSettings;
        //dd($reportSettings);

                /*DB::enableQueryLog();
        $reportSettings->get();
        dd(DB::getQueryLog());*/
    }

    public function totalWorkingTimeTimestamp($startTime, $workTimeStr)
    {
        if (!$workTimeStr) {
            return null;
        }
        $carbonStartTime = Carbon::parse($workTimeStr);
        $carbonStartTime = Carbon::parse($startTime)
            ->addHour($carbonStartTime->hour)
            ->addMinute($carbonStartTime->minute)
            ->addSecond($carbonStartTime->second)->timestamp;
        return $carbonStartTime;
    }

    /**
     * Clockout
     * @param $clockMaster
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    public function clockOut($clockMaster, $request, $clock)
    {
        if ($request->status == HrmClockStatus::clockin) {
            throw new \Exception("Clockin is allowed only after previous session clockout", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $breakTime = $clockMaster->{HrmClockMaster::total_break_time};
        $startDate = $clockMaster->{HrmClockMaster::start_date};
        //$breakTime = $this->getBreakTimes($clockMaster->id);
        $clockMaster->{HrmClockMaster::stop_date}   = $request->currentTime;
        //$clockMaster->{HrmClockMaster::is_clockout} = true;

        if ($this->statusArray[HrmClockStatus::pause] == $clock->{HrmClock::clock_status_id}) {
            $breakSeconds      = $request->currentTime - strtotime($clock->{HrmClock::clock_datetime});
            $previousBreakTime = $clockMaster->{HrmClockMaster::total_break_time};
            $breakTime    = date("H:i:s",strtotime($previousBreakTime)+$breakSeconds);
            $clockMaster->{HrmClockMaster::total_break_time} = $breakTime;
        }

        if ($request->lastRecordedTime)
            $clockMaster->{HrmClockMaster::last_recorded_time} = $request->lastRecordedTime;

        $breakTime     = strtotime($breakTime) - strtotime('00:00:00');
        $totalDuration = $this->calculateTotalWorkingTime($startDate, $request->currentTime, $breakTime);
        $clockMaster->{HrmClockMaster::total_working_time} = date('H:i:s' ,$totalDuration);

        if ($request->note) {
            $clockMaster->{HrmClockMaster::note} = $request->note;
        }

        return $clockMaster;
    }

    public function calculateTotalWorkingTime($startTime, $currentTime, $breakTime)
    {
        $startTime     = $startTime;
        $currentTime   =  Utilities::createDateTimeFromUtc($currentTime);
        //echo 'startTime: '.$startTime. 'CurrentTime: '.$currentTime;die;
        $totalDuration = $currentTime->diffInSeconds($startTime) - $breakTime;
        return $totalDuration;
    }

    public function isCurrentTimeWorking($currentTime, $startDate)
    {
        $timing = HrmShiftTimings::select(HrmShiftTimings::start_time, HrmShiftTimings::end_time)
            ->first();
        $todayStartTime = Carbon::parse('now')->setTimeFromTimeString($timing->{HrmShiftTimings::start_time})->timestamp;

        $todayEndTime   = Carbon::parse($startDate)->addDay(1)->setTimeFromTimeString($timing->{HrmShiftTimings::start_time})->timestamp;
        //$todayEndTime   = Carbon::parse('now')->setTimeFromTimeString($timing->{HrmShiftTimings::end_time})->timestamp;
        $now      = $currentTime;

        //echo 'startTime: '. $todayStartTime .' currentTime: '.$now. ' endTime: '.$todayEndTime;die;
        return (($now >= $todayStartTime) && ($now <= $todayEndTime)) ? true : false;
    }

    /**
     * Evaluate breakTime
     * @param $clockMasterId
     * @return false|string
     */
    public function getBreakTimes($clockMasterId)
    {
        $statusArr = $this->statusArray;
        DB::enableQueryLog();
        $hrmClockBreakTimes  = DB::table(HrmClock::table)->select(HrmClock::clock_datetime)
            ->whereIn(HrmClock::clock_status_id, [$statusArr[HrmClockStatus::pause], $statusArr[HrmClockStatus::clockContinue]])
            ->where(HrmClock::clock_master_id, $clockMasterId)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
        dd(DB::getQueryLog());

        $timeStr = 0;
        foreach($hrmClockBreakTimes as $hrmClockBreakTime) {
            $timeStr = strtotime($hrmClockBreakTime->{HrmClock::clock_datetime});

            /*if ($timeStr == 0)
                $timeStr = strtotime($hrmClockBreakTime->{HrmClock::clock_datetime});
            else*/
                $timeStr-= strtotime($hrmClockBreakTime->{HrmClock::clock_datetime});
            //echo $timeStr.'++++++++++';
        }
die;
        //$breakTime = date('H:i:s' ,$timeStr);

        return $timeStr;
    }

    /**
     * clock In
     * @param $user
     * @param $org
     * @param $request
     * @return HrmClockMaster
     * @throws \Exception
     */
    public function clockIn($user, $org, $request)
    {
        if ($request->status == HrmClockStatus::clockout) {
            throw new \Exception("Please Clock In First!", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $clockMaster = new HrmClockMaster;
        $clockMaster->{HrmClockMaster::slug}    = Utilities::getUniqueId();
        $clockMaster->{HrmClockMaster::org_id}  = $org->id;
        $clockMaster->{HrmClockMaster::user_id} = $user->id;
        $clockMaster->{HrmClockMaster::start_date} = $request->currentTime;
        if ($request->lastRecordedTime)
            $clockMaster->{HrmClockMaster::last_recorded_time} = $request->lastRecordedTime;
        return $clockMaster;
    }


    public function checkUserLogInClock($user, $org, $master)
    {

        //DB::enableQueryLog();
          return DB::table(HrmClock::table)
            ->select(HrmClock::table. '.id', HrmClock::table. '.' .HrmClock::clock_status_id,
                HrmClock::table. '.' .HrmClock::clock_datetime)
            ->where(HrmClock::table. '.' .HrmClock::user_id, $user->id)
            ->where(HrmClock::table. '.' .HrmClock::org_id, $org->id)
            ->where(HrmClock::table. '.' .HrmClock::clock_master_id, $master->id)
            ->latest();
        //dd(DB::getQueryLog());
    }



    /**
     * @param $user
     * @param $org
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function checkUserLogInClockMaster($user, $org, $request)
    {

        $timing = HrmShiftTimings::select(HrmShiftTimings::start_time)->first();
        $shiftStartTime = $timing->{HrmShiftTimings::start_time};
        $currentTimestamp    = Utilities::createDateTimeFromUtc($request->currentTime);

        $raw = sprintf("IF ('%s' < CONCAT(DATE(%s)+INTERVAL 1 DAY, ' %s'), false, true) as is_previous_clock_out",
            $currentTimestamp, HrmClockMaster::start_date, $shiftStartTime);

            //DB::enableQueryLog();
             $clockMaster =  HrmClockMaster::select('id',DB::raw($raw))
                ->where(HrmClockMaster::user_id, $user->id)
                ->where(HrmClockMaster::org_id, $org->id)
                ->where(function ($query) use ($currentTimestamp, $shiftStartTime) {
                    $raw = sprintf("'%s' between %s and CONCAT(DATE(%s)+INTERVAL 1 DAY,' %s')",
                        $currentTimestamp, HrmClockMaster::start_date, HrmClockMaster::start_date, $shiftStartTime);
                    $query->whereRaw($raw);
                    $query->orWhere(function ($query) {
                        $query->orWhereNull(HrmClockMaster::start_date)
                            ->orWhereNull(HrmClockMaster::stop_date);
                    });
                });
             //dd($clockMaster->get());
        //$clockMaster->get();
        //dd(DB::getQueryLog());
             return $clockMaster;

        //dd(DB::getQueryLog());
    }

    /**
     * fetch work day
     * @param Request $request
     * @return array
     */
    public function fetchWorkDay(Request $request)
    {
        $selectedDate = Utilities::createDateTimeFromUtc($request->selectDate);
        $loggedUser   = Auth::user();

        try {
            //DB::enableQueryLog();
            $clockMaster  = DB::table(HrmClockMaster::table)
                ->select(HrmClockMaster::table. '.' .HrmClockMaster::slug. ' as masterSlug',
                    DB::raw("unix_timestamp(".HrmClockMaster::table. '.' .HrmClockMaster::start_date.") AS startTime"),
                    DB::raw("unix_timestamp(".HrmClockMaster::table. '.' .HrmClockMaster::stop_date.") AS endTime"),
                    HrmClockMaster::table. '.' .HrmClockMaster::total_break_time
                )
                ->join(Organization::table, HrmClockMaster::table. '.' .HrmClockMaster::org_id, '=', Organization::table. '.id')
                ->where(HrmClockMaster::table. '.' .HrmClockMaster::user_id, $loggedUser->id)
                ->where(Organization::table. '.' .Organization::slug, $request->orgSlug)
                ->whereDate(HrmClockMaster::table. '.' .HrmClockMaster::start_date, $selectedDate->toDateString())->first();
            //dd(DB::getQueryLog());
            $response = array();
            if ($clockMaster) {
                $totalBreakTime = $clockMaster->{HrmClockMaster::total_break_time};
                $clockMaster->break = array(
                    'hour' => date('H' ,strtotime($totalBreakTime)),
                    'minute' => date('i' ,strtotime($totalBreakTime))
                );
                unset($clockMaster->{HrmClockMaster::total_break_time});
                $response = $clockMaster;
            }

            if (empty($response)) {
                $response = new \stdClass();
            }

            return $this->content = array(
                'data'   => $response,
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );
        } catch (ModelNotFoundException $e) {
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        } catch (\Exception $e) {
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

    }

    /**
     * update work time and clockout
     * @param Request $request
     * @return array
     */
    public function saveWorkDay(Request $request)
    {
        $user  = Auth::user();
        $org   = $this->getOrganization($request->orgSlug);
        $currentDate = Utilities::createDateTimeFromUtc($request->workDate);
        //DB::enableQueryLog();


        try {
            if ($currentDate->isToday()) {
                throw new \Exception("Not able to edit today", Response::HTTP_UNPROCESSABLE_ENTITY);
            } else if ($currentDate->isFuture()) {
                throw new \Exception("Not able to edit for future dates", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            DB::beginTransaction();
            $clockMaster =  HrmClockMaster::select('id', HrmClockMaster::start_date,
                HrmClockMaster::stop_date, HrmClockMaster::total_break_time,
                HrmClockMaster::total_working_time
                )
                ->where(HrmClockMaster::user_id, $user->id)
                ->where(HrmClockMaster::org_id, $org->id)
                ->whereDate(HrmClockMaster::start_date, $currentDate)
                ->first();


            $prevStartDate     = ($clockMaster)? $clockMaster->{HrmClockMaster::start_date}: NULL;
            $prevEndDate       = ($clockMaster)? $clockMaster->{HrmClockMaster::stop_date} : NULL;
            $prevBreakTime     = ($clockMaster)? $clockMaster->{HrmClockMaster::total_break_time} : NULL;
            $prevWorkingTime   = ($clockMaster)? $clockMaster->{HrmClockMaster::total_working_time} : NULL;

            $breakH = ($request->break['hour']) ? $request->break['hour']: "00";
            $breakM = ($request->break['minute']) ? $request->break['minute']: "00";
            $breakS = "00";
            $breakTimeStr = strtotime($breakH. ':' .$breakM. ":" .$breakS);

            $breakTimeSec = $breakTimeStr - strtotime('00:00:00');
            $startTime = Utilities::createDateTimeFromUtc($request->startTime);
            $totalDuration = $this->calculateTotalWorkingTime($startTime, $request->endTime, $breakTimeSec);

            if ($totalDuration < 0) {
                throw new \Exception("break time cannot be larger than total working time", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if (!$clockMaster) {
                $clockMaster = new HrmClockMaster;
                $clockMaster->{HrmClockMaster::slug} = Utilities::getUniqueId();
                $clockMaster->{HrmClockMaster::org_id} = $org->id;
                $clockMaster->{HrmClockMaster::user_id} = $user->id;

                $currentTime   =  Utilities::createDateTimeFromUtc($request->startTime);


                $clockMaster->{HrmClockMaster::total_working_time} = date('H:i:s' ,$totalDuration);
                $clockMaster->{HrmClockMaster::last_recorded_time} = $totalDuration;
            }

            if ($request->note) {
                $clockMaster->{HrmClockMaster::note}       = $request->note;
            }

            $clockMaster->{HrmClockMaster::start_date}  = $request->startTime;
            $clockMaster->{HrmClockMaster::stop_date}   = $request->endTime;
            $clockMaster->{HrmClockMaster::total_break_time} = date('H:i:s' ,$breakTimeStr);

            $clockMaster->save();


            $clockHistory =  new HrmClockEditHistory;
            $clockHistory->{HrmClockEditHistory::slug}   = Utilities::getUniqueId();
            $clockHistory->{HrmClockEditHistory::org_id} = $org->id;
            $clockHistory->{HrmClockEditHistory::clock_master_id} = $clockMaster->id;
            $clockHistory->{HrmClockEditHistory::prev_start_date} = $prevStartDate;
            $clockHistory->{HrmClockEditHistory::prev_end_date}   = $prevEndDate;
            $clockHistory->{HrmClockEditHistory::prev_break_time} = $prevBreakTime;
            $clockHistory->{HrmClockEditHistory::start_date} = $request->startTime;
            $clockHistory->{HrmClockEditHistory::end_date}   = $request->endTime;
            $clockHistory->{HrmClockEditHistory::break_time} = date('H:i:s' ,$breakTimeStr);

            if ($request->note) {
                $clockHistory->{HrmClockEditHistory::note} = $request->note;
                $clockMaster->{HrmClockMaster::note}       = $request->note;
            }

            $clockHistory->save();

            DB::commit();


        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

        return $this->content = array(
            'data'   => array('message' => "Updated Work Time and clocked out!"),
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );

        //dd(DB::getQueryLog());
    }

    /**
     * get Current Clock Status
     * @param Request $request
     * @return array
     */
    public function currentClockStatus(Request $request)
    {
        $user  = Auth::user();
        $org   = $this->getOrganization($request->orgSlug);

        try {
            $clockMaster   = $this->checkUserLogInClockMaster($user, $org, $request)
                ->addSelect(HrmClockMaster::start_date, HrmClockMaster::total_working_time,
                    HrmClockMaster::last_recorded_time,
                    HrmClockMaster::total_break_time)->first();
            $startTime = NULL;
            $currentStatus = "";
            $previousDayClockOut = false;
            $clock  = null;

            if (!$clockMaster) {
                $clockStatusBtns = $this->clockStatusButtons();
                $previousDayClockOut = true;
            } else {
                $previousDayClockOut = !$clockMaster->is_previous_clock_out;

                $clock = $this->checkUserLogInClock($user, $org, $clockMaster)
                    ->addSelect(HrmClockStatus::name)
                    ->join(HrmClockStatus::table, HrmClockStatus::table. '.id', '=', HrmClock::table. '.' .HrmClock::clock_status_id)
                    ->first();

                $clockStatusBtns = $this->clockStatusButtons($clock);
                $currentStatus = ($clock)? $clock->{HrmClockStatus::name} : NULL;

                $startTime = Carbon::parse($clockMaster->{HrmClockMaster::start_date})->timestamp;


                /*if ((!in_array($currentStatus, [HrmClockStatus::clockin, HrmClockStatus::clockout]))
                    && is_null($clockMaster->{HrmClockMaster::stop_date})) {
                    $previousDayClockOut = true;
                }*/
            }

            $reportPrompt = false;
            $request->action = 'reportPrompt';
            $workReportDates = $this->commonRepository->workReportPrompt($request, $user, $org);
            if (!empty($workReportDates)) {
                $endDate = Carbon::parse($workReportDates->endDate);
                if (!Carbon::today()->isSameMonth($endDate)) {
                    $reportPrompt = true;
                }
            }

            $lastRecordedTime = ($clockMaster) ? $clockMaster->{HrmClockMaster::last_recorded_time} : NULL;


            // calculate elapsed time from clockin
            if (($clockMaster) && in_array($currentStatus, [HrmClockStatus::clockin, HrmClockStatus::clockContinue])) {
                if (empty($lastRecordedTime)) {
                    $lastRecordedTime = Carbon::now()->diffInRealSeconds(Carbon::parse($clockMaster->{HrmClockMaster::start_date}));
                } else {
                    $lastRecordedTime = Carbon::now()->diffInRealSeconds(Carbon::parse($clock->{HrmClock::clock_datetime})) + $lastRecordedTime;
                }
            }



            return $this->content = array(
                'data'   => array(
                    'startTime' => $startTime,
                    'clockStatusButtons' => $clockStatusBtns,
                    'currentStatusName' => $currentStatus,
                    "totalWorkingTime"   => ($clockMaster) ? $clockMaster->{HrmClockMaster::total_working_time} : NULL,
                    "totalBreakTime"     => ($clockMaster) ? $clockMaster->{HrmClockMaster::total_break_time} : NULL,
                    "lastRecordedTime" => $lastRecordedTime,
                    'isPreviousDayClockout' => $previousDayClockOut,
                    "isWorkReportPrompt" => $reportPrompt
                ),
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );

        } catch (\Exception $e) {
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

    }

    public function getClockStatusButtons($clock = null)
    {
        //dd($this->statusArray);
        $buttonsArr = [
            HrmClockStatus::clockin       => false, HrmClockStatus::pause    => false,
            HrmClockStatus::clockContinue => false, HrmClockStatus::clockResume => false,
            HrmClockStatus::clockout => false,
            'editWorkingTime' => false
        ];

        if ($clock) {
            $clockStatusId = $clock->{HrmClock::clock_status_id};

            switch ($clockStatusId) {
                case $this->statusArray[HrmClockStatus::clockin] :
                    $buttonsArr[HrmClockStatus::pause]    = true;
                    $buttonsArr[HrmClockStatus::clockout] = true;
                    $buttonsArr['editWorkingTime'] = true;
                    break;

                case $this->statusArray[HrmClockStatus::pause] :
                    $buttonsArr[HrmClockStatus::clockContinue]    = true;
                    $buttonsArr[HrmClockStatus::clockout] = true;
                    $buttonsArr['editWorkingTime'] = true;
                    break;

                case $this->statusArray[HrmClockStatus::clockContinue] :
                    $buttonsArr[HrmClockStatus::pause]    = true;
                    $buttonsArr[HrmClockStatus::clockout] = true;
                    $buttonsArr['editWorkingTime'] = true;
                    break;

                case $this->statusArray[HrmClockStatus::earlyClockout] :
                    $buttonsArr[HrmClockStatus::clockResume] = true;
                    $buttonsArr['editWorkingTime'] = true;
                    break;

                case $this->statusArray[HrmClockStatus::clockout] :
                    $buttonsArr[HrmClockStatus::clockin] = true;
                    break;
                default:
                    $buttonsArr[HrmClockStatus::clockin] = true;
            }

        } else {
            $buttonsArr[HrmClockStatus::clockin] = true;
        }

        return $buttonsArr;
    }

    /** This function may be deleted in future
     * @param null $clock
     * @return array
     */
    public function clockStatusButtons($clock = null)
    {
        $buttonsArr = [
            HrmClockStatus::clockin       => false, HrmClockStatus::pause    => false,
            HrmClockStatus::clockContinue => false, HrmClockStatus::clockResume => false,
            HrmClockStatus::clockout => false,
            'editWorkingTime' => false
        ];

        if ($clock) {
            $clockStatusName = $clock->{HrmClockStatus::name};

            switch ($clockStatusName) {
                case HrmClockStatus::clockin :
                    $buttonsArr[HrmClockStatus::pause]    = true;
                    $buttonsArr[HrmClockStatus::clockout] = true;
                    $buttonsArr['editWorkingTime'] = true;
                    break;

                case HrmClockStatus::pause :
                    $buttonsArr[HrmClockStatus::clockContinue]    = true;
                    $buttonsArr[HrmClockStatus::clockout] = true;
                    $buttonsArr['editWorkingTime'] = true;
                    break;

                case HrmClockStatus::clockContinue :
                    $buttonsArr[HrmClockStatus::pause]    = true;
                    $buttonsArr[HrmClockStatus::clockout] = true;
                    $buttonsArr['editWorkingTime'] = true;
                    break;

                case HrmClockStatus::earlyClockout :
                    $buttonsArr[HrmClockStatus::clockResume] = true;
                    $buttonsArr['editWorkingTime'] = true;
                    break;

                case HrmClockStatus::clockout :
                    $buttonsArr[HrmClockStatus::clockin] = true;
                    break;
                default:
                    $buttonsArr[HrmClockStatus::clockin] = true;
            }

        } else {
            $buttonsArr[HrmClockStatus::clockin] = true;
        }

        return $buttonsArr;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function clockOutPreviousDay(Request $request)
    {
        $user  = Auth::user();
        $org   = $this->getOrganization($request->orgSlug);

        try {
            $this->statusArray = $this->getClockStatusArray();
            $clockMaster       = $this->checkUserLogInClockMaster($user, $org, $request)
                ->addSelect(HrmClockMaster::start_date)
                ->firstOrFail();

            $clock    = $this->checkUserLogInClock($user, $org, $clockMaster)->first();

            $startDate = Carbon::parse($clockMaster->{HrmClockMaster::start_date});

            if ($startDate->isToday()) {
                throw new \Exception("Cannot ClockOut today", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $clockOut   = $this->clockOut($clockMaster, $request, $clock);
            $clockOut->{HrmClockMaster::stop_date} = $request->clockOutTime;

            DB::beginTransaction();

            $clockOut->save();

            $clock = new HrmClock;
            $clock->{HrmClock::slug}           = Utilities::getUniqueId();
            $clock->{HrmClock::org_id}         = $org->id;
            $clock->{HrmClock::user_id}        = $user->id;
            $clock->{HrmClock::clock_datetime}  = $request->clockOutTime;
            $clock->{HrmClock::clock_master_id} = $clockMaster->id;
            $clock->{HrmClock::clock_status_id} = $this->statusArray[HrmClockStatus::clockout];
            $clock->save();

            $clockStatus = $clock->{HrmClock::clock_status_id};
            $data = [
                'startTime' => Carbon::parse($clockMaster->{HrmClockMaster::start_date})->timestamp,
                'clockStatusButtons' => $this->getClockStatusButtons($clock),
                'currentStatusName'  => array_flip($this->statusArray)[$clockStatus],
                'currentStatusId'    => $clockStatus,
                'isPreviousDayClockout' => true
            ];

            DB::commit();

            return $this->content = array(
                'data'   => $data,
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );



        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

    }

    /**
     * @param $slug
     * @return mixed
     */
    public function getOrganization($slug)
    {
        return Organization::select('id')->where(Organization::slug, $slug)->firstOrFail();
    }

    public function getClockStatus($status)
    {
        return HrmClockStatus::select('id')->where(HrmClockStatus::name, $status)->firstOrFail();
    }

    /**
     * get status and return as array with status title as key
     * @return array
     */
    public function getClockStatusArray()
    {
        $clockStatusArr = collect();
        $clockStatuses = HrmClockStatus::select(HrmClockStatus::table. '.id', HrmClockStatus::table. '.' .HrmClockStatus::name)
            ->get();

        $clockStatuses->map(function ($status)  use ($clockStatusArr) {
            $clockStatusArr[$status->{HrmClockStatus::name}] = $status->id;
        });

        return $clockStatusArr->toArray();
    }

}