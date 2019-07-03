<?php

namespace Modules\TaskManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\TaskManagement\Http\Requests\CreateTaskFilterRequest;
use Modules\TaskManagement\Repositories\TaskFilterRepositoryInterface;

class TaskFilterController extends Controller
{

    protected $taskFilter;

    public function __construct(TaskFilterRepositoryInterface $taskFilter)
    {
        $this->taskFilter     = $taskFilter;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $response  = $this->taskFilter->listFilters($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateTaskFilterRequest $request)
    {
        $response  = $this->taskFilter->createFilter($request, 'create');
        return response()->json($response, $response['code']);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('taskmanagement::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request)
    {
        $response  = $this->taskFilter->editFilter($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $response  = $this->taskFilter->createFilter($request, 'update');
        return response()->json($response, $response['code']);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Request $request)
    {
        $response  = $this->taskFilter->deleteFilter($request);
        return response()->json($response, $response['code']);
    }
}
