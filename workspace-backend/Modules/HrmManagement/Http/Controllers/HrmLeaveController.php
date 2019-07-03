<?php

namespace Modules\HrmManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\HrmManagement\Http\Requests\AddHolidayRequest;
use Modules\HrmManagement\Repositories\LeaveInterface;

class HrmLeaveController extends Controller
{

    public $leave;

    public function __construct(LeaveInterface $leave)
    {
        $this->leave = $leave;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('hrmmanagement::index');
    }


    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function createLeaveType(Request $request)
    {
        $response = $this->leave->createLeaveType($request);
        return response()->json($response, $response['code']);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function fetchAllLeaveTypes(Request $request)
    {
        $response = $this->leave->fetchAllLeaveTypes($request);
        return response()->json($response, $response['code']);
    }

    /**
     * @param AddHolidayRequest $request
     * @return mixed
     */
    public function createHoliday(AddHolidayRequest $request)
    {
        $response = $this->leave->createHoliday($request);
        return response()->json($response, $response['code']);
    }

    public function fetchAllHolidays(Request $request)
    {
        $response = $this->leave->fetchAllHolidays($request);
        return response()->json($response, $response['code']);
    }

    public function createAbsent(Request $request)
    {
        $response = $this->leave->createAbsent($request);
        return response()->json($response, $response['code']);
    }

    public function fetchUserLeaveTypes(Request $request)
    {
        $response = $this->leave->fetchUserLeaveTypes($request);
        return response()->json($response, $response['code']);
    }

    public function createOrCancelLeaveRequest(Request $request)
    {
        $response = $this->leave->createOrCancelLeaveRequest($request);
        return response()->json($response, $response['code']);
    }

    public function fetchAllLeaveRequest(Request $request)
    {
        $response = $this->leave->fetchAllLeaveRequest($request);
        return response()->json($response, $response['code']);
    }

    public function approveLeaveRequest(Request $request)
    {
        $response = $this->leave->approveLeaveRequest($request);
        return response()->json($response, $response['code']);
    }

    public function absentChart(Request $request)
    {
        $response = $this->leave->absentChart($request);
        return response()->json($response, $response['code']);
    }
}
