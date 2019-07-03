<?php

namespace Modules\HrmManagement\Repositories;

use Illuminate\Http\Request;

interface LeaveInterface
{
    public function createLeaveType(Request $request);
    public function fetchAllLeaveTypes(Request $request);
    public function createHoliday(Request $request);
    public function fetchAllHolidays(Request $request);
    public function createAbsent(Request $request);
    public function createOrCancelLeaveRequest(Request $request);
    public function fetchAllLeaveRequest(Request $request);
    public function fetchUserLeaveTypes(Request $request);
    public function approveLeaveRequest(Request $request);
    public function absentChart(Request $request);
}