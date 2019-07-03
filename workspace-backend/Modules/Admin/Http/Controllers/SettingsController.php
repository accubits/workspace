<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Repositories\SettingsRepositoryInterface;

class SettingsController extends Controller
{
    private $settings;
    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    public function fetchDashboardSettings(Request $request)
    {
        $response = $this->settings->fetchDashboardSettings($request);
        return response()->json($response, $response['code']);
    }

}
