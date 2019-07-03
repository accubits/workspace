<?php

namespace Modules\Admin\Repositories;
use Illuminate\Http\Request;

interface SettingsRepositoryInterface
{
    public function fetchDashboardSettings(Request $request);
}