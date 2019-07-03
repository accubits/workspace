<?php

namespace Modules\HrmManagement\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\HrmManagement\Entities\HrmClockEditHistory;
use Modules\HrmManagement\Entities\HrmClockMaster;
use Modules\OrgManagement\Entities\OrgDesignation;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\OrgManagement\Entities\OrgEmployeeDepartment;
use Modules\OrgManagement\Entities\OrgEmployeeDesignation;
use Modules\OrgManagement\Entities\OrgEmployeeDesignationDepartment;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;

class DailyReportResource extends Resource
{
    protected $s3BasePath;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $this->s3BasePath = env('S3_PATH');

        $reportUsers = $this->reportTo($request->departmentId);

        return [
            'date' => Carbon::parse($this->{HrmClockMaster::start_date})->timestamp,
            'reportFrom' => $this->when($this->{User::name}, function () {
                return [
                    'slug' => $this->userSlug,
                    'name' => $this->{User::name},
                    'image' => $this->userImage
                ];
            }, new \stdClass()),
            'reportTo' => $this->when($reportUsers, function () use ($reportUsers) {
                return [
                    'slug' => $reportUsers->{User::slug},
                    'name' => $reportUsers->{User::name},
                    'image' => $reportUsers->userImage,
                ];
            }, new \stdClass()),
            'duration' => $this->when($this->totalWorkingTime, function () {
                //$worktime = Carbon::parse($this->totalWorkingTime);
                return [
                    'hours'   => gmdate("H", $this->totalWorkingTime),
                    'minutes' => gmdate("i", $this->totalWorkingTime)
                ];
            }),
            'startedOn' => Carbon::parse($this->{HrmClockMaster::start_date})->timestamp,
            'changedTime' => $this->when($this->{HrmClockEditHistory::prev_start_date}, function () {
                return [
                    'changedFrom'=> ($this->{HrmClockEditHistory::prev_start_date}) ? Carbon::parse($this->{HrmClockEditHistory::prev_start_date})->timestamp : null,
                    'reason'  => ($this->{HrmClockEditHistory::note}) ? $this->{HrmClockEditHistory::note} : null
                ];
            },new \stdClass()),
            'endedOn' => Carbon::parse($this->{HrmClockMaster::stop_date})->timestamp,
            'isConfirmReportButton' => (($reportUsers) && Auth::user()->id == $reportUsers->id && !$this->isReportConfirmed) ? true : false,
            'isReportConfirmed' => (bool) $this->isReportConfirmed

        ];
    }

    public function reportTo($departmentId)
    {

        $reportingUser = DB::table(OrgEmployeeDepartment::table)
            ->select(
                User::table. '.' .User::name,
                User::table. '.' .User::slug,
                User::table. '.id',
                DB::raw('concat("'.$this->s3BasePath.'",'. UserProfile::table. '.' . UserProfile::image_path .') as userImage')
            )
            ->join(OrgEmployee::table, OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::org_employee_id, '=', OrgEmployee::table. '.id')
            ->join(User::table, OrgEmployee::table. '.' .OrgEmployee::user_id, '=', User::table. '.id')
            ->leftjoin(UserProfile::table, User::table. '.id', '=', UserProfile::table. '.' .UserProfile::user_id)
            ->where(OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::org_department_id, $departmentId)
            ->where(OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::is_head, true)->first();

        return $reportingUser;
    }
}
