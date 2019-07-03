<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Repositories\OrgRepositoryInterface;

class OrgController extends Controller
{
    private $orgRepository;
    public function __construct(OrgRepositoryInterface $orgRepository)
    {
        $this->orgRepository = $orgRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function fetchAllOrg(Request $request)
    {
        $response = $this->orgRepository->fetchAllOrg($request);
        return response()->json($response, $response['code']);
    }

    public function fetchOrgSettings(Request $request)
    {
        $response = $this->orgRepository->fetchOrgSettings($request);
        return response()->json($response, $response['code']);
    }

    public function saveOrgSettings(Request $request)
    {
        $response = $this->orgRepository->saveOrgSettings($request);
        return response()->json($response, $response['code']);
    }


}
