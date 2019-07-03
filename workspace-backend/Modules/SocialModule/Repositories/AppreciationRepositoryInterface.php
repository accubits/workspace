<?php

namespace Modules\SocialModule\Repositories;

use Illuminate\Http\Request;

interface AppreciationRepositoryInterface
{
    public function createAppreciation(Request $request);
}