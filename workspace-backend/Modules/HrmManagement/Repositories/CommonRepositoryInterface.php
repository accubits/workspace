<?php

namespace Modules\HrmManagement\Repositories;

use Illuminate\Http\Request;

interface CommonRepositoryInterface
{
    public function getWorkReportDates($request, $loggeduser, $orgId);

}