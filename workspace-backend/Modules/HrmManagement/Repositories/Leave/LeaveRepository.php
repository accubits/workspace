<?php
/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 07/10/18
 * Time: 12:00 PM
 */

namespace Modules\HrmManagement\Repositories\Leave;


use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\ResponseStatus;
use Modules\Common\Utilities\Utilities;
use Modules\HrmManagement\Entities\HrmAbsence;
use Modules\HrmManagement\Entities\HrmAbsentCount;
use Modules\HrmManagement\Entities\HrmHolidays;
use Modules\HrmManagement\Entities\HrmLeaveRequests;
use Modules\HrmManagement\Entities\HrmLeaveType;
use Modules\HrmManagement\Entities\HrmLeaveTypeCategory;
use Modules\HrmManagement\Entities\HrmLeaveTypeUserMapping;
use Modules\HrmManagement\Repositories\LeaveInterface;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgDepartment;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\OrgManagement\Entities\OrgEmployeeDepartment;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;

class LeaveRepository implements LeaveInterface
{

    protected $content;

    public function __construct()
    {
        $this->content = array();
        $this->s3BasePath = env('S3_PATH');
    }

    /**
     * Create Leave Type
     * @param Request $request
     * @return array
     */
    public function createLeaveType(Request $request)
    {
        $user  = Auth::user();
        $org   = DB::table(Organization::table)->select('id')->where(Organization::slug, $request->orgSlug)
                ->first();

        try {
            if (!$org) {
                throw new \Exception("Organization is invalid");
            }

            DB::beginTransaction();

            if ($request->action == 'create') {
                $leaveTypeCat   = DB::table(HrmLeaveTypeCategory::table)
                    ->select('id')->where(HrmLeaveTypeCategory::category_name, $request->type)
                    ->first();

                if (!$leaveTypeCat) {
                    throw new \Exception("Leave Type is invalid");
                }


                $hrmLeaveType = new HrmLeaveType;
                $hrmLeaveType->{HrmLeaveType::slug}   = Utilities::getUniqueId();
                $hrmLeaveType->{HrmLeaveType::org_id} = $org->id;
                $hrmLeaveType->{HrmLeaveType::type_category_id} = $leaveTypeCat->id;
                $hrmLeaveType->{HrmLeaveType::name} = $request->name;
                $hrmLeaveType->{HrmLeaveType::description} = $request->description;
                $hrmLeaveType->{HrmLeaveType::creator_id}  = $user->id;
                //$hrmLeaveType->{HrmLeaveType::period} = $request->period;
                $hrmLeaveType->{HrmLeaveType::to_all_employee} = $request->is_to_all_employees;
                $hrmLeaveType->{HrmLeaveType::leave_count}     = $request->leaveCount;
                $hrmLeaveType->{HrmLeaveType::is_applicable_for} = $request->isApplicableFor;
                $hrmLeaveType->{HrmLeaveType::is_active}         = true;

                if (!empty($request->colorCode))
                    $hrmLeaveType->{HrmLeaveType::color_code}         = $request->colorCode;

                $hrmLeaveType->save();

                if (($request->toUsers) && !($request->is_to_all_employees)) {
                    //applicable for users
                    if ($request->isApplicableFor) {
                        $leaveUsers   = DB::table(OrgEmployee::table)
                            ->join(User::table, User::table. '.id', '=', OrgEmployee::table. '.' .OrgEmployee::user_id)
                            ->select(User::table. '.id')
                            ->whereIn(User::table. '.' .User::slug, $request->toUsers)
                            ->where(OrgEmployee::table. '.' .OrgEmployee::org_id, $org->id)
                            ->get()
                            ->pluck('id')
                            ->toArray();

                        if (empty($leaveUsers)) {
                            throw new \Exception("Invalid users");
                        }
                    }

                    //not applicable for  users
                    if (!$request->isApplicableFor) {
                        $leaveUsers   = DB::table(OrgEmployee::table)
                            ->join(User::table, User::table. '.id', '=', OrgEmployee::table. '.' .OrgEmployee::user_id)
                            ->select(User::table. '.id')
                            ->whereNotIn(User::table. '.' .User::slug, $request->toUsers)
                            ->where(OrgEmployee::table. '.' .OrgEmployee::org_id, $org->id)
                            ->get()
                            ->pluck('id')
                            ->toArray();

                        if (empty($leaveUsers)) {
                            throw new \Exception("No more users found!");
                        }
                    }

                    $leaveTypeId  = $hrmLeaveType->id;
                    $leaveTypeArr = [];

                    foreach($leaveUsers as $leaveUser) {
                        $leaveTypeArr[] = [
                            HrmLeaveTypeUserMapping::org_id => $org->id,
                            HrmLeaveTypeUserMapping::leave_type_id => $leaveTypeId,
                            HrmLeaveTypeUserMapping::user_id => $leaveUser,
                            HrmLeaveTypeUserMapping::CREATED_AT => now(),
                            HrmLeaveTypeUserMapping::UPDATED_AT => now()
                        ];
                    }

                    HrmLeaveTypeUserMapping::insert($leaveTypeArr);
                }
            } else if ($request->action == 'delete') {
                if (empty($request->leaveTypeSlug)) {
                    throw new \Exception("Leave type slug is invalid");
                }
                $leaveType = DB::table(HrmLeaveType::table)->where(HrmLeaveType::slug, $request->leaveTypeSlug);

                if ($leaveType->doesntExist()) {
                    throw new \Exception("Leave type slug is invalid");
                }

                $leaveType->delete();
            } else if ($request->action == 'update') {
                if (!$org) {
                    throw new \Exception("Organization is invalid");
                }

                if (empty($request->leaveTypeSlug)) {
                    throw new \Exception("Leave type slug is invalid");
                }
                $leaveType = DB::table(HrmLeaveType::table)->where(HrmLeaveType::slug, $request->leaveTypeSlug);

                if ($leaveType->doesntExist()) {
                    throw new \Exception("Leave type slug is invalid");
                }

                $leaveTypeCat = DB::table(HrmLeaveTypeCategory::table)
                    ->select('id')->where(HrmLeaveTypeCategory::category_name, $request->type)
                    ->first();

                if (!$leaveTypeCat) {
                    throw new \Exception("Leave Type is invalid");
                }

                $leaveType = HrmLeaveType::where(HrmLeaveType::slug, $request->leaveTypeSlug)->first();

                $isApplicableFor = (bool) $leaveType->{HrmLeaveType::is_applicable_for};

                $leaveType->{HrmLeaveType::org_id} = $org->id;
                $leaveType->{HrmLeaveType::type_category_id} = $leaveTypeCat->id;
                $leaveType->{HrmLeaveType::name} = $request->name;
                $leaveType->{HrmLeaveType::description} = $request->description;
                $leaveType->{HrmLeaveType::creator_id} = $user->id;
                //$leaveType->{HrmLeaveType::period} = $request->period;
                $leaveType->{HrmLeaveType::to_all_employee} = $request->is_to_all_employees;
                $leaveType->{HrmLeaveType::leave_count} = $request->leaveCount;
                $leaveType->{HrmLeaveType::is_applicable_for} = $request->isApplicableFor;
                $leaveType->{HrmLeaveType::is_active} = true;
                //dd($leaveType);

                if (!empty($request->colorCode))
                    $leaveType->{HrmLeaveType::color_code} = $request->colorCode;


                $leaveType->save();

                if (($request->toUsers) && !($request->is_to_all_employees)) {
                    //applicable for users
                    if ($request->isApplicableFor) {
                        $leaveUsers = DB::table(OrgEmployee::table)
                            ->join(User::table, User::table . '.id', '=', OrgEmployee::table . '.' . OrgEmployee::user_id)
                            ->select(User::table . '.id')
                            ->whereIn(User::table . '.' . User::slug, $request->toUsers)
                            ->where(OrgEmployee::table . '.' . OrgEmployee::org_id, $org->id)
                            ->get()
                            ->pluck('id')
                            ->toArray();

                        if (empty($leaveUsers)) {
                            throw new \Exception("Invalid users");
                        }
                    }

                    //not applicable for  users
                    if (!$request->isApplicableFor) {
                        $leaveUsers = DB::table(OrgEmployee::table)
                            ->join(User::table, User::table . '.id', '=', OrgEmployee::table . '.' . OrgEmployee::user_id)
                            ->select(User::table . '.id')
                            ->whereNotIn(User::table . '.' . User::slug, $request->toUsers)
                            ->where(OrgEmployee::table . '.' . OrgEmployee::org_id, $org->id)
                            ->get()
                            ->pluck('id')
                            ->toArray();

                        if (empty($leaveUsers)) {
                            throw new \Exception("No more users found!");
                        }
                    }

                    $leaveTypeId = $leaveType->id;
                    $leaveTypeArr = [];

                    if ($isApplicableFor === $leaveType->{HrmLeaveType::is_applicable_for}) {
                        DB::table(HrmLeaveTypeUserMapping::table)
                            ->where(HrmLeaveTypeUserMapping::leave_type_id, '=', $leaveTypeId)
                            ->delete();
                    }

                    foreach ($leaveUsers as $leaveUser) {
                        HrmLeaveTypeUserMapping::updateOrCreate(
                            [
                                HrmLeaveTypeUserMapping::leave_type_id => $leaveTypeId,
                                HrmLeaveTypeUserMapping::user_id => $leaveUser
                            ],
                            [
                                HrmLeaveTypeUserMapping::org_id => $org->id,
                                HrmLeaveTypeUserMapping::leave_type_id => $leaveTypeId,
                                HrmLeaveTypeUserMapping::user_id => $leaveUser,
                                HrmLeaveTypeUserMapping::CREATED_AT => now(),
                                HrmLeaveTypeUserMapping::UPDATED_AT => now()
                            ]
                        );
                    }

                    //delete rest
                    DB::table(HrmLeaveTypeUserMapping::table)
                        ->where(HrmLeaveTypeUserMapping::leave_type_id, '=', $leaveTypeId)
                        ->whereNotIn(HrmLeaveTypeUserMapping::user_id, $leaveUsers)
                        ->delete();

                } else {
                    DB::table(HrmLeaveTypeUserMapping::table)
                        ->where(HrmLeaveTypeUserMapping::leave_type_id, '=', $leaveType->id)
                        ->delete();
                }
            }

            DB::commit();

            $content = array();
            $content['data'] = array();
            if ($request->action == "create") {
                $content['data']['msg'] = "Leave Type Created Successfully!";
                $content['code'] = Response::HTTP_CREATED;
            } else if($request->action == "update") {
                $content['data']['msg'] = "Leave Type Updated Successfully!";
                $content['code'] = Response::HTTP_OK;
            } else if($request->action == "delete") {
                $content['data']['msg'] = "Leave Type Deleted Successfully!";
                $content['code'] = Response::HTTP_OK;
            }

            $content['status'] = ResponseStatus::OK;
            return $content;

        } catch (QueryException $e) {
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

    }


    public function fetchAllLeaveTypes(Request $request)
    {
        $org   = DB::table(Organization::table)->select('id')->where(Organization::slug, $request->orgSlug)
            ->first();

        try {
            if (!$org) {
                throw new \Exception("Organization is invalid");
            }

            $leaveTypeQuery   = DB::table(HrmLeaveType::table)
                ->join(HrmLeaveTypeCategory::table, HrmLeaveTypeCategory::table. '.id', '=', HrmLeaveType::table. '.' .HrmLeaveType::type_category_id)
                ->leftjoin(HrmLeaveTypeUserMapping::table, HrmLeaveType::table. '.id', '=', HrmLeaveTypeUserMapping::table. '.' .HrmLeaveTypeUserMapping::leave_type_id)
                ->leftjoin(User::table, User::table. '.id', '=', HrmLeaveTypeUserMapping::table. '.' .HrmLeaveTypeUserMapping::user_id)
                ->leftjoin(UserProfile::table, UserProfile::table. '.' .UserProfile::user_id, '=', User::table. '.id')
                ->where(HrmLeaveType::table. '.' .HrmLeaveType::org_id, $org->id)
                ->select(
                    HrmLeaveType::table. '.' .HrmLeaveType::name. ' as name',
                    HrmLeaveType::table. '.' .HrmLeaveType::slug. ' as leaveTypeSlug',
                    HrmLeaveTypeCategory::table. '.' .HrmLeaveTypeCategory::category_name. ' as type',
                    HrmLeaveType::table. '.' .HrmLeaveType::leave_count. ' as maximumLeave',
                    HrmLeaveType::table. '.' .HrmLeaveType::period,
                    HrmLeaveType::table. '.' .HrmLeaveType::description,
                    HrmLeaveType::table. '.' .HrmLeaveType::color_code. ' as colorCode',
                    HrmLeaveType::table. '.' .HrmLeaveType::is_applicable_for. ' as isApplicableFor',
                    HrmLeaveType::table. '.' .HrmLeaveType::to_all_employee. ' as allEmployees',
                    DB::raw('GROUP_CONCAT( '.User::table.'.' .User::name . ') AS users'),
                    DB::raw('GROUP_CONCAT( '.User::table.'.' .User::slug . ') AS userSlugs'),
                    DB::raw('GROUP_CONCAT( coalesce(concat("'. $this->s3BasePath.'", '. UserProfile::image_path.'),"")) as userImageUrl')
            )->groupBy(HrmLeaveType::table. '.id');

            if ($request->sortBy) {
                $key = [
                    'name' => HrmLeaveType::table. '.' .HrmLeaveType::name,
                    'type'       => HrmLeaveTypeCategory::table. '.' .HrmLeaveTypeCategory::category_name,
                    'maxLeave'  => HrmLeaveType::table. '.' .HrmLeaveType::leave_count,
                    'period' => HrmLeaveType::table. '.' .HrmLeaveType::period
                ];

                if (!array_key_exists($request->sortBy, $key)) {
                    throw new \Exception('Invalid sort key', 422);
                }

                $request->sortBy = $key[$request->sortBy];
            } else {
                $leaveTypeQuery = $leaveTypeQuery->orderBy(HrmLeaveType::table. '.' .HrmLeaveType::CREATED_AT, 'desc');
            }

            $leaveTypeQuery = Utilities::sort($leaveTypeQuery);

            $leaveTypeQueryCnt = $leaveTypeQuery->count();
            $leaveTypeQueryPaginated = $leaveTypeQuery
                ->skip(Utilities::getParams()['offset']) //$request['offset']
                ->take(Utilities::getParams()['perPage']) //$request['perPage']
                ->get();

            $leaveTypeQueryPaginated->transform(function ($item, $key) {
                $userArr = [];
                $users = (!empty($item->users)) ? explode(',', $item->users) : [];
                $userImageUrl = explode(',', $item->userImageUrl);
                $userSlugs    = explode(',', $item->userSlugs);

                foreach($users as $userKey => $user) {
                    $userArr[] = ['name' => $user,
                        'userSlug' => $userSlugs[$userKey],
                        'imageUrl' => $userImageUrl[$userKey]];
                }

                $item->users = $userArr;
                $item->isApplicableFor = (bool) $item->isApplicableFor;
                $item->allEmployees = (bool) $item->allEmployees;
                unset($item->userImageUrl);
                unset($item->userSlugs);
                return $item;
            });


            $paginatedData = Utilities::paginate($leaveTypeQueryPaginated, Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $leaveTypeQueryCnt)->toArray();

            $responseArr = array(
                "leaveTypes"  => $paginatedData['data'],
                "total"       => $paginatedData['total'],
                "to"          => $paginatedData['to'],
                "from"        => $paginatedData['from'],
                "currentPage" => $paginatedData['current_page']
            );

            return $this->content = array(
                'data'   => $responseArr,
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );

        } catch (QueryException $e) {
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
    }

    /**
     * create or update Holiday
     * @param Request $request
     * @return array
     */
    public function createHoliday(Request $request)
    {
        $user  = Auth::user();
        $org   = DB::table(Organization::table)->select('id')->where(Organization::slug, $request->orgSlug)
            ->first();
        $responseArr = [];

        try {
            if (!$org) {
                throw new \Exception("Organization is invalid");
            }

            DB::beginTransaction();

            if ($request->action == 'create') {
                $addHoliday = new HrmHolidays;
                $responseArr['response']['msg'] = 'Holiday Added Succesfully!';
                $responseArr['response']['code'] = Response::HTTP_CREATED;
            }

            if ($request->action == 'update') {
                $addHoliday = HrmHolidays::where(HrmHolidays::slug, $request->holidaySlug)->first();
                if (!$addHoliday) {
                    throw new \Exception("Holiday is invalid");
                }

                $responseArr['response']['msg'] = 'Holiday Updated Succesfully!';
                $responseArr['response']['code'] = Response::HTTP_OK;
            }

            if ($request->action == 'delete') {
                $addHoliday = HrmHolidays::where(HrmHolidays::slug, $request->holidaySlug)->first();
                if (!$addHoliday) {
                    throw new \Exception("Holiday is invalid");
                }
                $addHoliday->delete();
                $responseArr['response']['msg'] = 'Holiday Deleted Succesfully!';
                $responseArr['response']['code'] = Response::HTTP_OK;
            }

            if ($request->action != 'delete') {
                $addHoliday->{HrmHolidays::slug}   = Utilities::getUniqueId();
                $addHoliday->{HrmHolidays::org_id} = $org->id;
                $addHoliday->{HrmHolidays::creator_id} = $user->id;
                $addHoliday->{HrmHolidays::name} = $request->name;
                $addHoliday->{HrmHolidays::description}  = $request->description;
                $addHoliday->{HrmHolidays::holiday_date} = Carbon::createFromTimestampUTC($request->holidayDate)->toDateString();
                $addHoliday->{HrmHolidays::is_restricted} = $request->isRestricted;
                $addHoliday->{HrmHolidays::is_repeat_yearly} = $request->isRepeatYearly;
                $addHoliday->save();
            }


            DB::commit();
            return $this->content = array(
                'data'   => array('msg' => $responseArr['response']['msg']),
                'code'   => $responseArr['response']['code'],
                'status' => ResponseStatus::OK
            );

        } catch (QueryException $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }
    }

    public function fetchAllHolidays(Request $request)
    {
        $org   = DB::table(Organization::table)->select('id')->where(Organization::slug, $request->orgSlug)
            ->first();

        try {
            if (!$org) {
                throw new \Exception("Organization is invalid");
            }

            //$rawRestricteQuery = sprintf("IF ('%s' = '0', 'No', 'Yes') as restricted", HrmHolidays::table. '.' .HrmHolidays::is_restricted);

            $holidaysQuery   = DB::table(HrmHolidays::table)
                ->where(HrmHolidays::table. '.' .HrmHolidays::org_id, $org->id)
                ->select(
                    HrmHolidays::table. '.' .HrmHolidays::slug. ' as holidaySlug',
                    HrmHolidays::table. '.' .HrmHolidays::name. ' as name',
                    DB::raw("unix_timestamp(".HrmHolidays::table . '.'.HrmHolidays::holiday_date.") AS date"),
                    //DB::raw($rawRestricteQuery),
                    HrmHolidays::table. '.' .HrmHolidays::is_restricted. ' as restricted',
                    HrmHolidays::table. '.' .HrmHolidays::is_repeat_yearly. ' as repeatYearly',
                    HrmHolidays::table. '.' .HrmHolidays::description. ' as info'
                );

            if ($request->sortBy) {
                $key = [
                    'name' => HrmHolidays::table. '.' .HrmHolidays::name,
                    'date'       => HrmHolidays::table. '.' .HrmHolidays::holiday_date,
                    'repeatYearly'       => HrmHolidays::table. '.' .HrmHolidays::is_repeat_yearly,
                    'restricted'       => HrmHolidays::table. '.' .HrmHolidays::is_restricted,
                    'info'       => HrmHolidays::table. '.' .HrmHolidays::description,
                ];

                if (!array_key_exists($request->sortBy, $key)) {
                    throw new \Exception('Invalid sort key', 422);
                }

                $request->sortBy = $key[$request->sortBy];
            } else {
                $holidaysQuery = $holidaysQuery->orderBy(HrmHolidays::table. '.' .HrmHolidays::CREATED_AT, 'desc');
            }

            $holidaysQuery = Utilities::sort($holidaysQuery);

//dd($holidaysQuery->toSql());
            $holidaysQueryCnt = $holidaysQuery->count();

            $holidaysQueryPaginated = $holidaysQuery
                ->skip(Utilities::getParams()['offset']) //$request['offset']
                ->take(Utilities::getParams()['perPage']) //$request['perPage']
                ->get();

            $holidaysQueryPaginated->map(function ($holidays) {
                $holidays->restricted   = (bool) $holidays->restricted;
                $holidays->repeatYearly = (bool) $holidays->repeatYearly;
            });


            $paginatedData = Utilities::paginate($holidaysQueryPaginated, Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $holidaysQueryCnt)->toArray();

            $responseArr = array(
                "holidays"  => $paginatedData['data'],
                "total"       => $paginatedData['total'],
                "to"          => $paginatedData['to'],
                "from"        => $paginatedData['from'],
                "currentPage" => $paginatedData['current_page']
            );

            return $this->content = array(
                'data'   => $responseArr,
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );

        } catch (QueryException $e) {
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
    }

    public function createAbsent(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->createAbsentFactory($request);

            if ($request->action == 'create') {
                $this->content = array(
                    'data'   => array('msg' => 'Absent added successfully'),
                    'code'   => Response::HTTP_CREATED,
                    'status' => ResponseStatus::OK
                );
            } else if ($request->action == 'update') {
                $this->content = array(
                    'data'   => array('msg' => 'Absent updated successfully'),
                    'code'   => Response::HTTP_OK,
                    'status' => ResponseStatus::OK
                );
            } else if ($request->action == 'remove') {
                $this->content = array(
                    'data'   => array('msg' => 'Absent deleted successfully'),
                    'code'   => Response::HTTP_OK,
                    'status' => ResponseStatus::OK
                );
            }

            DB::commit();
            return $this->content;

        } catch (QueryException $e) {
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
    }

    public function createOrCancelLeaveRequest(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->leaveRequestFactory($request);
            if ($request->action == 'create') {
                $this->content = array(
                    'data'   => array('msg' => 'Leave request is sent'),
                    'code'   => Response::HTTP_CREATED,
                    'status' => ResponseStatus::OK
                );

            } else if ($request->action == 'cancel') {
                $this->content = array(
                    'data'   => array('msg' => 'Leave request is cancelled'),
                    'code'   => Response::HTTP_OK,
                    'status' => ResponseStatus::OK
                );

            }
            DB::commit();
            return $this->content;

        } catch (QueryException $e) {
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
    }

    /**
     * @param $request
     * @throws \Exception
     */
    public function createAbsentFactory($request)
    {

        if (!in_array($request->action, ['create', 'update', 'remove'])) {
            throw new \Exception("invalid action");
        }

        if ($request->action == 'create') {
            $user  = Auth::user();
            $org   = DB::table(Organization::table)->select('id')->where(Organization::slug, $request->orgSlug)
                ->first();

            $absentUser = DB::table(User::table)->select('id')->where(User::slug, $request->absentUser)
                ->first();

            $absentType = DB::table(HrmLeaveType::table)->select('id')->where(HrmLeaveType::slug, $request->absentTypeSlug)
                ->first();

            if (!$org) {
                throw new \Exception("Organization is invalid");
            }

            if (!$absentUser) {
                throw new \Exception("Absent User is invalid");
            }

            if (!$absentType) {
                throw new \Exception("invalid Absent Type ");
            }

            $absentStartDate = Carbon::createFromTimestampUTC($request->absentStartsOn);
            $absentEndDate   = Carbon::createFromTimestampUTC($request->absentEndsOn);

            if ($absentEndDate->lessThan($absentStartDate)) {
                throw new \Exception("Ends on cannot be less than Starts On date");
            }

            $diffDays = $absentEndDate->diffInDays($absentStartDate);
            $diffDays = $diffDays + 1;

            if ($request->startsOnHalfDay === true && $request->endsOnHalfDay === true) {
            } else if ($request->startsOnHalfDay === true || $request->endsOnHalfDay === true) {
                $diffDays = abs($diffDays - 0.5);
            }

            $absence     = new HrmAbsence;
            $absence->{HrmAbsence::slug}     = Utilities::getUniqueId();
            $absence->{HrmAbsence::org_id}   = $org->id;
            $absence->{HrmAbsence::user_id}  = $absentUser->id;

            $absentCount = new HrmAbsentCount();

            $absence->{HrmAbsence::absent_start_date_time}  = $absentStartDate->toDateString();
            $absence->{HrmAbsence::absent_end_date_time}    = $absentEndDate->toDateString();
            $absence->{HrmAbsence::is_starts_on_halfday}    = $request->startsOnHalfDay;
            $absence->{HrmAbsence::is_ends_on_halfday}    = $request->endsOnHalfDay;
            $absence->{HrmAbsence::leave_type_id}    = $absentType->id;
            $absence->{HrmAbsence::reason}    = $request->reason;
            $absence->save();


            $absentCount->{HrmAbsentCount::absence_id}    = $absence->id;
            $absentCount->{HrmAbsentCount::leave_type_id} = $absentType->id;
            $absentCount->{HrmAbsentCount::absent_days}   = $diffDays;
            $absentCount->save();
        } else if ($request->action == 'update') {
            $user  = Auth::user();
            $org   = DB::table(Organization::table)->select('id')->where(Organization::slug, $request->orgSlug)
                ->first();

            $absentUser = DB::table(User::table)->select('id')->where(User::slug, $request->absentUser)
                ->first();

            $absentType = DB::table(HrmLeaveType::table)->select('id')->where(HrmLeaveType::slug, $request->absentTypeSlug)
                ->first();

            if (!$org) {
                throw new \Exception("Organization is invalid");
            }

            if (!$absentUser) {
                throw new \Exception("Absent User is invalid");
            }

            if (!$absentType) {
                throw new \Exception("invalid Absent Type ");
            }

            $absentStartDate = Carbon::createFromTimestampUTC($request->absentStartsOn);
            $absentEndDate   = Carbon::createFromTimestampUTC($request->absentEndsOn);

            if ($absentEndDate->lessThan($absentStartDate)) {
                throw new \Exception("Ends on cannot be less than Starts On date");
            }

            $diffDays = $absentEndDate->diffInDays($absentStartDate);
            $diffDays = $diffDays + 1;

            if ($request->startsOnHalfDay === true && $request->endsOnHalfDay === true) {
            } else if ($request->startsOnHalfDay === true || $request->endsOnHalfDay === true) {
                $diffDays = abs($diffDays - 0.5);
            }

            if (empty($request->absentSlug)) {
                throw new \Exception("Error in absent update");
            }

            $absence = HrmAbsence::where(HrmAbsence::slug, $request->absentSlug)->first();
            if (empty($absence)) {
                throw new \Exception("Error in absent slug");
            }

            //DB::enableQueryLog();

            $absentCount = HrmAbsentCount::where(HrmAbsentCount::absence_id, $absence->id)
                ->where(HrmAbsentCount::leave_type_id, $absence->{HrmAbsence::leave_type_id})
                ->first();

            //dd(DB::getQueryLog());
            if (empty($absentCount)) {
                throw new \Exception("Error in leave type slug");
            }

            $absence->{HrmAbsence::absent_start_date_time}  = $absentStartDate->toDateString();
            $absence->{HrmAbsence::absent_end_date_time}    = $absentEndDate->toDateString();
            $absence->{HrmAbsence::is_starts_on_halfday}    = $request->startsOnHalfDay;
            $absence->{HrmAbsence::is_ends_on_halfday}    = $request->endsOnHalfDay;
            $absence->{HrmAbsence::leave_type_id}    = $absentType->id;
            $absence->{HrmAbsence::reason}    = $request->reason;
            $absence->save();


            $absentCount->{HrmAbsentCount::absence_id}    = $absence->id;
            $absentCount->{HrmAbsentCount::leave_type_id} = $absentType->id;
            $absentCount->{HrmAbsentCount::absent_days}   = $diffDays;
            $absentCount->save();
        } else if ($request->action == 'remove') {
            $absence = HrmAbsence::where(HrmAbsence::slug, $request->absentSlug)->first();
            if (empty($absence)) {
                throw new \Exception("Error in absent slug");
            }
            $absence->delete();
        }

    }

    public function leaveRequestFactory($request)
    {
        if (!in_array($request->action, ['create', 'cancel'])) {
            throw new \Exception("invalid action");
        }

        $loggedUser  = Auth::user();

        if ($request->action == 'create') {
            $org   = DB::table(Organization::table)->select('id')->where(Organization::slug, $request->orgSlug)
                ->first();

            $requestToUser = DB::table(OrgEmployee::table)->join(User::table, OrgEmployee::table. '.' .OrgEmployee::user_id, '=',
                User::table. '.id')
                ->select(User::table. '.id as id')
                ->where(OrgEmployee::table. '.' .OrgEmployee::slug, $request->requestTo)
                ->first();

            /*$requestToUser = DB::table(User::table)->select('id')->where(User::slug, $request->requestTo)
                ->first();*/

            $leaveType = DB::table(HrmLeaveType::table)->select('id')->where(HrmLeaveType::slug, $request->leaveTypeSlug)
                ->first();

            if (!$org) {
                throw new \Exception("Organization is invalid");
            }

            if (!$requestToUser) {
                throw new \Exception("Request to user is invalid");
            }

            if (!$leaveType) {
                throw new \Exception("invalid Leave Type");
            }

            $requestStartDate = Carbon::createFromTimestampUTC($request->requestStartsOn);
            $requestEndsDate  = Carbon::createFromTimestampUTC($request->requestEndsOn);

            if ($requestEndsDate->lessThan($requestStartDate)) {
                throw new \Exception("Ends on cannot be less than Starts On date");
            }

            $leaveRequest = new HrmLeaveRequests();
            $leaveRequest->{HrmLeaveRequests::slug} = Utilities::getUniqueId();
            $leaveRequest->{HrmLeaveRequests::org_id} = $org->id;
            $leaveRequest->{HrmLeaveRequests::requesting_user_id} = $loggedUser->id;
            $leaveRequest->{HrmLeaveRequests::request_to_user_id} = $requestToUser->id;
            $leaveRequest->{HrmLeaveRequests::request_leave_start_date} = $requestStartDate;
            $leaveRequest->{HrmLeaveRequests::request_leave_end_date} = $requestEndsDate;
            $leaveRequest->{HrmLeaveRequests::is_request_leave_start_halfday} = $request->isRequestStartsOnhalfday;
            $leaveRequest->{HrmLeaveRequests::is_request_leave_ends_halfday} = $request->isRequestEndsOnhalfday;
            $leaveRequest->{HrmLeaveRequests::leave_type_id} = $leaveType->id;
            if ($request->reason)
                $leaveRequest->{HrmLeaveRequests::reason} = $request->reason;
            $leaveRequest->save();

        } else if ($request->action == 'cancel') {
            if (!$request->leaveRequestSlug) {
                throw new \Exception("invalid Leave request slug!");
            }

            $leaveRequest = HrmLeaveRequests::where(HrmLeaveRequests::slug, $request->leaveRequestSlug)->first();

            if (!$leaveRequest) {
                throw new \Exception("invalid Leave request!");
            }

            $leaveRequest->{HrmLeaveRequests::is_cancelled} = true;
            $leaveRequest->save();
        }


    }

    public function fetchAllLeaveRequest(Request $request)
    {
        $loggedUserId = Auth::id();

        try {
            //DB::enableQueryLog();
            $orgEmployeeisHead = DB::table(OrgEmployee::table)->join(OrgEmployeeDepartment::table,
                OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::org_employee_id, '=', OrgEmployee::table. '.id')
                ->where(function ($query) use ($loggedUserId) {
                    $query->where(OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::is_head,  true)
                        ->where(OrgEmployee::table. '.' .OrgEmployee::user_id,  $loggedUserId);
                })->orWhere(function ($query) use ($loggedUserId) {
                    $query->where(OrgEmployee::table. '.' .OrgEmployee::reporting_manager_id,  $loggedUserId);
                })->exists();
            //dd(DB::getQueryLog());

            $leaveRequestsQuery = DB::table(HrmLeaveRequests::table)
                ->select(
                    HrmLeaveRequests::table. '.' .HrmLeaveRequests::slug. ' as leaveRequestSlug',
                    HrmLeaveRequests::table. '.' .HrmLeaveRequests::reason. ' as reason',
                    DB::raw("unix_timestamp(".HrmLeaveRequests::table . '.'.HrmLeaveRequests::request_leave_start_date.") AS dateFrom"),
                    DB::raw("unix_timestamp(".HrmLeaveRequests::table . '.'.HrmLeaveRequests::request_leave_end_date.") AS dateTo"),
                    HrmLeaveType::table. '.' .HrmLeaveType::name. ' as leaveType',
                    HrmLeaveType::table. '.' .HrmLeaveType::color_code. ' as colorCode',
                    HrmLeaveRequests::table. '.' .HrmLeaveRequests::is_request_leave_start_halfday. ' as isLeaveStartHalfDay',
                    HrmLeaveRequests::table. '.' .HrmLeaveRequests::is_request_leave_ends_halfday. ' as isLeaveEndHalfDay',
                    User::table. '.' .User::name. ' as userName',
                    DB::raw('concat("'.$this->s3BasePath.'",'. UserProfile::table. '.' . UserProfile::image_path .') as userImage')
                );

            if ($request->tab == 'leaveRequest' || empty($request->tab)) {

                $leaveRequestsQuery->join(HrmLeaveType::table, HrmLeaveType::table. '.id', '=',
                    HrmLeaveRequests::table. '.' .HrmLeaveRequests::leave_type_id)
                    ->join(User::table, User::table. '.id', '=',
                        HrmLeaveRequests::table. '.' .HrmLeaveRequests::request_to_user_id)
                    ->leftjoin(UserProfile::table, UserProfile::table. '.' .UserProfile::user_id, '=',
                        HrmLeaveRequests::table. '.' .HrmLeaveRequests::request_to_user_id)
                    ->where(HrmLeaveRequests::table. '.' .HrmLeaveRequests::requesting_user_id, $loggedUserId);

            } else if ($request->tab == 'forApproval') {
                $leaveRequestsQuery->join(HrmLeaveType::table, HrmLeaveType::table. '.id', '=',
                    HrmLeaveRequests::table. '.' .HrmLeaveRequests::leave_type_id)
                    ->join(User::table, User::table. '.id', '=',
                        HrmLeaveRequests::table. '.' .HrmLeaveRequests::requesting_user_id)
                    ->leftjoin(UserProfile::table, UserProfile::table. '.' .UserProfile::user_id, '=',
                        HrmLeaveRequests::table. '.' .HrmLeaveRequests::requesting_user_id)
                    ->where(HrmLeaveRequests::table. '.' .HrmLeaveRequests::request_to_user_id, $loggedUserId);
            }

            $leaveRequestsQuery
                ->where(HrmLeaveRequests::table. '.' .HrmLeaveRequests::is_cancelled, false)
                ->where(HrmLeaveRequests::table. '.' .HrmLeaveRequests::is_approved, false);


            if ($request->sortBy) {
                $key = [
                    'dateFrom' => HrmLeaveRequests::table. '.' .HrmLeaveRequests::request_leave_start_date,
                    'dateTo' => HrmLeaveRequests::table. '.' .HrmLeaveRequests::request_leave_end_date,
                    'type'       => HrmLeaveType::table. '.' .HrmLeaveType::name
                ];

                if (!array_key_exists($request->sortBy, $key)) {
                    throw new \Exception('Invalid sort key', 422);
                }

                $request->sortBy = $key[$request->sortBy];
            } else {
                $leaveRequestsQuery = $leaveRequestsQuery->orderBy(HrmLeaveRequests::table. '.' .Organization::CREATED_AT, 'desc');
            }

            $leaveRequestsQuery = Utilities::sort($leaveRequestsQuery);

            $leaveRequests    = $leaveRequestsQuery->get();
            $leaveRequestsCnt = $leaveRequestsQuery->count();

            $leaveRequests->transform(function ($item) use ($orgEmployeeisHead, $request) {
                $item = (array) $item;
                $item['isLeaveStartHalfDay'] = (bool) $item['isLeaveStartHalfDay'];
                $item['isLeaveEndHalfDay']   = (bool) $item['isLeaveEndHalfDay'];
                $item['isApproveBtn']   = ($request->tab && $request->tab == 'forApproval') ? true : false;
                return $item;
            });

            $response = Utilities::paginate($leaveRequests,
                Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $leaveRequestsCnt
            );



            $response = $response->toArray();

            $response['leaveRequests'] =  $response['data'];
            $response         =  Utilities::unsetResponseData($response);
            return $this->content = array(
                'data'   => $response,
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );
        } catch (QueryException $e) {
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
    }

    public function approveLeaveRequest(Request $request)
    {
        try {
            DB::beginTransaction();
            $leaveRequest = HrmLeaveRequests::where(HrmLeaveRequests::slug, $request->leaveRequestSlug)->first();


            if (!$leaveRequest) {
                throw new \Exception("Invalid leave request.");
            }

            $leaveRequest->{HrmLeaveRequests::is_approved} = true;
            $leaveRequest->save();

            $leaveEndDate   = Carbon::parse($leaveRequest->{HrmLeaveRequests::request_leave_end_date});
            $leaveStartDate = Carbon::parse($leaveRequest->{HrmLeaveRequests::request_leave_start_date});
            $startsOnHalfDay = $leaveRequest->{HrmLeaveRequests::is_request_leave_start_halfday};
            $endsOnHalfDay   = $leaveRequest->{HrmLeaveRequests::is_request_leave_ends_halfday};

            //dd(Carbon::parse($leaveRequest->{HrmLeaveRequests::request_leave_start_date})->toDateString());
            $absence = new HrmAbsence;
            $absence->{HrmAbsence::slug}     = Utilities::getUniqueId();
            $absence->{HrmAbsence::org_id}   = $leaveRequest->{HrmLeaveRequests::org_id};
            $absence->{HrmAbsence::user_id}  = $leaveRequest->{HrmLeaveRequests::requesting_user_id};
            $absence->{HrmAbsence::absent_start_date_time}  = $leaveStartDate->toDateString();
            $absence->{HrmAbsence::absent_end_date_time}    = $leaveEndDate->toDateString();
            $absence->{HrmAbsence::is_starts_on_halfday}    = $startsOnHalfDay;
            $absence->{HrmAbsence::is_ends_on_halfday}      = $endsOnHalfDay;
            $absence->{HrmAbsence::leave_type_id}    = $leaveRequest->{HrmLeaveRequests::leave_type_id};

            if ($leaveRequest->{HrmLeaveRequests::reason})
                $absence->{HrmAbsence::reason}    = $leaveRequest->{HrmLeaveRequests::reason};

            $absence->save();


            $diffDays = $leaveEndDate->diffInDays($leaveStartDate);
            $diffDays = $diffDays + 1;

            if ($startsOnHalfDay === true && $endsOnHalfDay === true) {
            } else if ($startsOnHalfDay === true || $endsOnHalfDay === true) {
                $diffDays-= 0.5;
            }

            $absentCount = new HrmAbsentCount();
            $absentCount->{HrmAbsentCount::absence_id}    = $absence->id;
            $absentCount->{HrmAbsentCount::leave_type_id} = $leaveRequest->{HrmLeaveRequests::leave_type_id};
            $absentCount->{HrmAbsentCount::absent_days}   = $diffDays;
            $absentCount->save();

            DB::commit();

            return $this->content = array(
                'data'   => array('msg' => 'Leave request is approved'),
                'code'   => 201,
                'status' => ResponseStatus::OK
            );

        } catch (QueryException $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }
    }

    /**
     * Absent Chart -  departmentwise fiter pending
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function absentChart(Request $request)
    {
        $org   = DB::table(Organization::table)->select('id')->where(Organization::slug, $request->orgSlug)
            ->first();

        $month = $request->month;
        $year  = $request->year;

        if (!$org) {
            throw new \Exception("Invalid organization");
        }

        $departmentSlug = null;
        $leaveTypeSlugs = null;

        if ($request->has('filter') && $request->filter) {
            $departmentSlug = $request->filter['departmentSlug'];
            $leaveTypeSlugs = $request->filter['leaveTypeSlugs'];
        }

        $departments = OrgDepartment::select(
            OrgDepartment::table. '.' . OrgDepartment::slug,
            OrgDepartment::table. '.' .OrgDepartment::name. ' as departmentName')
            ->where(OrgDepartment::table. '.' .OrgDepartment::org_id, $org->id)
            ->join(OrgEmployeeDepartment::table, OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::org_department_id,
                '=', OrgDepartment::table. '.id')
            ->join(OrgEmployee::table, OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::org_employee_id,
                '=', OrgEmployee::table. '.id')
            ->groupBy(OrgDepartment::table. '.' . OrgDepartment::slug);

        //filter by department slug
        if ($departmentSlug) {
            $departments->where(OrgDepartment::table. '.' .OrgDepartment::slug, $departmentSlug);
        }

        $departmentCount = $departments->count();
        $departments = $departments->skip(Utilities::getParams()['offset'])
            ->take(Utilities::getParams()['perPage'])
            ->get();

        $departmentUsers = OrgDepartment::where(OrgDepartment::table. '.' .OrgDepartment::org_id, $org->id)
            ->join(OrgEmployeeDepartment::table, OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::org_department_id,
                '=', OrgDepartment::table. '.id')
            ->join(OrgEmployee::table, OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::org_employee_id,
                '=', OrgEmployee::table. '.id')
            ->leftjoin(User::table, OrgEmployee::table. '.' .OrgEmployee::user_id,
                '=', User::table. '.id')
            ->leftjoin(UserProfile::table, UserProfile::table. '.' .UserProfile::user_id,
                '=', User::table. '.id')
            ->select(
                User::table. '.id',
                User::table. '.' . User::name. ' as userName',
                User::table. '.' . User::slug. ' as userSlug',
                DB::raw('concat("'.$this->s3BasePath.'",'. UserProfile::table. '.' . UserProfile::image_path .') as userImage'),
                OrgDepartment::table. '.' .OrgDepartment::slug.' as orgDepartmentSlug',
                OrgDepartment::table. '.' .OrgDepartment::name.' as departmentName');
        $departmentUsers = $departmentUsers->get();
        $userArr     = $departmentUsers->pluck('id');


        $hrmAbsence = DB::table(HrmAbsence::table)
            ->join(HrmLeaveType::table, HrmLeaveType::table. '.id', '=', HrmAbsence::table. '.' .HrmAbsence::leave_type_id)
            ->join(User::table, User::table. '.id', '=', HrmAbsence::table. '.' .HrmAbsence::user_id)
            ->leftjoin(HrmAbsentCount::table, HrmAbsence::table. '.id', '=', HrmAbsentCount::table. '.' .HrmAbsentCount::absence_id)
            ->select(
                DB::raw("unix_timestamp(". HrmAbsence::table. '.' .HrmAbsence::absent_start_date_time.") as leaveFrom"),
                DB::raw("unix_timestamp(". HrmAbsence::table. '.' .HrmAbsence::absent_end_date_time.") as leaveTo"),
                HrmAbsence::table. '.' .HrmAbsence::absent_start_date_time. ' as leaveFromDateStr',
                HrmAbsence::table. '.' .HrmAbsence::absent_end_date_time. ' as leaveToDateStr',
                DB::raw("day(". HrmAbsence::table. '.' .HrmAbsence::absent_start_date_time.") as leaveFromDay"),
                DB::raw("day(". HrmAbsence::table. '.' .HrmAbsence::absent_end_date_time.") as leaveToDay"),
                DB::raw("PERIOD_DIFF(DATE_FORMAT(". HrmAbsence::table. '.' .HrmAbsence::absent_end_date_time .", '%Y%m'), DATE_FORMAT(". HrmAbsence::table. '.' .HrmAbsence::absent_start_date_time .", '%Y%m')) as monthcross"),
                DB::raw("month(". HrmAbsence::table. '.' .HrmAbsence::absent_start_date_time .") - ". $month ." as monthDiff"),
                HrmAbsence::table. '.' .HrmAbsence::slug.  ' as absenceSlug',
                HrmAbsence::table. '.' .HrmAbsence::is_starts_on_halfday.  ' as isfromHalfDay',
                HrmAbsence::table. '.' .HrmAbsence::is_ends_on_halfday.  ' as isToHalfDay',
                HrmAbsence::table. '.' .HrmAbsence::reason,
                HrmLeaveType::table. '.' .HrmLeaveType::color_code. '  as colorCode',
                HrmLeaveType::table. '.' .HrmLeaveType::name. ' as leaveTypeName',
                User::table. '.' .User::slug. ' as userSlug',
                User::table. '.' .User::name. ' as userName',
                HrmAbsentCount::table. '.' .HrmAbsentCount::absent_days. ' as totalAbsentDays'
            )
            ->where(HrmAbsence::table. '.' .HrmAbsence::org_id, $org->id)
            ->whereIn(HrmAbsence::table. '.' .HrmAbsence::user_id, $userArr)
            ->where(function ($query) use ($month, $year) {
                $query->where(function ($query) use ($month, $year) {
                    $query->whereMonth(HrmAbsence::table. '.' .HrmAbsence::absent_start_date_time, $month)
                        ->whereYear(HrmAbsence::table. '.' .HrmAbsence::absent_start_date_time, $year);
                })
                ->orWhere(function ($query) use ($month, $year) {
                    $query->whereMonth(HrmAbsence::table. '.' .HrmAbsence::absent_end_date_time, $month)
                        ->whereYear(HrmAbsence::table. '.' .HrmAbsence::absent_end_date_time, $year);
                });
            });


        if ($leaveTypeSlugs) {
            $hrmAbsence = $hrmAbsence->whereIn(HrmLeaveType::table. '.' .HrmLeaveType::slug, $leaveTypeSlugs);
        }

        //DB::enableQueryLog();
        $hrmAbsence = $hrmAbsence->get();
        //dd(DB::getQueryLog());
        //dd($hrmAbsence);
        $hrmAbsenceUserGrp = $hrmAbsence->groupBy('userSlug')->toArray();
        $totDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $departmentUsers = $departmentUsers->toArray();
        $daysArr = $daysResetArr = $this->getDayArrays($month, $year);

        foreach ($departmentUsers as &$user) {
            unset($user['id']);
            $user['leaveReport'] = $daysArr;
            if (isset($hrmAbsenceUserGrp[$user['userSlug']])) {
                foreach($hrmAbsenceUserGrp[$user['userSlug']] as $leave) {

                    $leaveFromDay = $leave->leaveFromDay;
                    $currentMonthLeave = $leave->totalAbsentDays;

                    $monthDiff = false;
                    /**
                     * not in the start month
                     *
                     */
                    if ($leave->monthDiff != 0) {
                        $leaveFromDay = 1;
                        $user['leaveReport'][$leaveFromDay]['plotLeave'] = (array) $user['leaveReport'][$leaveFromDay]['plotLeave'];
                        $user['leaveReport'][$leaveFromDay]['plotLeave']['leaveFrom'] = 1;
                        $user['leaveReport'][$leaveFromDay]['plotLeave']['leaveTo'] = $leave->leaveToDay;
                        //$currentMonthLeave = ($totDaysInMonth - $leaveFromDay);
                        $monthDiff = true;
                    }

                    // absent start date on end of first month and end date is on the next month, means absent is crossing two months
                    if ($leave->monthcross != 0) {
                        //dd($leave);
                        if ($monthDiff) $currentMonthLeave = ($leave->leaveToDay - $leaveFromDay) + 1;
                        else $currentMonthLeave = ($totDaysInMonth - $leaveFromDay) + 1;
                    }

                    $user['leaveReport'][$leaveFromDay]['absenceSlug'] = $leave->absenceSlug;
                    $user['leaveReport'][$leaveFromDay]['leaveFrom'] = $leave->leaveFrom;
                    $user['leaveReport'][$leaveFromDay]['leaveTo'] = $leave->leaveTo;
                    $user['leaveReport'][$leaveFromDay]['leaveFromDateStr'] = $leave->leaveFromDateStr;
                    $user['leaveReport'][$leaveFromDay]['leaveToDateStr'] = $leave->leaveToDateStr;
                    $user['leaveReport'][$leaveFromDay]['leaveFromDayStr'] = $leave->leaveFromDay;
                    $user['leaveReport'][$leaveFromDay]['leaveToDayStr'] = $leave->leaveToDay;
                    $user['leaveReport'][$leaveFromDay]['leaveFromIsHalfDay'] = (bool) $leave->isfromHalfDay;
                    $user['leaveReport'][$leaveFromDay]['leaveToIsHalfDay'] = (bool) $leave->isToHalfDay;
                    $user['leaveReport'][$leaveFromDay]['leaveType'] = $leave->leaveTypeName;
                    $user['leaveReport'][$leaveFromDay]['leaveTypeColorCode'] = $leave->colorCode;
                    $user['leaveReport'][$leaveFromDay]['leaveReason'] = $leave->reason;
                    $user['leaveReport'][$leaveFromDay]['totalLeaveDays'] = $leave->totalAbsentDays;
                    $user['leaveReport'][$leaveFromDay]['currentMonthLeaveDays'] = $currentMonthLeave;
                }
            }
            $user['leaveReport'] = array_values($user['leaveReport']);
        }

        $departmentUserGroup = collect($departmentUsers)->groupBy('orgDepartmentSlug')->toArray();


        $departments->map(function ($department) use ($departmentUserGroup) {
            $department->users = isset($departmentUserGroup[$department->{OrgDepartment::slug}]) ? $departmentUserGroup[$department->{OrgDepartment::slug}] : [];
        });

        $response = Utilities::paginate($departments,
            Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $departmentCount
        );
        $response = $response->toArray();

        $response['absenceChart'] =  $response['data'];
        $response         =  Utilities::unsetResponseData($response);
        return $this->content = array(
            'data'   => $response,
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );

    }

    public function getDayArrays($month, $year)
    {
        $totDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $daysArr = [];
        for ($i = 1 ; $i<=$totDaysInMonth; $i++) {
            $daysArr[$i] = array(
                'day' => $i,
                'leaveFrom' => null,
                'leaveTo' => null,
                'leaveFromDateStr' => null,
                'leaveToDateStr' => null,
                'leaveFromDayStr' => null,
                'leaveToDayStr' => null,
                'leaveFromIsHalfDay' => false,
                'leaveToIsHalfDay' => false,
                'leaveType' => null,
                'leaveTypeColorCode' => null,
                'leaveReason' => null,
                'totalLeaveDays' => null,
                'currentMonthLeaveDays' => null,
                'plotLeave' => new \stdClass()
            );
        }

        return $daysArr;
    }

    public function fetchUserLeaveTypes(Request $request)
    {

        try {
            $org   = DB::table(Organization::table)->select('id')->where(Organization::slug, $request->orgSlug)
                ->first();

            $absentUser = DB::table(User::table)->select('id')->where(User::slug, $request->absentUser)
                ->first();

            if (!$org) {
                throw new \Exception("Organization is invalid");
            }

            if (!$absentUser) {
                throw new \Exception("Absent User is invalid");
            }

            $hrmLeaveTypeUnionQuery = DB::table(HrmLeaveType::table)
                ->select(
                    HrmLeaveType::table. '.' .HrmLeaveType::name,
                    HrmLeaveType::table. '.' .HrmLeaveType::slug.' as leaveTypeSlug',
                    HrmLeaveType::table. '.' .HrmLeaveType::leave_count,
                    HrmAbsentCount::table. '.' .HrmAbsentCount::absent_days
                )
                ->leftjoin(HrmAbsentCount::table, HrmAbsentCount::table. '.' .HrmAbsentCount::leave_type_id, '=',
                    HrmLeaveType::table. '.id')
                ->where(HrmLeaveType::table. '.' .HrmLeaveType::org_id, $org->id)
                ->where(HrmLeaveType::table. '.' .HrmLeaveType::to_all_employee, true);

            $hrmLeaveTypesQuery = DB::table(HrmLeaveType::table)
                ->select(
                    HrmLeaveType::table. '.' .HrmLeaveType::name,
                    HrmLeaveType::table. '.' .HrmLeaveType::slug.' as leaveTypeSlug',
                    HrmLeaveType::table. '.' .HrmLeaveType::leave_count,
                    HrmAbsentCount::table. '.' .HrmAbsentCount::absent_days
                )
                ->join(HrmLeaveTypeUserMapping::table, HrmLeaveTypeUserMapping::table. '.' .HrmLeaveTypeUserMapping::leave_type_id, '=',
                    HrmLeaveType::table. '.id')
                ->leftjoin(HrmAbsentCount::table, HrmAbsentCount::table. '.' .HrmAbsentCount::leave_type_id, '=',
                    HrmLeaveType::table. '.id')
                ->union($hrmLeaveTypeUnionQuery)

                ->where(HrmLeaveTypeUserMapping::table. '.' .HrmLeaveTypeUserMapping::user_id, $absentUser->id)
                ->where(HrmLeaveType::table. '.' .HrmLeaveType::org_id, $org->id)
                ->get();
            $hrmLeaveTypesQuery->map(function ($leaveType) {
                $remaningDays = $leaveType->{HrmLeaveType::leave_count};
                if ($leaveType->{HrmAbsentCount::absent_days}) {
                    $remaningDays = $remaningDays - $leaveType->{HrmAbsentCount::absent_days};
                }
                $leaveType->remainingLeaveCount = $remaningDays;
            });

            return $this->content = array(
                'data'   => array('absentTypes' => $hrmLeaveTypesQuery),
                'code'   => 200,
                'status' => ResponseStatus::OK
            );
        } catch (QueryException $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

        dd($hrmLeaveTypesQuery);
    }

}