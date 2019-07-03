<?php

namespace Modules\SocialModule\Repositories;

use Illuminate\Http\Request;

interface EventCommentsRepositoryInterface
{
    public function setEventComment(Request $request);
    public function setEventResponse(Request $request);
    public function setEventCommentResponse(Request $request);
    public function getEventCommentsAndResponses(Request $request);
}