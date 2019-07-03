<?php

namespace Modules\OrgManagement\Repositories;
use Illuminate\Http\Request;

interface LicenseRepositoryInterface
{
    public function addLicense(Request $request);
    public function updateLicense(Request $request);
    public function deleteLicense(Request $request);
    public function getLicense(Request $request);
    public function listLicense(Request $request);
    public function renewLicense(Request $request);
    public function fetchOrgLicense(Request $request);

    public function createLicenseRequest(Request $request);
    public function updateLicenseRequest(Request $request);
    public function deleteLicenseRequest(Request $request);
    public function bulkDeleteLicenseRequest(Request $request);
    public function cancelLicenseRequest(Request $request);
    public function getLicenseRequest(Request $request);
    public function listLicenseRequest(Request $request);
    public function forwardLicenseRequest(Request $request);
    public function fetchAllPartnerLicense(Request $request);
    public function activateLicense(Request $request);
    public function approveLicense(Request $request);

}