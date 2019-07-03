<?php

namespace Modules\HrmManagement\Repositories;

use Illuminate\Http\Request;

interface KraModuleRepositoryInterface
{

    public function setKraModule(Request $request);
    public function getKraModules(Request $request);
}