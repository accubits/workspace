<?php

namespace Modules\HrmManagement\Repositories\TrainingModule;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\ResponseStatus;
use Modules\Common\Utilities\Utilities;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;
use Modules\HrmManagement\Entities\HrmTrainingRequest;
use Modules\HrmManagement\Entities\HrmTrainingRequestApprover;
use Modules\HrmManagement\Entities\HrmTrainingStatus;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\HrmManagement\Repositories\TrainingModuleRepositoryInterface;
use Modules\UserManagement\Entities\UserProfile;
use Modules\HrmManagement\Entities\HrmTrainingBudget;
use Modules\HrmManagement\Entities\HrmTrainingBudgetHistory;
use Modules\HrmManagement\Entities\HrmTrainingFeedbackDuration;
use Modules\HrmManagement\Entities\HrmTrainingFeedbackFormMap;
use Modules\FormManagement\Entities\FormMaster;
use Modules\HrmManagement\Entities\HrmTrainingFeedbackResponse;

use Modules\FormManagement\Entities\FormAnswerSheet;

class TrainingModuleRepository implements TrainingModuleRepositoryInterface
{

    protected $content;
    protected $statusArray;
    protected $s3BasePath;

    public function __construct()
    {
        $this->content = array();
        $this->statusArray = array();
        $this->s3BasePath= env('S3_PATH');
    }

    /**
     * create, update, delete a TrainingRequest
     * @param Request $request
     * @return array
     */
    public function setTrainingRequest(Request $request)
    {
        $user  = Auth::user();
        $msg = "invalid action / action missing";

        DB::beginTransaction();
        try {
            if(!in_array($request->action, ['create','update','delete'])){
                throw new \Exception($msg);
            }

            if($request->action == "create"){

                $organisationObj = DB::table(Organization::table)
                        ->where(Organization::slug, '=',$request->orgSlug)
                        ->first();
                if(empty($organisationObj)){
                    throw new \Exception("Invalid Organisation");
                }
                $employeeObj = DB::table(OrgEmployee::table)
                        ->where(OrgEmployee::org_id, '=',$organisationObj->id)
                        ->where(OrgEmployee::user_id, '=',$user->id)
                        ->select('id')
                        ->first();

                if(empty($employeeObj)){
                    throw new \Exception("Invalid Employee");
                }
                $hrmTrainingRequest = new HrmTrainingRequest;
                $hrmTrainingRequest->{HrmTrainingRequest::slug} = Utilities::getUniqueId();
                
                $startDateTime = Carbon::createFromTimestamp($request->startsOn);
                $endDateTime      = Carbon::createFromTimestamp($request->endsOn);

                if( !(($startDateTime < $endDateTime) && ($endDateTime->diffInMinutes($startDateTime) >= 15)) ) {
                    throw new \Exception("Invalid Training start/end date");
                }

                $hrmTrainingRequest->{HrmTrainingRequest::starts_on}  = !empty($request->startsOn) ? date('Y-m-d H:i:s', $request->startsOn):null;
                $hrmTrainingRequest->{HrmTrainingRequest::ends_on}  = !empty($request->endsOn) ? date('Y-m-d H:i:s', $request->endsOn):null;
                
                $hrmTrainingRequest->{HrmTrainingRequest::org_id} = $organisationObj->id;
                $hrmTrainingRequest->{HrmTrainingRequest::from_employee_id} = $employeeObj->id;
                $hrmTrainingRequest->{HrmTrainingRequest::name} = $request->name;
                $hrmTrainingRequest->{HrmTrainingRequest::details} = $request->details;
                $hrmTrainingRequest->{HrmTrainingRequest::cost} = $request->cost;
                $hrmTrainingRequest->save();

                $hrmTrainingRequestApprover = new HrmTrainingRequestApprover;
                $hrmTrainingRequestApprover->{HrmTrainingRequestApprover::training_request_id} = $hrmTrainingRequest->id;

                $approverEmployeeObj = DB::table(OrgEmployee::table)
                        ->where(OrgEmployee::slug, '=',$request->approverEmployeeSlug)
                        ->select('id')
                        ->first();
                if(empty($approverEmployeeObj)){
                    throw new \Exception("Invalid Approver Employee");
                }
                $hrmTrainingRequestApprover->{HrmTrainingRequestApprover::employee_id} = $approverEmployeeObj->id;
                $hrmTrainingRequestApprover->{HrmTrainingRequestApprover::has_approved} = FALSE;
                $hrmTrainingRequestApprover->save();
                $msg = "Training Request created successfully";
                
            } else if($request->action == "update"){

                $organisationObj = DB::table(Organization::table)
                        ->where(Organization::slug, '=',$request->orgSlug)
                        ->first();
                if(empty($organisationObj)){
                    throw new \Exception("Invalid Organisation");
                }
                $employeeObj = DB::table(OrgEmployee::table)
                        ->where(OrgEmployee::org_id, '=',$organisationObj->id)
                        ->where(OrgEmployee::user_id, '=',$user->id)
                        ->select('id')
                        ->first();
                if(empty($employeeObj)){
                    throw new \Exception("Invalid Employee");
                }

                $hrmTrainingRequest = HrmTrainingRequest::where(HrmTrainingRequest::slug, '=',$request->slug)
                        ->select('id')
                        ->first();
                if(empty($hrmTrainingRequest)){
                    throw new \Exception("Invalid TrainingRequest");
                }
                
                $startDateTime = Carbon::createFromTimestamp($request->startsOn);
                $endDateTime      = Carbon::createFromTimestamp($request->endsOn);

                if( !(($startDateTime < $endDateTime) && ($endDateTime->diffInMinutes($startDateTime) >= 15)) ) {
                    throw new \Exception("Invalid Training start/end date");
                }

                $hrmTrainingRequest->{HrmTrainingRequest::starts_on}  = !empty($request->startsOn) ? date('Y-m-d H:i:s', $request->startsOn):null;
                $hrmTrainingRequest->{HrmTrainingRequest::ends_on}  = !empty($request->endsOn) ? date('Y-m-d H:i:s', $request->endsOn):null;
                
                $hrmTrainingRequest->{HrmTrainingRequest::org_id} = $organisationObj->id;
                $hrmTrainingRequest->{HrmTrainingRequest::from_employee_id} = $employeeObj->id;
                $hrmTrainingRequest->{HrmTrainingRequest::name} = $request->name;
                $hrmTrainingRequest->{HrmTrainingRequest::details} = $request->details;
                $hrmTrainingRequest->{HrmTrainingRequest::cost} = $request->cost;
                $hrmTrainingRequest->save();

                $hrmTrainingRequestApprover = HrmTrainingRequestApprover::where(HrmTrainingRequestApprover::training_request_id, '=',$hrmTrainingRequest->id)
                        ->first();
                

                $approverEmployeeObj = DB::table(OrgEmployee::table)
                        ->where(OrgEmployee::slug, '=',$request->approverEmployeeSlug)
                        ->select('id')
                        ->first();
                if(empty($approverEmployeeObj)){
                    throw new \Exception("Invalid Approver Employee");
                }
                
                if(!empty($hrmTrainingRequestApprover) && 
                        $hrmTrainingRequestApprover->{HrmTrainingRequestApprover::employee_id} == $approverEmployeeObj->id ){
                    //no update needed
                } else { //delete old and register new
                    $hrmTrainingRequestApprover->delete();
                    
                    $hrmTrainingRequestApprover = new HrmTrainingRequestApprover;
                    $hrmTrainingRequestApprover->{HrmTrainingRequestApprover::training_request_id} = $hrmTrainingRequest->id;
                    $hrmTrainingRequestApprover->{HrmTrainingRequestApprover::employee_id} = $approverEmployeeObj->id;
                    $hrmTrainingRequestApprover->{HrmTrainingRequestApprover::has_approved} = FALSE;
                    $hrmTrainingRequestApprover->save();
                }
                $msg = "Training Request updated successfully";
                
            } else if($request->action == "delete"){

                $hrmTrainingRequest = HrmTrainingRequest::where(HrmTrainingRequest::slug, '=',$request->slug)
                        ->first();
                if(empty($hrmTrainingRequest)){
                    throw new \Exception("Invalid Training Request");
                }
                HrmTrainingRequest::where(HrmTrainingRequest::slug, '=',$request->slug)
                        ->delete();
                $msg = "Training Request deleted successfully";
            }

            DB::commit();

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

        
        return $this->content = array(
            'data'   => array(
                "msg"=>$msg,
                "slug"=>$hrmTrainingRequest->{HrmTrainingRequest::slug}
                ),
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );

    }

    /**
     * update TrainingRequest Status
     * @param Request $request
     * @return array
     */
    public function setTrainingRequestStatus(Request $request)
    {
        $user  = Auth::user();
        
        DB::beginTransaction();
        try {
                $organisationObj = DB::table(Organization::table)
                        ->where(Organization::slug, '=',$request->orgSlug)
                        ->first();
                if(empty($organisationObj)){
                    throw new \Exception("Invalid Organisation");
                }

                $approverEmployeeObj = DB::table(OrgEmployee::table)
                        ->where(OrgEmployee::org_id, '=',$organisationObj->id)
                        ->where(OrgEmployee::user_id, '=',$user->id)
                        ->select('id')
                        ->first();
                if(empty($approverEmployeeObj)){
                    throw new \Exception("Invalid Approver Employee");
                }

                $hrmTrainingRequestObj = HrmTrainingRequest::where(HrmTrainingRequest::slug, '=',$request->slug)->first();
                if(empty($hrmTrainingRequestObj)){
                    throw new \Exception("Invalid TrainingRequest");
                }
                
                $hrmTrainingRequestApproverObj = HrmTrainingRequestApprover::where(HrmTrainingRequestApprover::training_request_id, '=',$hrmTrainingRequestObj->id)
                        ->where(HrmTrainingRequestApprover::employee_id, '=',$approverEmployeeObj->id)
                        ->first();
                if(empty($hrmTrainingRequestApproverObj)){
                    throw new \Exception("Invalid Training Request Approver Employee");
                }
                
                
                $hrmTrainingStatusObj = HrmTrainingStatus::where(HrmTrainingStatus::value, '=',$request->status)->first();
                if(empty($hrmTrainingStatusObj)){
                    throw new \Exception("Invalid Training Status");
                }
                
                $postTrainingForm = FormMaster::where(FormMaster::form_slug, '=',$request->forms['postTrainingFormSlug'])
                        ->select('id')->first();
                if(empty($postTrainingForm)){
                    throw new \Exception("Invalid  Post Training Form");
                }
                
                $postCourseForm = FormMaster::where(FormMaster::form_slug, '=',$request->forms['postCourseFormSlug'])
                        ->select('id')->first();
                if(empty($postCourseForm)){
                    throw new \Exception("Invalid  Post Course Form");
                }
                
                if($request->status == 'approved'){
                    
                    if($hrmTrainingRequestObj->{HrmTrainingRequest::status_id} == $hrmTrainingStatusObj->id){
                        throw new \Exception("TrainingRequest is already approved!");
                    }
                    
                    $hrmTrainingBudgetObj = HrmTrainingBudget::where(HrmTrainingBudget::org_id, '=',$organisationObj->id)->first();
                    if(empty($hrmTrainingBudgetObj)){
                        throw new \Exception("Training Budget is not set!");
                    }

                    $trainingCost = $hrmTrainingRequestObj->{HrmTrainingRequest::cost};
                    $trainingBudgetBalance = $hrmTrainingBudgetObj->{HrmTrainingBudget::current_balance};
                    if( $trainingCost > $trainingBudgetBalance ){
                        throw new \Exception("Insufficient Training Budget balance!");
                    }
                    $hrmTrainingBudgetObj->{HrmTrainingBudget::current_balance} = $trainingBudgetBalance - $trainingCost;
                    $hrmTrainingBudgetObj->save();
                    
                    
                    $hrmTrainingBudgetHistoryObj = new HrmTrainingBudgetHistory;
                    $hrmTrainingBudgetHistoryObj->{HrmTrainingBudgetHistory::org_id} = $organisationObj->id;
                    $hrmTrainingBudgetHistoryObj->{HrmTrainingBudgetHistory::training_request_id} = $hrmTrainingRequestObj->id;
                    $hrmTrainingBudgetHistoryObj->{HrmTrainingBudgetHistory::training_budget_id} = $hrmTrainingBudgetObj->id;
                    $hrmTrainingBudgetHistoryObj->{HrmTrainingBudgetHistory::old_balance}=$trainingBudgetBalance;
                    $hrmTrainingBudgetHistoryObj->{HrmTrainingBudgetHistory::new_balance}=$hrmTrainingBudgetObj->{HrmTrainingBudget::current_balance};
                    $hrmTrainingBudgetHistoryObj->{HrmTrainingBudgetHistory::creator_user_id}=$user->id;
                    $hrmTrainingBudgetHistoryObj->save();
                    
                    
                    //set forms 
                    $hrmTrainingFeedbackFormMap = HrmTrainingFeedbackFormMap::where(HrmTrainingFeedbackFormMap::hrm_training_request_id, '=',$hrmTrainingRequestObj->id)
                        ->first();
                    if(empty($hrmTrainingFeedbackFormMap)){//create
                        $hrmTrainingFeedbackFormMap = new HrmTrainingFeedbackFormMap;
                        $hrmTrainingFeedbackFormMap->{HrmTrainingFeedbackFormMap::org_id}=$organisationObj->id;
                        $hrmTrainingFeedbackFormMap->{HrmTrainingFeedbackFormMap::hrm_training_request_id}=$hrmTrainingRequestObj->id;
                        $hrmTrainingFeedbackFormMap->{HrmTrainingFeedbackFormMap::post_training_form_master_id}=$postTrainingForm->id;
                        $hrmTrainingFeedbackFormMap->{HrmTrainingFeedbackFormMap::post_course_form_master_id}=$postCourseForm->id;
                        $hrmTrainingFeedbackFormMap->save();
                    } else {//update
                        $hrmTrainingFeedbackFormMap->{HrmTrainingFeedbackFormMap::post_training_form_master_id}=$postTrainingForm->id;
                        $hrmTrainingFeedbackFormMap->{HrmTrainingFeedbackFormMap::post_course_form_master_id}=$postCourseForm->id;
                        $hrmTrainingFeedbackFormMap->save();                        
                    }
                    
                    // set status
                    $hrmTrainingRequestApproverObj->{HrmTrainingRequestApprover::has_approved} = TRUE;
                    $hrmTrainingRequestApproverObj->save();
                    
                    
                    
                } else if($request->status == 'rejected'){
                    
                    if($hrmTrainingRequestObj->{HrmTrainingRequest::status_id} == $hrmTrainingStatusObj->id){
                        throw new \Exception("TrainingRequest is already rejected!");
                    }
                    $hrmTrainingRequestApproverObj->{HrmTrainingRequestApprover::has_approved} = FALSE;
                    $hrmTrainingRequestApproverObj->save();
                }

                $hrmTrainingRequestObj->{HrmTrainingRequest::status_id} = $hrmTrainingStatusObj->id;
                
                
                if(!empty($request->hasFeedbackForm)){
                    $hrmTrainingRequestObj->{HrmTrainingRequest::has_feedback_form} = TRUE;
                    
                } else {
                    $hrmTrainingRequestObj->{HrmTrainingRequest::has_feedback_form} = FALSE;
                }
                
                $hrmTrainingRequestObj->save();
                
                DB::commit();
                
                $msg = "Training status updated successfully";
                
        } catch(\Exception $e){
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;            
        }
        
        return $this->content = array(
            'data'   => array(
                "msg"=>$msg
                ),
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );
    
    }

    
    public function getTrainingRequestList(Request $request) {

        try{
            
            $user  = Auth::user();
            //DB::enableQueryLog();
            
            $organisationObj = DB::table(Organization::table)
                            ->where(Organization::slug, '=',$request->orgSlug)
                            ->first();
                    if(empty($organisationObj)){
                        throw new \Exception("Invalid Organisation");
                    }

            $trainingRequestQueryBuilder = DB::table(HrmTrainingRequest::table)
            ->leftJoin(HrmTrainingStatus::table, HrmTrainingStatus::table . ".id", '=', HrmTrainingRequest::table . '.' . HrmTrainingRequest::status_id)
            ->join(OrgEmployee::table, OrgEmployee::table . ".id", '=', HrmTrainingRequest::table . '.' . HrmTrainingRequest::from_employee_id)
            ->join(User::table, User::table . ".id", '=', OrgEmployee::table . '.' . OrgEmployee::user_id)
            ->leftJoin(UserProfile::table, User::table . ".id", '=', UserProfile::table . '.' . UserProfile::user_id)
            ->leftJoin(HrmTrainingRequestApprover::table, HrmTrainingRequestApprover::table . "." .HrmTrainingRequestApprover::training_request_id, '=', HrmTrainingRequest::table . '.id')
            ->leftJoin(OrgEmployee::table.' as aprEmp',  "aprEmp.id", '=', HrmTrainingRequestApprover::table . '.' . HrmTrainingRequestApprover::employee_id)
            ->leftJoin(User::table. ' as aprUser',  "aprUser.id", '=', "aprEmp.".OrgEmployee::user_id)
            ->leftJoin(UserProfile::table. ' as aprUserProfile', "aprUser.id", '=', 'aprUserProfile.' . UserProfile::user_id)
            ->leftJoin(HrmTrainingFeedbackFormMap::table. ' as feedbackFormMap', "feedbackFormMap.".HrmTrainingFeedbackFormMap::hrm_training_request_id, '=', HrmTrainingRequest::table.'.id')
            ->leftJoin(FormMaster::table. ' as postTrainingForm', "feedbackFormMap.".HrmTrainingFeedbackFormMap::post_training_form_master_id, '=', 'postTrainingForm.id')
            ->leftJoin(FormMaster::table. ' as postCourseForm', "feedbackFormMap.".HrmTrainingFeedbackFormMap::post_course_form_master_id, '=', 'postCourseForm.id')
            ->select(
                    HrmTrainingRequest::table.'.id AS trainingRequestId',
                    HrmTrainingRequest::table . '.'.HrmTrainingRequest::slug." AS slug",
                    HrmTrainingRequest::table . '.'.HrmTrainingRequest::name." AS name",
                    HrmTrainingRequest::table . '.'.HrmTrainingRequest::details." AS details",
                    DB::raw("unix_timestamp(".HrmTrainingRequest::table . '.'.HrmTrainingRequest::starts_on.") AS startsOn"), 
                    //86399 = seconds in a day - 1 second
                    DB::raw(" unix_timestamp( TIMESTAMPADD(SECOND,86399, ".HrmTrainingRequest::table . '.'.HrmTrainingRequest::ends_on.")) AS endsOn"),
                    DB::raw("unix_timestamp(".HrmTrainingRequest::table . '.'.HrmTrainingRequest::CREATED_AT.") AS requestedOn"),
                    DB::raw("unix_timestamp(".HrmTrainingRequestApprover::table . '.'.HrmTrainingRequestApprover::UPDATED_AT.") AS aprUpdatedAt"),
                    HrmTrainingRequest::table . '.'.HrmTrainingRequest::cost." AS cost",
                    HrmTrainingStatus::table . '.' . HrmTrainingStatus::value. ' AS status',
                    HrmTrainingRequest::table . '.'.HrmTrainingRequest::in_progress." AS inProgress",
                    HrmTrainingRequest::table . '.'.HrmTrainingRequest::is_cancelled." AS isCancelled",
                    HrmTrainingRequest::table . '.'.HrmTrainingRequest::is_completed." AS isCompleted",
                    
                    "postTrainingForm.".FormMaster::form_slug.' AS postTrainingFormSlug',
                    "postTrainingForm.".FormMaster::form_title.' AS postTrainingFormTitle',
                    
                    "postCourseForm.".FormMaster::form_slug.' AS postCourseFormSlug',
                    "postCourseForm.".FormMaster::form_title.' AS postCourseFormTitle',
                    
                    
                    'aprEmp.'.OrgEmployee::slug.' as approverEmployeeSlug',
                    'aprUser.'.User::slug." AS approverUserSlug",
                    'aprUser.'.User::name." AS approverUserName",
                    DB::raw('concat("'. $this->s3BasePath.'", aprUserProfile.'. UserProfile::image_path.') as approverUserImageUrl'),

                    OrgEmployee::table.'.'.OrgEmployee::slug.' as employeeSlug',
                    User::table . '.'.User::slug." AS userSlug",
                    User::table . '.'.User::name." AS userName",
                    DB::raw('concat("'. $this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as imageUrl'),
                    HrmTrainingRequestApprover::table . '.'.HrmTrainingRequestApprover::has_approved." AS hasApproved"
                     )
            ->where(HrmTrainingRequest::table .'.'. HrmTrainingRequest::org_id, $organisationObj->id);
            
            
            //tab check
            if($request->tab == 'myTrainings'){
                $trainingRequestQueryBuilder = $trainingRequestQueryBuilder->where(User::table . '.id','=',$user->id);
            } elseif ($request->tab == 'request') {
                $trainingRequestQueryBuilder = $trainingRequestQueryBuilder->where('aprUser.id','=',$user->id)
                        ->where(function ($subquery) use ($user) {
                            $subquery->where(HrmTrainingStatus::table . '.' . HrmTrainingStatus::value,'=','awaitingApproval')
                                    ->orWhereNull(HrmTrainingRequest::table.'.'.HrmTrainingRequest::status_id);
                        });
                        
            } elseif ($request->tab == 'onGoing' || $request->tab == 'active') { //active
                
                $trainingRequestQueryBuilder = $trainingRequestQueryBuilder->where(HrmTrainingRequest::table . '.'. HrmTrainingRequest::in_progress,'=',TRUE);
                
            } elseif ($request->tab == 'approved') {
                $trainingRequestQueryBuilder = $trainingRequestQueryBuilder->where(User::table . '.id', '=', $user->id)
                    ->where(HrmTrainingStatus::table.'.'.HrmTrainingStatus::value,'=','approved');

            } elseif($request->tab == 'completed'){
                $trainingRequestQueryBuilder = $trainingRequestQueryBuilder->where(HrmTrainingRequest::table . '.' .HrmTrainingRequest::is_completed,'=',TRUE)
                        ->where(function ($subquery) use ($user) {
                            $subquery->where(User::table . '.id','=',$user->id)
                                    ->orWhere('aprUser.id','=',$user->id);
                        });
            } elseif ($request->tab == 'overview') {
               //list all under a org else TODO 
            }
            
            
            $trainingRequestCol = $trainingRequestQueryBuilder->get();
            
            $trainingRequestIdsArr = array();
            $trainingRequestCol->each(function($item) use (&$trainingRequestIdsArr){
                array_push($trainingRequestIdsArr, $item->trainingRequestId);
            });

            $trainingFeedbackResponseCol = DB::table(HrmTrainingFeedbackResponse::table)
                ->join(FormAnswerSheet::table, FormAnswerSheet::table . ".id", '=', HrmTrainingFeedbackResponse::table . '.' . HrmTrainingFeedbackResponse::form_answersheet_id)
                ->join(HrmTrainingFeedbackFormMap::table, HrmTrainingFeedbackFormMap::table . "." . HrmTrainingFeedbackFormMap::hrm_training_request_id, '=', HrmTrainingFeedbackResponse::table . '.' . HrmTrainingFeedbackResponse::training_request_id)
                ->whereIn(HrmTrainingFeedbackResponse::table.'.'.HrmTrainingFeedbackResponse::training_request_id,$trainingRequestIdsArr)
                ->where(HrmTrainingFeedbackResponse::table.'.'.HrmTrainingFeedbackResponse::is_final,TRUE)
                ->select(
                    FormAnswerSheet::table.'.id AS answersheetId',
                    FormAnswerSheet::table.'.'.FormAnswerSheet::form_master_id.' AS answersheetFormMasterId',
                    FormAnswerSheet::table.'.'.FormAnswerSheet::slug.' AS answersheetSlug',
                    HrmTrainingFeedbackFormMap::table.'.'.HrmTrainingFeedbackFormMap::post_training_form_master_id.' AS postTrainingFormMasterId',
                    HrmTrainingFeedbackFormMap::table.'.'.HrmTrainingFeedbackFormMap::post_course_form_master_id.' AS postCourseFormMasterId',
                    HrmTrainingFeedbackResponse::table.'.'.HrmTrainingFeedbackResponse::training_request_id.' AS trainingRequestId',
                    HrmTrainingFeedbackResponse::table.'.id AS trainingFeedbackResponseId',
                    HrmTrainingFeedbackResponse::table.'.'.HrmTrainingFeedbackResponse::score.' AS score'
                    )
                ->get();

            $groupedTrainingResponseArr = $trainingFeedbackResponseCol->groupBy("trainingRequestId")->toArray();
            
            //dd(DB::getQueryLog());

            $trainingRequestCol->transform(function($item) use ($groupedTrainingResponseArr) {
                $itemArr = (array)$item;
                $itemArr['inProgress'] = (boolean)$itemArr['inProgress']; 
                $itemArr['isCancelled'] = (boolean)$itemArr['isCancelled']; 
                $itemArr['isCompleted'] = (boolean)$itemArr['isCompleted']; 
                $itemArr['status'] = empty($itemArr['status'])?'awaitingApproval':$itemArr['status'];
                
                //add responses if any
                $responsesArr = !empty($groupedTrainingResponseArr[$itemArr['trainingRequestId']])?$groupedTrainingResponseArr[$itemArr['trainingRequestId']]:array();
                $itemArr['postTrainingFormAnswerSheetSlug'] = null;
                $itemArr['postTrainingResponseId'] = null;
                $itemArr['postTrainingResponseScore'] = null;
                
                $itemArr['postCourseFormAnswerSheetSlug'] = null;
                $itemArr['postCourseResponseId'] = null;
                $itemArr['postCourseResponseScore'] = null;
                
                foreach ($responsesArr as $value) {
                    if($value->answersheetFormMasterId == $value->postTrainingFormMasterId){
                        $itemArr['postTrainingFormAnswerSheetSlug'] = $value->answersheetSlug;
                        $itemArr['postTrainingResponseId']=$value->trainingFeedbackResponseId;
                        $itemArr['postTrainingResponseScore'] = $value->score;
                    } else if ($value->answersheetFormMasterId == $value->postCourseFormMasterId){
                        $itemArr['postCourseFormAnswerSheetSlug'] = $value->answersheetSlug;
                        $itemArr['postCourseResponseId']=$value->trainingFeedbackResponseId;
                        $itemArr['postCourseResponseScore'] = $value->score;
                    }
                }
                ////////////

                $itemArr['isOnGoing'] = $itemArr['inProgress'];
                
                if(!empty($itemArr['hasApproved'])){
                    $itemArr['approvedAt'] = $itemArr['aprUpdatedAt'];
                } else {
                    $itemArr['approvedAt'] = null;
                }
                unset($itemArr['aprUpdatedAt']);
                unset($itemArr['hasApproved']);
                unset($itemArr['trainingRequestId']);
                return $itemArr;
            });

            $respArr['trainings'] = $trainingRequestCol;
                return array(
                    "status" => "OK",
                    "data" => $respArr,
                    "code" => Response::HTTP_OK);
        } catch (\Exception $e) {
            return array(
                "status" => "ERROR",
                "error" => array(
                    "msg" => $e->getMessage()
                ),
                "code" => Response::HTTP_OK);
        }
    }
    
    /**
     * setTrainingBudget
     * @param Request $request
     * @return array
     */
    public function setTrainingBudget(Request $request)
    {
        $user  = Auth::user();
        
        DB::beginTransaction();
        try {
                $organisationObj = DB::table(Organization::table)
                        ->where(Organization::slug, '=',$request->orgSlug)
                        ->select('id')
                        ->first();
                if(empty($organisationObj)){
                    throw new \Exception("Invalid Organisation");
                }

                $hrmTrainingBudgetObj = HrmTrainingBudget::where(HrmTrainingBudget::org_id, '=',$organisationObj->id)->first();
                if(empty($hrmTrainingBudgetObj)){ //create
                    $hrmTrainingBudgetObj = new HrmTrainingBudget;
                    $hrmTrainingBudgetObj->{HrmTrainingBudget::org_id} = $organisationObj->id;
                    $hrmTrainingBudgetObj->{HrmTrainingBudget::current_balance} = $request->amount;
                    $hrmTrainingBudgetObj->{HrmTrainingBudget::total_balance} = $request->amount;
                    $hrmTrainingBudgetObj->{HrmTrainingBudget::creator_user_id} = $user->id;
                    $hrmTrainingBudgetObj->{HrmTrainingBudget::last_updated_user_id} = $user->id;
                    $hrmTrainingBudgetObj->save();
                } else { // update
                    $hrmTrainingBudgetObj->{HrmTrainingBudget::current_balance} = $request->amount;
                    $hrmTrainingBudgetObj->{HrmTrainingBudget::total_balance} = $request->amount;
                    $hrmTrainingBudgetObj->{HrmTrainingBudget::last_updated_user_id} = $user->id;
                    $hrmTrainingBudgetObj->save();
                }
                
                DB::commit();
                
                $msg = "Training budget updated successfully";
                
        } catch(\Exception $e){
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;            
        }
        
        return $this->content = array(
            'data'   => array(
                "msg"=>$msg
                ),
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );
    
    }

    /**
     * setTrainingFeedbackDuration
     * @param Request $request
     * @return array
     */
    public function setTrainingFeedbackDuration(Request $request)
    {
        $user  = Auth::user();
        
        DB::beginTransaction();
        try {
                $organisationObj = DB::table(Organization::table)
                        ->where(Organization::slug, '=',$request->orgSlug)
                        ->select('id')
                        ->first();
                if(empty($organisationObj)){
                    throw new \Exception("Invalid Organisation");
                }

                $hrmTrainingFeedbackDurationObj = HrmTrainingFeedbackDuration::where(HrmTrainingFeedbackDuration::org_id, '=',$organisationObj->id)->first();
                if(empty($hrmTrainingFeedbackDurationObj)){ //create
                    $hrmTrainingFeedbackDurationObj = new HrmTrainingFeedbackDuration;
                    $hrmTrainingFeedbackDurationObj->{HrmTrainingFeedbackDuration::org_id} = $organisationObj->id;
                    $hrmTrainingFeedbackDurationObj->{HrmTrainingFeedbackDuration::duration_in_days} = $request->days;
                    $hrmTrainingFeedbackDurationObj->save();
                } else { // update
                    $hrmTrainingFeedbackDurationObj->{HrmTrainingFeedbackDuration::duration_in_days} = $request->days;
                    $hrmTrainingFeedbackDurationObj->save();
                }
                
                DB::commit();
                
                $msg = "Training feedback duration updated successfully";
                
        } catch(\Exception $e){
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;            
        }
        
        return $this->content = array(
            'data'   => array(
                "msg"=>$msg
                ),
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );
    
    }
    
    /**
     * update Training Status (start , cancel or complete a training) 
     * @param Request $request
     * @return array
     */
    public function setTrainingStatus(Request $request)
    {
        $user  = Auth::user();
        
        DB::beginTransaction();
        try {
                $msg = "invalid request status";
                $organisationObj = DB::table(Organization::table)
                        ->where(Organization::slug, '=',$request->orgSlug)
                        ->first();
                if(empty($organisationObj)){
                    throw new \Exception("Invalid Organisation");
                }

                $employeeObj = DB::table(OrgEmployee::table)
                        ->where(OrgEmployee::org_id, '=',$organisationObj->id)
                        ->where(OrgEmployee::user_id, '=',$user->id)
                        ->select('id')
                        ->first();
                if(empty($employeeObj)){
                    throw new \Exception("No permission to start training");
                }

                $hrmTrainingRequestObj = HrmTrainingRequest::where(HrmTrainingRequest::slug, '=',$request->slug)->first();
                if(empty($hrmTrainingRequestObj)){
                    throw new \Exception("Invalid TrainingRequest");
                }
             
                $hrmTrainingStatusObj = HrmTrainingStatus::where(HrmTrainingStatus::value, '=',"approved")->first();
                if(empty($hrmTrainingStatusObj)){
                    throw new \Exception("Invalid Training Status");
                }
                
                $hrmTrainingBudgetObj = HrmTrainingBudget::where(HrmTrainingBudget::org_id, '=',$organisationObj->id)->first();
                if(empty($hrmTrainingBudgetObj)){
                    throw new \Exception("Training Budget is not set!");
                }
                
                if($request->status == 'start'){
                    
                    if($hrmTrainingRequestObj->{HrmTrainingRequest::is_cancelled}){
                        throw new \Exception("Training is cancelled,cannot start a cancelled training!");
                    }
                    if($hrmTrainingRequestObj->{HrmTrainingRequest::status_id} != $hrmTrainingStatusObj->id){
                        throw new \Exception("Cannot start, TrainingRequest is not approved!");
                    }
                    
                    if($hrmTrainingRequestObj->{HrmTrainingRequest::in_progress}){
                        throw new \Exception("Training is already started!");
                    }

                    $trainingCost = $hrmTrainingRequestObj->{HrmTrainingRequest::cost};
                    $trainingBudgetTotal = $hrmTrainingBudgetObj->{HrmTrainingBudget::total_balance};
                    if( $trainingCost > $trainingBudgetTotal ){
                        throw new \Exception("Insufficient Training Budget total!");
                    }
                    $hrmTrainingBudgetObj->{HrmTrainingBudget::total_balance} = $trainingBudgetTotal - $trainingCost;
                    $hrmTrainingBudgetObj->save();
                    
                    
                    $hrmTrainingBudgetHistoryObj = new HrmTrainingBudgetHistory;
                    $hrmTrainingBudgetHistoryObj->{HrmTrainingBudgetHistory::org_id} = $organisationObj->id;
                    $hrmTrainingBudgetHistoryObj->{HrmTrainingBudgetHistory::training_request_id} = $hrmTrainingRequestObj->id;
                    $hrmTrainingBudgetHistoryObj->{HrmTrainingBudgetHistory::training_budget_id} = $hrmTrainingBudgetObj->id;
                    $hrmTrainingBudgetHistoryObj->{HrmTrainingBudgetHistory::old_balance}=$trainingBudgetTotal;
                    $hrmTrainingBudgetHistoryObj->{HrmTrainingBudgetHistory::new_balance}=$hrmTrainingBudgetObj->{HrmTrainingBudget::total_balance};
                    $hrmTrainingBudgetHistoryObj->{HrmTrainingBudgetHistory::creator_user_id}=$user->id;
                    $hrmTrainingBudgetHistoryObj->save();
                    
                    
                    //set in_progress  --> started
                    $hrmTrainingRequestObj->{HrmTrainingRequest::in_progress}=TRUE;
                    $hrmTrainingRequestObj->save();
                    
                    $msg = "Training started successfully";
                    
                } else if($request->status == 'cancel'){
                    
                    if($hrmTrainingRequestObj->{HrmTrainingRequest::is_cancelled}){
                        throw new \Exception("Training is already cancelled!");
                    }

                    $trainingCost = $hrmTrainingRequestObj->{HrmTrainingRequest::cost};
                    $trainingBudgetTotal = $hrmTrainingBudgetObj->{HrmTrainingBudget::total_balance};
                    $trainingBudgetBalance = $hrmTrainingBudgetObj->{HrmTrainingBudget::current_balance};
                    
                    $hrmTrainingBudgetObj->{HrmTrainingBudget::current_balance} = $trainingBudgetBalance + $trainingCost;
                    if($hrmTrainingRequestObj->{HrmTrainingRequest::in_progress}){
                        $hrmTrainingBudgetObj->{HrmTrainingBudget::total_balance} = $trainingBudgetTotal + $trainingCost;
                    }                    
                    $hrmTrainingBudgetObj->save();
                    
                    
                    $hrmTrainingBudgetHistoryObj = new HrmTrainingBudgetHistory;
                    $hrmTrainingBudgetHistoryObj->{HrmTrainingBudgetHistory::org_id} = $organisationObj->id;
                    $hrmTrainingBudgetHistoryObj->{HrmTrainingBudgetHistory::training_request_id} = $hrmTrainingRequestObj->id;
                    $hrmTrainingBudgetHistoryObj->{HrmTrainingBudgetHistory::training_budget_id} = $hrmTrainingBudgetObj->id;
                    $hrmTrainingBudgetHistoryObj->{HrmTrainingBudgetHistory::old_balance}=$trainingBudgetBalance;
                    $hrmTrainingBudgetHistoryObj->{HrmTrainingBudgetHistory::new_balance}=$hrmTrainingBudgetObj->{HrmTrainingBudget::current_balance};
                    $hrmTrainingBudgetHistoryObj->{HrmTrainingBudgetHistory::creator_user_id}=$user->id;
                    $hrmTrainingBudgetHistoryObj->save();
                    
                    //set is_cancelled  
                    $hrmTrainingRequestObj->{HrmTrainingRequest::in_progress} = FALSE;
                    $hrmTrainingRequestObj->{HrmTrainingRequest::is_cancelled} = TRUE;
                    $hrmTrainingRequestObj->save();
                    
                    $msg = "Training cancelled successfully";
                } else if($request->status == "completed"){
                    if($hrmTrainingRequestObj->{HrmTrainingRequest::is_cancelled}){
                        throw new \Exception("Training is cancelled,cannot complete a cancelled training!");
                    }
                    if($hrmTrainingRequestObj->{HrmTrainingRequest::is_completed}){
                        throw new \Exception("Training is already completed!");
                    }
                    if(!$hrmTrainingRequestObj->{HrmTrainingRequest::in_progress}){
                        throw new \Exception("Training is not started!");
                    }
                    $hrmTrainingRequestObj->{HrmTrainingRequest::is_completed} = TRUE;
                    $hrmTrainingRequestObj->save();
                    $msg = "Training completed successfully";
                }
                
                DB::commit();

        } catch(\Exception $e){
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;            
        }
        
        return $this->content = array(
            'data'   => array(
                "msg"=>$msg
                ),
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );
    
    }
    
    /**
     * getTrainingSettings
     * @param Request $request
     * @return array
     */
    public function getTrainingSettings(Request $request)
    {
        $user  = Auth::user();
        
        DB::beginTransaction();
        

        try {
                $organisationObj = DB::table(Organization::table)
                        ->where(Organization::slug, '=',$request->orgSlug)
                        ->select('id')
                        ->first();
                if(empty($organisationObj)){
                    throw new \Exception("Invalid Organisation");
                }

                $hrmTrainingBudgetObj = HrmTrainingBudget::where(HrmTrainingBudget::org_id, '=',$organisationObj->id)->first();
                $hrmTrainingFeedbackDurationObj = HrmTrainingFeedbackDuration::where(HrmTrainingFeedbackDuration::org_id, '=',$organisationObj->id)->first();
                
                $settingsArr= array(
                    "trainingBudget"=>array("currentBalance"=>0,"totalBalance"=>0),
                    "trainingFeedback"=>array("days"=>30)
                    );
                
                if(!empty($hrmTrainingBudgetObj)){
                    $settingsArr["trainingBudget"]["currentBalance"]=$hrmTrainingBudgetObj->{HrmTrainingBudget::current_balance};
                    $settingsArr["trainingBudget"]["totalBalance"]=$hrmTrainingBudgetObj->{HrmTrainingBudget::total_balance};
                }
                if(!empty($hrmTrainingFeedbackDurationObj)){
                    $settingsArr["trainingFeedback"]["days"]=$hrmTrainingFeedbackDurationObj->{HrmTrainingFeedbackDuration::duration_in_days};
                }
                
        } catch(\Exception $e){
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;            
        }
        
        return $this->content = array(
            'data'   => array(
                "settings"=>$settingsArr
                ),
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );
    
    }
    
    
    /**
     * setTrainingScore
     * @param Request $request
     * @return array
     */
    public function setTrainingScore(Request $request)
    {
        $user  = Auth::user();
        
        DB::beginTransaction();

        try {
            $organisationObj = DB::table(Organization::table)
                    ->where(Organization::slug, '=',$request->orgSlug)
                    ->select('id')
                    ->first();
            if(empty($organisationObj)){
                throw new \Exception("Invalid Organisation");
            }
            $hrmTrainingRequestObj = HrmTrainingRequest::where(HrmTrainingRequest::slug, '=',$request->trainingRequestSlug)->first();
            if(empty($hrmTrainingRequestObj)){
                throw new \Exception("Invalid TrainingRequest");
            }
            
            $formAnswerSheetObj = FormAnswerSheet::where(FormAnswerSheet::slug, '=',$request->formAnswerSheetSlug)->first();
            if(empty($formAnswerSheetObj)){
                throw new \Exception("Invalid FormAnswerSheet");
            }
            $hrmTrainingFeedbackResponse = HrmTrainingFeedbackResponse::where(HrmTrainingFeedbackResponse::org_id, '=',$organisationObj->id)
                    ->where(HrmTrainingFeedbackResponse::training_request_id, '=',$hrmTrainingRequestObj->id)
                    ->where(HrmTrainingFeedbackResponse::form_answersheet_id, '=',$formAnswerSheetObj->id)
                    ->first();
            if(empty($hrmTrainingFeedbackResponse)){
                throw new \Exception("Invalid Hrm Training Feedback Response");
            }

            $hrmTrainingFeedbackResponse->{HrmTrainingFeedbackResponse::score}=$request->score;
            $hrmTrainingFeedbackResponse->save();
                
        } catch(\Exception $e){
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;            
        }
        
        return $this->content = array(
            'data'   => array(
                "msg"=>"Feedback score saved",
                "responseId"=>$hrmTrainingFeedbackResponse->id
                ),
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );
    
    }
}