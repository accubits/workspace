<?php

namespace Modules\HrmManagement\Repositories;

use Illuminate\Http\Request;

interface ReportInterface
{
    public function getWorkTimeReports(Request $request);
    public function getOneDayReport(Request $request);
    public function confirmDailyReport(Request $request);
    public function sendWorkReportToSupervisor(Request $request);
    public function getOneMonthWorkReport(Request $request);
    public function appyOrchangeScore(Request $request);
    public function getAllWorkReports(Request $request);
    public function fetchSingleAbsentDetails(Request $request);
    public function setWorkReportFrequency(Request $request);
    public function initiateWorkReportBeforeSubmit(Request $request);
    public function listAllTasksWorkReport(Request $request);
    public function listAllEventsWorkReport(Request $request);
    public function confirmWorkReport(Request $request);
}