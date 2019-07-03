<?php
/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 04/09/18
 * Time: 04:21 PM
 */

namespace Modules\HrmManagement\Repositories\Report;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\ResponseStatus;
use Modules\Common\Utilities\Utilities;
use Modules\HrmManagement\Common\ReportCommon;
use Modules\HrmManagement\Entities\HrmAbsence;
use Modules\HrmManagement\Entities\HrmClockEditHistory;
use Modules\HrmManagement\Entities\HrmClockMaster;
use Modules\HrmManagement\Entities\HrmDailyReport;
use Modules\HrmManagement\Entities\HrmLeaveType;
use Modules\HrmManagement\Entities\HrmWorkReport;
use Modules\HrmManagement\Entities\HrmWorkReportEvent;
use Modules\HrmManagement\Entities\HrmWorkReportFrequency;
use Modules\HrmManagement\Entities\HrmWorkReportScore;
use Modules\HrmManagement\Entities\HrmWorkReportSettings;
use Modules\HrmManagement\Entities\HrmWorkReportTask;
use Modules\HrmManagement\Repositories\CommonRepositoryInterface;
use Modules\HrmManagement\Repositories\ReportInterface;
use Modules\HrmManagement\Transformers\DailyReportResource;
use Modules\HrmManagement\Transformers\OneMonthWorReportResource;
use Modules\HrmManagement\Transformers\TimeReportResource;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgDepartment;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\OrgManagement\Entities\OrgEmployeeDepartment;
use Modules\SocialModule\Entities\SocialEvent;
use Modules\SocialModule\Entities\SocialEventMember;
use Modules\TaskManagement\Entities\Task;
use Modules\TaskManagement\Entities\TaskParticipants;
use Modules\TaskManagement\Entities\TaskScore;
use Modules\TaskManagement\Entities\TaskStatus;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;

class ReportRepository implements ReportInterface
{

    protected $content;
    protected $common;
    protected $s3BasePath;

    public function __construct(CommonRepositoryInterface $common)
    {
        $this->content = array();
        $this->common  = $common;
        $this->s3BasePath = env('S3_PATH');
    }

    /**
     * get WorkTime Report
     * @param Request $request
     * @return array
     */
    public function getWorkTimeReports(Request $request)
    {
        $user  = Auth::user();
        $org   = $this->getOrganization($request->orgSlug);

        try {
            $departmentSlug = null;
            $confirmedKey   = null;
            $unConfirmedKey = null;
            $isFilter = false;
            if ($request->has('filterBy') && $request->filterBy) {
                $departmentSlug = $request->filterBy['departmentSlug'];
                $confirmedKey   = $request->filterBy['confirmed'];
                $unConfirmedKey = $request->filterBy['unConfirmed'];
                $isFilter = true;
            }
            //DB::raw('concat("'.$this->s3BasePath.'",creatorImage.'. UserProfile::image_path .') as creatorImage'),
            //list all departments under org
            $departments = OrgDepartment::select(
                OrgDepartment::table. '.' . OrgDepartment::slug,
                OrgDepartment::table. '.' .OrgDepartment::name. ' as departmentName')
                ->where(OrgDepartment::table. '.' .OrgDepartment::org_id, $org->id)
                ->join(OrgEmployeeDepartment::table, OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::org_department_id,
                    '=', OrgDepartment::table. '.id')
                ->join(OrgEmployee::table, OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::org_employee_id,
                    '=', OrgEmployee::table. '.id')
                ->groupBy(OrgDepartment::table. '.' . OrgDepartment::slug);

            if ($departmentSlug) {
                $departments->where(OrgDepartment::table. '.' .OrgDepartment::slug, $departmentSlug);
            }

            $departmentCount = $departments->count();
            $departments = $departments->skip(Utilities::getParams()['offset'])
                ->take(Utilities::getParams()['perPage'])
                ->get();

            $monthYear = $request->monthYear;
            $month     = $monthYear['month'];
            $year      = $monthYear['year'];

            $users = $this->getDepartmentsUsersQuery($org)
                ->select(
                    User::table. '.' . User::name,
                    User::table. '.' . User::slug. ' as userSlug',
                    DB::raw('concat("'.$this->s3BasePath.'",'. UserProfile::table. '.' . UserProfile::image_path .') as userImage'),
                    OrgDepartment::table. '.' .OrgDepartment::slug.' as orgDepartmentSlug',
                    OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::is_head.' as isHeadOfDepartment');

            $departmentUsers = $users->get();
            $userSlugArr     = $departmentUsers->pluck('userSlug');


            $wokedTimeUsersQuery = DB::table(HrmClockMaster::table)
                ->join(User::table, User::table. '.id', '=', HrmClockMaster::table. '.' .HrmClockMaster::user_id)
                ->leftjoin(HrmDailyReport::table, HrmDailyReport::table. '.' .HrmDailyReport::clock_master_id,
                    '=', HrmClockMaster::table. '.id')
                ->select(
                    User::table. '.' . User::slug. ' as userSlug',
                    //DB::raw("unix_timestamp(". HrmClockMaster::table. '.' .HrmClockMaster::total_working_time.") as total_working_time"),
                    DB::raw("unix_timestamp(SEC_TO_TIME(". HrmClockMaster::table. '.' .HrmClockMaster::last_recorded_time.")) as total_working_time"),
                    HrmClockMaster::table. '.' .HrmClockMaster::slug,
                    DB::raw("CASE WHEN ". HrmDailyReport::table. ".id IS NULL THEN 0 ELSE 1 END as isConfirmed"),
                    DB::raw("unix_timestamp(". HrmClockMaster::table. '.' .HrmClockMaster::start_date .") as start_date")
                    //DB::raw("DATE_FORMAT(". HrmClockMaster::table. '.' .HrmClockMaster::start_date .", '%d') as start_day")
                )->where(function ($query) {
                    $query->whereNotNull(HrmClockMaster::table. '.' .HrmClockMaster::start_date)
                        ->whereNotNull(HrmClockMaster::table. '.' .HrmClockMaster::stop_date);
                })
                ->whereIn(User::table. '.' .User::slug, $userSlugArr)
                ->whereMonth(HrmClockMaster::table. '.' .HrmClockMaster::start_date, $month)
                ->whereYear(HrmClockMaster::table. '.' .HrmClockMaster::start_date, $year);

            if ($isFilter) {
                if (!$unConfirmedKey && !$confirmedKey) {
                    $wokedTimeUsersQuery->whereNull(HrmDailyReport::table. '.id')
                        ->whereNotNull(HrmDailyReport::table. '.id');
                } else if ($unConfirmedKey && !$confirmedKey) {
                    $wokedTimeUsersQuery->whereNull(HrmDailyReport::table. '.id');
                } else if (!$unConfirmedKey && $confirmedKey) {
                    $wokedTimeUsersQuery->whereNotNull(HrmDailyReport::table. '.id');
                }
            }

            $wokedTimeUsers = $wokedTimeUsersQuery->get()->groupBy('userSlug');

            $daysArr = $daysResetArr = $this->getDayArrays($month, $year);
            $departmentUsers = $departmentUsers->toArray();
            //dd($departmentUsers);

            $absentUserDetails = DB::table(HrmAbsence::table)
                ->select(
                    User::table. '.' .User::slug. ' as userSlug',
                    HrmAbsence::table. '.' .HrmAbsence::slug. ' as absenceSlug',
                    HrmAbsence::table. '.' .HrmAbsence::absent_start_date_time. ' as absentStartDateStr',
                    HrmAbsence::table. '.' .HrmAbsence::absent_end_date_time. ' as absentEndDateStr',
                    DB::raw("unix_timestamp(". HrmAbsence::table. '.' .HrmAbsence::absent_start_date_time .") as absentStartDate"),
                    DB::raw("unix_timestamp(". HrmAbsence::table. '.' .HrmAbsence::absent_end_date_time .") as absentStartDate"),
                    DB::raw("day(". HrmAbsence::table. '.' .HrmAbsence::absent_start_date_time .") as absentStartDay"),
                    DB::raw("day(". HrmAbsence::table. '.' .HrmAbsence::absent_end_date_time .") as absentEndDay"),
                    HrmAbsence::table. '.' .HrmAbsence::is_starts_on_halfday ." as absentStartDateHalfDay",
                    HrmAbsence::table. '.' .HrmAbsence::is_ends_on_halfday ." as absentEndsOnHalfDay",
                    HrmLeaveType::table. '.' .HrmLeaveType::name. ' as leaveTypeName',
                    HrmLeaveType::table. '.' .HrmLeaveType::color_code. ' as colorCode'
                )
                ->join(HrmLeaveType::table, HrmLeaveType::table. '.id', '=', HrmAbsence::table. '.'. HrmAbsence::leave_type_id)
                ->join(User::table, User::table. '.id', '=', HrmAbsence::table. '.'. HrmAbsence::user_id)
                ->where(HrmAbsence::table. '.'. HrmAbsence::org_id, $org->id)
                ->where(function ($query) use ($month, $year) {
                    $query->whereMonth(HrmAbsence::table. '.' .HrmAbsence::absent_start_date_time, $month)
                        ->whereYear(HrmAbsence::table. '.' .HrmAbsence::absent_start_date_time, $year);
                });
            $absentUserDetails = $absentUserDetails->get()->groupBy('userSlug')->toArray();


            foreach ($departmentUsers as &$user) {
                $user['totalWorkedHours']   = 0;
                $user['totalWorkedMinutes'] = 0;
                $user['totalWorkedDays']    = 0;
                $user['isHeadOfDepartment'] = (bool) $user['isHeadOfDepartment'];
                $user['workDayReport'] = $daysArr;

                if (isset($wokedTimeUsers[$user['userSlug']])) {
                    //dd($wokedTimeUsers);
                    $workedTimeSum = $wokedTimeUsers[$user['userSlug']]->sum('total_working_time');
                    $workedTimeSum = Carbon::createFromTimestamp($workedTimeSum);
                    $user['totalWorkedHours']   = $workedTimeSum->hour;
                    $user['totalWorkedMinutes'] = $workedTimeSum->minute;
                    $user['totalWorkedDays']    = count($wokedTimeUsers[$user['userSlug']]);
                    foreach ($wokedTimeUsers[$user['userSlug']] as $userWorkedDay) {
                        $startDate = date('j', ($userWorkedDay->start_date));
                        $workTime = Carbon::createFromTimestamp($userWorkedDay->total_working_time);
                        //dd($userWorkedDay);

                        $user['workDayReport'][$startDate]['hours'] = $workTime->hour;
                        $user['workDayReport'][$startDate]['minutes'] = $workTime->minute;
                        $user['workDayReport'][$startDate]['confirm'] = (bool) $userWorkedDay->isConfirmed;
                        $user['workDayReport'][$startDate]['reportSlug'] = $userWorkedDay->master_slug;
                    }
                }

                //absent users
                if (isset($absentUserDetails[$user['userSlug']])) {
                    foreach($absentUserDetails[$user['userSlug']] as $absentUserDetail) {
                        $user['workDayReport'][$absentUserDetail->absentStartDay]['absent'] = [
                            'absenceSlug' => $absentUserDetail->absenceSlug,
                            'absentStartDateStr' => $absentUserDetail->absentStartDateStr,
                            'absentEndDateStr' => $absentUserDetail->absentEndDateStr,
                            'absentStartDate' => $absentUserDetail->absentStartDate,
                            'absentStartDay' => $absentUserDetail->absentStartDay,
                            'absentEndDay' => $absentUserDetail->absentEndDay,
                            'absentStartDateHalfDay' => (bool) $absentUserDetail->absentStartDateHalfDay,
                            'absentEndsOnHalfDay' => (bool) $absentUserDetail->absentEndsOnHalfDay,
                            'leaveTypeName' => $absentUserDetail->leaveTypeName,
                            'colorCode' => $absentUserDetail->colorCode
                        ];
                    }
                }

                $user['workDayReport'] = array_values($user['workDayReport']);
            }

            //dd($departmentUsers);

            $departmentUserGroup = collect($departmentUsers)->groupBy('orgDepartmentSlug')->toArray();


            $departments->map(function ($department) use ($departmentUserGroup) {
                $department->users = isset($departmentUserGroup[$department->{OrgDepartment::slug}]) ? $departmentUserGroup[$department->{OrgDepartment::slug}] : [];
            });


            $response = Utilities::paginate($departments,
                Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $departmentCount
            );
            $response = $response->toArray();

            $response['workTime'] =  $response['data'];
            $response         =  Utilities::unsetResponseData($response);
            return $this->content = array(
                'data'   => $response,
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );

        } catch (ModelNotFoundException $e) {

            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        } catch (\Exception $e) {
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

        return $this->content = array(
            'data'   => $response,
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );
    }

    public function fetchSingleAbsentDetails(Request $request)
    {
        try {
            $hrmAbsence = DB::table(HrmAbsence::table)->where(HrmAbsence::table. '.' .HrmAbsence::slug, $request->absenceSlug);

            if ($hrmAbsence->doesntExist()) {
                throw new \Exception("Invalid absence slug", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $hrmAbsence = $hrmAbsence->join(HrmLeaveType::table, HrmLeaveType::table. '.id', '=', HrmAbsence::table. '.' .HrmAbsence::leave_type_id)
                ->join(User::table, User::table. '.id', '=', HrmAbsence::table. '.' .HrmAbsence::user_id)
                ->leftjoin(UserProfile::table, User::table. '.id', '=', UserProfile::table. '.' .UserProfile::user_id)
                ->select(
                    HrmLeaveType::table. '.' .HrmLeaveType::name. '  as leaveType',
                    User::table. '.' .User::name. '  as userName',
                    DB::raw('concat("'.$this->s3BasePath.'",'. UserProfile::table. '.' . UserProfile::image_path .') as userImage'),
                    DB::raw("unix_timestamp(".HrmAbsence::table . '.'.HrmAbsence::absent_start_date_time.") as absentStartDate"),
                    DB::raw("unix_timestamp(".HrmAbsence::table . '.'.HrmAbsence::absent_end_date_time.") as absentEndDate"),
                    HrmAbsence::table. '.' .HrmAbsence::reason. '  as absentEndDate',
                    HrmLeaveType::table. '.' .HrmLeaveType::color_code. '  as color'
                )
                ->first();

            return $this->content = array(
                'data'   => array('hrmAbsence' => $hrmAbsence),
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );

        } catch (\Exception $e) {
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }
    }

    /**
     * @TODO - api has pending works (departmentwise, validations)
     * @param Request $request
     * @return array
     */
    public function setWorkReportFrequency(Request $request)
    {
        $org = DB::table(Organization::table)->where(Organization::slug, $request->orgSlug)->select('id')
            ->first();

        $reportFreq = DB::table(HrmWorkReportFrequency::table)
            ->where(HrmWorkReportFrequency::frequency_name, $request->frequency['period'])->select('id')
            ->first();

        DB::beginTransaction();
        try {
            if (!$org) {
                throw new \Exception("Invalid Organisation");
            }

            if (!$reportFreq) {
                throw new \Exception("Invalid Frequency Period");
            }

            if (!in_array($request->frequency['period'], ['daily', 'monthly', 'weekly'])) {
                throw new \Exception("Invalid Frequency period");
            }


            $hrmWorkReportSettings = new HrmWorkReportSettings;
            if ($request->userSlug) {
                $user = DB::table(User::table)->where(User::slug, $request->userSlug)->select('id')
                    ->first();

                if (!$user) {
                    throw new \Exception("Invalid User");
                }

                $hrmWorkReportSettings->{HrmWorkReportSettings::user_id} = $user->id;
            }

            if ($request->departmentSlug) {
                $department = DB::table(OrgDepartment::table)->where(OrgDepartment::slug, $request->departmentSlug)->select('id')
                    ->first();

                if (empty($department)) {
                    throw new \Exception("Invalid User");
                }
                $hrmWorkReportSettings->{HrmWorkReportSettings::department_id} = $department->id;
            }

            if ($request->frequency['period'] == HrmWorkReportFrequency::monthly) {
                $hrmWorkReportSettings->{HrmWorkReportSettings::monthly_report_day} = $request->frequency['reportDay'];
            } else if ($request->frequency['period'] == HrmWorkReportFrequency::weekly) {
                $hrmWorkReportSettings->{HrmWorkReportSettings::weekly_report_day}  = $request->frequency['reportDay'];
            }

            $hrmWorkReportSettings->{HrmWorkReportSettings::slug}    = Utilities::getUniqueId();
            $hrmWorkReportSettings->{HrmWorkReportSettings::org_id}  = $org->id;
            $hrmWorkReportSettings->{HrmWorkReportSettings::report_frequency_id} = $reportFreq->id;
            $hrmWorkReportSettings->save();
            DB::commit();

            return $this->content = array(
                'data'   => array('msg' => 'Frequency Settings Added!'),
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  $e->getCode();
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

    }

    /**
     * get departments and users query
     * @param $org
     * @return mixed
     */
    public function getDepartmentsUsersQuery($org)
    {
        return OrgDepartment::where(OrgDepartment::table. '.' .OrgDepartment::org_id, $org->id)
            ->join(OrgEmployeeDepartment::table, OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::org_department_id,
                '=', OrgDepartment::table. '.id')
            ->join(OrgEmployee::table, OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::org_employee_id,
                '=', OrgEmployee::table. '.id')
            ->leftjoin(User::table, OrgEmployee::table. '.' .OrgEmployee::user_id,
                '=', User::table. '.id')
            ->leftjoin(UserProfile::table, UserProfile::table. '.' .UserProfile::user_id,
                '=', User::table. '.id');
    }

    /**
     * get simple report of a particulr day
     * @param Request $request
     * @return array
     */
    public function getOneDayReport(Request $request)
    {
        $org   = $this->getOrganization($request->orgSlug);
        $department = DB::table(OrgDepartment::table)->select('id')
            ->where(OrgDepartment::slug, $request->departmentSlug)
            ->first();

        try {

            if (!$org) {
                throw new \Exception("Invalid Organisation");
            }

            if (!$department) {
                throw new \Exception("Invalid Department");
            }

            $request->orgId = $org->id;
            $request->departmentId = $department->id;
            //DB::enableQueryLog();
            $clockMaster = HrmClockMaster::Join(User::table, HrmClockMaster::table. '.' .HrmClockMaster::user_id, '=',
                User::table. '.id')
                ->leftjoin(HrmClockEditHistory::table, HrmClockMaster::table. '.id', '=', HrmClockEditHistory::table. '.' .HrmClockEditHistory::clock_master_id)
                ->leftjoin(HrmDailyReport::table, HrmClockMaster::table. '.id', '=', HrmDailyReport::table. '.' .HrmDailyReport::clock_master_id)
                ->leftjoin(UserProfile::table, User::table. '.id', '=', UserProfile::table. '.' .UserProfile::user_id)
                ->where(HrmClockMaster::table. '.' .HrmClockMaster::slug, $request->reportSlug)
                ->select(
                    User::table. '.' . User::name,
                    User::table. '.' . User::slug. ' as userSlug',
                    DB::raw('concat("'.$this->s3BasePath.'",'. UserProfile::table. '.' . UserProfile::image_path .') as userImage'),
                    //HrmClockMaster::table. '.' .HrmClockMaster::total_working_time,
                    HrmClockMaster::table. '.' .HrmClockMaster::last_recorded_time. ' as totalWorkingTime',
                    HrmClockMaster::table. '.' .HrmClockMaster::slug,
                    HrmClockMaster::table. '.' .HrmClockMaster::start_date,
                    HrmClockMaster::table. '.' .HrmClockMaster::stop_date,
                    HrmClockEditHistory::table. '.' .HrmClockEditHistory::prev_start_date,
                    HrmClockEditHistory::table. '.' .HrmClockEditHistory::note,
                    DB::raw("CASE WHEN ". HrmDailyReport::table. ".id IS NULL THEN 0 ELSE 1 END as isReportConfirmed")
                )
                ->orderBy(HrmClockEditHistory::table. '.id', 'desc')
                ->first();
            //dd(DB::getQueryLog());
            if (!$clockMaster) {
                throw new \Exception("Invalid Report");
            }

            $response = new DailyReportResource($clockMaster);

            return $this->content = array(
                'data'   => $response,
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

    public function getDayArrays($month, $year)
    {
        $totDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $daysArr = [];
        for ($i = 1 ; $i<=$totDaysInMonth; $i++) {
            $daysArr[$i] = array(
                'day' => $i,
                'hours' => null,
                'minutes' => null,
                'reportSlug' => null,
                'confirm' => false,
                'absent' => new \stdClass()
            );
        }

        return $daysArr;
    }

    public function confirmDailyReport(Request $request)
    {
        $loggedUser = Auth::user()->id;

        try {
            DB::beginTransaction();

            $clockMaster = DB::table(HrmClockMaster::table)->select('id', HrmClockMaster::user_id)->where(HrmClockMaster::slug, $request->reportSlug)
                ->first();

            if(empty($clockMaster)){
                throw new \Exception("request is invalid");
            }

            $hrmDailyReport = DB::table(HrmDailyReport::table)->where(HrmDailyReport::clock_master_id, $clockMaster->id);

            if($hrmDailyReport->exists()){
                throw new \Exception("Report already confirmed!");
            }

            $hrmDailyReport = new HrmDailyReport;
            $hrmDailyReport->{HrmDailyReport::slug} = Utilities::getUniqueId();
            $hrmDailyReport->{HrmDailyReport::clock_master_id} = $clockMaster->id;
            $hrmDailyReport->{HrmDailyReport::creator_id} = $clockMaster->{HrmClockMaster::user_id};
            $hrmDailyReport->{HrmDailyReport::supervisor_id} = $loggedUser;
            $hrmDailyReport->save();
            DB::commit();

            return $this->content = array(
                'data'   => array('message' => "Worktime Report Confirmed!"),
                'code'   => Response::HTTP_CREATED,
                'status' => ResponseStatus::OK
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

    }


    /**
     * workreport popup listing tasks,events
     * @param Request $request
     * @return array
     */
    public function initiateWorkReportBeforeSubmit(Request $request)
    {
        $loggeduser = Auth::user();
        //DB::enableQueryLog();
        $orgId = DB::table(Organization::table)->select('id')->where(Organization::slug, $request->orgSlug)
            ->first();



        try {
            if (empty($orgId)) {
                throw new \Exception("Invalid Organization", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $datesArray = $this->common->getWorkReportDates($request, $loggeduser, $orgId);
            $workReport = DB::table(HrmWorkReport::table)
                ->select('id', HrmWorkReport::title)
                ->where(HrmWorkReport::creator_id, $loggeduser->id)
                ->whereDate(HrmWorkReport::start_date, Utilities::createDateTimeFromUtc($datesArray['startDate']))
                ->whereDate(HrmWorkReport::end_date, Utilities::createDateTimeFromUtc($datesArray['endDate']))->first();


            $request['orgId'] = $orgId->id;
            $request['datesArray'] = $datesArray;
            return $this->content = array(
                'data'   => array(
                    'dates' => $datesArray,
                    'report' => ($workReport) ? $workReport->{HrmWorkReport::title} : null,
                    'tasks' => $this->getWorkReportTasks($request, $loggeduser, $workReport),
                    'events' => $this->getWorkReportEvents($request, $loggeduser, $workReport),
                    'to' => $this->getDepartmentHeadUsers($loggeduser),
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

        //dd(DB::getQueryLog());
    }

    /**@TODO -- error fix
     * @param Request $request
     * @return array
     */
    public function listAllTasksWorkReport(Request $request)
    {
        $loggeduser = Auth::user();

        try {
            $search = NULL;
            if (request()->has('q')) {
                $search = $request->q;
            }

            $tasks    = $this->getWorkReportTasks($request, $loggeduser, 'all', $search);
            $tasksCnt = $tasks->count();

            $tasks = $tasks->skip(Utilities::getParams()['offset'])
                ->take(Utilities::getParams()['perPage'])
                ->get();

            $response = Utilities::paginate($tasks,
                Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $tasksCnt
            );

            $response = $response->toArray();

            $response['tasks'] =  $response['data'];
            $response          =  Utilities::unsetResponseData($response);

            return $this->content = array(
                'data'   => $response,
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );

        } catch (\Exception $e) {
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }
    }

    /**@TODO -- error fix
     * @param Request $request
     * @return array
     */
    public function listAllEventsWorkReport(Request $request)
    {
        $loggeduser = Auth::user();

        try {
            $search = NULL;
            if (request()->has('q')) {
                $search = $request->q;
            }
            $events    = $this->getWorkReportEvents($request, $loggeduser, 'all', $search);
            $eventsCnt = $events->count();

            $events = $events->skip(Utilities::getParams()['offset'])
                ->take(Utilities::getParams()['perPage'])
                ->get();

            $response = Utilities::paginate($events,
                Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $eventsCnt
            );

            $response = $response->toArray();

            $response['events'] =  $response['data'];
            $response          =  Utilities::unsetResponseData($response);

            return $this->content = array(
                'data'   => $response,
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );

        } catch (\Exception $e) {
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }
    }



    public function getWorkReportTasks($request, $loggeduser, $workReport, $listType = NULL, $search = NULL)
    {
        $tasks = Task::where(Task::table. '.' .Task::org_id, $request['orgId']);

        $tasks = $tasks->Join(TaskStatus::table, TaskStatus::table. '.id', '=',  Task::table. '.' .Task::task_status_id)
            ->where(function ($q) use ($request) {
                $q->WhereDate(Task::table. '.' .Task::end_date, '<=', Carbon::createFromTimestamp($request['datesArray']['endDate']))
                    ->Where(TaskStatus::table. '.' .TaskStatus::title, TaskStatus::completed_approved);
            });


        $excludeTask = DB::table(HrmWorkReportTask::table)
            ->select(HrmWorkReportTask::task_id)
            ->join(HrmWorkReport::table, HrmWorkReport::table. '.id', '=', HrmWorkReportTask::table. '.' .HrmWorkReportTask::work_report_id)
            ->where(HrmWorkReport::table. '.' .HrmWorkReport::creator_id, $loggeduser->id)
            ->where(HrmWorkReport::table. '.' .HrmWorkReport::is_report_sent, true)
            ->get()
            ->pluck(HrmWorkReportTask::task_id)
            ->toArray();

        $tasks = $tasks->whereNotIn(Task::table. '.id', $excludeTask);



        //DB::enableQueryLog();
        $tasks = $tasks->leftJoin(TaskParticipants::table, TaskParticipants::table. '.' . TaskParticipants::task_id, '=',  Task::table. '.id')
            ->where(function ($query) use ($loggeduser) {
                $query->orWhere(Task::table . '.' .Task::responsible_person_id, $loggeduser->id)
                    ->orWhere(TaskParticipants::table . '.' .TaskParticipants::user_id, $loggeduser->id)
                    ->orWhere(Task::table. '.' .Task::is_to_allemployees, true);
            })
            ->select(
                Task::table. '.id',
                Task::table. '.' .Task::slug,
                Task::table. '.' .Task::title
            )->get();
        //dd(DB::getQueryLog());
        $savedTasks = [];
        //workreport tasks which are saved...

        $savedTasks = DB::table(HrmWorkReportTask::table)
            ->select(HrmWorkReportTask::task_id)
            ->join(HrmWorkReport::table, HrmWorkReport::table. '.id', '=', HrmWorkReportTask::table. '.' .HrmWorkReportTask::work_report_id)
            ->get()
            ->pluck(HrmWorkReportTask::task_id)
            ->toArray();


        $tasks->map(function ($task) use ($savedTasks) {
            $task->isChecked = in_array($task->id, $savedTasks) ? true : false;
            unset($task->id);
        });

        return $tasks;

    }

    public function getWorkReportEvents($request, $loggeduser, $workReport, $listType = NULL, $search = NULL)
    {
        $events = SocialEvent::where(SocialEvent::table. '.' .SocialEvent::org_id, $request['orgId']);

        $events = $events->where(function ($q) use ($request) {
            $q->whereDate(SocialEvent::table. '.' .SocialEvent::event_end_date, '>=', Carbon::createFromTimestamp($request['datesArray']['startDate']))
                ->WhereDate(SocialEvent::table. '.' .SocialEvent::event_end_date, '<=', Carbon::createFromTimestamp($request['datesArray']['endDate']));
        });

        $excludeEvents = DB::table(HrmWorkReportEvent::table)
            ->select(HrmWorkReportEvent::event_id)
            ->join(HrmWorkReport::table, HrmWorkReport::table. '.id', '=', HrmWorkReportEvent::table. '.' .HrmWorkReportEvent::event_id)
            ->where(HrmWorkReport::table. '.' .HrmWorkReport::creator_id, $loggeduser->id)
            ->where(HrmWorkReport::table. '.' .HrmWorkReport::is_report_sent, true)
            ->get()
            ->pluck(HrmWorkReportEvent::event_id)
            ->toArray();

        $events = $events->whereNotIn(SocialEvent::table. '.id', $excludeEvents);

        $events = $events->leftJoin(SocialEventMember::table, SocialEventMember::table. '.' . SocialEventMember::social_event_id, '=',  SocialEventMember::table. '.id')
            ->where(function ($query) use ($loggeduser) {
                $query->orWhere(SocialEvent::table . '.' .SocialEvent::creator_user_id, $loggeduser->id)
                    ->orWhere(SocialEventMember::table . '.' .SocialEventMember::user_id, $loggeduser->id)
                    ->orWhere(SocialEvent::table. '.' .SocialEvent::is_event_to_all, true);
            })
            ->select(
                SocialEvent::table. '.id',
                SocialEvent::table. '.' .SocialEvent::event_slug. ' as eventSlug',
                SocialEvent::table. '.' .SocialEvent::event_title. ' as eventTitle',
                DB::raw("unix_timestamp(".SocialEvent::table . '.'.SocialEvent::event_start_date.") as eventStartDate"),
                DB::raw("unix_timestamp(".SocialEvent::table . '.'.SocialEvent::event_end_date.") as eventEndDate"),
                SocialEvent::table. '.' .SocialEvent::location
            )
            ->groupBy('id')
            ->get();


        $addedEvents = DB::table(HrmWorkReportEvent::table)
            ->select(HrmWorkReportEvent::event_id)
            ->join(HrmWorkReport::table, HrmWorkReport::table. '.id', '=', HrmWorkReportEvent::table. '.' .HrmWorkReportEvent::work_report_id)
            ->get()
            ->pluck(HrmWorkReportEvent::event_id)
            ->toArray();


        $events->map(function ($event) use ($addedEvents) {
            $event->isChecked = in_array($event->id, $addedEvents) ? true : false;
            unset($event->id);
        });

        return $events;
    }

    public function getDepartmentHeadUsers($loggeduser)
    {
        $loggedUserDepartmentIds = DB::table(OrgEmployeeDepartment::table)
            ->join(OrgEmployee::table, OrgEmployee::table. '.id', '=', OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::org_employee_id)
            ->where(OrgEmployee::table. '.' .OrgEmployee::user_id, $loggeduser->id)
            ->select(OrgEmployeeDepartment::org_department_id)
            ->get()
            ->pluck(OrgEmployeeDepartment::org_department_id)
            ->toArray();

        $departmentHeadUsers = DB::table(OrgEmployeeDepartment::table)
            ->join(OrgEmployee::table, OrgEmployee::table. '.id', '=', OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::org_employee_id)
            ->join(User::table, User::table. '.id', '=', OrgEmployee::table. '.' .OrgEmployee::user_id)
            ->join(UserProfile::table, User::table. '.id', '=', UserProfile::table. '.' .UserProfile::user_id)
            ->whereIn(OrgEmployeeDepartment::org_department_id, $loggedUserDepartmentIds)
            ->where(OrgEmployeeDepartment::is_head, true)
            ->select(
                User::table. '.' .User::slug,
                User::table. '.' .User::name,
                DB::raw('concat("'.$this->s3BasePath.'",'. UserProfile::table. '.' . UserProfile::image_path .') as image')
            );
            return $departmentHeadUsers->get();
    }

    /**
     * send or dave work report
     * @param Request $request
     * @return array
     */
    public function sendWorkReportToSupervisor(Request $request)
    {
        $loggedUser = Auth::user();


        $org   = $this->getOrganization($request->orgSlug);
        $isSendOrSave = false;

        $startDate = Carbon::createFromTimestampUTC($request->fromDate);
        $endDate   = Carbon::createFromTimestampUTC($request->toDate);

        try {
            if (empty($request->reportDescription)) {
                throw new \Exception("Please enter report", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            DB::beginTransaction();

            $workReport = HrmWorkReport::where(function ($q) use ($endDate) {
                    $q->whereMonth(HrmWorkReport::end_date, $endDate->month)
                        ->whereYear(HrmWorkReport::end_date, $endDate->year);
                })
                ->where(HrmWorkReport::creator_id, $loggedUser->id)->first();


            if (empty($request->tasks) && empty($request->events)) {
                throw new \Exception("Please select any task or events", Response::HTTP_UNPROCESSABLE_ENTITY);
            }


            if (empty($workReport)) {
                $workReport = new HrmWorkReport;
                $workReport->{HrmWorkReport::slug} = Utilities::getUniqueId();
            } else {
                /*if ($workReport->{HrmWorkReport::is_report_sent}) {
                    throw new \Exception("Report for current month already sent to supervisor", Response::HTTP_UNPROCESSABLE_ENTITY);
                }*/
                $startDate = $workReport->{HrmWorkReport::start_date};
                $startDate = Carbon::parse($startDate);
            }

            //saving data to workreport
            $workReport->{HrmWorkReport::title} = $request->reportDescription;
            $workReport->{HrmWorkReport::org_id} = $org->id;
            $workReport->{HrmWorkReport::creator_id} = $loggedUser->id;
            $workReport->{HrmWorkReport::start_date} = $startDate;
            $workReport->{HrmWorkReport::end_date}   = $endDate;

            $isSendOrSave = $request->isSend;
            if ($isSendOrSave) {
                $supervisorUser = $this->getUser($request->toUser);
                $workReport->{HrmWorkReport::superviser_id}  = $supervisorUser->id;
                $workReport->{HrmWorkReport::is_report_sent} = true;
                $workReport->{HrmWorkReport::report_sent_date} = Carbon::now();
            }

            $workReport->save();

            $wordReportId = $workReport->id;

            //adding Task to report
            $taskIdQuery = DB::table(Task::table)->select(Task::table. '.id')->whereIn(Task::table. '.' .Task::slug, $request->tasks);
            $taskDelArr = $taskIdQuery->get()->pluck('id')
                ->toArray();

            $taskIdArr = $taskIdQuery->leftjoin(HrmWorkReportTask::table, Task::table. '.id', '=',
                    HrmWorkReportTask::table. '.' .HrmWorkReportTask::task_id)
                ->whereNull(HrmWorkReportTask::table. '.' .HrmWorkReportTask::work_report_id)
                ->get()
                ->pluck('id')
                ->toArray();

            $workReportTask = collect($taskIdArr)->map(function ($taskId) use ($wordReportId) {
                return [
                    HrmWorkReportTask::work_report_id => $wordReportId,
                    HrmWorkReportTask::task_id => $taskId
                ];
            })->all();

            HrmWorkReportTask::insert($workReportTask);

            HrmWorkReportTask::whereNotIn(HrmWorkReportTask::table. '.' .HrmWorkReportTask::task_id, $taskDelArr)
                ->where(HrmWorkReportTask::table. '.' .HrmWorkReportTask::work_report_id, $wordReportId)
                ->delete();

            //adding Event to report
            $eventIdQuery = DB::table(SocialEvent::table)->select(SocialEvent::table. '.id')->whereIn(SocialEvent::table. '.' .SocialEvent::event_slug, $request->events);
            $eventDelArr = $eventIdQuery->get()->pluck('id')
                ->toArray();

            $eventIdArr = $eventIdQuery->leftjoin(HrmWorkReportEvent::table, SocialEvent::table. '.id', '=',
                HrmWorkReportEvent::table. '.' .HrmWorkReportEvent::event_id)
                ->whereNull(HrmWorkReportEvent::table. '.' .HrmWorkReportEvent::work_report_id)
                ->get()
                ->pluck('id')
                ->toArray();

            $workReportEvent = collect($eventIdArr)->map(function ($eventId) use ($wordReportId) {
                return [
                    HrmWorkReportEvent::work_report_id => $wordReportId,
                    HrmWorkReportEvent::event_id => $eventId
                ];
            })->all();

            HrmWorkReportEvent::insert($workReportEvent);

            HrmWorkReportEvent::whereNotIn(HrmWorkReportEvent::table. '.' .HrmWorkReportEvent::event_id, $eventDelArr)
                ->where(HrmWorkReportEvent::table. '.' .HrmWorkReportEvent::work_report_id, $wordReportId)
                ->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  $e->getCode();
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

        return $this->content = array(
            'data'   => array('msg' => ($isSendOrSave)? 'Work Report Send' : 'Work Report Saved'),
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );

    }

    /**
     * @TODO - events listing, scoreGiven : on (date)
     * @param Request $request
     * @return array
     */
    public function getOneMonthWorkReport(Request $request)
    {
        $workReportScore = HrmWorkReport::join(User::table. ' as reportFrom',
                'reportFrom.id', '=', HrmWorkReport::table. '.' .HrmWorkReport::creator_id)
            ->join(User::table. ' as reportTo',
                'reportTo.id', '=', HrmWorkReport::table. '.' .HrmWorkReport::superviser_id)
            ->leftjoin(UserProfile::table. ' as reportFromProfile', 'reportFromProfile.' .UserProfile::user_id,
                '=', 'reportFrom.id')
            ->leftjoin(UserProfile::table. ' as reportToProfile', 'reportToProfile.' .UserProfile::user_id,
                '=', 'reportTo.id')
            ->where(HrmWorkReport::table. '.' .HrmWorkReport::slug, $request->reportSlug)
            ->select(
                HrmWorkReport::table. '.id',
                HrmWorkReport::title,
                HrmWorkReport::start_date,
                HrmWorkReport::end_date,
                HrmWorkReport::is_confirmed. '  as isReportConfirmed',
                'reportFrom.' .User::name. ' as reportFromUserName',
                DB::raw('concat("'.$this->s3BasePath.'",reportFromProfile.'. UserProfile::image_path .') as reportFromUserImg'),
                DB::raw('concat("'.$this->s3BasePath.'",reportToProfile.'. UserProfile::image_path .') as reportToUserImg'),
                'reportTo.'. User::name. ' as reportToUserName',
                'reportTo.id'. ' as supervisorId')
            ->firstOrFail();

        $response = new OneMonthWorReportResource($workReportScore);
        return $this->content = array(
            'data'   => $response,
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );
    }

    public function confirmWorkReport(Request $request)
    {

        try {
            $loggedUser = Auth::user();
            $workReport = HrmWorkReport::where(HrmWorkReport::slug, $request->reportSlug)->first();

            if (empty($workReport)) {
                throw new \Exception("invalid report slug", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $supervisorId = $workReport->{HrmWorkReport::superviser_id};

            if ($supervisorId != $loggedUser->id) {
                throw new \Exception("you are not authorised to confirm!", Response::HTTP_UNPROCESSABLE_ENTITY);
            }


            DB::beginTransaction();
            $workReport->{HrmWorkReport::is_confirmed} = true;
            $workReport->save();
            DB::commit();

            return $this->content = array(
                'data'   => array('msg' => 'Work Report confirmed!'),
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  $e->getCode();
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

    }

    /**
     * TODO - need to save supervisor score added date
     * @param Request $request
     * @return array
     */
    public function appyOrchangeScore(Request $request)
    {
        $me = Auth::user();
        $workReport = HrmWorkReport::where(HrmWorkReport::slug, $request->reportSlug)->firstOrFail();
        $score      = HrmWorkReportScore::where(HrmWorkReportScore::name, $request->scoreName)
            ->select('id')
            ->firstOrFail();

        try {
            if ($workReport->{HrmWorkReport::superviser_id} != $me->id) {
                throw new \Exception("You have no permission to give score!", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $workReport->{HrmWorkReport::report_score_id} = $score->id;
            $workReport->save();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  $e->getCode();
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

        return $this->content = array(
            'data'   => array('message' => 'score updated!'),
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );
    }

    public function getAllWorkReports(Request $request)
    {
        $org   = $this->getOrganization($request->orgSlug);

        //** new code start*/

        $departmentSlug = null;
        $confirmedKey   = null;
        $unConfirmedKey = null;
        $isFilter = false;
        if ($request->has('filterBy') && $request->filterBy) {
            $departmentSlug = $request->filterBy['departmentSlug'];
            $confirmedKey   = $request->filterBy['confirmed'];
            $unConfirmedKey = $request->filterBy['unConfirmed'];
            $isFilter = true;
        }
        //DB::raw('concat("'.$this->s3BasePath.'",creatorImage.'. UserProfile::image_path .') as creatorImage'),
        //list all departments under org
        $departments = OrgDepartment::select(
            OrgDepartment::table. '.' . OrgDepartment::slug,
            OrgDepartment::table. '.' .OrgDepartment::name. ' as departmentName')
            ->where(OrgDepartment::table. '.' .OrgDepartment::org_id, $org->id)
            ->join(OrgEmployeeDepartment::table, OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::org_department_id,
                '=', OrgDepartment::table. '.id')
            ->join(OrgEmployee::table, OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::org_employee_id,
                '=', OrgEmployee::table. '.id')
            ->groupBy(OrgDepartment::table. '.' . OrgDepartment::slug);

        if ($departmentSlug) {
            $departments->where(OrgDepartment::table. '.' .OrgDepartment::slug, $departmentSlug);
        }

        $departmentCount = $departments->count();
        $departments = $departments->skip(Utilities::getParams()['offset'])
            ->take(Utilities::getParams()['perPage'])
            ->get();

        $users = $this->getDepartmentsUsersQuery($org)
            ->select(
                User::table. '.' . User::name. ' as userName',
                User::table. '.' . User::slug. ' as userSlug',
                DB::raw('concat("'.$this->s3BasePath.'",'. UserProfile::table. '.' . UserProfile::image_path .') as userImage'),
                OrgDepartment::table. '.' .OrgDepartment::slug.' as orgDepartmentSlug',
                OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::is_head.' as isHeadOfDepartment');

        $departmentUsers = $users->get();
        $userSlugArr     = $departmentUsers->pluck('userSlug');

        //query for deparments and users
        $workReportUserQuery = DB::table(HrmWorkReport::table)
            ->join(User::table, HrmWorkReport::table. '.' .HrmWorkReport::creator_id, '=', User::table. '.id')
            ->select(
                HrmWorkReport::table. '.id' . ' as reportId',
                HrmWorkReport::table. '.' .HrmWorkReport::report_score_id. ' as reportScoreId',
                HrmWorkReport::table. '.' .HrmWorkReport::start_date. ' as reportStartDate',
                HrmWorkReport::table. '.' .HrmWorkReport::end_date. ' as reportEndDate',
                HrmWorkReport::table. '.' .HrmWorkReport::slug. ' as reportSlug',
                HrmWorkReport::table. '.' .HrmWorkReport::title. ' as reportTitle',
                User::table. '.' . User::slug. ' as userSlug',
                User::table. '.' . User::name. ' as userName'
            )
            ->where(HrmWorkReport::table. '.' .HrmWorkReport::is_report_sent, true)
            ->where(function ($query) use ($request){
                $query->whereYear(HrmWorkReport::table. '.' .HrmWorkReport::start_date, $request->year);
            });

        $workReportIdArr = $workReportUserQuery->pluck('reportId')->toArray();

        //dd($workReportIdArr);

        $userScore = DB::table(HrmWorkReport::table)
            ->select(
                User::table. '.' .User::slug. ' as userSlug',
                TaskScore::table. '.' .TaskScore::score_name,
                DB::raw("count(" . TaskScore::table. '.' .TaskScore::score_name .") as scoreCount")
            )
            ->join(User::table, User::table. '.id', '=', HrmWorkReport::table. '.' .HrmWorkReport::creator_id)
            ->join(HrmWorkReportTask::table, HrmWorkReportTask::table. '.' .HrmWorkReportTask::work_report_id, '=',
                HrmWorkReport::table. '.id')
            ->join(Task::table, Task::table. '.id', '=', HrmWorkReportTask::table. '.' .HrmWorkReportTask::task_id)
            ->join(TaskScore::table, TaskScore::table. '.id', '=', Task::table. '.' .Task::task_score_id)
            ->whereIn(HrmWorkReport::table. '.id', $workReportIdArr)
            ->groupBy( TaskScore::table. '.' .TaskScore::score_name)->get();

        $userScoreArr = $userScore->groupBy('userSlug');

        $userScoreAvgArr = [];
        foreach ($userScoreArr as $userKey => $userScore) {
            $totScoreCnt = $userScore->sum('scoreCount');
            $userScoreAvgArr[$userKey]['excellence'] = 0;
            $userScoreAvgArr[$userKey]['positive'] = 0;
            $userScoreAvgArr[$userKey]['negative'] = 0;
            foreach($userScore as $score) {
                if ($score->score_name == 'excellence')
                    $userScoreAvgArr[$userKey]['excellent'] = round($score->scoreCount/$totScoreCnt*100, 2);
                else if ($score->score_name == 'positive')
                    $userScoreAvgArr[$userKey]['positive'] = round($score->scoreCount/$totScoreCnt*100, 2);
                else if ($score->score_name == 'negative')
                    $userScoreAvgArr[$userKey]['negative'] = round($score->scoreCount/$totScoreCnt*100, 2);
            }
        }

        //dd($userScoreAvgArr);

        $workReportUsers = $workReportUserQuery->get()->groupBy('userSlug');

        $departmentUsers = $departmentUsers->toArray();

        $monthArr = $this->getMonthArrays();
        foreach ($departmentUsers as &$user) {
            $user['reportSlug'] = null;
            $user['totalReports'] = 0;
            $user['unconfirmed']  = 0;
            $user['reportTimeline'] = $monthArr;
            $user['isHeadOfDepartment'] = (bool) $user['isHeadOfDepartment'];
            $user['scoreRatings'] = [
                'excellent' => 0,
                'positive'  => 0,
                'negative'  => 0,
            ];
            if (!empty($workReportUsers[$user['userSlug']])) {
                $user['totalReports'] = count($workReportUsers[$user['userSlug']]);
                if (!empty($userScoreAvgArr) && isset($userScoreAvgArr[$user['userSlug']]))
                    $user['scoreRatings'] = $userScoreAvgArr[$user['userSlug']];
                foreach ($workReportUsers[$user['userSlug']] as $userWorkReport) {
                    //dd($userWorkReport);
                    $monthKey = date('M', strtotime($userWorkReport->reportStartDate));

                    $user['reportSlug']     = $userWorkReport->reportSlug;
                    $user['unconfirmed']    = is_null($userWorkReport->reportScoreId)?  $user['unconfirmed'] + 1 : $user['unconfirmed'];
                    $user['reportTimeline'][$monthKey]['startDate']   = $userWorkReport->reportStartDate;
                    $user['reportTimeline'][$monthKey]['endDate']     = $userWorkReport->reportEndDate;
                    $user['reportTimeline'][$monthKey]['reportTitle'] = $userWorkReport->reportTitle;
                    $user['reportTimeline'][$monthKey]['reportSlug']  = $userWorkReport->reportSlug;
                }

            }

            $user['reportTimeline'] = array_values($user['reportTimeline']);
        }

        $departmentUserGroup = collect($departmentUsers)->groupBy('orgDepartmentSlug')->toArray();


        $departments->map(function ($department) use ($departmentUserGroup) {
            $department->users = isset($departmentUserGroup[$department->{OrgDepartment::slug}]) ? $departmentUserGroup[$department->{OrgDepartment::slug}] : [];
        });


        $response = Utilities::paginate($departments,
            Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $departmentCount
        );
        $response = $response->toArray();

        $response['workReport'] =  $response['data'];
        $response         =  Utilities::unsetResponseData($response);
        return $this->content = array(
            'data'   => $response,
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );


        //** new code end */

        $scoreStatusArr = $this->getScoreStatus();

        //list all departments under org
        $departments = OrgDepartment::select(OrgDepartment::slug, OrgDepartment::name. ' as departmentName')
            ->where(OrgDepartment::org_id, $org->id)
            ->get();

        //query for deparments and users
        $departmentUsers = $this->getDepartmentsUsersQuery($org)
            ->join(HrmWorkReport::table, HrmWorkReport::table. '.' .HrmWorkReport::creator_id,
                '=', User::table. '.id')
            ->leftjoin(HrmWorkReportTask::table, HrmWorkReportTask::table. '.' .HrmWorkReportTask::work_report_id,
                '=', HrmWorkReport::table. '.id')
            ->leftjoin(Task::table, HrmWorkReportTask::table. '.' .HrmWorkReportTask::task_id, '=', Task::table. '.id')
            ->leftjoin(TaskScore::table, Task::table. '.' .Task::task_score_id, '=', TaskScore::table. '.id')
            ->select(
                DB::raw('count('. TaskScore::score_name .') as totalTask'),
                DB::raw('GROUP_CONCAT(' . HrmWorkReportTask::table . '.'. HrmWorkReportTask::task_id .') AS taskIds'),
                HrmWorkReport::table. '.id' . ' as reportId',
                HrmWorkReport::table. '.' .HrmWorkReport::report_score_id. ' as reportScoreId',
                HrmWorkReport::table. '.' .HrmWorkReport::start_date. ' as reportStartDate',
                HrmWorkReport::table. '.' .HrmWorkReport::end_date. ' as reportEndDate',
                HrmWorkReport::table. '.' .HrmWorkReport::slug. ' as reportSlug',
                HrmWorkReport::table. '.' .HrmWorkReport::title. ' as reportTitle',
                User::table. '.' . User::slug. ' as userSlug',
                User::table. '.' . User::name. ' as userName',
                OrgDepartment::table. '.' .OrgDepartment::slug.' as orgDepartmentSlug')
            ->where(HrmWorkReport::table. '.' .HrmWorkReport::is_report_sent, true)
            ->where(function ($query) use ($request){
                $query->whereYear(HrmWorkReport::table. '.' .HrmWorkReport::start_date, $request->year);
            })
            ->get();

        $reportIdArr = $departmentUsers->pluck('reportId')->toArray();

dd($departmentUsers);

        $score = DB::table(TaskScore::table)
            ->select(
                DB::raw('('. TaskScore::table. '.' .TaskScore::score_name .') as scoreName'),
                DB::raw('count('. HrmWorkReportTask::table. '.' . HrmWorkReportTask::task_id .') as taskScore'),
                DB::raw('GROUP_CONCAT(' . HrmWorkReportTask::table . '.'. HrmWorkReportTask::task_id .') AS taskIds')
            )
            ->leftjoin(Task::table, Task::table. '.' .Task::task_score_id, '=', TaskScore::table. '.id')
            ->leftjoin(HrmWorkReportTask::table, HrmWorkReportTask::table. '.' .HrmWorkReportTask::task_id, '=', Task::table. '.id')
            ->whereIn(HrmWorkReportTask::table. '.' .HrmWorkReportTask::work_report_id, $reportIdArr)
            ->groupBy(TaskScore::table. '.' .TaskScore::score_name)
            ->get();

        dd($score->groupBy('taskIds'));
        dd($score->toSql());

        $workReportTask = DB::table(HrmWorkReportTask::table)
            ->select(
                //DB::raw('count('. HrmWorkReportTask::work_report_id .') as totalTask')
/*                DB::raw('count('. TaskScore::score_name .') as totalTask'),
                DB::raw('('. TaskScore::score_name .')')*/
                DB::raw('('. TaskScore::score_name .') as scoreName'),
                DB::raw('count('. TaskScore::table. '.id' .') as taskScore')

            )
            ->leftjoin(Task::table, HrmWorkReportTask::table. '.' .HrmWorkReportTask::task_id, '=', Task::table. '.id')
            ->leftjoin(TaskScore::table, Task::table. '.' .Task::task_score_id, '=', TaskScore::table. '.id')

            ->whereIn(HrmWorkReportTask::table. '.' .HrmWorkReportTask::work_report_id, $reportIdArr)
            ->groupBy(TaskScore::score_name)
            ->get();


//dd($workReportTask);
//dd($departmentUsers);
        $users = array();

        $departmentUsers->map(function ($user) use (&$users, $scoreStatusArr) {
            $totReport = 1;
            $userArr = $user->toArray();

            //checks same user
            if (isset($users[$user->orgDepartmentSlug][$user->userSlug])) {
                $existingUserArr = $users[$user->orgDepartmentSlug][$user->userSlug];

                $userArr['totalReports']   = $existingUserArr['totalReports'] + 1;
                $userArr['unconfirmed']    = is_null($userArr['reportScoreId'])?  $existingUserArr['unconfirmed'] + 1 : $existingUserArr['unconfirmed'];
                $userArr['scoreRatings']   = $existingUserArr['scoreRatings'];

                if ($userArr['reportScoreId'] == $scoreStatusArr[HrmWorkReportScore::excellence]) {
                    $userArr['scoreRatings']['excellent'] = $existingUserArr['scoreRatings']['excellent'] + 1;
                } else if ($userArr['reportScoreId'] == $scoreStatusArr[HrmWorkReportScore::positive]) {
                    $userArr['scoreRatings']['positive'] = $existingUserArr['scoreRatings']['positive'] + 1;
                } else if ($userArr['reportScoreId'] == $scoreStatusArr[HrmWorkReportScore::negative]) {
                    $userArr['scoreRatings']['negative'] = $existingUserArr['scoreRatings']['negative'] + 1;
                }

                $userArr['reportTimeline'] = $existingUserArr['reportTimeline'];
                $startDate = Carbon::parse($userArr['reportStartDate']);
                $userArr['reportTimeline'][] = [
                    'month'     => $startDate->format('M'),
                    'startDate' => $startDate->day,
                    'endDate' => Carbon::parse($userArr['reportEndDate'])->day,
                    'reportSlug' => $userArr['reportSlug'],
                    'reportTitle' => $userArr['reportTitle']
                ];

            } else {
                $userArr['totalReports']   = $totReport;
                $userArr['unconfirmed']    = is_null($userArr['reportScoreId'])? 1 : 0;
                $userArr['scoreRatings']   = [
                    'excellent' => ($user->reportScoreId == $scoreStatusArr[HrmWorkReportScore::excellence]) ? 1 : 0,
                    'positive' => ($user->reportScoreId == $scoreStatusArr[HrmWorkReportScore::positive]) ? 1 : 0,
                    'negative' => ($user->reportScoreId == $scoreStatusArr[HrmWorkReportScore::negative]) ? 1 : 0
                ];

                $startDate = Carbon::parse($userArr['reportStartDate']);
                $userArr['reportTimeline'][] = [
                    'month'     => $startDate->format('M'),
                    'startDate' => $startDate->day,
                    'endDate' => Carbon::parse($userArr['reportEndDate'])->day,
                    'reportSlug' => $userArr['reportSlug'],
                    'reportTitle' => $userArr['reportTitle']
                ];
            }

            $users[$user->orgDepartmentSlug][$user->userSlug] = $userArr;
        });


        $monthArr = $this->getMonthArrays();

        $departmentUserGroup = array();
        foreach ($users as $departmentSlug) {
            foreach ($departmentSlug as $userArr) {
                unset($userArr['reportScoreId']);
                unset($userArr['reportTitle']);
                unset($userArr['reportStartDate']);
                unset($userArr['reportEndDate']);
                //dd($userArr);
                $userArr['scoreRatings']['excellent'] = round(($userArr['scoreRatings']['excellent'])/$userArr['totalReports']*100, 2);
                $userArr['scoreRatings']['positive'] = round(($userArr['scoreRatings']['positive'])/$userArr['totalReports']*100, 2);
                $userArr['scoreRatings']['negative'] = round(($userArr['scoreRatings']['negative'])/$userArr['totalReports']*100, 2);
                //dd($userArr);
                foreach ($userArr['reportTimeline'] as $timelineArr) {
                    $monthArr[$timelineArr['month']] = $timelineArr;
                    $userArr['reportTimeline'] = array_values($monthArr);
                }
                $departmentUserGroup[] = $userArr;
            }
        }

        $departmentUserGroup = collect($departmentUserGroup)->groupBy('orgDepartmentSlug')->toArray();

        $departments->map(function ($department) use ($departmentUserGroup) {
            $department->users = isset($departmentUserGroup[$department->{OrgDepartment::slug}]) ? $departmentUserGroup[$department->{OrgDepartment::slug}] : [];
        });

        return $this->content = array(
            'data'   => array('workReport' => $departments),
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );

    }

    public function getMonthArrays()
    {
        $months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        $monthArr = [];
        foreach ($months as $month) {
            $monthArr[$month] = array(
                'month'     => $month,
                'startDate' => null,
                'endDate' => null,
                'reportTitle' => null,
                'reportSlug'  => null
            );
        }

        return $monthArr;
    }

    public function getOrganization($slug)
    {
        return Organization::select('id')->where(Organization::slug, $slug)->firstOrFail();
    }

    public function getUser($slug)
    {
        return User::select('id')->where(User::slug, $slug)->firstOrFail();
    }

    public function getScoreStatus()
    {
        $score = collect();
        $scores = HrmWorkReportScore::select(HrmWorkReportScore::table. '.id', HrmWorkReportScore::table. '.' .HrmWorkReportScore::name)
            ->get();

        $scores->map(function ($status)  use ($score) {
            $score[$status->{HrmWorkReportScore::name}] = $status->id;
        });

        return $score->toArray();
    }

}