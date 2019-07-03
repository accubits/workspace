<?php

namespace Modules\HrmManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\HrmManagement\Repositories\CalenderInterface;

class CalenderController extends Controller
{
    public $calender;

    public function __construct(CalenderInterface $calender)
    {
        $this->calender = $calender;
    }

    public function fetchAllCalender(Request $request)
    {
        $response = $this->calender->fetchAllCalender($request);

        if (in_array($request->type, ['day', 'week']))
            return response()->json($response, 200);

        return response()->json($response, $response['code']);
    }

}
