<?php

namespace Modules\FormManagement\Repositories\FormSubmissionResponse;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Common\Utilities\Utilities;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;
use Modules\FormManagement\Repositories\FormSubmissionResponseRepositoryInterface;
use Modules\FormManagement\Entities\FormStatus;
use Modules\FormManagement\Entities\FormAccessType;
use Modules\FormManagement\Entities\FormMaster;
use Modules\FormManagement\Entities\FormQuestion;
use Modules\FormManagement\Entities\FormComponentType;

use Modules\FormManagement\Entities\FormAnswerSheet;
use Modules\FormManagement\Entities\FormAnswerText;
use Modules\FormManagement\Entities\FormAnswerAddress;
use Modules\FormManagement\Entities\FormAnswerAttachment;
use Modules\FormManagement\Entities\FormAnswerDatetime;
use Modules\FormManagement\Entities\FormAnswerLikert;
use Modules\FormManagement\Entities\FormAnswerInteger;
use Modules\FormManagement\Entities\FormAnswerLongText;
use Modules\FormManagement\Entities\FormAnswers;
use Modules\FormManagement\Repositories\FormFetcherRepositoryInterface;



class FormSubmissionResponseRepository implements FormSubmissionResponseRepositoryInterface {

    private $multiOptionTypes;
    private $formFetcher;
    public $s3BasePath;
    public function __construct(FormFetcherRepositoryInterface $formFetcher) {
            $this->multiOptionTypes=array("checkboxes", "multipleChoice", "dropdown");
            $this->formFetcher = $formFetcher;
            $this->s3BasePath= env('S3_PATH');
    }

    public function getAllAnswersheet($form_slug, Request $request){ 

        try{

            $formMasterDataObj = DB::table(FormMaster::table)
                    ->leftJoin(User::table, User::table . ".id", '=', FormMaster::table . '.' . FormMaster::creator_user_id)
                    ->leftJoin(UserProfile::table, User::table . ".id", '=', UserProfile::table . '.' . UserProfile::user_id)
                    ->select(
                            FormMaster::table . '.'.FormMaster::form_slug. ' AS formSlug',
                            FormMaster::table . '.'.FormMaster::form_title. ' AS formTitle',
                            FormMaster::table . '.'.FormMaster::description,
                            User::table . '.' . User::email,
                            User::table . '.' . User::name,
                            User::table . '.' . User::slug.' as creatorUserSlug',
                            DB::raw('concat("'. $this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as imageUrl')
                        )
        	    ->where(FormMaster::table.'.'.FormMaster::form_slug, $form_slug)
                    ->first();
            if(empty($formMasterDataObj)){
                throw new \Exception("invalid formSlug");
            }

            $formAnswersQueryObj = DB::table(FormAnswerSheet::table)
                    ->join(FormMaster::table, FormMaster::table . ".id", '=', FormAnswerSheet::table . '.' . FormAnswerSheet::form_master_id)
                    ->join(FormStatus::table, FormStatus::table . ".id", '=', FormMaster::table . '.' . FormMaster::form_status_id)
                    ->join(FormAccessType::table, FormAccessType::table . ".id", '=', FormMaster::table . '.' . FormMaster::form_access_type_id)
                    ->leftJoin(User::table, User::table . ".id", '=', FormAnswerSheet::table . '.' . FormAnswerSheet::form_user_id)
                    ->leftJoin(UserProfile::table, User::table . ".id", '=', UserProfile::table . '.' . UserProfile::user_id)
                    ->select(
                            FormAnswerSheet::table . '.' . FormAnswerSheet::slug. " AS answersheetSlug",
                            FormMaster::table . '.'.FormMaster::form_title." AS formTitle",
                            FormMaster::table . '.'.FormMaster::description,
                            FormMaster::table . '.'.FormMaster::form_slug." AS formSlug",
                            User::table . '.'.User::slug . ' AS userSlug',
                            User::table . '.'.User::name,
                            User::table . '.'.User::email,
                            DB::raw('concat("'. $this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as imageUrl'),
                            DB::raw("unix_timestamp(".FormAnswerSheet::table . '.'.FormAnswerSheet::submit_datetime.") AS submittedAt"),
                            FormStatus::table . '.' . FormStatus::status_name.' AS formStatus',
                            FormAccessType::table . '.' . FormAccessType::name.' AS formAccessType'
                            )
                    ->where(FormAnswerSheet::is_discarded, FALSE)
        	    ->where(FormMaster::form_slug, $form_slug);

            $formAnswersCount = $formAnswersQueryObj->count();

            $formAnswerSheets = $formAnswersQueryObj->skip(Utilities::getParams()['offset']) //$request['offset']
                     ->take(Utilities::getParams()['perPage']) //$request['perPage']
                     ->get();

            $responseData=array();
            $responseData['status']="OK";
            $formResponsePaginatedData = Utilities::paginate($formAnswerSheets, 
                            Utilities::getParams()['perPage'], 
                            Utilities::getParams()['page'], 
                            array(), 
                            $formAnswersCount)
                    ->toArray();
            $responseData['data']= $this->reformatFormRespData(
                    $formResponsePaginatedData,
                    $formMasterDataObj);
            $responseData['code']= Response::HTTP_OK;
            return $responseData;
        } catch (ModelNotFoundException $e) {
            return array(
                "status" => "NOT_FOUND",
                "error" => array(
                    "msg" => $e->getMessage()
                ),
                "code" => Response::HTTP_OK);
        }  catch (\Exception $e) {
            return array(
                "status" => "ERROR",
                "error" => array(
                    "msg" => $e->getMessage()
                ),
                "code" => Response::HTTP_OK);
        }
    }

    public function reformatFormRespData($dataArr, $formMasterDataObj) {
        $dataArr['formSlug'] = $formMasterDataObj->formSlug;
        $dataArr['formTitle'] = $formMasterDataObj->formTitle;
        $dataArr['description'] = $formMasterDataObj->description;
        $dataArr['creatorUserSlug'] =  $formMasterDataObj->creatorUserSlug;
        $dataArr['creatorName'] = $formMasterDataObj->name;
        $dataArr['creatorEmail'] = $formMasterDataObj->email;
        $dataArr['imageUrl'] = $formMasterDataObj->imageUrl;
        $dataArr['formResponses'] = $dataArr['data'];
        unset($dataArr['data']);
        $dataArr = Utilities::unsetResponseData($dataArr);
        return $dataArr;
    }
    
    public function getAnswersheet($answersheet_slug, Request $request) {
        try{
            $formAnswerSheetDataObj = DB::table(FormAnswerSheet::table)
                    ->join(FormMaster::table, FormMaster::table . ".id", '=', FormAnswerSheet::table . '.' . FormAnswerSheet::form_master_id)
                    ->leftJoin(User::table, User::table . ".id", '=', FormAnswerSheet::table . '.' . FormAnswerSheet::form_user_id)
                    ->leftJoin(UserProfile::table, User::table . ".id", '=', UserProfile::table . '.' . UserProfile::user_id)
                    ->select(
                            FormAnswerSheet::table . '.' . FormAnswerSheet::slug.' AS answersheetSlug',
                            FormAnswerSheet::table . '.' . FormAnswerSheet::is_discarded.' AS isDiscarded',
                             DB::raw("unix_timestamp(".FormAnswerSheet::table . '.'.FormAnswerSheet::submit_datetime.") AS submitDateTime") ,
                            FormMaster::table . '.'.FormMaster::form_slug. ' AS formSlug',
                            FormMaster::table . '.'.FormMaster::form_title. ' AS formTitle',
                            FormMaster::table . '.'.FormMaster::description,
                            User::table . '.' . User::email,
                            User::table . '.' . User::name,
                            User::table . '.' . User::slug.' as submittedUserSlug',
                            DB::raw('concat("'. $this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as imageUrl')
                        )
        	    ->where(FormAnswerSheet::table.'.'.FormAnswerSheet::slug, $answersheet_slug)
                    ->firstOrFail(); // firstOrFail from Macro

            $responseData=array();

            $formAnswerSheetDataArr=(array)$formAnswerSheetDataObj;

            $req = collect(array('form_slug'=>$formAnswerSheetDataArr['formSlug']));

            $clientFormData=$this->formFetcher->fetchClientForm($req);


            $formResponses = $this->getAnswerSheetResponsesArr($answersheet_slug);
            //$formAnswerSheetDataArr['form_response'] = $formResponses;
            $mergeRespArr = $this->mergeFormQuestionAndAnswers($clientFormData['data']['formPages'], $formResponses);
            $formAnswerSheetDataArr['formPages'] = $mergeRespArr['paginatedFormComponents'];

            $responseData['status'] = "OK";
            $responseData['data']= $formAnswerSheetDataArr;
            $responseData['code']= Response::HTTP_OK;
            return $responseData;
        } catch (ModelNotFoundException $e) {
            return array(
                "status" => "NOT_FOUND",
                "error" => array(
                    "msg" => $e->getMessage()
                ),
                "code" => Response::HTTP_OK);
        }  catch(\Illuminate\Database\QueryException $ex){ 
            return array(
                "status" => "ERROR",
                "error" => array(
                    "msg" => $ex->getMessage()
                ),
                "code" => Response::HTTP_OK);
        }
        catch (\Exception $e) {
            return array(
                "status" => "ERROR",
                "error" => array(
                    "msg" => $e->getMessage()
                ),
                "code" => Response::HTTP_OK);
        }
    }

    public function getAnswerSheetResponsesArr($answersheet_slug) {

        $formAnswerListDataObj = DB::table(FormAnswerSheet::table)
            ->leftJoin(FormAnswers::table, FormAnswers::table . "." . FormAnswers::form_answersheet_id, '=', FormAnswerSheet::table . '.id')
            ->leftJoin(FormAnswerText::table, FormAnswerText::table.'.'.FormAnswerText::form_answers_id, '=', FormAnswers::table . '.id')
            ->leftJoin(FormAnswerLongText::table, FormAnswerLongText::table.'.'.FormAnswerLongText::form_answers_id, '=', FormAnswers::table . '.id')
            ->leftJoin(FormAnswerInteger::table, FormAnswerInteger::table.'.'.FormAnswerInteger::form_answers_id, '=', FormAnswers::table . '.id')
            ->leftJoin(FormAnswerDatetime::table, FormAnswerDatetime::table.'.'.FormAnswerDatetime::form_answers_id, '=', FormAnswers::table . '.id')
            ->leftJoin(FormAnswerAddress::table, FormAnswerAddress::table.'.'. FormAnswerAddress::form_answers_id, '=', FormAnswers::table . '.id')
            ->leftJoin(FormAnswerLikert::table, FormAnswerLikert::table.'.'. FormAnswerLikert::form_answers_id, '=', FormAnswers::table . '.id')
            ->select(
                FormAnswerSheet::table . '.' . FormAnswerSheet::slug.' AS answersheet_slug',
                FormAnswerSheet::table . '.' . FormAnswerSheet::is_discarded.' AS isDiscarded',
                FormAnswerSheet::table . '.' . FormAnswerSheet::form_user_id,
                FormAnswerSheet::table . '.' . FormAnswerSheet::submit_datetime,
                FormAnswers::table . '.id',
                FormAnswers::table . '.' . FormAnswers::form_components_id,
                FormAnswers::table . '.' . FormAnswers::form_question_id,
                FormAnswers::table . '.' . FormAnswers::form_qns_options_id,
                FormAnswerText::table . '.' . FormAnswerText::answer_text,
                FormAnswerText::table . '.' . FormAnswerText::answer_text2,
                FormAnswerLongText::table . '.' . FormAnswerLongText::answer_longtext,
                FormAnswerInteger::table . '.' . FormAnswerInteger::answer_integer,
                //DB::raw("unix_timestamp(".FormAnswerDatetime::table . '.'.FormAnswerDatetime::answer_datetime.") AS answer_datetime"),
                //fix for y2k38
                //https://zavaboy.org/2013/01/29/alternative-for-mysql-unix_timestamp/
                DB::raw("TO_SECONDS(".FormAnswerDatetime::table . '.'.FormAnswerDatetime::answer_datetime.")-62167219200+TO_SECONDS(UTC_TIMESTAMP())-TO_SECONDS(NOW())  AS answer_datetime"),

                FormAnswerAddress::table . '.' . FormAnswerAddress::street_address,
                FormAnswerAddress::table . '.' . FormAnswerAddress::address_line2,
                FormAnswerAddress::table . '.' . FormAnswerAddress::state,
                FormAnswerAddress::table . '.' . FormAnswerAddress::city,
                FormAnswerAddress::table . '.' . FormAnswerAddress::country_id,
                FormAnswerAddress::table . '.' . FormAnswerAddress::zip_code,
                FormAnswerLikert::table . '.id as likertAnswerId',
                FormAnswerLikert::table . '.' . FormAnswerLikert::form_qns_likert_stmt_id,
                FormAnswerLikert::table . '.' . FormAnswerLikert::form_qns_likert_col_id
                )
            ->where(FormAnswerSheet::table.'.'.FormAnswerSheet::slug, $answersheet_slug)->get();

        $componentResponsesCol =  !empty($formAnswerListDataObj)?$formAnswerListDataObj->groupBy("form_components_id"):collect();

        return $componentResponsesCol; 
    }

    private function mergeFormQuestionAndAnswers($clientFormArr, $formResponsesColObj){

        $tempClientFormArr = $clientFormArr;
        $nonPagenatedFormComponentsArr = array();

        foreach ($tempClientFormArr as $page_slug => $pageComponents) {            
            foreach ($pageComponents['formComponents'] as $key=>$componentArr) {
                if($componentArr['type']=='singleLineText'){
                    $responseTextObj=$formResponsesColObj->has($componentArr['componentId'])?$formResponsesColObj->get($componentArr['componentId']):null;
                    $tempClientFormArr[$page_slug]['formComponents'][$key]['singleLineText']['answer']=!empty($responseTextObj)?$responseTextObj->get(0)->answer_text:'';
                }
                if($componentArr['type']=='paragraphText'){
                    $responseTextObj=$formResponsesColObj->has($componentArr['componentId'])?$formResponsesColObj->get($componentArr['componentId']):null;
                    $tempClientFormArr[$page_slug]['formComponents'][$key]['paragraphText']['answer']=!empty($responseTextObj)?$responseTextObj->get(0)->answer_longtext:'';
                }
                if($componentArr['type']=='number'){
                    $responseTextObj=$formResponsesColObj->has($componentArr['componentId'])?$formResponsesColObj->get($componentArr['componentId']):null;

                    $tempClientFormArr[$page_slug]['formComponents'][$key]['number']['answer']=!empty($responseTextObj)?$responseTextObj->get(0)->answer_integer:'';
                }
                if($componentArr['type']=='email'){
                    $responseTextObj=$formResponsesColObj->has($componentArr['componentId'])?$formResponsesColObj->get($componentArr['componentId']):null;
                    $tempClientFormArr[$page_slug]['formComponents'][$key]['email']['answer']=!empty($responseTextObj)?$responseTextObj->get(0)->answer_text:'';
                }
                if($componentArr['type']=='website'){
                    $responseTextObj=$formResponsesColObj->has($componentArr['componentId'])?$formResponsesColObj->get($componentArr['componentId']):null;
                    $tempClientFormArr[$page_slug]['formComponents'][$key]['website']['answer']=!empty($responseTextObj)?$responseTextObj->get(0)->answer_text:'';
                }
                if($componentArr['type']=='phone'){
                    $responseTextObj=$formResponsesColObj->has($componentArr['componentId'])?$formResponsesColObj->get($componentArr['componentId']):null;
                    $tempClientFormArr[$page_slug]['formComponents'][$key]['phone']['answer']=!empty($responseTextObj)?$responseTextObj->get(0)->answer_text:'';
                }
                if($componentArr['type']=='date'){
                    $responseTextObj=$formResponsesColObj->has($componentArr['componentId'])?$formResponsesColObj->get($componentArr['componentId']):null;
                    $tempClientFormArr[$page_slug]['formComponents'][$key]['date']['answer']=!empty($responseTextObj)?$responseTextObj->get(0)->answer_datetime:'';
                }
                if($componentArr['type']=='time'){
                    $responseTextObj=$formResponsesColObj->has($componentArr['componentId'])?$formResponsesColObj->get($componentArr['componentId']):null;
                    $tempClientFormArr[$page_slug]['formComponents'][$key]['time']['answer']=!empty($responseTextObj)?$responseTextObj->get(0)->answer_text:'';
                }
                if($componentArr['type']=='name'){
                    $responseTextObj=$formResponsesColObj->has($componentArr['componentId'])?$formResponsesColObj->get($componentArr['componentId']):null;
                    $tempClientFormArr[$page_slug]['formComponents'][$key]['name']['first']=!empty($responseTextObj)?$responseTextObj->get(0)->answer_text:null;
                    $tempClientFormArr[$page_slug]['formComponents'][$key]['name']['last']=!empty($responseTextObj)?$responseTextObj->get(0)->answer_text2:null;
                }
                if($componentArr['type']=='price'){
                    $responseTextObj=$formResponsesColObj->has($componentArr['componentId'])?$formResponsesColObj->get($componentArr['componentId']):null;
                    $tempClientFormArr[$page_slug]['formComponents'][$key]['price']['currency']=!empty($responseTextObj)?$responseTextObj->get(0)->answer_text:'';
                    $tempClientFormArr[$page_slug]['formComponents'][$key]['price']['currencyUnit']=!empty($responseTextObj)?$responseTextObj->get(0)->answer_text2:'';
                }
                if($componentArr['type']=='address'){
                    $responseTextObj=$formResponsesColObj->has($componentArr['componentId'])?$formResponsesColObj->get($componentArr['componentId']):null;
                    $tempClientFormArr[$page_slug]['formComponents'][$key]['address']['streetAddress']=!empty($responseTextObj)?$responseTextObj->get(0)->street_address:'';
                    $tempClientFormArr[$page_slug]['formComponents'][$key]['address']['addressLine2']=!empty($responseTextObj)?$responseTextObj->get(0)->address_line2:'';
                    $tempClientFormArr[$page_slug]['formComponents'][$key]['address']['city']=!empty($responseTextObj)?$responseTextObj->get(0)->city:'';
                    $tempClientFormArr[$page_slug]['formComponents'][$key]['address']['state']=!empty($responseTextObj)?$responseTextObj->get(0)->state:'';
                    $tempClientFormArr[$page_slug]['formComponents'][$key]['address']['countryId']=!empty($responseTextObj)?$responseTextObj->get(0)->country_id:'';
                    $tempClientFormArr[$page_slug]['formComponents'][$key]['address']['zipCode']=!empty($responseTextObj)?$responseTextObj->get(0)->zip_code:'';
                }

                if($componentArr['type']=='likert'){
                    $responseLikertObj=$formResponsesColObj->has($componentArr['componentId'])?$formResponsesColObj->get($componentArr['componentId']):null;

                    //dd($responseLikertObj);
                    $tempClientFormArr[$page_slug]['formComponents'][$key][$componentArr['type']]["answer"]=array();
                    $responseLikertObj->each(function($item) use (&$tempClientFormArr, $page_slug, $key, $componentArr){
                        $ab=array();
                        $ab['likertAnswerId']=$item->likertAnswerId;
                        $ab['stmtId']=$item->form_qns_likert_stmt_id;
                        $ab['colId']=$item->form_qns_likert_col_id;
                        $tempClientFormArr[$page_slug]['formComponents'][$key][$componentArr['type']]["answer"][]=$ab;
                    });
                }

                if($componentArr['type']=='rating'){
                    $responseRatingObj=$formResponsesColObj->has($componentArr['componentId'])?$formResponsesColObj->get($componentArr['componentId']):null;
                    $tempClientFormArr[$page_slug]['formComponents'][$key][$componentArr['type']]['answer']=!empty($responseRatingObj)?$responseRatingObj->get(0)->answer_integer:null;
                }

                if(in_array($componentArr['type'], $this->multiOptionTypes)){
                    $responseArrObj=$formResponsesColObj->has($componentArr['componentId'])?$formResponsesColObj->get($componentArr['componentId']):null;

                    $optIdCmpMap=!empty($responseArrObj)?$responseArrObj->groupBy('form_qns_options_id'): collect();

                    $choices=$componentArr[$componentArr['type']]['choices'];
                    foreach ($choices as $optIndex => $cho){
                        $tempClientFormArr[$page_slug]['formComponents'][$key][$componentArr['type']]['choices'][$optIndex]['selected'] = $optIdCmpMap->has($cho['optId'])? true : false;
                    }
                }
                //build a non pagenated Form Components Array
                array_push($nonPagenatedFormComponentsArr, $tempClientFormArr[$page_slug]['formComponents'][$key]);
            }
        }
        
        return array(
            "paginatedFormComponents" => $tempClientFormArr,
            "nonPaginatedFormComponents" => $nonPagenatedFormComponentsArr);
    }
    
    
    public function getNonPaginatedAnswersheet($answersheet_slug, Request $request) {
        try{
            $formAnswerSheetDataObj = DB::table(FormAnswerSheet::table)
                    ->join(FormMaster::table, FormMaster::table . ".id", '=', FormAnswerSheet::table . '.' . FormAnswerSheet::form_master_id)
                    ->leftJoin(User::table, User::table . ".id", '=', FormAnswerSheet::table . '.' . FormAnswerSheet::form_user_id)
                    ->leftJoin(UserProfile::table, User::table . ".id", '=', UserProfile::table . '.' . UserProfile::user_id)
                    ->select(
                            FormAnswerSheet::table . '.' . FormAnswerSheet::slug.' AS answersheetSlug',
                             DB::raw("unix_timestamp(".FormAnswerSheet::table . '.'.FormAnswerSheet::submit_datetime.") AS submitDateTime") ,
                            FormMaster::table . '.'.FormMaster::form_slug. ' AS formSlug',
                            FormMaster::table . '.'.FormMaster::form_title. ' AS formTitle',
                            FormMaster::table . '.'.FormMaster::description,
                            User::table . '.' . User::email,
                            User::table . '.' . User::name,
                            User::table . '.' . User::slug.' as submittedUserSlug',
                            DB::raw('concat("'. $this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as imageUrl')
                        )
        	    ->where(FormAnswerSheet::table.'.'.FormAnswerSheet::slug, $answersheet_slug)
                    ->firstOrFail(); // firstOrFail from Macro

            $responseData=array();

            $formAnswerSheetDataArr=(array)$formAnswerSheetDataObj;

            $req = collect(array('form_slug'=>$formAnswerSheetDataArr['formSlug']));

            $clientFormData=$this->formFetcher->fetchClientForm($req);


            $formResponses = $this->getAnswerSheetResponsesArr($answersheet_slug);

            $mergeRespArr = $this->mergeFormQuestionAndAnswers($clientFormData['data']['formPages'], $formResponses);
            $formAnswerSheetDataArr['formComponents'] = $mergeRespArr['nonPaginatedFormComponents'];

            $responseData['status'] = "OK";
            $responseData['data']= $formAnswerSheetDataArr;
            $responseData['code']= Response::HTTP_OK;
            return $responseData;
        } catch (ModelNotFoundException $e) {
            return array(
                "status" => "NOT_FOUND",
                "error" => array(
                    "msg" => $e->getMessage()
                ),
                "code" => Response::HTTP_OK);
        }  catch(\Illuminate\Database\QueryException $ex){ 
            return array(
                "status" => "ERROR",
                "error" => array(
                    "msg" => $ex->getMessage()
                ),
                "code" => Response::HTTP_OK);
        }
        catch (\Exception $e) {
            return array(
                "status" => "ERROR",
                "error" => array(
                    "msg" => $e->getMessage()
                ),
                "code" => Response::HTTP_OK);
        }
    }

}
