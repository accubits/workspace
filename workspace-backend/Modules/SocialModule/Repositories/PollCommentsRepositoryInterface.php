<?php

namespace Modules\SocialModule\Repositories;

use Illuminate\Http\Request;

interface PollCommentsRepositoryInterface
{
    public function setPollComment(Request $request);
    public function setPollResponse(Request $request);
    public function setPollCommentResponse(Request $request);
    public function getPollCommentsAndResponses(Request $request);
}