<?php

namespace Modules\OrgManagement\Repositories;
use Illuminate\Http\Request;

interface OrganizationRepositoryInterface
{
    public function addOrganization(Request $request);
    public function updateOrganization(Request $request);
    public function deleteOrganization(Request $request);
    public function bulkDeleteOrg(Request $request);
    public function getOrganization(Request $request);
    public function listOrganization(Request $request);
    public function listVertical(Request $request);
    public function setVertical(Request $request);
    public function saveOrgSettings(Request $request);
    public function fetchOrgSettings(Request $request);
    public function orgSettingWorkReport(Request $request);
    public function partnerDashboardSettings(Request $request);
    public function fetchPartnerDashboardSettings(Request $request);
}