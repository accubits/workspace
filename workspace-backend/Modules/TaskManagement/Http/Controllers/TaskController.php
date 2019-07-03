<?php

namespace Modules\TaskManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\TaskManagement\Http\Requests\createRequestBulkDelete;
use Modules\TaskManagement\Http\Requests\CreateTaskRequest;
use Modules\TaskManagement\Http\Requests\ListTaskRequest;
use Modules\TaskManagement\Http\Requests\UpdateTaskRequest;
use Modules\TaskManagement\Http\Requests\UpdateTaskStatusRequest;
use Modules\TaskManagement\Repositories\ListTaskRepositoryInterface;
use Modules\TaskManagement\Repositories\TaskRepositoryInterface;

class TaskController extends Controller
{
    private $task;
    private $listTask;
    public function __construct(TaskRepositoryInterface $task, ListTaskRepositoryInterface $listTask)
    {
        $this->task     = $task;
        $this->listTask = $listTask;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function fetchall(ListTaskRequest $request)
    {
        $response = $this->listTask->taskListAll($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateTaskRequest $request)
    {
        $response  = $this->task->createTask($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function edit($task)
    {
        $response  = $this->listTask->showTask($task);
        return response()->json($response, $response['code']);
    }

    /**
     * @return mixed
     */
    public function fetch(Request $request)
    {
        $response  = $this->listTask->fetch($request);
        return response()->json($response, $response['code']);
    }


    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateTaskRequest $request, $task)
    {
        $request['taskId'] = $task;
        $response  = $this->task->updateTask($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Update Task Status
     * @param UpdateTaskStatusRequest $request
     * @return mixed
     */
    public function updateTaskStatus(UpdateTaskStatusRequest $request)
    {
        $response  = $this->task->updateTaskStatus($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Request $task)
    {
        $response  = $this->task->deleteTask($task);
        return response()->json($response, $response['code']);
    }

    public function taskBulkDelete(createRequestBulkDelete $task)
    {
        $response  = $this->task->taskBulkDelete($task);
        return response()->json($response, $response['code']);
    }

    public function getOrgUsers(Request $request)
    {
        $response  = $this->task->getOrganizationUsers($request);
        return response()->json($response, $response['code']);
    }

    public function getTemplates()
    {
        $response  = $this->listTask->getTemplates();
        return response()->json($response, $response['code']);
    }

    public function getParentTasks()
    {
        $response  = $this->task->getParentTaskLists();
        return response()->json($response, $response['code']);
    }

    public function listTaskStatus()
    {
        $response  = $this->task->getAllTaskStatus();
        return response()->json($response, $response['code']);
    }

    public function getTaskSubtask(Request $request)
    {
        $response  = $this->listTask->getSubtaskFromTask($request);
        return response()->json($response, $response['code']);
    }

    public function getTaskHistory(Request $request)
    {
        $response  = $this->listTask->taskHistory($request);
        return response()->json($response, $response['code']);
    }

    /**
     * partial update for task
     * @param Request $request
     * @return mixed
     */
    public function taskPartialUpdate(Request $request)
    {
        $response  = $this->task->TaskPartialUpdate($request);
        return response()->json($response, $response['code']);
    }

    public function changeChecklistStatus(Request $request)
    {
        $response  = $this->task->changeChecklistStatus($request);
        return response()->json($response, $response['code']);
    }

    /**
     * move to archieve
     * @param Request $request
     * @return mixed
     */
    public function addToArchive(Request $request)
    {
        $response  = $this->task->addToArchive($request);
        return response()->json($response, $response['code']);
    }


    /**
     * favourite or priority multiple update
     * @return mixed
     */
    public function favOrPriorityMultipleUpdate(Request $request)
    {
        $response  = $this->task->favOrPriorityMultipleUpdate($request);
        return response()->json($response, $response['code']);
    }

}
