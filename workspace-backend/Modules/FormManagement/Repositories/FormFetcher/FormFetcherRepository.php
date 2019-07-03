<?php

namespace Modules\FormManagement\Repositories\FormFetcher;

use Illuminate\Support\Facades\DB;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;
use Illuminate\Support\Facades\Auth;
use Modules\FormManagement\Repositories\FormFetcherRepositoryInterface;
use Modules\FormManagement\Entities\FormMasterUsers;
use Modules\FormManagement\Entities\FormPublishUsers;

use Modules\OrgManagement\Entities\Organization;

use Modules\FormManagement\Entities\FormMaster;
use Modules\FormManagement\Entities\FormStatus;
use Modules\FormManagement\Entities\FormAccessType;
use Modules\FormManagement\Entities\FormPage;
use Modules\FormManagement\Entities\FormComponentType;
use Modules\FormManagement\Entities\FormComponents;
use Modules\FormManagement\Entities\FormSection;
use Modules\FormManagement\Entities\FormQuestion;
use Modules\FormManagement\Entities\FormQuestionOptions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\FormManagement\Entities\FormPageComponentsMap;
use Illuminate\Http\Response;
use Modules\Common\Utilities\Utilities;
use Modules\FormManagement\Entities\FormQnsLikertStatement;
use Modules\FormManagement\Entities\FormQnsLikertColumns;

use Modules\FormManagement\Entities\FormAnswers;
use Modules\FormManagement\Entities\FormAnswerSheet;
use Illuminate\Http\Request;

class FormFetcherRepository implements FormFetcherRepositoryInterface {

    public $choiceTypes;
    public $s3BasePath;
    public function __construct() {

        $this->choiceTypes = ["checkboxes","multipleChoice","dropdown"];
        $this->s3BasePath= env('S3_PATH');
    }

    public function componentTypes() {
        try{
            $formComponentsCol = FormComponentType::all();
            $reformatedComponents = $formComponentsCol->map(function($item) {
                $itemArr = $item->toArray();
                unset($itemArr['updated_at']);
                unset($itemArr['created_at']);
                unset($itemArr['is_active']);
                unset($itemArr['id']);
                return $itemArr;
            });
            return array(
                "status" => "OK",
                "data" => $reformatedComponents->toArray(),
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

    public function getMultiFormMasterResponsesMap($formSlugArr) {

        $formAnswerListDataObj = DB::table(FormAnswerSheet::table)
            ->join(FormMaster::table, FormMaster::table . ".id", '=', FormAnswerSheet::table . '.' . FormAnswerSheet::form_master_id)    
            ->leftJoin(User::table, User::table . ".id", '=', FormAnswerSheet::table . '.' . FormAnswerSheet::form_user_id)
            ->leftJoin(UserProfile::table, User::table . ".id", '=', UserProfile::table . '.' . UserProfile::user_id)
            ->select(
                FormMaster::table.'.'.FormMaster::form_slug.' as formSlug',
                FormAnswerSheet::table.'.'.FormAnswerSheet::form_master_id,
                FormAnswerSheet::table . '.' . FormAnswerSheet::slug.' AS answersheetSlug',
                FormAnswerSheet::table . '.' . FormAnswerSheet::form_user_id,
                User::table. '.' .User::name,
                User::table. '.' .User::email,
                DB::raw('concat("'. $this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as imageUrl')
                   )
            ->whereIn(FormMaster::table . '.'. FormMaster::form_slug, $formSlugArr)
            ->where(FormAnswerSheet::table . '.'. FormAnswerSheet::is_discarded, false)->get();

        $multiFormMasterResponsesMap =  $formAnswerListDataObj->groupBy('formSlug');
        return $multiFormMasterResponsesMap; 
    }

    private function getMultipleShareList($formSlugArr) {
        $formMasterUserArr = DB::table(FormMasterUsers::table)
        ->join(FormMaster::table, FormMaster::table . ".id", '=', FormMasterUsers::table . '.' . FormMasterUsers::form_master_id)
        ->join(User::table, User::table . ".id", '=', FormMasterUsers::table . '.' . FormMasterUsers::user_id)
        ->leftJoin(UserProfile::table, User::table . ".id", '=', UserProfile::table . '.' . UserProfile::user_id)
        ->select(
                FormMaster::table . '.'.FormMaster::form_slug." AS formSlug",
                FormMasterUsers::table . '.id AS shareId',
                User::table . '.'.User::slug." AS userSlug",
                User::table . '.'.User::name." AS userName",
                DB::raw('concat("'. $this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as imageUrl'),
                FormMasterUsers::table . '.'.FormMasterUsers::form_permission." AS permission"
                 )
        ->whereIn(FormMaster::form_slug, $formSlugArr)
        ->get();
        $groupedFormMasterUserArr = $formMasterUserArr->groupBy("formSlug");

        return $groupedFormMasterUserArr;
    }
    
    private function getPublishUserList($formSlugArr) {
        $formPublishUserArr = DB::table(FormPublishUsers::table)
        ->join(FormMaster::table, FormMaster::table . ".id", '=', FormPublishUsers::table . '.' . FormPublishUsers::form_master_id)
        ->join(User::table, User::table . ".id", '=', FormPublishUsers::table . '.' . FormPublishUsers::user_id)
        ->leftJoin(UserProfile::table, User::table . ".id", '=', UserProfile::table . '.' . UserProfile::user_id)
        ->select(
                FormMaster::table . '.'.FormMaster::form_slug." AS formSlug",
                FormPublishUsers::table . '.id AS sendId',
                User::table . '.'.User::slug." AS userSlug",
                User::table . '.'.User::name." AS userName",
                DB::raw('concat("'. $this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as imageUrl')
                 )
        ->whereIn(FormMaster::form_slug, $formSlugArr)
        ->get();
        $groupedFormPublishUserArr = $formPublishUserArr->groupBy("formSlug");

        return $groupedFormPublishUserArr;
    }

    public function getShareList(Request $request) {

        try{
        $formMasterUserArr = DB::table(FormMasterUsers::table)
        ->join(FormMaster::table, FormMaster::table . ".id", '=', FormMasterUsers::table . '.' . FormMasterUsers::form_master_id)
        ->join(User::table, User::table . ".id", '=', FormMasterUsers::table . '.' . FormMasterUsers::user_id)
        ->leftJoin(UserProfile::table, User::table . ".id", '=', UserProfile::table . '.' . UserProfile::user_id)
        ->select(
                FormMaster::table . '.'.FormMaster::form_slug." AS formSlug",
                //FormMaster::table . '.'.FormMaster::form_title." AS formTitle",
                FormMasterUsers::table . '.id AS shareId',
                User::table . '.'.User::slug." AS userSlug",
                User::table . '.'.User::name." AS userName",
                DB::raw('concat("'. $this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as imageUrl'),
                FormMasterUsers::table . '.'.FormMasterUsers::form_permission." AS permission"
                 )
        ->where(FormMaster::form_slug, $request->formSlug)
        ->get();

        $respArr = array();
        $formMasterUserArr->transform(function($itemArr) use (&$respArr) {
            $itemArr = (array)$itemArr;
            $respArr['formSlug'] = $itemArr['formSlug']; 
            unset($itemArr['formSlug']);
            return $itemArr;
        });
        
        $respArr['sharedUsers'] = $formMasterUserArr;
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

    public function getFormSendList(Request $request) {

        try{
        $formPublishUserArr = DB::table(FormPublishUsers::table)
        ->join(FormMaster::table, FormMaster::table . ".id", '=', FormPublishUsers::table . '.' . FormPublishUsers::form_master_id)
        ->join(FormStatus::table, FormStatus::table . ".id", '=', FormMaster::table . '.' . FormMaster::form_status_id)
        ->join(User::table, User::table . ".id", '=', FormPublishUsers::table . '.' . FormPublishUsers::user_id)
        ->leftJoin(UserProfile::table, User::table . ".id", '=', UserProfile::table . '.' . UserProfile::user_id)
        ->leftJoin(Organization::table, Organization::table . ".id", '=', FormPublishUsers::table . '.' . FormPublishUsers::org_id)
        ->select(
                FormMaster::table . '.'.FormMaster::form_slug." AS formSlug",
                FormMaster::table . '.'.FormMaster::form_title." AS formTitle",
                FormStatus::table . '.'.FormStatus::status_name." AS formStatus",
                FormMaster::table . '.'.FormMaster::is_published . " AS isPublished",
                FormPublishUsers::table . '.id AS sendId',
                User::table . '.'.User::slug." AS userSlug",
                User::table . '.'.User::name." AS userName",
                DB::raw('concat("'. $this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as imageUrl'),
                Organization::table . '.'. Organization::slug." AS orgSlug"
                 )
        ->where(FormMaster::form_slug, $request->formSlug)
        ->get();

        $respArr = array();

        $formPublishUserArr->transform(function($itemArr) use (&$respArr) {
            
            $itemArr = (array)$itemArr;
            
            $respArr['formSlug'] = $itemArr['formSlug']; 
            $respArr['orgSlug'] = $itemArr['orgSlug'];
            $respArr['formTitle'] = $itemArr['formTitle'];
            $respArr['formStatus'] = $itemArr['formStatus'];
            $respArr['isPublished'] = (boolean)$itemArr['isPublished'];
            unset($itemArr['formSlug']);
            unset($itemArr['orgSlug']);
            unset($itemArr['formTitle']);
            unset($itemArr['formStatus']);
            unset($itemArr['isPublished']);
            return $itemArr;
        });

        $respArr['sendUsers'] = $formPublishUserArr;
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
    
    
    public function fetch($form_slug) {

        try {
            $user = Auth::user();
            $formMaster = FormMaster::where(FormMaster::form_slug, $form_slug)->firstOrFail();

            $permissionObj = FormMasterUsers::where(FormMasterUsers::user_id,$user->id)
                    ->where(FormMasterUsers::form_permission,"edit")
                    ->where(FormMasterUsers::form_master_id, $formMaster->id);

            $formCreatorCheckObj =  FormMaster::where(FormMaster::form_slug, $form_slug)
                    ->where(FormMaster::creator_user_id, $user->id)
                    ->first();

            if(empty($formCreatorCheckObj) && empty($permissionObj)){
                throw new \Exception("you don't have permission to fetch this form.");
            }
            $formmaster = $this->getFormMasterData($form_slug, $user);

            $multiTypeQuestionIdResponsesMap = $this->getMultiTypeQuestionIdResponsesMap($formmaster->id);

            $formComponentsObjColl = DB::table(FormComponents::table)
                    ->join(FormComponentType::table, FormComponentType::table . ".id", '=', FormComponents::table . '.' . FormComponents::form_component_type_id)
                    ->select(FormComponents::table . '.*', FormComponentType::table . '.' . FormComponentType::cmp_type_name)
                    ->where(FormComponents::table . "." . FormComponents::form_master_id, '=', $formmaster->id)
                    ->orderBy(FormComponents::table . '.' . FormComponents::fc_sort_no, 'asc')
                    ->get();

            $groupedComponentsColl = $formComponentsObjColl->groupBy(FormComponentType::cmp_type_name);

            //dd($groupedComponentsColl);

            $allFormComponentCol = collect();
            $groupedComponentsColl->each(function($componentColl, $componentName) use (&$allFormComponentCol) {
                $cmpCol = $this->getAllSingleComponentTypeDataArr($componentName, $componentColl);
                $allFormComponentCol = $allFormComponentCol->merge($cmpCol);
            });
            //dd($allFormComponentCol);
            $tempDataCol = $allFormComponentCol->flatMap(function ($item) {
                $tempItem=$item->toArray();
                return array_map(function($obj) {
                        return (array)$obj;
                    }, $tempItem);
            });
            //dd($tempDataCol);
            $componentDataArr = array();
            $tempDataCol->each(function($item) use(&$componentDataArr) {
                $indexVal = $item[FormQuestion::form_components_id];
                $componentDataArr[$indexVal][] = $item;
            });

            $formComponentsResponseArr = (array)$formmaster;
            $formComponentsResponseArr['isPublished'] = (boolean)$formComponentsResponseArr['isPublished'];
            $formComponentsResponseArr['isArchived'] = (boolean)$formComponentsResponseArr['isArchived'];
            $formComponentsResponseArr['isTemplate'] = (boolean)$formComponentsResponseArr['isTemplate'];

            $sharedListFormSlugMap = $this->getMultipleShareList([$form_slug]);
            $publishUserListFormSlugMap = $this->getPublishUserList([$form_slug]);
            $formComponentsResponseArr['sharedUsers'] = $sharedListFormSlugMap->has($form_slug) ? $sharedListFormSlugMap->get($form_slug) : [];
            $formComponentsResponseArr['sendUsers'] = $publishUserListFormSlugMap->has($form_slug) ? $publishUserListFormSlugMap->get($form_slug) : [];

            $formComponentsResponseArr["formComponents"]= array();
            $formComponentsObjColl->each(function($comp, $index) use ($componentDataArr, &$formComponentsResponseArr, $multiTypeQuestionIdResponsesMap) {    
                $formComponentsResponseArr["formComponents"][] = $this->filterComponentData($componentDataArr, $comp, $index, $multiTypeQuestionIdResponsesMap);
            });

            return array(
                "status" => "OK",
                "data" => $formComponentsResponseArr,
                "code" => Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return array(
                "status" => "NOT_FOUND",
                "error" => array("status" => "error",
                    "msg" => $e->getMessage()
                ),
                "code" => Response::HTTP_OK);
        } /* catch (\Exception $e) {
            return array(
                "error" => array("status" => "error",
                    "msg" => $e->getMessage()
                ),
                "code" => Response::HTTP_OK);
        }*/
    }

    private function filterComponentData($componentDataArr, $singleComp, $index, $multiTypeQuestionIdResponsesMap){
        $choiceTypes=$this->choiceTypes;
        $cmpName = $singleComp->{FormComponentType::cmp_type_name};
        $cmpData= array();
        if(in_array($cmpName, $choiceTypes)){

            $cmpData['label']=$componentDataArr[$singleComp->id][0]['form_question_text'];
            $cmpData['isRequired']=(boolean)$componentDataArr[$singleComp->id][0]['isRequired'];
            $cmpData['choices']=$this->filterOptionsData($componentDataArr[$singleComp->id], $multiTypeQuestionIdResponsesMap);
        } else {
            $cmpData= $this->filterCompData($componentDataArr[$singleComp->id]);
        }
        $tempData = array(
            "type" => $cmpName,
            "componentId" => $singleComp->id,
            $cmpName => $cmpData
        );
        return $tempData;
    }

    private function filterOptionsData($optionsData, $multiTypeQuestionIdResponsesMap){
        return array_map(function($row) use ($multiTypeQuestionIdResponsesMap){

            //dd($row['optId']);
            $qnsId = $row['form_question_id'];
            $optId = $row['optId'];
            $cObj = !empty($multiTypeQuestionIdResponsesMap[$qnsId][$optId])? $multiTypeQuestionIdResponsesMap[$qnsId][$optId] : [];
            $row['alreadySelectedCount'] = count($cObj);
            unset($row['form_question_text']);
            unset($row['isRequired']);
            unset($row['noDuplicate']);
            unset($row['form_components_id']);
            unset($row['form_question_id']);
            return $row;
        }, $optionsData);
    }

    private function filterCompData($cmpDataArr){

        $dataArr=$cmpDataArr[0];
        if(!empty($dataArr['form_question_text'])){
            $dataArr['label']=$dataArr['form_question_text'];
            unset($dataArr['form_question_text']);
        }
        unset($dataArr['form_components_id']);        
        return $dataArr;
    }

    public function getPageComponents($componentsColl) {
        $pageComponentIds = $componentsColl->pluck("id");

        $pagesDataCol = DB::table(FormPage::table)
                ->select(FormPage::form_page_slug.' as formPageSlug', FormPage::page_title.' AS title', FormPage::form_components_id)
                ->whereIn(FormPage::form_components_id, $pageComponentIds->toArray())
                ->get();


        $groupedPageData = $pagesDataCol->groupBy(FormPage::form_components_id);

        return $groupedPageData;
    }

    public function getSectionComponents($componentsColl) {
        $sessionComponentIds = $componentsColl->pluck("id");

        $sessionDataCol = DB::table(FormSection::table)
                ->select(FormSection::fs_title.' AS title', FormSection::fs_desc. ' AS description', FormSection::form_components_id)
                ->whereIn(FormSection::form_components_id, $sessionComponentIds->toArray())
                ->get();


        $groupedSessionData = $sessionDataCol->groupBy(FormSection::form_components_id);

        return $groupedSessionData;
    }

    public function getFileUploads($componentsColl) {
        $qnsComponentIds = $componentsColl->pluck("id");

        $qnsDataCol = DB::table(FormQuestion::table)
                ->select(FormQuestion::form_question_text, FormQuestion::is_mandatory. ' AS isRequired', FormQuestion::form_components_id)
                ->whereIn(FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        $qnsDataCol->transform(function($item){
            $item->isRequired = (boolean)$item->isRequired;
            return $item;
        });

        $groupedQnsData = $qnsDataCol->groupBy(FormQuestion::form_components_id);

        return $groupedQnsData;
    }
    
    public function getPrice($componentsColl) {
        $qnsComponentIds = $componentsColl->pluck("id");

        $qnsDataCol = DB::table(FormQuestion::table)
                ->select(FormQuestion::form_question_text, FormQuestion::is_mandatory. ' AS isRequired', FormQuestion::has_unique_answer. ' AS noDuplicate', FormQuestion::currency_type. ' AS currencyType', FormQuestion::form_components_id)
                ->whereIn(FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        $qnsDataCol->transform(function($item){
            $item->isRequired = (boolean)$item->isRequired;
            $item->noDuplicate = (boolean)$item->noDuplicate;
            return $item;
        });

        $groupedQnsData = $qnsDataCol->groupBy(FormQuestion::form_components_id);

        return $groupedQnsData;
    }

    public function getSingleLineTexts($componentsColl) {
        $qnsComponentIds = $componentsColl->pluck("id");

        $qnsDataCol = DB::table(FormQuestion::table)
                ->select(FormQuestion::form_question_text, FormQuestion::is_mandatory. ' AS isRequired', FormQuestion::has_unique_answer. ' AS noDuplicate', FormQuestion::form_components_id)
                ->whereIn(FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        $qnsDataCol->transform(function($item){
            $item->isRequired = (boolean)$item->isRequired;
            $item->noDuplicate = (boolean)$item->noDuplicate;
            return $item;
        });

        $groupedQnsData = $qnsDataCol->groupBy(FormQuestion::form_components_id);

        return $groupedQnsData;
    }

    public function getParagraphTexts($componentsColl) {
        $qnsComponentIds = $componentsColl->pluck("id");

        $qnsDataCol = DB::table(FormQuestion::table)
                ->select(FormQuestion::form_question_text, FormQuestion::is_mandatory. ' AS isRequired', FormQuestion::has_unique_answer. ' AS noDuplicate', FormQuestion::form_components_id)
                ->whereIn(FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        
        $qnsDataCol->transform(function($item){
            $item->isRequired = (boolean)$item->isRequired;
            $item->noDuplicate = (boolean)$item->noDuplicate;
            return $item;
        });
        
        $groupedQnsData = $qnsDataCol->groupBy(FormQuestion::form_components_id);

        return $groupedQnsData;
    }

    public function getNumbers($componentsColl) {
        $qnsComponentIds = $componentsColl->pluck("id");

        $qnsDataCol = DB::table(FormQuestion::table)
                ->select(FormQuestion::form_question_text, FormQuestion::is_mandatory. ' AS isRequired', FormQuestion::has_unique_answer. ' AS noDuplicate', FormQuestion::form_components_id, FormQuestion::min_range. ' AS minRange', FormQuestion::max_range. ' AS maxRange')
                ->whereIn(FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        $qnsDataCol->transform(function($item){
            $item->isRequired = (boolean)$item->isRequired;
            $item->noDuplicate = (boolean)$item->noDuplicate;
            return $item;
        });

        $groupedQnsData = $qnsDataCol->groupBy(FormQuestion::form_components_id);

        return $groupedQnsData;
    }

    public function getEmail($componentsColl) {
        $qnsComponentIds = $componentsColl->pluck("id");

        $qnsDataCol = DB::table(FormQuestion::table)
                ->select(FormQuestion::form_question_text, FormQuestion::is_mandatory. ' AS isRequired', FormQuestion::has_unique_answer. ' AS noDuplicate', FormQuestion::form_components_id)
                ->whereIn(FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        $qnsDataCol->transform(function($item){
            $item->isRequired = (boolean)$item->isRequired;
            $item->noDuplicate = (boolean)$item->noDuplicate;
            return $item;
        });

        $groupedQnsData = $qnsDataCol->groupBy(FormQuestion::form_components_id);

        return $groupedQnsData;
    }

    public function getPhone($componentsColl) {
        $qnsComponentIds = $componentsColl->pluck("id");

        $qnsDataCol = DB::table(FormQuestion::table)
                ->select(FormQuestion::form_question_text, FormQuestion::is_mandatory. ' AS isRequired', FormQuestion::has_unique_answer. ' AS noDuplicate', FormQuestion::form_components_id)
                ->whereIn(FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        $qnsDataCol->transform(function($item){
            $item->isRequired = (boolean)$item->isRequired;
            $item->noDuplicate = (boolean)$item->noDuplicate;
            return $item;
        });

        $groupedQnsData = $qnsDataCol->groupBy(FormQuestion::form_components_id);

        return $groupedQnsData;
    }

    public function getDate($componentsColl) {
        $qnsComponentIds = $componentsColl->pluck("id");

        $qnsDataCol = DB::table(FormQuestion::table)
                ->select(FormQuestion::form_question_text, FormQuestion::is_mandatory. ' AS isRequired', FormQuestion::has_unique_answer. ' AS noDuplicate', FormQuestion::form_components_id)
                ->whereIn(FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        $qnsDataCol->transform(function($item){
            $item->isRequired = (boolean)$item->isRequired;
            $item->noDuplicate = (boolean)$item->noDuplicate;
            return $item;
        });

        $groupedQnsData = $qnsDataCol->groupBy(FormQuestion::form_components_id);

        return $groupedQnsData;
    }

    public function getTime($componentsColl) {
        $qnsComponentIds = $componentsColl->pluck("id");

        $qnsDataCol = DB::table(FormQuestion::table)
                ->select(FormQuestion::form_question_text, FormQuestion::is_mandatory. ' AS isRequired', FormQuestion::has_unique_answer. ' AS noDuplicate', FormQuestion::form_components_id)
                ->whereIn(FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        $qnsDataCol->transform(function($item){
            $item->isRequired = (boolean)$item->isRequired;
            $item->noDuplicate = (boolean)$item->noDuplicate;
            return $item;
        });

        $groupedQnsData = $qnsDataCol->groupBy(FormQuestion::form_components_id);

        return $groupedQnsData;
    }

    public function getWebsite($componentsColl) {
        $qnsComponentIds = $componentsColl->pluck("id");

        $qnsDataCol = DB::table(FormQuestion::table)
                ->select(FormQuestion::form_question_text, FormQuestion::is_mandatory. ' AS isRequired', FormQuestion::has_unique_answer. ' AS noDuplicate', FormQuestion::form_components_id)
                ->whereIn(FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        $qnsDataCol->transform(function($item){
            $item->isRequired = (boolean)$item->isRequired;
            $item->noDuplicate = (boolean)$item->noDuplicate;
            return $item;
        });

        $groupedQnsData = $qnsDataCol->groupBy(FormQuestion::form_components_id);

        return $groupedQnsData;
    }
    
    public function getRating($componentsColl) {
        $qnsComponentIds = $componentsColl->pluck("id");

        $qnsDataCol = DB::table(FormQuestion::table)
                ->select(FormQuestion::form_question_text, FormQuestion::is_mandatory. ' AS isRequired', FormQuestion::has_unique_answer. ' AS noDuplicate', FormQuestion::form_components_id)
                ->whereIn(FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        $qnsDataCol->transform(function($item){
            $item->isRequired = (boolean)$item->isRequired;
            $item->noDuplicate = (boolean)$item->noDuplicate;
            return $item;
        });

        $groupedQnsData = $qnsDataCol->groupBy(FormQuestion::form_components_id);

        return $groupedQnsData;
    }    

    public function getName($componentsColl) {
        $qnsComponentIds = $componentsColl->pluck("id");

        $qnsDataCol = DB::table(FormQuestion::table)
                ->select(FormQuestion::form_question_text, FormQuestion::is_mandatory. ' AS isRequired', FormQuestion::has_unique_answer. ' AS noDuplicate', FormQuestion::form_components_id)
                ->whereIn(FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        $qnsDataCol->transform(function($item){
            $item->isRequired = (boolean)$item->isRequired;
            $item->noDuplicate = (boolean)$item->noDuplicate;
            return $item;
        });
        //dd($pagesData);
        $groupedQnsData = $qnsDataCol->groupBy(FormQuestion::form_components_id);

        return $groupedQnsData;
    }

    public function getAddress($componentsColl) {
        $qnsComponentIds = $componentsColl->pluck("id");

        $qnsDataCol = DB::table(FormQuestion::table)
                ->select(FormQuestion::form_question_text, FormQuestion::is_mandatory. ' AS isRequired', FormQuestion::has_unique_answer. ' AS noDuplicate', FormQuestion::form_components_id)
                ->whereIn(FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        $qnsDataCol->transform(function($item){
            $item->isRequired = (boolean)$item->isRequired;
            $item->noDuplicate = (boolean)$item->noDuplicate;
            return $item;
        });
        $groupedQnsData = $qnsDataCol->groupBy(FormQuestion::form_components_id);

        return $groupedQnsData;
    }

    public function getCheckboxes($componentsColl) {
        $qnsComponentIds = $componentsColl->pluck("id");

        $qnsDataCol = DB::table(FormQuestion::table)
                ->leftJoin(FormQuestionOptions::table, FormQuestion::table . ".id", '=', FormQuestionOptions::table . '.' . FormQuestionOptions::form_question_id)
                ->select(
                        FormQuestion::form_question_text, FormQuestion::is_mandatory. ' AS isRequired', FormQuestion::has_unique_answer. ' AS noDuplicate', FormQuestion::form_components_id, FormQuestionOptions::form_question_id, FormQuestionOptions::table.".id AS optId", FormQuestionOptions::fqo_sort_no .' AS fqoSortNo', FormQuestionOptions::option_text .' AS label', FormQuestionOptions::max_quantity . ' AS maxQuantity'
                )
                ->whereIn(FormQuestion::table . '.' . FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        $qnsDataCol->transform(function($item){
            $item->isRequired = (boolean)$item->isRequired;
            $item->noDuplicate = (boolean)$item->noDuplicate;
            return $item;
        });

        $groupedQnsData = $qnsDataCol->groupBy(FormQuestion::form_components_id);

        return $groupedQnsData;
    }

    public function getMultipleChoice($componentsColl) {
        $qnsComponentIds = $componentsColl->pluck("id");

        $qnsDataCol = DB::table(FormQuestion::table)
                ->leftJoin(FormQuestionOptions::table, FormQuestion::table . ".id", '=', FormQuestionOptions::table . '.' . FormQuestionOptions::form_question_id)
                ->select(
                        FormQuestion::form_question_text, FormQuestion::is_mandatory. ' AS isRequired', FormQuestion::has_unique_answer. ' AS noDuplicate', FormQuestion::form_components_id, FormQuestionOptions::form_question_id, FormQuestionOptions::table.".id AS optId", FormQuestionOptions::fqo_sort_no.' AS fqoSortNo', FormQuestionOptions::option_text .' AS label', FormQuestionOptions::max_quantity . ' AS maxQuantity'
                )
                ->whereIn(FormQuestion::table . '.' . FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        $qnsDataCol->transform(function($item){
            $item->isRequired = (boolean)$item->isRequired;
            $item->noDuplicate = (boolean)$item->noDuplicate;
            return $item;
        });

        $groupedQnsData = $qnsDataCol->groupBy(FormQuestion::form_components_id);

        return $groupedQnsData;
    }

    public function getDropdown($componentsColl) {
        $qnsComponentIds = $componentsColl->pluck("id");

        $qnsDataCol = DB::table(FormQuestion::table)
                ->leftJoin(FormQuestionOptions::table, FormQuestion::table . ".id", '=', FormQuestionOptions::table . '.' . FormQuestionOptions::form_question_id)
                ->select(
                        FormQuestion::form_question_text, FormQuestion::is_mandatory. ' AS isRequired', FormQuestion::has_unique_answer. ' AS noDuplicate', FormQuestion::form_components_id, FormQuestionOptions::form_question_id, FormQuestionOptions::table.".id AS optId", FormQuestionOptions::fqo_sort_no.' AS fqoSortNo', FormQuestionOptions::option_text .' AS label', FormQuestionOptions::max_quantity . ' AS maxQuantity'
                )
                ->whereIn(FormQuestion::table . '.' . FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        $qnsDataCol->transform(function($item){
            $item->isRequired = (boolean)$item->isRequired;
            $item->noDuplicate = (boolean)$item->noDuplicate;
            return $item;
        });

        $groupedQnsData = $qnsDataCol->groupBy(FormQuestion::form_components_id);
        
        return $groupedQnsData;
    }
    
    public function getLikertStatements($qnsComponentIds) {
        $qnsDataCol = DB::table(FormQuestion::table)
                ->leftJoin(FormQnsLikertStatement::table, FormQuestion::table . ".id", '=', FormQnsLikertStatement::table . '.' . FormQnsLikertStatement::form_question_id)
                ->select(
                        FormQuestion::form_question_text, 
                        FormQuestion::is_mandatory. ' AS isRequired',
                        FormQuestion::form_components_id, 
                        FormQnsLikertStatement::form_question_id, 
                        FormQnsLikertStatement::table.".id AS stmtId",
                        FormQnsLikertStatement::likert_statement." AS stmt"
                )
                ->whereIn(FormQuestion::table . '.' . FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        $qnsDataCol->transform(function($item){
            $item->isRequired = (boolean)$item->isRequired;
            return $item;
        });

        $groupedQnsData = $qnsDataCol->groupBy(FormQuestion::form_components_id);

        return $groupedQnsData;
    }

    public function getLikertColumns($qnsComponentIds) {

        $qnsDataCol = DB::table(FormQuestion::table)
                ->leftJoin(FormQnsLikertColumns::table, FormQuestion::table . ".id", '=', FormQnsLikertColumns::table . '.' . FormQnsLikertColumns::form_question_id)
                ->select(
                        FormQuestion::form_question_text, 
                        FormQuestion::is_mandatory. ' AS isRequired',                         
                        FormQuestion::form_components_id, 
                        FormQnsLikertColumns::form_question_id, 
                        FormQnsLikertColumns::table.".id AS colId",
                        FormQnsLikertColumns::likert_column." AS column"
                )
                ->whereIn(FormQuestion::table . '.' . FormQuestion::form_components_id, $qnsComponentIds->toArray())
                ->get();

        $qnsDataCol->transform(function($item){
            $item->isRequired = (boolean)$item->isRequired;
            return $item;
        });

        $groupedQnsData = $qnsDataCol->groupBy(FormQuestion::form_components_id);

        return $groupedQnsData;
    }

    public function getLikert($componentsColl){
        $qnsComponentIds = $componentsColl->pluck("id");

        $statementGrpObj = $this->getLikertStatements($qnsComponentIds);
        $columnsGrpObj = $this->getLikertColumns($qnsComponentIds);
        //dd($statementGrpObj);
        $likertArr = array();
        $tempHolder = array();
        //set statements, form_components_id, and labels attributes
        $statementGrpObj->each(function($scmp, $cmpId) use (&$tempHolder){
            $tempArr=array();
            $tempArr["form_components_id"] = $cmpId;
            $tempArr["isRequired"] = $scmp->get(0)->isRequired;
            $tempArr["label"]=$scmp->get(0)->form_question_text;
            $scmp->transform(function($itm){
                unset($itm->form_question_text);
                unset($itm->form_components_id);
                unset($itm->isRequired);
                unset($itm->form_question_id);
                return $itm;
            });
            $tempArr["statements"]=$scmp->toArray();
            $tempHolder[$cmpId] = $tempArr;
        });
        //append columns
        $columnsGrpObj->each(function($ccmp, $cmpId)  use (&$tempHolder, &$likertArr){
            $ccmp->transform(function($itm){
                unset($itm->form_question_text);
                unset($itm->form_components_id);
                unset($itm->isRequired);
                unset($itm->form_question_id);
                return $itm;
            });
            $tempHolder[$cmpId]["columns"] = $ccmp->toArray();
            $holder=array($tempHolder[$cmpId]);
            $likertArr[$cmpId] = collect($holder);
        });

        return collect($likertArr);        
    }
    
    private function getAllSingleComponentTypeDataArr($componentName, $componentsColl) {
        $componentCol = null;
        switch ($componentName) {
            case "page":
                $componentCol = $this->getPageComponents($componentsColl);
                break;
            case "section":
                $componentCol = $this->getSectionComponents($componentsColl);
                break;
            case "singleLineText":
                $componentCol = $this->getSingleLineTexts($componentsColl);
                break;
            case "paragraphText":
                $componentCol = $this->getParagraphTexts($componentsColl);
                break;
            case "number":
                $componentCol = $this->getNumbers($componentsColl);
                break;
            case "checkboxes":
                $componentCol = $this->getCheckboxes($componentsColl);
                break;
            case "multipleChoice":
                $componentCol = $this->getMultipleChoice($componentsColl);
                break;
            case "dropdown":
                $componentCol = $this->getDropdown($componentsColl);
                break;
            case "email":
                $componentCol = $this->getEmail($componentsColl);
                break;
            case "phone":
                $componentCol = $this->getPhone($componentsColl);
                break;
            case "date":
                $componentCol = $this->getDate($componentsColl);
                break;
            case "time":
                $componentCol = $this->getTime($componentsColl);
                break;
            case "website":
                $componentCol = $this->getWebsite($componentsColl);
                break;
            case "rating" :
                $componentCol = $this->getRating($componentsColl);
                break;
            case "likert" :
                $componentCol = $this->getLikert($componentsColl);
                break;
            case "name":
                $componentCol = $this->getName($componentsColl);
                break;
            case "address":
                $componentCol = $this->getAddress($componentsColl);
                break;
            case "fileUpload":
                $componentCol = $this->getFileUploads($componentsColl);
                break;
            case "price":
                $componentCol = $this->getPrice($componentsColl);
                break;
            default :
                throw new \Exception("[" . __FUNCTION__ . "] error: Invalid component name :-" . $componentName);
        }

        return $componentCol;
    }

    public function getParams() {
        $page = 1;
        $perPage = 10;

        if (request()->has('page')) {
            $page = (int) request()->page;
        }

        if (request()->has('per_page')) {
            $perPage = (int) request()->per_page;
        }

        $offset = ($page * $perPage) - $perPage;

        return array('page' => $page, 'perPage' => $perPage, 'offset' => $offset);
    }

    public function getAllForms($request) {

        $loggedInUser = Auth::user();
        //dd($loggedInUser->{User::slug});

        //DB::enableQueryLog();

        //base query
        $queryBuilder = DB::table(FormMaster::table)
        ->join(FormStatus::table, FormStatus::table . ".id", '=', FormMaster::table . '.' . FormMaster::form_status_id)
        ->join(FormAccessType::table, FormAccessType::table . ".id", '=', FormMaster::table . '.' . FormMaster::form_access_type_id)
        ->join(User::table, User::table . ".id", '=', FormMaster::table . '.' . FormMaster::creator_user_id)
        ->leftJoin(UserProfile::table, User::table . ".id", '=', UserProfile::table . '.' . UserProfile::user_id);


        $trainingFeedbackFormAccessType = FormAccessType::where(FormAccessType::name,'=',"postTrainingFeedbackForm")
                ->select('id')->first();

        $postCourseFeedbackFormAccessType = FormAccessType::where(FormAccessType::name,'=',"postCourseFeedbackForm")
                ->select('id')->first();
        if(empty($postCourseFeedbackFormAccessType)){
            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['error'] = array("msg" => "postCourseFeedbackForm AccessTypeId missing");
            $responseData['code']= 422;
            return $responseData;
        }

        $feedbackFormTypeIds = array($trainingFeedbackFormAccessType->id, $postCourseFeedbackFormAccessType->id);

        if($request->has('type')){
            if($request->type == 'postTrainingFeedbackForm'){
                $queryBuilder = $queryBuilder->where(FormMaster::form_access_type_id, '=', $trainingFeedbackFormAccessType->id);
            } else if($request->type == 'postCourseFeedbackForm'){
                $queryBuilder = $queryBuilder->where(FormMaster::form_access_type_id, '=', $postCourseFeedbackFormAccessType->id);
            } else {
               $queryBuilder = $queryBuilder->whereNotIn(FormMaster::form_access_type_id, $feedbackFormTypeIds);            
            }
        } else {
            $queryBuilder = $queryBuilder->whereNotIn(FormMaster::form_access_type_id, $feedbackFormTypeIds);
        }
        //tab handling
        if($request->has('tab') ){
            switch ($request->tab){
                case "sharedWithMe":
                    $queryBuilder = $queryBuilder
                    ->join(FormMasterUsers::table, function($join) use ($loggedInUser){
                        $join->on(FormMasterUsers::table . ".".FormMasterUsers::user_id, '=', DB::raw($loggedInUser->id));
                        $join->on(FormMasterUsers::table . ".".FormMasterUsers::form_master_id, '=', FormMaster::table . '.id');
                    })
                    ->where(FormMasterUsers::table . ".".FormMasterUsers::user_id, '=', $loggedInUser->id);
                    break;
                case "published":
                    $queryBuilder = $queryBuilder
                        ->where(FormMaster::table . ".".FormMaster::creator_user_id, '=', $loggedInUser->id)
                        ->where(FormMaster::table . ".".FormMaster::is_published, '=', TRUE)
                        ->where(FormMaster::table . ".".FormMaster::is_template, '=', FALSE);
                    break;
                case "archived":
                    $queryBuilder = $queryBuilder
                        ->where(FormMaster::table . ".".FormMaster::creator_user_id, '=', $loggedInUser->id)
                        ->where(FormMaster::table . ".".FormMaster::is_archived, '=', TRUE)
                        ->where(FormMaster::table . ".".FormMaster::is_template, '=', FALSE);
                    break;
                case "draft":
                    $queryBuilder = $queryBuilder
                        ->where(FormMaster::table . ".".FormMaster::creator_user_id, '=', $loggedInUser->id)
                        ->where(FormStatus::table . ".".FormStatus::status_name, '=', "draft")
                        ->where(FormMaster::table . ".".FormMaster::is_template, '=', FALSE);
                    break;
                case "inactive":
                    $queryBuilder = $queryBuilder
                        ->where(FormMaster::table . ".".FormMaster::creator_user_id, '=', $loggedInUser->id)
                        ->where(FormStatus::table . ".".FormStatus::status_name, '=', "inactive")
                        ->where(FormMaster::table . ".".FormMaster::is_template, '=', FALSE);
                    break;
                case "active":
                    $queryBuilder = $queryBuilder
                        ->where(FormMaster::table . ".".FormMaster::creator_user_id, '=', $loggedInUser->id)
                        ->where(FormStatus::table . ".".FormStatus::status_name, '=', "active")
                        ->where(FormMaster::table . ".".FormMaster::is_template, '=', FALSE);
                    break;
                case "template":
                    $queryBuilder = $queryBuilder
                        ->where(FormMaster::table . ".".FormMaster::creator_user_id, '=', $loggedInUser->id)
                        ->where(FormMaster::table . ".".FormMaster::is_template, '=', TRUE);
                    break;
                default:
                    $queryBuilder = $queryBuilder
                        ->where(FormMaster::creator_user_id, '=', $loggedInUser->id)
                        ->where(FormMaster::table . ".".FormMaster::is_template, '=', FALSE);
            }
        } else {
            $queryBuilder = $queryBuilder
                ->where(FormMaster::creator_user_id, '=', $loggedInUser->id)
                ->where(FormMaster::table . ".".FormMaster::is_archived, '=', FALSE);
        }


        //search query
        if($request->q){
            $queryBuilder = $queryBuilder->where(FormMaster::form_title,'like','%'.$request->q.'%');
        }


        //select count
        $formCount = $queryBuilder->count();    

        //select columns
        $queryBuilder =$queryBuilder    
        ->select(
                FormMaster::table . '.id',
                FormMaster::table . '.'.FormMaster::form_slug." AS formSlug",
                FormMaster::table . '.'.FormMaster::form_title." AS formTitle",
                FormMaster::table . '.'.FormMaster::description,
                User::table . '.'.User::slug." AS creatorUserSlug",
                User::table . '.'.User::name." AS creatorName",
                User::table . '.'.User::email." AS creatorEmail",
                DB::raw('concat("'. $this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as imageUrl'),
                FormMaster::table . '.'.FormMaster::is_published. " AS isPublished",
                FormMaster::table . '.'.FormMaster::is_archived. " AS isArchived",
                FormMaster::table . '.'.FormMaster::is_template. " AS isTemplate",                            
                FormStatus::table . '.' . FormStatus::status_name.' AS formStatus',
                FormAccessType::table . '.' . FormAccessType::name.' AS formAccessType',
                DB::raw("unix_timestamp(".FormMaster::table . '.'.FormMaster::CREATED_AT.") AS createdAt"),
                DB::raw("unix_timestamp(".FormMaster::table . '.'.FormMaster::UPDATED_AT.") AS lastUpdated"));

        if($request->tab == "sharedWithMe"){
            $queryBuilder = $queryBuilder    
            ->addSelect(
                FormMasterUsers::table . ".".FormMasterUsers::form_permission.' AS permission'   
                );
        } else { // show permission in sharedWithMe tab only
            $queryBuilder = $queryBuilder    
            ->addSelect(
                DB::raw("NULL as permission")   
                );
        }
        //sorting
       

        $sortBy = $request->has('sortBy')? $request->sortBy: "";

        switch ($sortBy){
            case "formTitle":
                $sortColumn = FormMaster::table.'.'.FormMaster::form_title;
                break;
            case "createdAt":
                $sortColumn = FormMaster::table.'.'.FormMaster::CREATED_AT;
                break;
            case "updatedAt":
                $sortColumn = FormMaster::table.'.'.FormMaster::UPDATED_AT;
                break;
            default : 
                $sortColumn = FormMaster::table.'.'.FormMaster::UPDATED_AT;
        }

        $sortOrder = $request->has('sortOrder')? $request->sortOrder: "";

        switch ($sortOrder){
            case "asc":
                $queryBuilder = $queryBuilder->orderBy($sortColumn, 'asc');
                break;
            case "desc":
                $queryBuilder = $queryBuilder->orderBy($sortColumn, 'desc');
                break;
            default : 
                $queryBuilder = $queryBuilder->orderBy($sortColumn, 'desc');
        }

        //pagination
        $formMasterData =$queryBuilder  
                ->skip(Utilities::getParams()['offset']) //$request['offset']
                ->take(Utilities::getParams()['perPage']) //$request['perPage']
                ->get();

        // dd(DB::getQueryLog());

        $paginatedData = Utilities::paginate($formMasterData, Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $formCount)->toArray();

        $formatedData = $this->reformatData($paginatedData);

        $formSlugArr = array();
        $formMasterData->transform(function($item) use(&$formSlugArr){
            $item->isPublished = (boolean)$item->isPublished;
            $item->isArchived = (boolean)$item->isArchived;
            $item->isTemplate = (boolean)$item->isTemplate;
            array_push($formSlugArr, $item->formSlug);
            return $item;
        });

        $sharedListFormSlugMap = $this->getMultipleShareList($formSlugArr);
        $publishUserListFormSlugMap = $this->getPublishUserList($formSlugArr);
        $formResponsesCountMap = $this->getMultiFormMasterResponsesMap($formSlugArr);
        
        $formMasterData->transform(function($item) use($sharedListFormSlugMap, $formResponsesCountMap, $publishUserListFormSlugMap){
            $item->sharedUsers = $sharedListFormSlugMap->has($item->formSlug) ? $sharedListFormSlugMap->get($item->formSlug) : [];
            $item->sendUsers = $publishUserListFormSlugMap->has($item->formSlug) ? $publishUserListFormSlugMap->get($item->formSlug) : [];
            
            $item->responseCount = $formResponsesCountMap->has($item->formSlug)? $formResponsesCountMap->get($item->formSlug)->count():0;
            return $item;
        });

        $responseData = array();
        $responseData['status'] = 'OK';
        $responseData['data'] = $formatedData;
        $responseData['code']= Response::HTTP_OK;
        return $responseData;
    }

    public function reformatData($dataArr) {
        $dataArr['forms'] = $dataArr['data'];
        unset($dataArr['data']);
        $dataArr = Utilities::unsetResponseData($dataArr);
        return $dataArr;
    }

    public function getFormMasterData($form_slug){       
        $formmaster = DB::table(FormMaster::table)
        ->join(FormStatus::table, FormStatus::table . ".id", '=', FormMaster::table . '.' . FormMaster::form_status_id)
        ->join(FormAccessType::table, FormAccessType::table . ".id", '=', FormMaster::table . '.' . FormMaster::form_access_type_id)
        ->join(User::table, User::table . ".id", '=', FormMaster::table . '.' . FormMaster::creator_user_id)
        ->leftJoin(UserProfile::table, User::table . ".id", '=', UserProfile::table . '.' . UserProfile::user_id)
        ->select(
                FormMaster::table . '.id',
                FormMaster::table . '.'.FormMaster::form_slug." AS formSlug",
                FormMaster::table . '.'.FormMaster::form_title." AS formTitle",
                FormMaster::table . '.'.FormMaster::description,
                User::table . '.'.User::slug." AS creatorUserSlug",
                User::table . '.'.User::name." AS creatorName",
                User::table . '.'.User::email." AS creatorEmail",
                DB::raw('concat("'. $this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as imageUrl'),
                FormMaster::table . '.'.FormMaster::is_published. " AS isPublished",
                FormMaster::table . '.'.FormMaster::is_archived. " AS isArchived",
                FormMaster::table . '.'.FormMaster::is_template. " AS isTemplate",
                FormMaster::table . '.' . FormMaster::allow_multi_submit.' AS allowMultiSubmit',
                FormStatus::table . '.' . FormStatus::status_name.' AS formStatus',
                FormAccessType::table . '.' . FormAccessType::name.' AS formAccessType',
                DB::raw("unix_timestamp(".FormMaster::table . '.'.FormMaster::CREATED_AT.") AS createdAt") )
        ->where(FormMaster::form_slug, $form_slug)
        ->firstOrFail(); // firstOrFail from Macro
        return $formmaster;
    }

    public function fetchClientForm($form_slug) {

        try {
            $formmaster = $this->getFormMasterData($form_slug);
            
            $multiTypeQuestionIdResponsesMap = $this->getMultiTypeQuestionIdResponsesMap($formmaster->id);

            $formComponentsObjColl = DB::table(FormComponents::table)
                    ->join(FormComponentType::table, FormComponentType::table . ".id", '=', FormComponents::table . '.' . FormComponents::form_component_type_id)
                    ->select(FormComponents::table . '.*', FormComponentType::table . '.' . FormComponentType::cmp_type_name)
                    ->where(FormComponents::table . "." . FormComponents::form_master_id, '=', $formmaster->id)
                    ->orderBy(FormComponents::table . '.' . FormComponents::fc_sort_no, 'asc')
                    ->get();

            $groupedComponentsColl = $formComponentsObjColl->groupBy(FormComponentType::cmp_type_name);

            //dd($groupedComponentsColl);

            $allFormComponentCol = collect();
            $groupedComponentsColl->each(function($componentColl, $componentName) use (&$allFormComponentCol) {
                $cmpCol = $this->getAllSingleComponentTypeDataArr($componentName, $componentColl);
                $allFormComponentCol = $allFormComponentCol->merge($cmpCol);
            });

            $tempDataCol = $allFormComponentCol->flatMap(function ($item) {
                return array_map(function($obj) {
                    return (array) $obj;
                }, $item->toArray());
            });

            $componentDataArr = array();
            $tempDataCol->each(function($item) use(&$componentDataArr) {
                $indexVal = $item[FormQuestion::form_components_id];
                $componentDataArr[$indexVal][] = $item;
            });

            $formComponentsResponseArr = (array)$formmaster;
            $formComponentsResponseArr['allowMultiSubmit'] = (boolean)$formComponentsResponseArr['allowMultiSubmit'];
            $formComponentsResponseArr['isPublished'] = (boolean)$formComponentsResponseArr['isPublished'];
            $formComponentsResponseArr['isArchived'] = (boolean)$formComponentsResponseArr['isArchived'];
            $formComponentsResponseArr['isTemplate'] = (boolean)$formComponentsResponseArr['isTemplate'];
            $formComponentsResponseArr["formPages"]= array();
            //dd("hello $formmaster->id");
            $pageComponentsCol=DB::table(FormPageComponentsMap::table)
                ->join(FormPage::table, FormPageComponentsMap::table . ".". FormPageComponentsMap::form_page_id, '=', FormPage::table . '.id')
                ->select(FormPageComponentsMap::form_page_id,
                        FormPageComponentsMap::fpc_sort_no,
                        FormPageComponentsMap::table.'.'.FormPageComponentsMap::form_components_id,
                        FormPage::form_page_slug. ' AS formPageSlug')
                ->where(FormPageComponentsMap::table.'.'.FormPageComponentsMap::form_master_id, $formmaster->id)
                ->get();
            
            $componentPageMap=$pageComponentsCol->groupBy(FormPageComponentsMap::form_components_id);
            
            //dd($componentPageMap);
            
            
            $formComponentsObjColl->each(function($comp, $index) use ($componentDataArr, &$formComponentsResponseArr, $componentPageMap, $multiTypeQuestionIdResponsesMap) {    
                
                $tempCmpData = $this->filterComponentData($componentDataArr, $comp, $index, $multiTypeQuestionIdResponsesMap);
                $singleCmpCol=$componentPageMap->get($tempCmpData['componentId']);
                
                $pageId=$singleCmpCol->get(0)->form_page_id;
                $pageSlug=$singleCmpCol->get(0)->formPageSlug;
                
                $formComponentsResponseArr['formPages'][$pageSlug]["formComponents"][] =$tempCmpData; 
            });

            return array(
                "status" => "OK",
                "data" => $formComponentsResponseArr,
                "code" => Response::HTTP_OK);
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
    
    public function getMultiTypeQuestionIdResponsesMap($formmasterId) {

        $formAnswerListDataObj = DB::table(FormAnswerSheet::table)
            ->leftJoin(FormAnswers::table, FormAnswers::table . "." . FormAnswers::form_answersheet_id, '=', FormAnswerSheet::table . '.id')
            ->join(FormComponents::table, FormAnswers::table . "." . FormAnswers::form_components_id, '=', FormComponents::table . '.id')
            ->join(FormComponentType::table, FormComponentType::table . ".id", '=', FormComponents::table . '.'.FormComponents::form_component_type_id)
            ->select(
                FormAnswerSheet::table.'.'.FormAnswerSheet::form_master_id,
                FormAnswerSheet::table . '.' . FormAnswerSheet::slug.' AS answersheet_slug',
                FormAnswerSheet::table . '.' . FormAnswerSheet::form_user_id,
                FormAnswerSheet::table . '.' . FormAnswerSheet::submit_datetime,
                FormAnswers::table . '.' . FormAnswers::form_components_id,
                FormComponents::table . '.'.FormComponents::form_component_type_id,
                FormComponentType::table . '.'.FormComponentType::cmp_type_displayname,
                FormComponentType::table . '.'.FormComponentType::cmp_type_name,
                FormAnswers::table . '.' . FormAnswers::form_question_id,
                FormAnswers::table . '.' . FormAnswers::form_qns_options_id)
            ->where(FormAnswerSheet::table.'.'.FormAnswerSheet::form_master_id, $formmasterId)
            ->whereIn(FormComponentType::table . '.'.FormComponentType::cmp_type_name, $this->choiceTypes)->get();

        //$formResponses =  $formAnswerListDataObj->groupBy(FormAnswers::form_question_id);

        $arrOfObj = $formAnswerListDataObj->toArray();
        $questionIdAndOptionResponsesMap=array();
        foreach ($arrOfObj as $key => $value) {
            $questionIdAndOptionResponsesMap[$value->form_question_id][$value->form_qns_options_id][]=$value;
        }

        return $questionIdAndOptionResponsesMap; 
    }

}
