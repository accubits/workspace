<?php

namespace Modules\HrmManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\HrmManagement\Repositories\KraModuleRepositoryInterface;

class KraModuleController extends Controller
{
  
    protected $kraModuleRepository;
    
    public function __construct(KraModuleRepositoryInterface $kraModuleRepository)
    {
        $this->kraModuleRepository = $kraModuleRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('hrmmanagement::index');
    }

    public function setKraModule(Request $request)
    {
        $response = $this->kraModuleRepository->setKraModule($request);
        return response()->json($response, $response['code']);
    }
    
    public function getKraModule(Request $request)
    {
        $response = $this->kraModuleRepository->getKraModules($request);
        return response()->json($response, $response['code']);
    }

}
