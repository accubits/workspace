<?php

namespace Modules\Admin\Repositories;
use Illuminate\Http\Request;

interface LicenseRepositoryInterface
{
    public function fetchAllLicense(Request $request);
    public function fetchAllPartnersList(Request $request);
    public function fetchAllOrgsFromPartner(Request $request);
    public function approveLicenseRequest(Request $request);
    public function licenseHistory(Request $request);
}