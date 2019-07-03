<?php

namespace Modules\HrmManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\HrmManagement\Repositories\TrainingModuleRepositoryInterface;

class TrainingController extends Controller
{
    
    protected $trainingModule;
    
    public function __construct(TrainingModuleRepositoryInterface $trainingModule)
    {
        $this->trainingModule = $trainingModule;
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
     * create, update and delete of a Training Request
     * @return Response
     */
    public function setTrainingRequest(Request $request)
    {
        $response = $this->trainingModule->setTrainingRequest($request);
        return response()->json($response, $response['code']);
    }

    /**
     * create, update and delete of a Training Request Status
     * @return Response
     */
    public function setTrainingRequestStatus(Request $request)
    {
        $response = $this->trainingModule->setTrainingRequestStatus($request);
        return response()->json($response, $response['code']);
    }
    
    public function getTrainingRequestList(Request $request)
    {
        $response = $this->trainingModule->getTrainingRequestList($request);
        return response()->json($response, $response['code']);
    }
    
    public function setTrainingFeedbackDuration(Request $request)
    {
        $response = $this->trainingModule->setTrainingFeedbackDuration($request);
        return response()->json($response, $response['code']);
    }
    
    public function setTrainingBudget(Request $request)
    {
        $response = $this->trainingModule->setTrainingBudget($request);
        return response()->json($response, $response['code']);
    }
    
    public function setTrainingStatus(Request $request)
    {
        $response = $this->trainingModule->setTrainingStatus($request);
        return response()->json($response, $response['code']);
    }
    public function getTrainingSettings(Request $request)
    {
        $response = $this->trainingModule->getTrainingSettings($request);
        return response()->json($response, $response['code']);
    }
    public function setTrainingScore(Request $request)
    {
        $response = $this->trainingModule->setTrainingScore($request);
        return response()->json($response, $response['code']);
    }
}
