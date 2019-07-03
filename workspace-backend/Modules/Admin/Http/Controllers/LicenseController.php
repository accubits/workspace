<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Repositories\LicenseRepositoryInterface;

class LicenseController extends Controller
{
    private $licenseRepository;
    public function __construct(LicenseRepositoryInterface $licenseRepository)
    {
        $this->licenseRepository = $licenseRepository;
    }

    public function fetchAllLicense(Request $request)
    {
        $response = $this->licenseRepository->fetchAllLicense($request);
        return response()->json($response, $response['code']);
    }

    public function fetchAllPartnersList(Request $request)
    {
        $response = $this->licenseRepository->fetchAllPartnersList($request);
        return response()->json($response, $response['code']);
    }

    public function fetchAllOrgsFromPartner(Request $request)
    {
        $response = $this->licenseRepository->fetchAllOrgsFromPartner($request);
        return response()->json($response, $response['code']);
    }

    public function approveLicenseRequest(Request $request)
    {
        $response = $this->licenseRepository->approveLicenseRequest($request);
        return response()->json($response, $response['code']);
    }

    public function licenseHistory(Request $request)
    {
        $response = $this->licenseRepository->licenseHistory($request);
        return response()->json($response, $response['code']);
    }

}
