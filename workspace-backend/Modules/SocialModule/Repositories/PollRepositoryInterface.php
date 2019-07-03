<?php

namespace Modules\SocialModule\Repositories;

use Illuminate\Http\Request;

interface PollRepositoryInterface
{
    public function createPoll(Request $request);
    
    public function setPollAnswers(Request $request);

}