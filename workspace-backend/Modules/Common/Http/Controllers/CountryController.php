<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Entities\Country;
use Modules\Common\Repositories\CountryRepositoryInterface;



class CountryController extends Controller
{
    protected $country;
    public function __construct(CountryRepositoryInterface $countries)
    {
        $this->country = $countries;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('common::index');
    }

    /**
     * list of countries
     * @return Response
     */
    public function getCountry()
    {
        $response = $this->country->getAllCountries();
        return response()->json($response, $response['code']);
    }

    /**
     * list of countries
     * @return Response
     */
    public function setCountry(Request $request)
    {
        $response = $this->country->setCountry($request);
        return response()->json($response, $response['code']);
    }    
    
}
