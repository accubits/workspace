<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Repositories\CommonRepositoryInterface;

class CommonController extends Controller
{
    private $common;

    public function __construct(CommonRepositoryInterface $common)
    {
        $this->common = $common;
    }

    public function getRoleDetails(Request $request)
    {
        $response = $this->common->getRoleDetails($request);
        return response()->json($response, $response['code']);
    }
}
