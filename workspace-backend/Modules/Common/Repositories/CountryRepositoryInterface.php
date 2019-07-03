<?php

namespace Modules\Common\Repositories;

use Illuminate\Http\Request;

interface CountryRepositoryInterface
{
    public function getAllCountries();  
    
    public function setCountry(Request $request);
}