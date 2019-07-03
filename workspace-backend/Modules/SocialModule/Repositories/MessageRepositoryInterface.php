<?php

namespace Modules\SocialModule\Repositories;

use Illuminate\Http\Request;

interface MessageRepositoryInterface
{
    public function createMessage(Request $request);

}