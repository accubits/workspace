<?php

namespace Modules\Admin\Repositories;
use Illuminate\Http\Request;

interface OrgRepositoryInterface
{
    public function fetchAllOrg(Request $request);
    public function fetchOrgSettings(Request $request);
    public function saveOrgSettings(Request $request);
}