<?php

namespace Modules\HrmManagement\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\HrmManagement\Entities\HrmWorkReport;
use Modules\HrmManagement\Entities\HrmWorkReportEvent;
use Modules\HrmManagement\Entities\HrmWorkReportScore;
use Modules\HrmManagement\Entities\HrmWorkReportTask;
use Modules\SocialModule\Entities\SocialEvent;
use Modules\TaskManagement\Entities\Task;
use Modules\TaskManagement\Entities\TaskScore;
use Modules\UserManagement\Entities\User;

class OneMonthWorReportResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $me = Auth::user();
        return [
            'reportPeriod' => $this->when($this->{HrmWorkReport::start_date}, function () {
                return [
                    'fromDate' => Carbon::parse($this->{HrmWorkReport::start_date})->timestamp,
                    'toDate' => Carbon::parse($this->{HrmWorkReport::end_date})->timestamp
                ];
            }),
            'reportFrom' => $this->when($this->reportFromUserName, function () {
                return [
                    'name' => $this->reportFromUserName,
                    'image' => $this->reportFromUserImg
                ];
            }),
            'reportTo' => $this->when($this->reportToUserName, function () {
                return [
                    'name' => $this->reportToUserName,
                    'image' => $this->reportToUserImg
                ];
            }),

            'reportTitle' => $this->{HrmWorkReport::title},

            'tasks' => $this->when($this->id, function () {
                return HrmWorkReportTask::join(Task::table, Task::table. '.id', '=', HrmWorkReportTask::task_id)
                    ->where(HrmWorkReportTask::table. '.' .HrmWorkReportTask::work_report_id, $this->id)
                    ->select(Task::table. '.' .Task::title. ' as title')
                    ->get()
                    ->toArray();
            },[]),

            'events' => $this->when($this->id, function () {
                return HrmWorkReportEvent::join(SocialEvent::table, SocialEvent::table. '.id', '=', HrmWorkReportEvent::event_id)
                    ->where(HrmWorkReportEvent::table. '.' .HrmWorkReportEvent::work_report_id, $this->id)
                    ->select(
                        SocialEvent::table. '.' .SocialEvent::event_slug. ' as eventSlug',
                        SocialEvent::table. '.' .SocialEvent::event_title. ' as eventTitle',
                        DB::raw("unix_timestamp(".SocialEvent::table . '.'.SocialEvent::event_start_date.") as eventStartDate"),
                        DB::raw("unix_timestamp(".SocialEvent::table . '.'.SocialEvent::event_end_date.") as eventEndDate"),
                        SocialEvent::table. '.' .SocialEvent::location
                    )
                    ->get()
                    ->toArray();
            },[]),

            'overallTaskScore' => $this->when($this->id, function () {
                return DB::table(TaskScore::table)->leftjoin(Task::table, TaskScore::table. '.id', '=' , Task::table. '.' .Task::task_score_id)
                    ->leftjoin(HrmWorkReportTask::table, Task::table. '.id', '=', HrmWorkReportTask::table. '.' .HrmWorkReportTask::task_id)
                    ->where(HrmWorkReportTask::table. '.' .HrmWorkReportTask::work_report_id, $this->id)
                    ->select(
                        TaskScore::table. '.' .TaskScore::score_display_name
                    )->groupBy( TaskScore::table. '.' .TaskScore::score_name)
                    ->orderBy(DB::raw("count(" . TaskScore::table. '.' .TaskScore::score_name .")"), 'desc')
                    ->pluck(TaskScore::score_display_name)
                    ->first();
            }, 'No Task Score'),
            'confirmButton' => (($this->supervisorId == $me->id) && (!$this->isReportConfirmed))? true : false,
            'isReportConfirmed' => (bool) $this->isReportConfirmed,
            'reportSlug' => $request->reportSlug
        ];
    }
}
