<?php

namespace Modules\HrmManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\HrmManagement\Repositories\PerformanceRepositoryInterface;

class AppraisalCycleController extends Controller
{
    protected $performanceRepository;
    
    public function __construct(PerformanceRepositoryInterface $performanceRepository)
    {
        $this->performanceRepository = $performanceRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('hrmmanagement::index');
    }

    /**
     * create/update/delete an appraisalcycle
     * @return Response
     */
    public function setAppraisalCycle(Request $request)
    {
        $response = $this->performanceRepository->setAppraisalCycle($request);
        return response()->json($response, $response['code']);
    }

    /**
     * get appraisalcycle
     * @return Response
     */
    public function getAppraisalCycle(Request $request)
    {
        $response = $this->performanceRepository->getAppraisalCycle($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * get appraisalcycle
     * @return Response
     */
    public function getAppraisalCycles(Request $request)
    {
        $response = $this->performanceRepository->getAppraisalCycles($request);
        return response()->json($response, $response['code']);
    }
}
