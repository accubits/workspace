<?php

namespace Modules\HrmManagement\Repositories;

use Illuminate\Http\Request;

interface TimeInterface
{
    public function logClockStatus(Request $request);
    public function fetchWorkDay(Request $request);
    public function saveWorkDay(Request $request);
    public function currentClockStatus(Request $request);
    public function clockOutPreviousDay(Request $request);

}