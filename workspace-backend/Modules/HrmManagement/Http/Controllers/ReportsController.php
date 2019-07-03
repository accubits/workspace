<?php

namespace Modules\HrmManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\HrmManagement\Repositories\ReportInterface;

class ReportsController extends Controller
{
    private $report;

    public function __construct(ReportInterface $report)
    {
        $this->report = $report;
    }

    public function fetchWorkTimeReports(Request $request)
    {
        $response = $this->report->getWorkTimeReports($request);
        return response()->json($response, $response['code']);
    }

    /**
     * get report of a day
     * @param Request $request
     * @return mixed
     */
    public function fetchOneReport(Request $request)
    {
        $response = $this->report->getOneDayReport($request);
        return response()->json($response, $response['code']);
    }

    public function confirmDailyReport(Request $request)
    {
        $response = $this->report->confirmDailyReport($request);
        return response()->json($response, $response['code']);
    }

    /**
     * initiate workreport popup data
     * @param Request $request
     * @return mixed
     */
    public function initiateWorkReportBeforeSubmit(Request $request)
    {
        $response = $this->report->initiateWorkReportBeforeSubmit($request);
        return response()->json($response, $response['code']);
    }

    public function listAllTasksWorkReport(Request $request)
    {
        $response = $this->report->listAllTasksWorkReport($request);
        return response()->json($response, $response['code']);
    }

    public function listAllEventsWorkReport(Request $request)
    {
        $response = $this->report->listAllEventsWorkReport($request);
        return response()->json($response, $response['code']);
    }

    /**
     * send monthly work report to supervisor
     * @param Request $request
     * @return mixed
     */
    public function sendWorkReport(Request $request)
    {
        $response = $this->report->sendWorkReportToSupervisor($request);
        return response()->json($response, $response['code']);
    }

    /**
     * get WorkReport of a month
     * @return mixed
     */
    public function fetchWorkReport(Request $request)
    {
        $response = $this->report->getOneMonthWorkReport($request);
        return response()->json($response, $response['code']);
    }

    public function fetchSingleAbsentDetails(Request $request)
    {
        $response = $this->report->fetchSingleAbsentDetails($request);
        return response()->json($response, $response['code']);
    }

    public function confirmWorkReport(Request $request)
    {
        $response = $this->report->confirmWorkReport($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Add or Change Score by Supervisor
     * @param Request $request
     * @return mixed
     */
    public function addOrChangeScore(Request $request)
    {
        $response = $this->report->appyOrchangeScore($request);
        return response()->json($response, $response['code']);
    }

    /**
     * get All User work reports departmentwise
     * @param Request $request
     * @return mixed
     */
    public function fetchAllWorkReports(Request $request)
    {
        $response = $this->report->getAllWorkReports($request);
        return response()->json($response, $response['code']);
    }

    public function setWorkReportFrequency(Request $request)
    {
        $response = $this->report->setWorkReportFrequency($request);
        return response()->json($response, $response['code']);
    }

}
