<?php

namespace Modules\SocialModule\Repositories;

use Illuminate\Http\Request;

interface AppreciationCommentsRepositoryInterface
{
    public function setAppreciationComment(Request $request);
    public function setAppreciationResponse(Request $request);
    public function setAppreciationCommentResponse(Request $request);
    public function getAppreciationCommentsAndResponses(Request $request);
}