<?php

namespace Modules\SocialModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\SocialModule\Repositories\PollRepositoryInterface;

class PollController extends Controller
{
    private $pollRepository;

    public function __construct(PollRepositoryInterface $pollRepository)
    {
        $this->pollRepository = $pollRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('socialmodule::index');
    }

    /**
     * create, update, delete a Poll
     * @return Response
     */
    public function setPoll(Request $request)
    {
        $response = $this->pollRepository->createPoll($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * set Poll Answers
     * @return Response
     */
    public function setPollAnswers(Request $request)
    {
        $response = $this->pollRepository->setPollAnswers($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * set Poll Status
     * @return Response
     */
    public function setPollStatus(Request $request)
    {
        $response = $this->pollRepository->setPollStatus($request);
        return response()->json($response, $response['code']);
    }
}
