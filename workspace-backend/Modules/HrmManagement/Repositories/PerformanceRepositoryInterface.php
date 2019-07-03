<?php

namespace Modules\HrmManagement\Repositories;

use Illuminate\Http\Request;

interface PerformanceRepositoryInterface
{
    public function setAppraisalCycle(Request $request);
    public function getAppraisalCycle(Request $request);
    public function getAppraisalCycles(Request $request);
}