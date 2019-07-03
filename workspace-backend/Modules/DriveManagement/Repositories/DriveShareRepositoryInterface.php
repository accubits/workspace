<?php

namespace Modules\DriveManagement\Repositories;



use Illuminate\Http\Request;

interface DriveShareRepositoryInterface
{
    public function sharedUsers(Request $request);
    public function allPermissions(Request $request);
}