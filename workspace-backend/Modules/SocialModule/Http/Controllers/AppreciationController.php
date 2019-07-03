<?php

namespace Modules\SocialModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\SocialModule\Repositories\AppreciationRepositoryInterface;

class AppreciationController extends Controller
{
    private $appreciationRepository;

    public function __construct(AppreciationRepositoryInterface $appreciationRepository)
    {
        $this->appreciationRepository = $appreciationRepository;
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
     * create, update, delete an Appreciation
     * @return Response
     */
    public function setAppreciation(Request $request)
    {
        $response = $this->appreciationRepository->createAppreciation($request);
        return response()->json($response, $response['code']);
    }


}
