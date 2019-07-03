<?php

namespace Modules\OrgManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\OrgManagement\Repositories\DepartmentRepositoryInterface;

class DepartmentController extends Controller
{

    private $departmentRepository;

    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;   
    }

    /**
     * get a resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $response = $this->departmentRepository->getDepartment($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * get listing of the resource.
     * @return Response
     */
    public function listDepartment(Request $request)
    {
        $response = $this->departmentRepository->listDepartment($request);
        return response()->json($response, $response['code']);
    }

    //listDepartmentTree
    public function listDepartmentTree(Request $request)
    {
        $response = $this->departmentRepository->listDepartmentTree($request);
        return response()->json($response, $response['code']);
    }
    
    //get all DepartmentTree
    public function getAllDepartmentsTree(Request $request)
    {
        $response = $this->departmentRepository->getAllDepartmentsTree($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $response = $this->departmentRepository->addDepartment($request);
        return response()->json($response, $response['code']);
    }


    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $response = $this->departmentRepository->updateDepartment($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Request $request)
    {
        $response = $this->departmentRepository->deleteDepartment($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * add employee to department
     * @return Response
     */
    public function setEmployeeToDepartment(Request $request)
    {
        $response = $this->departmentRepository->setEmployeeToDepartment($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * listDepartmentEmployees
     * @return Response
     */
    public function listDepartmentEmployees(Request $request)
    {
        $response = $this->departmentRepository->listDepartmentEmployees($request);
        return response()->json($response, $response['code']);
    }

}
