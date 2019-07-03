<?php

namespace Modules\OrgManagement\Repositories;

use Illuminate\Http\Request;
use Modules\OrgManagement\Http\Requests\CreateEmployeeRequest;
use Modules\OrgManagement\Http\Requests\UpdateEmployeeRequest;

interface EmployeeRepositoryInterface
{
    public function addEmployee(CreateEmployeeRequest $request);
    public function inviteEmployee(Request $request);
    public function updateEmployee(Request $request);
    public function deleteEmployee($employee);
    public function getEmployeeUsers(Request $request);
    public function fetchEmployeeInfo(Request $request);
    public function fetchEmployeeLeaveInfo(Request $request);
}