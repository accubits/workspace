<?php

namespace Modules\TaskManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\TaskManagement\Http\Requests\TaskCommentRequest;
use Modules\TaskManagement\Repositories\CommentRepositoryInterface;

class TaskCommentController extends Controller
{
    protected $comment;

    public function __construct(CommentRepositoryInterface $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($task)
    {
        $response = $this->comment->listAll($task);
        return response()->json($response, $response['code']);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('taskmanagement::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(TaskCommentRequest $request)
    {
        $response = $this->comment->addComment($request);
        return response()->json($response, $response['code']);
    }

    public function fetchComment(Request $request)
    {
        $response = $this->comment->fetchComment($request);
        return response()->json($response, $response['code']);
    }

    public function updateComment(Request $request)
    {
        $request->validate([
            'commentSlug' => 'required',
            'commentMessage' => 'required|max:250',
        ]);
        $response = $this->comment->updateComment($request);
        return response()->json($response, $response['code']);
    }


    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('taskmanagement::edit');
    }


    /**
     * Remove the specified resource from storage.
     * @return Response
     */

    public function taskCommentDelete(Request $request)
    {
        $response = $this->comment->taskCommentDelete($request);
        return response()->json($response, $response['code']);
    }

    public function like($comment, Request $request)
    {
        $request->validate([
            'like'    => 'required|boolean'
        ]);

        $request->comment_id = $comment;
        $response = $this->comment->like($request);
        return response()->json($response, $response['code']);
    }
}
