<?php

namespace Modules\HrmManagement\Repositories;

use Illuminate\Http\Request;

interface TrainingModuleRepositoryInterface
{

    public function setTrainingRequest(Request $request);
    public function setTrainingRequestStatus(Request $request); //approve,reject training request
    public function getTrainingRequestList(Request $request);
    
    public function setTrainingBudget(Request $request);
    public function setTrainingFeedbackDuration(Request $request);
    public function setTrainingStatus(Request $request); //start,cancel,complete training
    public function getTrainingSettings(Request $request);
    public function setTrainingScore(Request $request);

}