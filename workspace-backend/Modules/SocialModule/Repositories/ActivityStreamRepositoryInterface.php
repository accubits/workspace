<?php

namespace Modules\SocialModule\Repositories;

use Illuminate\Http\Request;

interface ActivityStreamRepositoryInterface
{
    public function fetch(Request $request);

}