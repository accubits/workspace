<?php

namespace Modules\Common\Repositories;

use Illuminate\Http\Request;

interface CommonRepositoryInterface
{
    //public function getAllCountries();
    public function getRoleDetails(Request $request);

}