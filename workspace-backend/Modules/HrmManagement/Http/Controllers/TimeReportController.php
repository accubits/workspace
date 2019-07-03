<?php

namespace Modules\HrmManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\HrmManagement\Http\Requests\ClockStatusRequest;
use Modules\HrmManagement\Repositories\TimeInterface;

class TimeReportController extends Controller
{
    public $time;

    public function __construct(TimeInterface $time)
    {
        $this->time = $time;
    }

    public function clockInOut(ClockStatusRequest $request)
    {
        $response = $this->time->logClockStatus($request);
        return response()->json($response, $response['code']);
    }

    /**
     * edit workday
     * @param Request $request
     * @return mixed
     */
    public function fetchWorkDay(Request $request)
    {
        $response = $this->time->fetchWorkDay($request);
        return response()->json($response, $response['code']);
    }

    public function saveWorkingDay(Request $request)
    {
        $response = $this->time->saveWorkDay($request);
        return response()->json($response, $response['code']);
    }

    public function getCurrentClockStatus(Request $request)
    {
        $response = $this->time->currentClockStatus($request);
        return response()->json($response, $response['code']);
    }

    public function clockOutPreviousDay(Request $request)
    {
        $response = $this->time->clockOutPreviousDay($request);
        return response()->json($response, $response['code']);
    }
}
