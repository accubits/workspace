<?php

namespace Modules\FormManagement\Repositories\FormSubmission;


use Illuminate\Http\Request;
use App\Exceptions\FieldException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Modules\Common\Utilities\Utilities;
use Modules\UserManagement\Entities\User;
use Modules\FormManagement\Repositories\FormSubmissionRepositoryInterface;
use Modules\FormManagement\Entities\FormMaster;
use Modules\FormManagement\Entities\FormQuestion;
use Modules\FormManagement\Entities\FormComponentType;
use Modules\Common\Entities\Country;

use Modules\FormManagement\Entities\FormAnswerSheet;
use Modules\FormManagement\Entities\FormAnswerText;
use Modules\FormManagement\Entities\FormAnswerAddress;
use Modules\FormManagement\Entities\FormAnswerAttachment;
use Modules\FormManagement\Entities\FormAnswerDatetime;
use Modules\FormManagement\Entities\FormAnswerLikert;
use Modules\FormManagement\Entities\FormAnswerInteger;
use Modules\FormManagement\Entities\FormAnswerLongText;
use Modules\FormManagement\Entities\FormAnswers;
use Illuminate\Support\Facades\Auth;

use Modules\HrmManagement\Entities\HrmTrainingRequest;
use Modules\HrmManagement\Entities\HrmTrainingFeedbackFormMap;
use Modules\HrmManagement\Entities\HrmTrainingFeedbackResponse;
use Modules\OrgManagement\Entities\OrgEmployee;

class FormSubmissionRepository implements FormSubmissionRepositoryInterface {

    private $multiOptionTypes;
    public function __construct() {
            $this->multiOptionTypes=array("checkboxes", "multipleChoice", "dropdown");
    }

    public function save($form_slug, Request $request){ 

        DB::beginTransaction();
        try {
            $formAnswerSheetObj=null;
            $formmaster = $this->getFormMasterObj($form_slug);
            if ($request->has('formResponse')) {
                $formResponsesArr = json_decode($request->formResponse, true);
                $formComponentsResp = $formResponsesArr['formComponents'];
                $formAnswerSheetObj=$this->setAllFormComponentsResponse($formmaster, $formComponentsResp, $formResponsesArr);
            }
            DB::commit();
        } catch (FieldException $e) {
            DB::rollBack();
            $errorDetailsArr = $e->getField();
            return array(
                "status" => "ERROR",
                "error" => array(
                    "msg" => $e->getMessage(),
                    "type" => "duplicateError",
                    "form" =>array(
                        "componentType"=>$errorDetailsArr["componentType"],
                        "componentId"=>$errorDetailsArr['componentId']
                    )
                ),
                "code" => Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return array(
                "status" => "ERROR",
                "error" => array(
                    "msg" => $e->getMessage()
                ),
                "code" => Response::HTTP_OK);
        }
        return array("status" => "OK",
            "data" => array(
                "msg" => "saved",
                "answersheet_slug"=>$formAnswerSheetObj->{FormAnswerSheet::slug},
                "form_slug" => $formmaster->{FormMaster::form_slug}
            ),
            "code" => Response::HTTP_OK);
    }

    private function setAllFormComponentsResponse($formmaster, $formResponseArr, $formResponsesArr) {


        $formResponsesCol=collect($formResponseArr);

        $qnsComponentIds= $formResponsesCol->pluck('componentId');      
        $qnsDataCol = DB::table(FormQuestion::table)
                ->select(FormQuestion::table.'.id as qns_id',FormQuestion::form_question_text, FormQuestion::is_mandatory. ' AS isRequired', FormQuestion::has_unique_answer. ' AS noDuplicate', FormQuestion::form_components_id)
                ->whereIn(FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        //map form_components_id to qns_id and related fields
        $componentQnsMapCol=$qnsDataCol->groupBy('form_components_id');

        $user = Auth::user();
        
        $formAnswerSheetObj= $this->getNewFormAnswerSheet($formmaster, 
                !empty($user)? $user->id : null,
                $formResponsesArr
            );

        $formComponentNamesColObj = DB::table(FormComponentType::table)
                ->pluck(FormComponentType::cmp_type_name);

        $formResponsesCol
                ->each(function($item, $key) use ($formComponentNamesColObj, $componentQnsMapCol, $formAnswerSheetObj) {

                    $this->setOneFormComponentResponse($formComponentNamesColObj,$componentQnsMapCol, $item, $formAnswerSheetObj, $key);
                });
        return $formAnswerSheetObj;
    }

    private function getNewFormAnswerSheet($formmaster, $userId, $formResponsesArr) {

        $allowMultiSubmit = $formmaster->{FormMaster::allow_multi_submit};
        $formMasterId = $formmaster->id;
        $formPublishedAt = $formmaster->{FormMaster::UPDATED_AT};
        
        if(!empty($formResponsesArr["trainingRequestSlug"]) ){
            $hrmTrainingRequestArr = $this->getTrainingRequestObjAfterValidation($formResponsesArr["trainingRequestSlug"], $formMasterId, $userId);
            $hrmTrainingRequestObj = $hrmTrainingRequestArr["trainingRequestObj"];
            $employeeObj = $hrmTrainingRequestArr["employeeObj"];
        } else if(!$allowMultiSubmit){
            $formPreviousAnswerObj = FormAnswerSheet::where(FormAnswerSheet::form_user_id, $userId)
                    ->where(FormAnswerSheet::is_discarded, FALSE)
                    ->where(FormAnswerSheet::form_master_id, $formMasterId)
                    ->where(FormAnswerSheet::submit_datetime,">",$formPublishedAt)->first();

            if( !empty($formPreviousAnswerObj) ){
                throw new \Exception("Form already submitted by you!");
                //Previous form response slug:-".$formPreviousAnswerObj->{FormAnswerSheet::slug}
            }
        }

        //discard previous answersheets
        FormAnswerSheet::where(FormAnswerSheet::form_user_id, $userId)
            ->where(FormAnswerSheet::form_master_id, $formMasterId)
            ->update(array(FormAnswerSheet::is_discarded=>TRUE));
        
        $formAnswerSheetObj = new FormAnswerSheet;
        $formAnswerSheetObj->{FormAnswerSheet::form_master_id} = $formMasterId;
        $formAnswerSheetObj->{FormAnswerSheet::slug} = Utilities::getUniqueId();
        $formAnswerSheetObj->{FormAnswerSheet::form_user_id} = $userId;//null;
        $formAnswerSheetObj->{FormAnswerSheet::submit_datetime} = Carbon::now();
        $formAnswerSheetObj->{FormAnswerSheet::is_discarded} = FALSE;
        $formAnswerSheetObj->save();
        
        if(!empty($formResponsesArr["trainingRequestSlug"]) ){ 
            $hrmTrainingFeedbackResponseObj = new HrmTrainingFeedbackResponse;
            $hrmTrainingFeedbackResponseObj->{HrmTrainingFeedbackResponse::form_master_id} =  $formMasterId;
            $hrmTrainingFeedbackResponseObj->{HrmTrainingFeedbackResponse::form_answersheet_id} =  $formAnswerSheetObj->id;
            $hrmTrainingFeedbackResponseObj->{HrmTrainingFeedbackResponse::training_request_id} =  $hrmTrainingRequestObj->id;
            $hrmTrainingFeedbackResponseObj->{HrmTrainingFeedbackResponse::employee_id} =  $employeeObj->id;
            $hrmTrainingFeedbackResponseObj->{HrmTrainingFeedbackResponse::org_id} = $employeeObj->{OrgEmployee::org_id};
            $hrmTrainingFeedbackResponseObj->{HrmTrainingFeedbackResponse::is_final} =  TRUE;
            $hrmTrainingFeedbackResponseObj->save();
        }
        return $formAnswerSheetObj;
    }
    
    private function getTrainingRequestObjAfterValidation($trainingRequestSlug, $formMasterId, $userId) {
        $hrmTrainingRequestObj = HrmTrainingRequest::where(HrmTrainingRequest::slug, '=',$trainingRequestSlug)
                ->select('id')
                ->first();
        if(empty($hrmTrainingRequestObj)){
            throw new \Exception("Invalid TrainingRequest");
        }
        $hrmTrainingFeedbackFormMap = HrmTrainingFeedbackFormMap::where(HrmTrainingFeedbackFormMap::hrm_training_request_id, 
                '=',$hrmTrainingRequestObj->id)
                    ->first();
        if(empty($hrmTrainingFeedbackFormMap)){
            throw new \Exception("Error, TrainingRequest feedback form not mapped");
        }

        $trainingFormType=null;
        if($hrmTrainingFeedbackFormMap->{HrmTrainingFeedbackFormMap::post_training_form_master_id} == $formMasterId){
            $trainingFormType="postTrainingFeedbackForm";
        } else if($hrmTrainingFeedbackFormMap->{HrmTrainingFeedbackFormMap::post_course_form_master_id} == $formMasterId){
            $trainingFormType="postCourseFeedbackForm";
        } else {
            throw new \Exception("Invalid TrainingRequest feedback form invalid");
        }
        $employeeObj = DB::table(OrgEmployee::table)
        ->where(OrgEmployee::user_id, '=',$userId)
        ->first();
        if(empty($employeeObj)){
            throw new \Exception("Not an employee");
        }
            
        $hrmTrainingFeedbackResponseObj = HrmTrainingFeedbackResponse::where(
            HrmTrainingFeedbackResponse::form_master_id,$formMasterId)
            ->where(HrmTrainingFeedbackResponse::training_request_id,$hrmTrainingRequestObj->id)
            ->where(HrmTrainingFeedbackResponse::employee_id,$employeeObj->id)
            ->first();
        if( !empty($hrmTrainingFeedbackResponseObj) ){
            throw new \Exception("Feedback Form already submitted by you!");
        }

        return array(
                "trainingRequestObj" =>$hrmTrainingRequestObj,
                "trainingFeedbackFormMap" =>$hrmTrainingFeedbackFormMap,
                "employeeObj" => $employeeObj
            );
    }

    private function getFormAnswersFactoryObj($formAnswerSheetObj,$item,$compQnsDataObj) {

        $FormAnswerObj = null;
        if(in_array($item['type'], $this->multiOptionTypes)){
            $chosenOptionIdsCol=collect($item[$item['type']]['chosen']);
            $chosenOptionIdsCol->each(function($optId, $key) use ($formAnswerSheetObj,$item,$compQnsDataObj){
                $formAnswer=new FormAnswers;
                $formAnswer->{FormAnswers::form_answersheet_id} = $formAnswerSheetObj->id;
                $formAnswer->{FormAnswers::form_components_id} = $item['componentId'];
                $formAnswer->{FormAnswers::form_question_id} = $compQnsDataObj->qns_id;            
                $formAnswer->{FormAnswers::form_qns_options_id} = $optId;
                $formAnswer->save();
            });
            $FormAnswerObj = null;
        } else {
            $formAnswer=new FormAnswers;
            $formAnswer->{FormAnswers::form_answersheet_id} = $formAnswerSheetObj->id;
            $formAnswer->{FormAnswers::form_components_id} = $item['componentId'];
            $formAnswer->{FormAnswers::form_question_id} = $compQnsDataObj->qns_id;            
            $formAnswer->{FormAnswers::form_qns_options_id} = null;
            $formAnswer->save();
            $FormAnswerObj = $formAnswer;
        }
        return $FormAnswerObj;
    }
    
    

    private function setOneFormComponentResponse($formComponentNamesColObj, $componentQnsMapCol, $item, $formAnswerSheetObj, $key) {

        $compQnsDataObj = $componentQnsMapCol->get($item['componentId'])->get(0);

        if (!$formComponentNamesColObj->contains($item['type'])) {
            throw new \Exception("[".__FUNCTION__."] Invalid component name :-" . $item['type']);
        }

        $duplicateCheckQuestionObj = FormQuestion::where("id",$compQnsDataObj->qns_id)->where(FormQuestion::has_unique_answer, TRUE)->first();

        $isRequiredCheckQuestionObj = FormQuestion::where("id",$compQnsDataObj->qns_id)->where(FormQuestion::is_mandatory, TRUE)->first();
        $this->isRequiredValidation($isRequiredCheckQuestionObj, $item);

        $formAnswerObj = $this->getFormAnswersFactoryObj($formAnswerSheetObj, $item, $compQnsDataObj);
        //getFormAnswersFactoryObj:- sets all these component answers "checkboxes", "multipleChoice", "dropdown" rest set using below:-
        if(in_array($item['type'], $this->multiOptionTypes)){
            return null;
        }

        $item['action']= !empty($item['action'])? $item['action'] : "create";
        switch ($item['type']) {
            case "singleLineText":
                $this->duplicateCheck($item['type'], FormAnswerText::table, FormAnswerText::answer_text, $duplicateCheckQuestionObj, $item, $formAnswerObj);
                $this->setSingleLineTextObj($item, $formAnswerObj->id);
                break;
            case "paragraphText":
                $this->duplicateCheck($item['type'], FormAnswerLongText::table, FormAnswerLongText::answer_longtext, $duplicateCheckQuestionObj, $item, $formAnswerObj);
                $this->setParagraphTextObj($item, $formAnswerObj->id);
                break;
            case "number":
                $this->duplicateCheck($item['type'], FormAnswerInteger::table, FormAnswerInteger::answer_integer, $duplicateCheckQuestionObj, $item, $formAnswerObj);
                $this->setNumberObj($item, $formAnswerObj->id);
                break;
            case "email":
                $this->duplicateCheck($item['type'], FormAnswerText::table, FormAnswerText::answer_text, $duplicateCheckQuestionObj, $item, $formAnswerObj);
                $this->setEmailObj($item, $formAnswerObj->id);
                break;
            case "phone":
                $this->duplicateCheck($item['type'], FormAnswerText::table, FormAnswerText::answer_text, $duplicateCheckQuestionObj, $item, $formAnswerObj);
                $this->setPhoneObj($item, $formAnswerObj->id);
                break;
            case "date":
                $this->duplicateCheck($item['type'], FormAnswerDatetime::table, FormAnswerDatetime::answer_datetime, $duplicateCheckQuestionObj, $item, $formAnswerObj);
                $this->setDateObj($item, $formAnswerObj->id);
                break;
            case "time":
                $this->duplicateCheck($item['type'], FormAnswerText::table, FormAnswerText::answer_text, $duplicateCheckQuestionObj, $item, $formAnswerObj);
                $this->setTimeObj($item, $formAnswerObj->id);
                break;
          //  case "fileUpload":
          //      $this->setFileUploadObj($item, $formAnswerObj->id);
          //      break;
            case "rating":
                $this->setRatingObj($item, $formAnswerObj->id);
                break;
            case "website":
                $this->duplicateCheck($item['type'], FormAnswerText::table, FormAnswerText::answer_text, $duplicateCheckQuestionObj, $item, $formAnswerObj);
                $this->setWebsiteObj($item, $formAnswerObj->id);
                break;
            case "name":
                $this->setNameObj($item, $formAnswerObj->id);
                break;
            case "address":
                $this->setAddressObj($item, $formAnswerObj->id);
                break;
            case "likert":
                $this->setLikertObj($item, $formAnswerObj->id);
                break;
            case "price":
                $this->setPriceObj($item, $formAnswerObj->id);
                break;
            default :
                throw new \Exception("[".__FUNCTION__."] error: Invalid component name :-" . $item['type']);
        }

        return null;
    }

    private function setPriceObj($param, $formAnswerId) {
        $itArr = $param['price'];
        $itArr['answer'] = !empty($itArr['currency'])?$itArr['currency']:0;
        $itArr['answer2'] = !empty($itArr['currencyUnit'])?$itArr['currencyUnit']:0;
        $this->setAnswerTextObj($param['action'], $formAnswerId, $itArr);
    }

    public function isRequiredValidation($isRequiredCheckQuestionObj, $item) {

        if(empty($isRequiredCheckQuestionObj)){
            return null;
        }

        switch ($item['type']){
            case "name" :
                if( empty($item[$item['type']]['first']) || empty($item[$item['type']]['last']) ){
                    throw new \Exception("[".__FUNCTION__."] first and last field is mandatory. Component type :-" . $item['type'].". componentId:-".$item['componentId']);
                }
                break;
            case "address" :
                if( !isset($item[$item['type']]['streetAddress']) ){
                    throw new \Exception("[".__FUNCTION__."] Required field (streetAddress) is missing. Component type :-" . $item['type'].". componentId:-".$item['componentId']);
                }
                $countryObj = Country::where("id", $item[$item['type']]['countryId'])->first();
                if(empty($countryObj)){
                    throw new \Exception("Required field (country) is not selected.form cannot be submitted!");
                }
                break;
            case "likert" :
                if( empty($item[$item['type']]['answers']) ){
                    throw new \Exception("[".__FUNCTION__."] Required field is missing. Component type :-" . $item['type'].". componentId:-".$item['componentId']);
                }
                break;
            case "price" :
                if( !is_numeric($item[$item['type']]['currency'])  &&  !is_numeric($item[$item['type']]['currencyUnit']) ){
                    throw new \Exception("[".__FUNCTION__."] Required field is missing. Component type :-" . $item['type'].". componentId:-".$item['componentId']);
                }
                break;
            default :
                if( empty($item[$item['type']]['answer']) && !in_array($item['type'], $this->multiOptionTypes)){
                    throw new \Exception("[".__FUNCTION__."] Required field is missing. Component type :-" . $item['type'].". componentId:-".$item['componentId']);
                }
        }
    }

    public function duplicateCheck($componentType, $masterTableName, $searchColumnName,$duplicateCheckQuestionObj, $item, $formAnswerObj) {
        if(empty($duplicateCheckQuestionObj)){
            return null;
        }

        $answer = $item[$componentType]['answer'];
        $similarFormAnswerQueryObj = DB::table($masterTableName)
        ->join(FormAnswers::table, $masterTableName . "." . FormAnswerText::form_answers_id, '=', FormAnswers::table . '.id')
        ->join(FormAnswerSheet::table, FormAnswers::table . "." . FormAnswers::form_answersheet_id, '=', FormAnswerSheet::table . '.id')
        ->select($searchColumnName, FormAnswers::form_components_id)
        ->where($searchColumnName, $answer)
        ->where(FormAnswers::form_components_id, $item['componentId'])
        ->where(FormAnswerSheet::is_discarded, FALSE);

        $duplicateFound=false;
        if($item['action']=="create"){
            $similarFormAnswerObjCount = $similarFormAnswerQueryObj->count();
            if($similarFormAnswerObjCount > 0){
                $duplicateFound=true;
            }
        } else if($item['action']=="edit") {
            $similarFormAnswerObjCount = $similarFormAnswerQueryObj
                    ->where($masterTableName.".".FormAnswerText::form_answers_id,"!=",$formAnswerObj->id)->count();
            if($similarFormAnswerObjCount > 0){
                $duplicateFound=true;
            }
        }

        if($duplicateFound){
            throw new FieldException(
                    "'".$answer."' is a duplicate entry for ".$componentType.". Submission failed!",
                    0,NULL,
                    array("componentType"=>$componentType,
                        "componentId"=>$item['componentId'])
                    );
        }
    }

    public function setLikertObj($param, $formAnswerId) {
        $itArr = $param['likert'];
        foreach ($itArr['answers'] as $value) {
            $this->setAnswerSingleLikertObj($param['action'], $formAnswerId, $value);
        }        
    }
    
    private function setAnswerSingleLikertObj($action, $formAnswerId, $paramArr) {
        $formAnswerLikertObj = null;
        if ($action == 'create') {
            $formAnswerLikertObj = new FormAnswerLikert;
            $formAnswerLikertObj->{FormAnswerLikert::form_answers_id} = $formAnswerId;
            $formAnswerLikertObj->{FormAnswerLikert::form_qns_likert_stmt_id} = $paramArr['stmtId'];
            $formAnswerLikertObj->{FormAnswerLikert::form_qns_likert_col_id} = $paramArr['colId'];
            $formAnswerLikertObj->saveOrFail();
        } elseif ($action == 'update') {
            $formAnswerLikertObj = FormAnswerLikert::where(FormAnswerLikert::form_answers_id, $formAnswerId)->firstOrFail();
            $formAnswerLikertObj->{FormAnswerLikert::form_answers_id} = $formAnswerId;
            $formAnswerLikertObj->{FormAnswerText::form_qns_likert_stmt_id} = $paramArr['stmtId'];
            $formAnswerLikertObj->{FormAnswerText::form_qns_likert_col_id} = $paramArr['colId'];
            $formAnswerLikertObj->saveOrFail();
        } elseif ($action == 'delete') {
            $formAnswerLikertObj = FormAnswerLikert::where(FormAnswerLikert::form_answers_id, $formAnswerId)->firstOrFail();
            $formAnswerLikertObj->delete();
        }
        return $formAnswerLikertObj;
    }    
    
    
    public function setSingleLineTextObj($param, $formAnswerId) {
        $itArr = $param['singleLineText'];
        $this->setAnswerTextObj($param['action'], $formAnswerId, $itArr);
    }

    public function setParagraphTextObj($param, $formAnswerId) {
        $itArr = $param['paragraphText'];
        $this->setAnswerLongTextObj($param['action'], $formAnswerId, $itArr);
    }

    public function setEmailObj($param, $formAnswerId) {
        $itArr = $param['email'];
        $this->setAnswerTextObj($param['action'], $formAnswerId, $itArr);        
    }

    private function setNumberObj($param, $formAnswerId) {
        $itArr = $param['number'];
        $this->setIntegerObj($param['action'], $formAnswerId, $itArr);
    }
    
    private function setRatingObj($param, $formAnswerId) {
        $itArr = $param['rating'];
        $this->setIntegerObj($param['action'], $formAnswerId, $itArr);
    }    

    private function setPhoneObj($param, $formAnswerId) {
        $itArr = $param['phone'];
        $this->setAnswerTextObj($param['action'], $formAnswerId, $itArr);
    }
    
    private function setWebsiteObj($param, $formAnswerId) {
        $itArr = $param['website'];
        $this->setAnswerTextObj($param['action'], $formAnswerId, $itArr);
    }
    
    private function setDateObj($param, $formAnswerId) {
        $itArr = $param['date'];
        $this->setAnswerDateTime($param['action'], $formAnswerId, $itArr);        
    }
    
    private function setTimeObj($param, $formAnswerId) {
        $itArr = $param['time'];
        $this->setAnswerTextObj($param['action'], $formAnswerId, $itArr);
    }
    
    private function setAnswerDateTime($action, $formAnswerId, $paramArr) {
        $formAnswerDateTimeObj = null;
        if ($action == 'create') {
            $formAnswerDateTimeObj = new FormAnswerDatetime;
            $formAnswerDateTimeObj->{FormAnswerDatetime::form_answers_id} = $formAnswerId;
            $formAnswerDateTimeObj->{FormAnswerDatetime::answer_datetime} = !empty($paramArr['answer'])?date('Y-m-d H:i:s',$paramArr['answer']):null;
            $formAnswerDateTimeObj->saveOrFail();
        } elseif ($action == 'update') {
            $formAnswerDateTimeObj = FormAnswerDatetime::where(FormAnswerText::form_answers_id, $formAnswerId)->firstOrFail();
            $formAnswerDateTimeObj->{FormAnswerDatetime::form_answers_id} = $formAnswerId;
            $formAnswerDateTimeObj->{FormAnswerDatetime::answer_datetime} = !empty($paramArr['answer'])?date('Y-m-d H:i:s',$paramArr['answer']):null;
            $formAnswerDateTimeObj->saveOrFail();
        } elseif ($action == 'delete') {
            $formAnswerDateTimeObj = FormAnswerDatetime::where(FormAnswerText::form_answers_id, $formAnswerId)->firstOrFail();
            $formAnswerDateTimeObj->delete();
        }
        return $formAnswerDateTimeObj;
    }

    private function setAddressObj($param, $formAnswerId) {
        $itArr = $param['address'];
        $this->setAnswerAddress($param['action'], $formAnswerId, $itArr);
    }

    private function setAnswerAddress($action, $formAnswerId, $paramArr) {
        $formAnswerAddressObj = null;
        if ($action == 'create') {
            $formAnswerAddressObj = new FormAnswerAddress;
            $formAnswerAddressObj->{FormAnswerAddress::form_answers_id} = $formAnswerId;
            $formAnswerAddressObj->{FormAnswerAddress::street_address} = $paramArr['streetAddress'];
            $formAnswerAddressObj->{FormAnswerAddress::address_line2} = $paramArr['addressLine2'];
            $formAnswerAddressObj->{FormAnswerAddress::city} = $paramArr['city'];
            $formAnswerAddressObj->{FormAnswerAddress::state} = $paramArr['state'];
            $formAnswerAddressObj->{FormAnswerAddress::country_id} = $paramArr['countryId'];
            $formAnswerAddressObj->{FormAnswerAddress::zip_code} = $paramArr['zipCode'];
            $formAnswerAddressObj->saveOrFail();
        } elseif ($action == 'update') {
            $formAnswerAddressObj = FormAnswerAddress::where(FormAnswerAddress::form_answers_id, $formAnswerId)->firstOrFail();
            $formAnswerAddressObj->{FormAnswerAddress::form_answers_id} = $formAnswerId;
            $formAnswerAddressObj->{FormAnswerAddress::street_address} = $paramArr['streetAddress'];
            $formAnswerAddressObj->{FormAnswerAddress::address_line2} = $paramArr['addressLine2'];
            $formAnswerAddressObj->{FormAnswerAddress::city} = $paramArr['city'];
            $formAnswerAddressObj->{FormAnswerAddress::state} = $paramArr['state'];
            $formAnswerAddressObj->{FormAnswerAddress::country_id} = $paramArr['countryId'];
            $formAnswerAddressObj->{FormAnswerAddress::zip_code} = $paramArr['zipCode'];
            $formAnswerAddressObj->saveOrFail();
        } elseif ($action == 'delete') {
            $formAnswerAddressObj = FormAnswerAddress::where(FormAnswerText::form_answers_id, $formAnswerId)->firstOrFail();
            $formAnswerAddressObj->delete();
        }
        return $formAnswerAddressObj;
    }
    
    private function setNameObj($param, $formAnswerId) {
        $itArr = $param['name'];
        $itArr['answer'] = $itArr['first'];
        $itArr['answer2'] = $itArr['last'];
        $this->setAnswerTextObj($param['action'], $formAnswerId, $itArr);
    }

    private function setAnswerTextObj($action, $formAnswerId, $paramArr) {
        $formAnswerTextObj = null;
        if ($action == 'create') {
            $formAnswerTextObj = new FormAnswerText;
            $formAnswerTextObj->{FormAnswerText::form_answers_id} = $formAnswerId;
            $formAnswerTextObj->{FormAnswerText::answer_text} = $paramArr['answer'];
            $formAnswerTextObj->{FormAnswerText::answer_text2} = !empty($paramArr['answer2'])?$paramArr['answer2']:null;
            $formAnswerTextObj->saveOrFail();
        } elseif ($action == 'update') {
            $formAnswerTextObj = FormAnswerText::where(FormAnswerText::form_answers_id, $formAnswerId)->firstOrFail();
            $formAnswerTextObj->{FormAnswerText::form_answers_id} = $formAnswerId;
            $formAnswerTextObj->{FormAnswerText::answer_text} = $paramArr['answer'];
            $formAnswerTextObj->{FormAnswerText::answer_text2} = !empty($paramArr['answer2'])?$paramArr['answer2']:null;
            $formAnswerTextObj->saveOrFail();
        } elseif ($action == 'delete') {
            $formAnswerTextObj = FormAnswerText::where(FormAnswerText::form_answers_id, $formAnswerId)->firstOrFail();
            $formAnswerTextObj->delete();
        }
        return $formAnswerTextObj;
    }

    private function setAnswerLongTextObj($action, $formAnswerId, $paramArr) {
        $formAnswerLongTextObj = null;
        if ($action == 'create') {
            $formAnswerLongTextObj = new FormAnswerLongText;
            $formAnswerLongTextObj->{FormAnswerLongText::form_answers_id} = $formAnswerId;
            $formAnswerLongTextObj->{FormAnswerLongText::answer_longtext} = $paramArr['answer'];            
            $formAnswerLongTextObj->saveOrFail();
        } elseif ($action == 'update') {
            $formAnswerLongTextObj = FormAnswerLongText::where(FormAnswerLongText::form_answers_id, $formAnswerId)->firstOrFail();
            $formAnswerLongTextObj->{FormAnswerLongText::form_answers_id} = $formAnswerId;
            $formAnswerLongTextObj->{FormAnswerLongText::answer_longtext} = $paramArr['answer'];
            $formAnswerLongTextObj->saveOrFail();
        } elseif ($action == 'delete') {
            $formAnswerLongTextObj = FormAnswerLongText::where(FormAnswerLongText::form_answers_id, $formAnswerId)->firstOrFail();
            $formAnswerLongTextObj->delete();
        }
        return $formAnswerLongTextObj;
    }

    private function setIntegerObj($action, $formAnswerId, $paramArr) {
        $formAnswerIntegerObj = null;
        if ($action == 'create') {
            $formAnswerIntegerObj = new FormAnswerInteger;
            $formAnswerIntegerObj->{FormAnswerInteger::form_answers_id} = $formAnswerId;
            $formAnswerIntegerObj->{FormAnswerInteger::answer_integer} = is_numeric($paramArr['answer'])?$paramArr['answer']:null;
            $formAnswerIntegerObj->saveOrFail();
        } elseif ($action == 'update') {
            $formAnswerIntegerObj = FormAnswerInteger::where(FormAnswerInteger::form_answers_id, $formAnswerId)->firstOrFail();
            $formAnswerIntegerObj->{FormAnswerInteger::form_answers_id} = $formAnswerId;
            $formAnswerIntegerObj->{FormAnswerInteger::answer_integer} = is_numeric($paramArr['answer'])?$paramArr['answer']:null;
            $formAnswerIntegerObj->saveOrFail();
        } elseif ($action == 'delete') {
            $formAnswerIntegerObj = FormAnswerInteger::where(FormAnswerInteger::form_answers_id, $formAnswerId)->firstOrFail();
            $formAnswerIntegerObj->delete();
        }
        return $formAnswerIntegerObj;
    }

    public function getFormMasterObj($form_slug) {
        $formmaster = FormMaster::where(FormMaster::form_slug, $form_slug)->firstOrFail();

        return $formmaster;
    }
}
