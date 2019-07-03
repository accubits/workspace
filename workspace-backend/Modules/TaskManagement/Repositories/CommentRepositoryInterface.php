<?php

namespace Modules\TaskManagement\Repositories;

use Illuminate\Http\Request;
use Modules\TaskManagement\Http\Requests\TaskCommentRequest;

interface CommentRepositoryInterface
{
    public function addComment(TaskCommentRequest $request);
    public function fetchComment(Request $request);
    public function updateComment(Request $request);
    public function taskCommentDelete(Request $request);
    public function like(Request $request);
    public function listAll($task);
}