<?php

namespace Modules\OrgManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\OrgManagement\Repositories\OrganizationRepositoryInterface;

class OrgManagementController extends Controller
{

    private $orgRepository;

    public function __construct(OrganizationRepositoryInterface $orgRepository)
    {
        $this->orgRepository = $orgRepository;   
    }

    /**
     * get a resource.
     * @return Response
     */
    public function index($org_slug)
    {
        $response = $this->orgRepository->getOrganization($org_slug);
        return response()->json($response, $response['code']);
    }
    
    /**
     * get listing of the resource.
     * @return Response
     */
    public function listOrganization(Request $request)
    {
        $response = $this->orgRepository->listOrganization($request);
        return response()->json($response, $response['code']);
    }


    /**
     *  create/update/delete a vertical
     * @return Response
     */
    public function setVertical(Request $request)
    {
        $response = $this->orgRepository->setVertical($request);
        return response()->json($response, $response['code']);
    }

    /**
     * get listing of the resource.
     * @return Response
     */
    public function listVertical(Request $request)
    {
        $response = $this->orgRepository->listVertical($request);
        return response()->json($response, $response['code']);
    }

    /**
     *  creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $response = $this->orgRepository->addOrganization($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $response = $this->orgRepository->updateOrganization($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Request $request)
    {
        $response = $this->orgRepository->deleteOrganization($request);
        return response()->json($response, $response['code']);
    }

    public function bulkDeleteOrg(Request $request)
    {
        $response = $this->orgRepository->bulkDeleteOrg($request);
        return response()->json($response, $response['code']);
    }

    public function saveOrgSettings(Request $request)
    {
        $response = $this->orgRepository->saveOrgSettings($request);
        return response()->json($response, $response['code']);
    }

    public function orgSettingWorkReport(Request $request)
    {
        $response = $this->orgRepository->orgSettingWorkReport($request);
        return response()->json($response, $response['code']);
    }

    public function fetchOrgSettings(Request $request)
    {
        $response = $this->orgRepository->fetchOrgSettings($request);
        return response()->json($response, $response['code']);
    }


    /**
     * partner dashboard settings
     * @return mixed
     */
    public function partnerDashboardSettings(Request $request)
    {
        $response = $this->orgRepository->partnerDashboardSettings($request);
        return response()->json($response, $response['code']);
    }

    public function fetchPartnerDashboardSettings(Request $request)
    {
        $response = $this->orgRepository->fetchPartnerDashboardSettings($request);
        return response()->json($response, $response['code']);
    }
}
