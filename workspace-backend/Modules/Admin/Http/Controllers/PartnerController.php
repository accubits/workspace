<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Repositories\PartnerRepositoryInterface;

class PartnerController extends Controller
{

    private $partnerRepository;
    public function __construct(PartnerRepositoryInterface $partner)
    {
        $this->partnerRepository = $partner;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function createPartner(Request $request)
    {
        $response = $this->partnerRepository->createOrDeletePartner($request);
        return response()->json($response, $response['code']);
    }

    public function fetchAllPartners(Request $request)
    {
        $response = $this->partnerRepository->fetchAllPartners($request);
        return response()->json($response, $response['code']);
    }

    public function subadmin(Request $request)
    {
        $response = $this->partnerRepository->subadmin($request);
        return response()->json($response, $response['code']);
    }

    public function fetchSubadmin(Request $request)
    {
        $response = $this->partnerRepository->fetchSubadmin($request);
        return response()->json($response, $response['code']);
    }

    public function fetchAllSubadminRoles(Request $request)
    {
        $response = $this->partnerRepository->fetchAllSubadminRoles($request);
        return response()->json($response, $response['code']);
    }

    public function saveSubadminPermissions(Request $request)
    {
        $response = $this->partnerRepository->saveSubadminPermissions($request);
        return response()->json($response, $response['code']);
    }

}
