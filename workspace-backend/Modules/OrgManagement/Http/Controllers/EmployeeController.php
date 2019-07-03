<?php

namespace Modules\OrgManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\OrgManagement\Http\Requests\CreateEmployeeRequest;
use Modules\OrgManagement\Http\Requests\UpdateEmployeeRequest;
use Modules\OrgManagement\Repositories\EmployeeRepositoryInterface;


class EmployeeController extends Controller
{

    private $employee;

    public function __construct(EmployeeRepositoryInterface $employee)
    {
        $this->employee = $employee;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('orgmanagement::index');
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
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateEmployeeRequest $request)
    {
        $response  = $this->employee->addEmployee($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * 
     * @param  Request $request
     * @return Response
     */
    public function invite(Request $request)
    {
        $response  = $this->employee->inviteEmployee($request);
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
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateEmployeeRequest $request, $employee)
    {
        $request['employee_slug'] = $employee;
        $response = $this->employee->updateEmployee($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($employee)
    {
        $response = $this->employee->deleteEmployee($employee);
        return response()->json($response, $response['code']);
    }
    
    public function getEmployeeUsers(Request $request)
    {
        $response = $this->employee->getEmployeeUsers($request);
        return response()->json($response, $response['code']);
    }

    public function fetchEmployeeInfo(Request $request)
    {
        $response = $this->employee->fetchEmployeeInfo($request);
        return response()->json($response, $response['code']);
    }

    public function fetchEmployeeLeaveInfo(Request $request)
    {
        $response = $this->employee->fetchEmployeeLeaveInfo($request);
        return response()->json($response, $response['code']);
    }
}
