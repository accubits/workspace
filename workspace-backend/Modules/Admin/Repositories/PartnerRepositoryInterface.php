<?php

namespace Modules\Admin\Repositories;
use Illuminate\Http\Request;

interface PartnerRepositoryInterface
{
    public function createOrDeletePartner(Request $request);
    public function fetchAllPartners(Request $request);
    public function subadmin(Request $request);
    public function fetchSubadmin(Request $request);
    public function fetchAllSubadminRoles(Request $request);
    public function saveSubadminPermissions(Request $request);
}