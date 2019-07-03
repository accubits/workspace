<?php

namespace Modules\OrgManagement\Repositories;

use Illuminate\Http\Request;

interface EmployeePermissionRepositoryInterface
{
    public function getEmployeePermissions($slug);
    public function assignPermission(Request $request);
}