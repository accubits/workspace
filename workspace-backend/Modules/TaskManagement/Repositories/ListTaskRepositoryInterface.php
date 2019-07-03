<?php

namespace Modules\TaskManagement\Repositories;


use GuzzleHttp\Psr7\Request;
use Modules\TaskManagement\Http\Requests\ListTaskRequest;

interface ListTaskRepositoryInterface
{
    public function taskListAll(ListTaskRequest $request);
    public function showTask($task);
    public function fetch(Request $request);
    public function getTemplates();
    public function getSubtaskFromTask(Request $request);
    public function taskHistory(Request $request);
}