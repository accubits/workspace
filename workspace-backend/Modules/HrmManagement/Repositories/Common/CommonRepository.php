<?php
namespace Modules\HrmManagement\Repositories\Common;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\HrmManagement\Entities\HrmWorkReport;
use Modules\HrmManagement\Entities\HrmWorkReportFrequency;
use Modules\HrmManagement\Entities\HrmWorkReportSettings;
use Modules\HrmManagement\Repositories\CommonRepositoryInterface;
use Modules\OrgManagement\Entities\OrgEmployee;

class CommonRepository implements CommonRepositoryInterface
{
    public function __construct()
    {
    }

    /**
     * get workreport dates
     * @param $request
     * @param $loggeduser
     * @param $orgId
     * @return array
     */
    public function getWorkReportDates($request, $loggeduser, $orgId)
    {
        //latest report
        $workReport = DB::table(HrmWorkReport::table)
            ->where(HrmWorkReport::table. '.' .HrmWorkReport::creator_id, $loggeduser->id)
            ->where(HrmWorkReport::table. '.' .HrmWorkReport::org_id, $orgId->id)
            ->latest()
            ->first();

        //if report not set for a user then default select monthly
        /*$reportSettings = DB::table(HrmWorkReportSettings::table)
            ->join(HrmWorkReportFrequency::table, HrmWorkReportFrequency::table. '.id', '=', HrmWorkReportSettings::table. '.' .HrmWorkReportSettings::report_frequency_id)
            ->where(HrmWorkReportSettings::table. '.' .HrmWorkReportSettings::user_id, $loggeduser->id)
            ->where(HrmWorkReportSettings::table. '.' .HrmWorkReportSettings::org_id, $orgId->id)
            ->select(
                HrmWorkReportFrequency::table. '.' .HrmWorkReportFrequency::frequency_name,
                HrmWorkReportSettings::table. '.' .HrmWorkReportSettings::monthly_report_day,
                HrmWorkReportSettings::table. '.' .HrmWorkReportSettings::weekly_report_day
            )->first();*/

        //if report not set for a user then default select monthly
        /*if (empty($reportSettings)) {
            return $this->reportFrequencyMonthlyDatesArray($workReport, $reportSettings, );
        }*/

        //currently we set monthy as hardcoded value
        //$frequency = $reportSettings->{HrmWorkReportFrequency::frequency_name};
        $frequency = 'monthly';
        $reportSettings = null;

        if ($frequency == HrmWorkReportFrequency::daily) {
            return $this->reportFrequencyDailyDatesArray($workReport, $request);
        } else if ($frequency == HrmWorkReportFrequency::weekly) {
            return $this->reportFrequencyWeeklyDatesArray($workReport, $reportSettings);
        } else if ($frequency == HrmWorkReportFrequency::monthly) {
            return $this->reportFrequencyMonthlyDatesArray($workReport, $reportSettings, $loggeduser);
        }

    }

    private function reportFrequencyMonthlyDatesArray($workReport, $reportSettings, $loggeduser)
    {
        /*if (empty($reportSettings->{HrmWorkReportSettings::monthly_report_day})) {
            $monthyReportDate = ($workReport) ? Carbon::parse($workReport->{HrmWorkReport::end_date}) : null;
        } else {
            $monthyReportDate = Carbon::now()->day($reportSettings->{HrmWorkReportSettings::monthly_report_day});
        }

        if (empty($workReport)) {
            $startDate = Carbon::now()->startOfMonth();
            $endDate   = ($monthyReportDate) ? $monthyReportDate : Carbon::today();
        } else if ($workReport) {
            $currentDate    = Carbon::today();
            $nextReportDate = $monthyReportDate->addMonth(1);
            if ($currentDate->greaterThanOrEqualTo($nextReportDate)) {
                $startDate = Carbon::parse($workReport->{HrmWorkReport::end_date})->addDay(1);
                $endDate   = $nextReportDate;
            } else {
                $startDate = Carbon::parse($workReport->{HrmWorkReport::start_date});
                $endDate = Carbon::parse($workReport->{HrmWorkReport::end_date});
            }
        }*/

        $currentDate    = Carbon::today();

        // report added for the first time
        if (empty($workReport)) {
            $orgEmployee = DB::table(OrgEmployee::table)
                ->select(
                    OrgEmployee::joining_date. '  as joiningDate',
                    OrgEmployee::CREATED_AT. '  as createdDate'
                )
                ->where(OrgEmployee::user_id, $loggeduser->id)->first();
            if (empty($orgEmployee)) {
                throw new \Exception("Invalid Employee");
            }

            $joiningDate = Carbon::parse($orgEmployee->joiningDate);
            $joiningDateMonthEnd = $joiningDate->copy()->endOfMonth()->setTime(0, 0, 0);

            if ($currentDate->between($joiningDate, $joiningDateMonthEnd)) {
                $startDate = $joiningDate;
                $endDate   = $currentDate;
            } else {
                $startDate = $joiningDate;
                $endDate   = $joiningDateMonthEnd;
            }

        } else {
            $endDate = Carbon::parse($workReport->{HrmWorkReport::end_date});

            if ($currentDate->greaterThanOrEqualTo($endDate)) {
                if ($workReport->{HrmWorkReport::is_report_sent}) {
                    $originalEndDt  = $endDate;
                    $startDate = $endDate->copy()->addDay(1);
                    $endDate   = $startDate->copy()->endOfMonth()->setTime(0, 0, 0);


                    if ($currentDate->isSameMonth($endDate)) {
                        $startDate = ($currentDate->isSameMonth($originalEndDt))? Carbon::parse($workReport->{HrmWorkReport::start_date}) : $startDate;
                        $endDate   = $currentDate;
                    }

                } else {
                    $startDate = Carbon::parse($workReport->{HrmWorkReport::start_date});

                    if ($currentDate->isSameMonth($endDate)) {
                        $endDate   = $currentDate;
                    }
                }

            } else {
                $startDate = $endDate->copy()->addDay(1);
                $endDate   = $startDate->copy()->endOfMonth()->setTime(0, 0, 0);

                if ($currentDate->isSameMonth($endDate)) {
                    $endDate   = $currentDate;
                }
            }
        }

        return ['startDate' => $startDate->timestamp, 'endDate' => $endDate->timestamp];
    }

    public function workReportPrompt($request, $loggeduser, $orgId)
    {
        $workReport = DB::table(HrmWorkReport::table)
            ->select(
                HrmWorkReport::start_date. ' as startDate',
                HrmWorkReport::end_date. ' as endDate'
            )
            ->where(HrmWorkReport::table. '.' .HrmWorkReport::creator_id, $loggeduser->id)
            ->where(HrmWorkReport::table. '.' .HrmWorkReport::org_id, $orgId->id)
            ->latest()
            ->first();
        return $workReport;
    }

    /**
     * weekly Frequency
     * @param $workReport
     * @param $reportSettings
     * @return array
     */
    private function reportFrequencyWeeklyDatesArray($workReport, $reportSettings)
    {
        $latestWorkReportEndDate = Carbon::createFromTimeString($workReport->{HrmWorkReport::end_date});
        $startDate = $latestWorkReportEndDate;
        if ($reportSettings->{HrmWorkReportSettings::weekly_report_day} == 'sun') {
            $latestWorkReportEndDate->next(Carbon::SUNDAY);
        } else if ($reportSettings->{HrmWorkReportSettings::weekly_report_day} == 'mon') {
            $latestWorkReportEndDate->next(Carbon::MONDAY);
        } else if ($reportSettings->{HrmWorkReportSettings::weekly_report_day} == 'tue') {
            $latestWorkReportEndDate->next(Carbon::TUESDAY);
        } else if ($reportSettings->{HrmWorkReportSettings::weekly_report_day} == 'wed') {
            $latestWorkReportEndDate->next(Carbon::WEDNESDAY);
        } else if ($reportSettings->{HrmWorkReportSettings::weekly_report_day} == 'thu') {
            $latestWorkReportEndDate->next(Carbon::THURSDAY);
        } else if ($reportSettings->{HrmWorkReportSettings::weekly_report_day} == 'fri') {
            $latestWorkReportEndDate->next(Carbon::FRIDAY);
        } else if ($reportSettings->{HrmWorkReportSettings::weekly_report_day} == 'sat') {
            $latestWorkReportEndDate->next(Carbon::SATURDAY);
        }

        if ($latestWorkReportEndDate->greaterThan(now())) {
            $endDate   = Carbon::today();
        } else {
            $endDate   = $latestWorkReportEndDate;
        }

        return ['startDate' => $startDate->timestamp, 'endDate' => $startDate->timestamp];
    }

    /**
     * Daily Frequency
     * @param $workReport
     * @return array
     */
    private function reportFrequencyDailyDatesArray($workReport, $request)
    {
        if (empty($workReport)) {
            return ['startDate' => Carbon::today()->timestamp, 'endDate' => Carbon::today()->timestamp];
        }

        $latestWorkReportEndDate = Carbon::createFromTimeString($workReport->{HrmWorkReport::end_date});

        if ($request->action  && $request->action == 'reportPrompt') {
            return ['startDate' => $latestWorkReportEndDate->timestamp, 'endDate' => $latestWorkReportEndDate->timestamp];
        }

        if ($latestWorkReportEndDate->isToday()) {
            $startDate = $latestWorkReportEndDate;
        } else {
            $startDate = $latestWorkReportEndDate->addDay(1);
        }

        return ['startDate' => $startDate->timestamp, 'endDate' => $startDate->timestamp];
    }

}
