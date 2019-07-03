<?php

namespace Modules\OrgManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\OrgManagement\Repositories\EmployeePermissionRepositoryInterface;

class EmployeePermissionController extends Controller
{

    private $employee;

    public function __construct(EmployeePermissionRepositoryInterface $employee)
    {
        $this->employee = $employee;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($slug)
    {
        $response = $this->employee->getEmployeePermissions($slug);
        return response()->json($response, $response['code']);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('orgmanagement::create');
    }

    /**
     * assign new Permission
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request, $slug)
    {
        $request['employee_slug'] = $slug;
        $response = $this->employee->assignPermission($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('orgmanagement::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('orgmanagement::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
