<?php

namespace Modules\SocialModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\SocialModule\Repositories\ActivityStreamRepositoryInterface;
use Modules\SocialModule\Repositories\CommonRepositoryInterface;

class ActivityStreamController extends Controller
{
    
    private $activityStreamRepository;
    private $commonRepository;

    public function __construct(ActivityStreamRepositoryInterface $activityStream, CommonRepositoryInterface $common)
    {
        $this->activityStreamRepository = $activityStream;
        $this->commonRepository = $common;
    }
    /**
     *  listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('socialmodule::index');
    }

    /**
     * get listing of the resource.
     * @return Response
     */
    public function fetch(Request $request)
    {
        $response = $this->activityStreamRepository->fetch($request);
        return response()->json($response, $response['code']);
    }

    public function fetchTaskWidget(Request $request)
    {
        $response =$this->commonRepository->fetchTaskWidget($request);
        return response()->json($response, $response['code']);
    }
}
