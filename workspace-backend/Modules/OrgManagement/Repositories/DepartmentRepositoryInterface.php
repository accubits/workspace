<?php

namespace Modules\OrgManagement\Repositories;
use Illuminate\Http\Request;

interface DepartmentRepositoryInterface
{
    public function addDepartment(Request $request);
    public function updateDepartment(Request $request);
    public function deleteDepartment(Request $request);
    public function getDepartment(Request $request);
    public function listDepartment(Request $request);
    public function listDepartmentTree(Request $request);
    public function getAllDepartmentsTree(Request $request);
    public function setEmployeeToDepartment(Request $request);
    public function listDepartmentEmployees(Request $request);

}