<?php

namespace Modules\OrgManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\OrgManagement\Repositories\LicenseRepositoryInterface;

class LicenseController extends Controller
{
    private $licenseRepository;

    public function __construct(LicenseRepositoryInterface $licenseRepository)
    {
        $this->licenseRepository = $licenseRepository;   
    }

    /**
     * get a resource.
     * @return Response
     */
    public function index($request)
    {
        $response = $this->licenseRepository->getLicense($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * get listing of the resource.
     * @return Response
     */
    public function listLicense(Request $request)
    {
        $response = $this->licenseRepository->listLicense($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $response = $this->licenseRepository->addLicense($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $response = $this->licenseRepository->updateLicense($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function deleteLicense(Request $request)
    {
        $response = $this->licenseRepository->deleteLicense($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * get listing of the resource.
     * @return Response
     */
    public function listLicenseRequest(Request $request)
    {
        $response = $this->licenseRepository->listLicenseRequest($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function createLicenseRequest(Request $request)
    {
        $response = $this->licenseRepository->createLicenseRequest($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function updateLicenseRequest(Request $request)
    {
        $response = $this->licenseRepository->updateLicenseRequest($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function deleteLicenseRequest(Request $request)
    {
        $response = $this->licenseRepository->deleteLicenseRequest($request);
        return response()->json($response, $response['code']);
    }

    public function bulkDeleteLicenseRequest(Request $request)
    {
        $response = $this->licenseRepository->bulkDeleteLicenseRequest($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * get a resource.
     * @return Response
     */
    public function getLicenseRequest($request)
    {
        $response = $this->licenseRepository->getLicenseRequest($request);
        return response()->json($response, $response['code']);
    }

    /**
     * forward license request by to superadmin
     * @param Request $request
     * @return mixed
     */
    public function forwardLicenseRequest(Request $request)
    {
        $response = $this->licenseRepository->forwardLicenseRequest($request);
        return response()->json($response, $response['code']);
    }

    public function fetchAllPartnerLicense(Request $request)
    {
        $response = $this->licenseRepository->fetchAllPartnerLicense($request);
        return response()->json($response, $response['code']);
    }

    public function activateLicense(Request $request)
    {
        $response = $this->licenseRepository->activateLicense($request);
        return response()->json($response, $response['code']);
    }

    public function approveLicense(Request $request)
    {
        $response = $this->licenseRepository->approveLicense($request);
        return response()->json($response, $response['code']);
    }

    public function cancelLicenseRequest(Request $request)
    {
        $response = $this->licenseRepository->cancelLicenseRequest($request);
        return response()->json($response, $response['code']);
    }

    public function renewLicense(Request $request)
    {
        $response = $this->licenseRepository->renewLicense($request);
        return response()->json($response, $response['code']);
    }

    /**
     * org license for orgadmin
     * @return mixed
     */
    public function fetchOrgLicense(Request $request)
    {
        $response = $this->licenseRepository->fetchOrgLicense($request);
        return response()->json($response, $response['code']);
    }

}
