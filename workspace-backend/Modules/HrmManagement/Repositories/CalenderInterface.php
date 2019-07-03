<?php

namespace Modules\HrmManagement\Repositories;

use Illuminate\Http\Request;

interface CalenderInterface
{
    public function fetchAllCalender(Request $request);

}