<?php

namespace Modules\TaskManagement\Repositories;



use Illuminate\Http\Request;
use Modules\TaskManagement\Http\Requests\createRequestBulkDelete;
use Modules\TaskManagement\Http\Requests\CreateTaskRequest;
use Modules\TaskManagement\Http\Requests\UpdateTaskRequest;
use Modules\TaskManagement\Http\Requests\UpdateTaskStatusRequest;

interface TaskRepositoryInterface
{
    public function createTask(CreateTaskRequest $request);
    public function updateTask(UpdateTaskRequest $request);
    public function getOrganizationUsers(Request $request);
    public function getParentTaskLists();
    public function deleteTask(Request $request);
    public function updateTaskStatus(UpdateTaskStatusRequest $request);
    public function getAllTaskStatus();
    public function TaskPartialUpdate(Request $request);
    public function taskBulkDelete(createRequestBulkDelete $request);
    public function addToArchive(Request $request);
    public function changeChecklistStatus(Request $request);
    public function favOrPriorityMultipleUpdate(Request $request);
}