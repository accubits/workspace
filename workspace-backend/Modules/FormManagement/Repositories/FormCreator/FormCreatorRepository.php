<?php

namespace Modules\FormManagement\Repositories\FormCreator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\UserManagement\Entities\User;
use Modules\FormManagement\Entities\FormMasterUsers;
use Modules\FormManagement\Entities\FormPublishUsers;
use Modules\FormManagement\Repositories\FormCreatorRepositoryInterface;
use Modules\FormManagement\Entities\FormMaster;
use Modules\FormManagement\Entities\FormStatus;
use Modules\FormManagement\Entities\FormAccessType;
use Modules\FormManagement\Entities\FormPage;
use Modules\FormManagement\Entities\FormComponentType;
use Modules\FormManagement\Entities\FormComponents;
use Modules\FormManagement\Entities\FormSection;
use Modules\FormManagement\Entities\FormQuestion;
use Modules\FormManagement\Entities\FormQuestionOptions;
use Modules\FormManagement\Entities\FormPageComponentsMap;
use Modules\FormManagement\Entities\FormQnsLikertStatement;
use Modules\FormManagement\Entities\FormQnsLikertColumns;

use Modules\FormManagement\Entities\FormAnswerSheet;

use Modules\SocialModule\Entities\SocialActivityStreamFormMap;
use Modules\SocialModule\Entities\SocialActivityStreamMaster;


use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\SocialModule\Repositories\CommonRepositoryInterface;
use Modules\OrgManagement\Entities\Organization;
use Modules\Common\Utilities\Utilities;

class FormCreatorRepository implements FormCreatorRepositoryInterface {

    protected $formSharePermissionList;
    protected $commonRepository;
    public function __construct(CommonRepositoryInterface $commonRepository) {
        $this->formSharePermissionList = ['view','edit'];
        $this->commonRepository = $commonRepository;
    }

    public function deleteForms(Request $request) {
        DB::beginTransaction();
        try {
            $user = Auth::user();

            $deleteCount = DB::table(FormMaster::table)
                    ->where(FormMaster::creator_user_id, '=', $user->id)
                    ->whereIn(FormMaster::form_slug, (array)$request->formSlugs)->count();
            if($deleteCount > 0) {
                //delete all
                DB::table(FormMaster::table)
                        ->where(FormMaster::creator_user_id, '=', $user->id)
                        ->whereIn(FormMaster::form_slug, $request->formSlugs)
                        ->delete();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return array(
                "status" => "ERROR",
                "error" => array(
                    "msg" => $e->getMessage()
                ),
                "code" => Response::HTTP_OK);
        }
        if($deleteCount==0){
            return array(
                "status" => "ERROR",
                "error" => array(
                    "msg" => "no matching formSlugs found"
                ),
                "code" => Response::HTTP_OK);
        } else {
            return array(
                "status" => "OK",
                "data" => array(
                    "msg" => "delete success"
                   ),
                "code" => Response::HTTP_OK);
        }
    }
    
    public function updateFormStatus(Request $request) {
        DB::beginTransaction();
        $formmaster = null;
        try {
            $user = Auth::user();
            $formmaster = $this->updateFormMasterStatus($request, $user);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return array(
                "status" => "ERROR",
                "error" => array(
                    "msg" => $e->getMessage()
                ),
                "code" => Response::HTTP_OK);
        }
        return array(
            "status" => "OK",
            "data" => array(
                "formSlug" => $formmaster->{FormMaster::form_slug},
                "msg" => "Form status updated!"
               ),
            "code" => Response::HTTP_OK);
    }
    
    private function updateFormMasterStatus($paramObj){
        $formmaster = FormMaster::where(FormMaster::form_slug, $paramObj->formSlug)->firstOrFail();
        $formStatusObj = FormStatus::where(FormStatus::status_name, $paramObj->formStatus)->firstOrFail();
        $formmaster->{FormMaster::form_status_id} = $formStatusObj->id;
        if($paramObj->formStatus=='inactive'){
            $formmaster->{FormMaster::is_published} = false;
        } else if($paramObj->formStatus=='active'){
            $formmaster->{FormMaster::is_published} = true;
        }
        $formmaster->saveOrFail();

        ////////////////disable old stream on unpublish
        $this->disableOldActivityStreamOnUnpublish($formmaster);

        
        return $formmaster;
    }
    
    private function disableOldActivityStreamOnUnpublish($formmaster) {

        if($formmaster->{FormMaster::is_published} == false){
            $previousActivityStreamFormMaps = SocialActivityStreamFormMap::where(SocialActivityStreamFormMap::form_master_id,$formmaster->id)->get();
            $disableOldStreamsIds = array();
            $previousActivityStreamFormMaps->each(function($activityStreamFormMap) use(&$disableOldStreamsIds) {
                $id = $activityStreamFormMap->{SocialActivityStreamFormMap::activity_stream_master_id};
                array_push(
                    $disableOldStreamsIds, 
                    $id
                );
            }); 
            SocialActivityStreamMaster::whereIn("id",$disableOldStreamsIds)
                    ->update(
                        array(
                        SocialActivityStreamMaster::is_hidden=>TRUE
                    ));
        }
    }

    public function create(Request $request) {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $formmaster = $this->getFormMasterObj($request, $user);
            if ($request->has('formComponents')) {
                $this->manageFormComponents($formmaster, $request->formComponents);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return array(
                "status" => "ERROR",
                "error" => array(
                    "msg" => $e->getMessage()
                ),
                "code" => Response::HTTP_OK);
        }
        return array(
            "status" => "OK",
            "data" => array(
                "formslug" => $formmaster->{FormMaster::form_slug},
                "formId" => $formmaster->id
               ),
            "code" => Response::HTTP_OK);
    }
    
    public function share(Request $request) {
        DB::beginTransaction();
        try {
            $loggedInUser = Auth::user();
            $formmaster = FormMaster::where(FormMaster::form_slug, $request->formSlug)->firstOrFail();
            if($request->has("sharedUsers")){
                $sharedFormMasterUserIdArr = array();
                foreach ($request->sharedUsers as $shareUser) {
                    array_push($sharedFormMasterUserIdArr, 
                        $this->setFormMasterUserObj($loggedInUser, $formmaster, $shareUser));
                }
                //delete
                FormMasterUsers::whereNotIn('id',$sharedFormMasterUserIdArr)
                    ->where(FormMasterUsers::form_master_id, $formmaster->id)
                    ->delete();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return array(
                "status" => "ERROR",
                "error" => array(
                    "msg" => $e->getMessage()
                ),
                "code" => Response::HTTP_OK);
        }
        return array(
            "status" => "OK",
            "data" => array(
                "msg" => "share successfull"
               ),
            "code" => Response::HTTP_OK);
    }
    
    public function formSend(Request $request) {
        DB::beginTransaction();
        try {
            $loggedInUser = Auth::user();
            $formmaster = FormMaster::where(FormMaster::form_slug, $request->formSlug)->first();
            if(empty($formmaster)){
               return array(
                "status" => "ERROR",
                "error" => array(
                    "msg" => "Invalid formSlug"
                ),
                "code" => Response::HTTP_OK); 
            }

            $formStatusObj = FormStatus::where(FormStatus::status_name, $request->formStatus)->first();
            if(empty($formmaster)){
               return array(
                "status" => "ERROR",
                "error" => array(
                    "msg" => "Invalid formStatus"
                ),
                "code" => Response::HTTP_OK); 
            }
            $formmaster->{FormMaster::form_status_id} = $formStatusObj->id;
            if(!is_bool($request->isPublished)){
               return array(
                "status" => "ERROR",
                "error" => array(
                    "msg" => "Invalid isPublished"
                ),
                "code" => Response::HTTP_OK); 
            }
            $formmaster->{FormMaster::is_published} = $request->isPublished;
            $formmaster->saveOrFail();
            $organisationObj = Organization::where(Organization::slug, $request->orgSlug)->first();
            if(empty($organisationObj)){
               return array(
                "status" => "ERROR",
                "error" => array(
                    "msg" => "Invalid orgSlug"
                ),
                "code" => Response::HTTP_OK); 
            }
            if($request->has("sendUsers")){
                $sendFormMasterUserIdArr = array();
                $activityStreamToUserIdArr = array();
                foreach ($request->sendUsers as $sendUser) {
                    $respArr = $this->setFormMasterPublishUserObj($organisationObj, $loggedInUser, $formmaster, $sendUser);
                    array_push($sendFormMasterUserIdArr, $respArr['formMasterPublishId']);
                    array_push($activityStreamToUserIdArr, $respArr['userId']);
                }

                //ignore all previous answers for user not in send to list.
                $previousFormPublishUserIdsArr = FormPublishUsers::whereNotIn('id',$sendFormMasterUserIdArr)
                    ->where(FormPublishUsers::form_master_id, $formmaster->id)
                    ->select(FormPublishUsers::user_id)->get(FormPublishUsers::user_id);

                $previousFormPublishUserIdsArr->each(function($userArr) use($formmaster) {
                    $userId = $userArr->{FormPublishUsers::user_id};
                    FormAnswerSheet::where(FormAnswerSheet::form_master_id, $formmaster->id)
                        ->where(FormAnswerSheet::form_user_id, $userId )
                        ->update(array(FormAnswerSheet::is_discarded => TRUE));
                });
                ///////////

                //delete
                FormPublishUsers::whereNotIn('id',$sendFormMasterUserIdArr)
                    ->where(FormPublishUsers::form_master_id, $formmaster->id)
                    ->delete();
                $toUserIdsCollectionObj = collect($activityStreamToUserIdArr);
                $this->commonRepository->setSocialActivityStreamForm($loggedInUser, $organisationObj, $formmaster, $toUserIdsCollectionObj, "form published");

            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return array(
                "status" => "ERROR",
                "error" => array(
                    "msg" => $e->getMessage()
                ),
                "code" => Response::HTTP_OK);
        }
        return array(
            "status" => "OK",
            "data" => array(
                "msg" => "form successfully published"
               ),
            "code" => Response::HTTP_OK);
    }

    private function setFormMasterPublishUserObj($orgObj,$loggedInUser, $formmaster, $sendUser) {
        $formMasterPublishUserObj = null;
        $type=null;
        if(!empty($sendUser['sendId'])){
            $formMasterPublishUserObj = FormPublishUsers::where('id', $sendUser['sendId'])->first();
            if(empty($formMasterPublishUserObj)){
                throw new \Exception("Invalid sendId: ".$sendUser['sendId']." found");
            }
            $formMasterPublishUserObj->{FormPublishUsers::org_id} = $orgObj->id;
            $formMasterPublishUserObj->{FormPublishUsers::creator_id} = $loggedInUser->id;
            $formMasterPublishUserObj->{FormPublishUsers::form_master_id}=$formmaster->id;
            
            $userObj = User::where(User::slug, $sendUser['userSlug'])->first();
            if(empty($userObj)){
                throw new \Exception("Invalid userSlug: ".$sendUser['userSlug']." found");
            }
            $formMasterPublishUserObj->{FormPublishUsers::user_id} = $userObj->id;
            $formMasterPublishUserObj->save();
            $type="update";
        } else {
            $formMasterPublishUserObj = new FormPublishUsers;
            $formMasterPublishUserObj->{FormPublishUsers::org_id} = $orgObj->id;
            $formMasterPublishUserObj->{FormPublishUsers::creator_id} = $loggedInUser->id;
            $formMasterPublishUserObj->{FormPublishUsers::form_master_id}=$formmaster->id;

            $userObj = User::where(User::slug, $sendUser['userSlug'])->first();
            if(empty($userObj)){
                throw new \Exception("Invalid userSlug: ".$sendUser['userSlug']." found");
            }
            $formMasterPublishUserObj->{FormPublishUsers::user_id} = $userObj->id;
            $formMasterPublishUserObj->save();
            $type="create";
        }

        return array(
            "formMasterPublishId"=>$formMasterPublishUserObj->id,
            "userId"=>$formMasterPublishUserObj->{FormPublishUsers::user_id},
            "type" => $type
            );
    }

    
    private function setFormMasterUserObj($loggedInUser, $formmaster, $shareUser) {
        $formMasterUserObj = null;
        if(!empty($shareUser['shareId'])){
            $formMasterUserObj = FormMasterUsers::where('id', $shareUser['shareId'])->first();
            if(empty($formMasterUserObj)){
                throw new \Exception("Invalid shareId: ".$shareUser['shareId']." found");
            }
            $formMasterUserObj->{FormMasterUsers::creator_id} = $loggedInUser->id;
            $formMasterUserObj->{FormMasterUsers::form_master_id}=$formmaster->id;

            if(!in_array($shareUser['permission'], $this->formSharePermissionList)){
                throw new \Exception("Invalid permission: ".$shareUser['permission']." found. allowed types:-".print_r($this->formSharePermissionList,TRUE));
            }
            $formMasterUserObj->{FormMasterUsers::form_permission} = $shareUser['permission'];
            $userObj = User::where(User::slug, $shareUser['userSlug'])->first();
            if(empty($userObj)){
                throw new \Exception("Invalid userSlug: ".$shareUser['userSlug']." found");
            }
            $formMasterUserObj->{FormMasterUsers::user_id} = $userObj->id;
            $formMasterUserObj->save();
        } else {
            $formMasterUserObj = new FormMasterUsers;
            $formMasterUserObj->{FormMasterUsers::creator_id} = $loggedInUser->id;
            $formMasterUserObj->{FormMasterUsers::form_master_id}=$formmaster->id;

            if(!in_array($shareUser['permission'], $this->formSharePermissionList)){
                throw new \Exception("Invalid permission: ".$shareUser['permission']." found. allowed types:-".print_r($this->formSharePermissionList,TRUE));
            }
            $formMasterUserObj->{FormMasterUsers::form_permission} = $shareUser['permission'];
            $userObj = User::where(User::slug, $shareUser['userSlug'])->first();
            if(empty($userObj)){
                throw new \Exception("Invalid userSlug: ".$shareUser['userSlug']." found");
            }
            $formMasterUserObj->{FormMasterUsers::user_id} = $userObj->id;
            $formMasterUserObj->save();
        }

        return $formMasterUserObj->id;
    }
    


    private function manageFormComponents($formmaster, $formComponentsInputArr) {
        $formMetaInfoArr = array(
            "currentPageId" => null,
            "currentSessionId" => null,
            "pageWiseSortno" => 0
        );
        $formComponentNamesColObj = DB::table(FormComponentType::table)
                ->pluck(FormComponentType::cmp_type_name);

        $nonDividerComponentsCounts = 0;

        if(count($formComponentsInputArr) == 0){
            DB::table(FormPageComponentsMap::table)->where(FormPageComponentsMap::form_master_id, '=', $formmaster->id)->delete();
            DB::table(FormComponents::table)->where(FormComponents::form_master_id, '=', $formmaster->id)->delete();
        } else if(count($formComponentsInputArr)>0){
        // clear FormPageComponentsMap of given form
            DB::table(FormPageComponentsMap::table)->where(FormPageComponentsMap::form_master_id, '=', $formmaster->id)->delete();

            if($formComponentsInputArr[0]['type']!='page'){
                //create default page component as first component
                array_unshift($formComponentsInputArr, 
                        array(
                            "componentId" => null,
                            "action" => "create",
                            "type" => "page",
                            "page" => array(
                              "title" => "default page",
                              "description" => "default page component"
                            )
                           )
                        );
            }

            $newComponentIdArray = array();
            collect($formComponentsInputArr)
                    ->each(function($item, $key) use ($formComponentNamesColObj, $formmaster, &$formMetaInfoArr, &$newComponentIdArray, &$nonDividerComponentsCounts) {
                        //set and update $formMetaInfoArr -> page and sessionid
                        $formMetaInfoArr = $this->setOneFormComponent($formComponentNamesColObj, $item, $formmaster, $key, $formMetaInfoArr, $newComponentIdArray);

                        !empty($formMetaInfoArr['formComponentObj']) ? array_push($newComponentIdArray, $formMetaInfoArr['formComponentObj']->id):null;
                        
                        $nonDividerComponentsCounts = ($item['type']!="page" && $item['type']!="section")? $nonDividerComponentsCounts+1 : $nonDividerComponentsCounts;
            });

            if($nonDividerComponentsCounts==0){
                throw new \Exception("Form should have atleast one basic or fancy element!");
            }

            //delete all other components if any
            DB::table(FormComponents::table)
                    ->where(FormComponents::form_master_id, '=', $formmaster->id)
                    ->whereNotIn('id',$newComponentIdArray)
                    ->delete();
        }
    }
    
    private function setPageComponentsMapping($formMasterId,$pageId,$componentId, $fpc_sortno) {
        $formPageComponentMapObj = new FormPageComponentsMap;
        $formPageComponentMapObj->{FormPageComponentsMap::form_master_id} = $formMasterId;
        $formPageComponentMapObj->{FormPageComponentsMap::form_page_id} = $pageId;
        $formPageComponentMapObj->{FormPageComponentsMap::form_components_id} = $componentId;
        $formPageComponentMapObj->{FormPageComponentsMap::fpc_sort_no} = $fpc_sortno;
        $formPageComponentMapObj->save();
    }

    private function setOneFormComponent($formComponentNamesColObj, $item, $formmaster, $key, $formMetaInfoArr, $newComponentIdArray) {

        $previousPageId = $currentPageId = $formMetaInfoArr['currentPageId'];
        $currentSessionId = $formMetaInfoArr['currentSessionId'];
        $pageWiseSortno = $formMetaInfoArr['pageWiseSortno'];

        if (!$formComponentNamesColObj->contains($item['type'])) {
            throw new \Exception("[".__FUNCTION__."] Invalid component name :-" . $item['type']);
        }
        
        $formComponentObj = $this->getFormComponentsObj(
                $item, $formmaster, ($item['type'] == 'page' ? null : $currentPageId), $key, ($item['type'] == 'section' ? null : $currentSessionId)
        );
        if(in_array($formComponentObj->id, $newComponentIdArray)){ //duplicate check for component ids
            throw new \Exception("Invalid Duplicate form component found!. componentId: ".$formComponentObj->id." ");
        }
        switch ($item['type']) {
            case "page":
                $formpage = $this->getPageComponentObj($item, $formmaster, $formComponentObj->id);
                $currentPageId = !empty($formpage) ? $formpage->id : $currentPageId;
                break;
            case "section":
                $sectionObj = $this->getSectionComponentObj($item, $currentPageId, $formComponentObj->id);
                $currentSessionId = !empty($sectionObj) ? $sectionObj->id :  $currentSessionId;
                break;
            case "singleLineText":
                $this->setSingleLineTextObj($item, $formComponentObj->id);
                break;
            case "paragraphText":
                $this->setParagraphTextObj($item, $formComponentObj->id);
                break;
            case "number":
                $this->setNumberObj($item, $formComponentObj->id);
                break;
            case "checkboxes":
                $this->setCheckboxes($item, $formComponentObj->id);
                break;
            case "multipleChoice":
                $this->setMultipleChoice($item, $formComponentObj->id);
                break;
            case "dropdown":
                $this->setDropdown($item, $formComponentObj->id);
                break;
            case "email":
                $this->setEmailObj($item, $formComponentObj->id);
                break;
            case "phone":
                $this->setPhoneObj($item, $formComponentObj->id);
                break;
            case "date":
                $this->setDateObj($item, $formComponentObj->id);
                break;
            case "time":
                $this->setTimeObj($item, $formComponentObj->id);
                break;
            case "fileUpload":
                $this->setFileUploadObj($item, $formComponentObj->id);
                break;
            case "rating":
                $this->setRatingObj($item, $formComponentObj->id);
                break;
            case "website":
                $this->setWebsiteObj($item, $formComponentObj->id);
                break;
            case "name":
                $this->setNameObj($item, $formComponentObj->id);
                break;
            case "address":
                $this->setAddressObj($item, $formComponentObj->id);
                break;
            case "likert" :
                $this->setLikertObj($item, $formComponentObj->id);
                break;
            case "price":
                $this->setPriceObj($item, $formComponentObj->id);
                break;
            default :
                throw new \Exception("[".__FUNCTION__."] error: Invalid component name :-" . $item['type']);
        }

        if($previousPageId==null || $previousPageId==$currentPageId){
            $fpc_sortno = $pageWiseSortno+1;
            $this->setPageComponentsMapping($formmaster->id, $currentPageId, $formComponentObj->id, $fpc_sortno);
        } else if($currentPageId!=null){
            $fpc_sortno = 1;
            $this->setPageComponentsMapping($formmaster->id, $currentPageId, $formComponentObj->id, $fpc_sortno);
        }

        return array(
            "currentPageId" => $currentPageId,
            "currentSessionId" => $currentSessionId,
            "pageWiseSortno"=>$fpc_sortno,
            "formComponentObj" => $formComponentObj
        );
    }

    public function getFormComponentsObj($param, $formmaster, $currentPageId, $index, $currentSessionId) {
        if ($param['action'] == 'create') {
            $formcomponentObj = new FormComponents;
            $formcomponentObj->{FormComponents::form_master_id} = $formmaster->id;
            $formcomponenttype = FormComponentType::where(FormComponentType::cmp_type_name, $param['type'])->firstOrFail();
            $formcomponentObj->{FormComponents::form_component_type_id} = $formcomponenttype->id;
            $formcomponentObj->{FormComponents::fc_sort_no} = $index + 1;
            $formcomponentObj->saveOrFail();
        } elseif($param['action'] == 'update') {
            $formcomponentObj = FormComponents::findOrFail($param['componentId']);
            $formcomponentObj->{FormComponents::form_master_id} = $formmaster->id;
            $formcomponenttype = FormComponentType::where(FormComponentType::cmp_type_name, $param['type'])->firstOrFail();
            $formcomponentObj->{FormComponents::form_component_type_id} = $formcomponenttype->id;
            $formcomponentObj->{FormComponents::fc_sort_no} = $index + 1;
            $formcomponentObj->saveOrFail();
        } elseif($param['action'] == 'delete') {
            $formcomponentObj = FormComponents::findOrFail($param['componentId']);
            $formcomponentObj->delete();
        }
        return $formcomponentObj;
    }

    public function setLikertObj($param, $componentId) {
        $itArr = $param['likert'];

        $qnsObj = $this->setQuestionObj($param['action'], $componentId, $itArr);
        //-------------------
        $likertStmtIdArr=array();
        foreach ($itArr['statements'] as $stmtArr){
            $likertStmtObj=$this->setLikertStatementObj($stmtArr['action'], $qnsObj->id, $stmtArr['stmt'], $stmtArr);
            array_push($likertStmtIdArr, $likertStmtObj->id);
        }

        //delete all other  if any
        DB::table(FormQnsLikertStatement::table)
                ->where(FormQnsLikertStatement::form_question_id, '=', $qnsObj->id)
                ->whereNotIn('id',$likertStmtIdArr)
                ->delete();

        //-------------------
        $likertClmIdArr=array();
        foreach ($itArr['columns'] as $clmArr){
            $likertClmObj = $this->setLikertColumnObj($clmArr['action'], $qnsObj->id, $clmArr['column'], $clmArr);
            array_push($likertClmIdArr, $likertClmObj->id);
        }

        //delete all other  if any
        DB::table(FormQnsLikertColumns::table)
                ->where(FormQnsLikertColumns::form_question_id, '=', $qnsObj->id)
                ->whereNotIn('id',$likertClmIdArr)
                ->delete();
    }
    
    private function setLikertStatementObj($action, $questionId, $stmtText, $stmtArr) {
        $formQnsLikertStatementObj = null;
        if ($action == 'create') {
            $formQnsLikertStatementObj = new FormQnsLikertStatement;
            $formQnsLikertStatementObj->{FormQnsLikertStatement::form_question_id} = $questionId;
            if(empty($stmtText)){
                throw new \Exception("likert statement cannot be empty");
            }
            $formQnsLikertStatementObj->{FormQnsLikertStatement::likert_statement} = $stmtText;
            $formQnsLikertStatementObj->saveOrFail();
        } elseif ($action == 'update') {
            $formQnsLikertStatementObj = FormQnsLikertStatement::where(FormQnsLikertStatement::table.'.id', $stmtArr['stmtId'])->firstOrFail();
            $formQnsLikertStatementObj->{FormQnsLikertStatement::form_question_id} = $questionId;
            if(empty($stmtText)){
                throw new \Exception("likert statement cannot be empty");
            }
            $formQnsLikertStatementObj->{FormQnsLikertStatement::likert_statement} = $stmtText;
            $formQnsLikertStatementObj->saveOrFail();
        } elseif ($action == 'delete') {
            $formQnsLikertStatementObj = FormQnsLikertStatement::where(FormQnsLikertStatement::table.'.id', $stmtArr['stmtId'])->firstOrFail();
            $formQnsLikertStatementObj->delete();
        }
        return $formQnsLikertStatementObj;
    }

    private function setLikertColumnObj($action, $questionId, $colmText, $colmArr) {
        $formQnsLikertColumnsObj = null;
        if ($action == 'create') {
            $formQnsLikertColumnsObj = new FormQnsLikertColumns();
            $formQnsLikertColumnsObj->{FormQnsLikertColumns::form_question_id} = $questionId;
            if(empty($colmText)){
                throw new \Exception("likert column cannot be empty");
            }
            $formQnsLikertColumnsObj->{FormQnsLikertColumns::likert_column} = $colmText;
            $formQnsLikertColumnsObj->saveOrFail();
        } elseif ($action == 'update') {
            $formQnsLikertColumnsObj = FormQnsLikertColumns::where(FormQnsLikertColumns::table.'.id', $colmArr['colId'])->firstOrFail();
            if(empty($colmText)){
                throw new \Exception("likert column cannot be empty");
            }
            $formQnsLikertColumnsObj->{FormQnsLikertColumns::likert_column} = $colmText;
            $formQnsLikertColumnsObj->saveOrFail();
        } elseif ($action == 'delete') {
            $formQnsLikertColumnsObj = FormQnsLikertColumns::where(FormQnsLikertColumns::table.'.id', $colmArr['colId'])->firstOrFail();
            $formQnsLikertColumnsObj->delete();
        }
        return $formQnsLikertColumnsObj;
    }
    
    
    public function setSingleLineTextObj($param, $componentId) {
        $itArr = $param['singleLineText'];
        $this->setQuestionObj($param['action'], $componentId, $itArr);
    }

    public function setPriceObj($param, $componentId) {
        $itArr = $param['price'];
        $this->setQuestionObj($param['action'], $componentId, $itArr);
    }

    public function setParagraphTextObj($param, $componentId) {
        $itArr = $param['paragraphText'];
        $this->setQuestionObj($param['action'], $componentId, $itArr);
    }

    public function setNumberObj($param, $componentId) {
        $itArr = $param['number'];
        $this->setQuestionObj($param['action'], $componentId, $itArr);
    }

    public function setEmailObj($param, $componentId) {
        $itArr = $param['email'];
        $this->setQuestionObj($param['action'], $componentId, $itArr);
    }

    public function setPhoneObj($param, $componentId) {
        $itArr = $param['phone'];
        $this->setQuestionObj($param['action'], $componentId, $itArr);
    }

    public function setDateObj($param, $componentId) {
        $itArr = $param['date'];
        $this->setQuestionObj($param['action'], $componentId, $itArr);
    }

    public function setTimeObj($param, $componentId) {
        $itArr = $param['time'];
        $this->setQuestionObj($param['action'], $componentId, $itArr);
    }

    public function setFileUploadObj($param, $componentId) {
        $itArr = $param['fileUpload'];
        $this->setQuestionObj($param['action'], $componentId, $itArr);
    }

    public function setRatingObj($param, $componentId) {
        $itArr = $param['rating'];
        $this->setQuestionObj($param['action'], $componentId, $itArr);
    }

    public function setWebsiteObj($param, $componentId) {
        $itArr = $param['website'];
        $this->setQuestionObj($param['action'], $componentId, $itArr);
    }

    public function setNameObj($param, $componentId) {
        $itArr = $param['name'];
        $this->setQuestionObj($param['action'], $componentId, $itArr);
    }

    public function setAddressObj($param, $componentId) {
        $itArr = $param['address'];
        $this->setQuestionObj($param['action'], $componentId, $itArr);
    }

    private function setQuestionObj($action, $componentId, $paramArr) {
        $formQuestionObj = null;
        if ($action == 'create') {
            if(empty($paramArr['label'])){
                throw new \Exception("label cannot be empty");
            }
            $formQuestionObj = new FormQuestion;
            $formQuestionObj->{FormQuestion::form_question_text} = $paramArr['label'];
            $formQuestionObj->{FormQuestion::form_components_id} = $componentId;
            $formQuestionObj->{FormQuestion::is_mandatory} = $paramArr['isRequired'];
            $formQuestionObj->{FormQuestion::has_unique_answer} = !empty($paramArr['noDuplicate'])?true:false;
            $formQuestionObj->{FormQuestion::allow_otheroption} = !empty($paramArr['allowOther']) ? true : false;
            $formQuestionObj->{FormQuestion::randomize_answeroption} = !empty($paramArr['randomize']) ? true : false;
            //for numbers only
            $formQuestionObj->{FormQuestion::min_range} = isset($paramArr['minRange']) ? $paramArr['minRange'] : null;
            $formQuestionObj->{FormQuestion::max_range} = isset($paramArr['minRange']) ? $paramArr['minRange'] : null;
            $formQuestionObj->{FormQuestion::currency_type} = !empty($paramArr['currencyType']) ? $paramArr['currencyType'] : null;
            
            $formQuestionObj->saveOrFail();
        } elseif ($action == 'update') {
            if(empty($paramArr['label'])){
                throw new \Exception("label cannot be empty");
            }
            $formQuestionObj = FormQuestion::where(FormQuestion::form_components_id, $componentId)->firstOrFail();
            $formQuestionObj->{FormQuestion::form_question_text} = $paramArr['label'];
            $formQuestionObj->{FormQuestion::is_mandatory} = $paramArr['isRequired'];
            $formQuestionObj->{FormQuestion::has_unique_answer} = !empty($paramArr['noDuplicate'])?true:false;
            $formQuestionObj->{FormQuestion::allow_otheroption} = !empty($paramArr['allowOther']) ? true : false;
            $formQuestionObj->{FormQuestion::randomize_answeroption} = !empty($paramArr['randomize']) ? true : false;
            //for numbers only
            $formQuestionObj->{FormQuestion::min_range} = isset($paramArr['minRange']) ? $paramArr['minRange'] : null;
            $formQuestionObj->{FormQuestion::max_range} = isset($paramArr['maxRange']) ? $paramArr['maxRange'] : null;
            $formQuestionObj->{FormQuestion::currency_type} = !empty($paramArr['currencyType']) ? $paramArr['currencyType'] : null;
            
            $formQuestionObj->saveOrFail();
        } elseif ($action == 'delete') {
            $formQuestionObj = FormQuestion::where(FormQuestion::form_components_id, $componentId)->firstOrFail();
            $formQuestionObj->delete();
        }
        return $formQuestionObj;
    }

    public function setCheckboxes($param, $componentId) {
        $itArr = $param['checkboxes'];
        $this->setQuestionAndOptions($param['action'], $itArr, $componentId);
    }

    public function setDropdown($param, $componentId) {
        $itArr = $param['dropdown'];
        $this->setQuestionAndOptions($param['action'], $itArr, $componentId);
    }

    public function setMultipleChoice($param, $componentId) {
        $itArr = $param['multipleChoice'];
        $this->setQuestionAndOptions($param['action'], $itArr, $componentId);
    }

    public function setQuestionAndOptions($action, $itArr, $componentId) {
        $formQuestionObj = $this->setQuestionObj($action, $componentId, $itArr);
        $formQuestionObj->save();
        $optParams = $itArr['choices'];
        $this->setQuestionOptions($formQuestionObj, $optParams, $componentId);
    }

    public function setQuestionOptions($formQuestionObj, $optParams, $componentId) {
        $optIdArr=array();
        collect($optParams)->each(
            function ($item, $key) use ($formQuestionObj, &$optIdArr) {
                $curOptObj=$this->setOneQnsOption($item, $formQuestionObj, $key+1);
                !empty($curOptObj)?array_push($optIdArr,$curOptObj->id):null;
        });
        //delete any other options if any for given qns obj
        DB::table(FormQuestionOptions::table)
        ->where(FormQuestionOptions::form_question_id, '=', $formQuestionObj->id)
        ->whereNotIn('id',$optIdArr)
        ->delete();
    }

    public function setOneQnsOption($item,$formQuestionObj,$OptIndex) {
        
        //treat empty as $item['action'] 
        if(empty($item['action'])){
            $item['action'] = 'create';
        }

        if ( $item['action'] == 'create') {
            if(empty($item['label'])){
                throw new \Exception("option cannot be empty");
            }
                $formQnsOptionsObj = new FormQuestionOptions;
                $formQnsOptionsObj->{FormQuestionOptions::form_question_id} = $formQuestionObj->id;
                $formQnsOptionsObj->{FormQuestionOptions::fqo_sort_no} = $OptIndex;
                $formQnsOptionsObj->{FormQuestionOptions::option_text} = $item['label'];
                $formQnsOptionsObj->{FormQuestionOptions::max_quantity} = !empty($item['maxQuantity'])?$item['maxQuantity']:0;
                $formQnsOptionsObj->save();

            } elseif ($item['action'] == 'update') {
                $formQnsOptionsObj = FormQuestionOptions::where(
                                [
                                    'id' => $item['optId'],
                                    FormQuestionOptions::form_question_id => $formQuestionObj->id
                                ]
                        )->firstOrFail();
                if(empty($item['label'])){
                throw new \Exception("option cannot be empty");
                }
                $formQnsOptionsObj->{FormQuestionOptions::fqo_sort_no} = $OptIndex;
                $formQnsOptionsObj->{FormQuestionOptions::option_text} = $item['label'];
                $formQnsOptionsObj->{FormQuestionOptions::max_quantity} = !empty($item['maxQuantity'])?$item['maxQuantity']:0;
                $formQnsOptionsObj->save();
            }elseif($item['action'] == 'delete'){
                $formQnsOptionsObj = FormQuestionOptions::where(
                                [
                                    'id' => $item['optId'],
                                    FormQuestionOptions::form_question_id => $formQuestionObj->id
                                ]
                        )->firstOrFail();
                $formQnsOptionsObj->delete();
            }
        return $formQnsOptionsObj;
    }

    public function getSectionComponentObj($param, $currentPageId, $componentId) {
        if ($param['action'] == 'create') {
            $formSectionObj = new FormSection;
            $formSectionObj->{FormSection::form_page_id} = $currentPageId;

            $formSectionObj->{FormSection::form_components_id} = $componentId;
            if(empty($param['section']['title'])){
                throw new \Exception("section title cannot be empty");
            }
            $formSectionObj->{FormSection::fs_title} = $param['section']['title'];

            $formSectionObj->{FormSection::fs_desc} = $param['section']['description'];
            $formSectionObj->saveOrFail();
        } elseif ($param['action'] == 'update') {
            $formSectionObj = FormSection::where(FormSection::form_components_id, $componentId)->firstOrFail();
            $formSectionObj->{FormSection::form_page_id} = $currentPageId;
            $formSectionObj->{FormSection::form_components_id} = $componentId;

            if(empty($param['section']['title'])){
                throw new \Exception("section title cannot be empty");
            }
            $formSectionObj->{FormSection::fs_title} = $param['section']['title'];

            if(empty($param['section']['description'])){
                throw new \Exception("section description cannot be empty");
            }
            $formSectionObj->{FormSection::fs_desc} = $param['section']['description'];

            $formSectionObj->saveOrFail();
        } elseif($param['action'] == 'delete'){
            $formSectionObj = FormSection::where(FormSection::form_components_id, $componentId)->firstOrFail();
            $formSectionObj->delete();
            $formSectionObj = null;
        } else {
            $formSectionObj = null;
        }
        return $formSectionObj;
    }

    public function getFormMasterObj($paramObj, $user) {
        if ( !$paramObj->has("action") || $paramObj->action == 'create') {
            $formmaster = new FormMaster;
            $formmaster->{FormMaster::form_slug} = Utilities::getUniqueId();
            
            $formmasterOld = FormMaster::where(FormMaster::form_title, $paramObj->title)
                    ->where(FormMaster::creator_user_id, $user->id)->first();
            if(!empty($formmasterOld)){
                throw new \Exception("duplicate form title found");
            }
            
            $formmaster->{FormMaster::form_title} = $paramObj->title;
            $formmaster->{FormMaster::description} = $paramObj->description;
            $formStatusObj = FormStatus::where(FormStatus::status_name, $paramObj->formStatus)->firstOrFail();
            $formmaster->{FormMaster::form_status_id} = $formStatusObj->id;
            $formAccessTypeObj = FormAccessType::where(FormAccessType::name, $paramObj->formAccessType)->firstOrFail();
            $formmaster->{FormMaster::form_access_type_id} = $formAccessTypeObj->id;
            $formmaster->{FormMaster::is_template} = false; //@TODO
            $formmaster->{FormMaster::is_archived} = $paramObj->isArchived;
            $formmaster->{FormMaster::is_published} = $paramObj->isPublished;
            $formmaster->{FormMaster::allow_multi_submit} = !empty($paramObj->allowMultiSubmit)?$paramObj->allowMultiSubmit:false;
            $formmaster->{FormMaster::creator_user_id} = $user->id; 
            $formmaster->saveOrFail();
        } elseif ($paramObj->action == 'update') {

            $formmaster = FormMaster::where(FormMaster::form_slug, $paramObj->formSlug)->firstOrFail();

            $formmasterOld = FormMaster::where(FormMaster::form_title, $paramObj->title)
                ->where(FormMaster::creator_user_id, $formmaster->{FormMaster::creator_user_id})
                ->where("id","!=", $formmaster->id)
                ->first();
            if(!empty($formmasterOld)){
                throw new \Exception("duplicate form title found");
            }

            $permissionObj = FormMasterUsers::where(FormMasterUsers::user_id,$user->id)
                    ->where(FormMasterUsers::form_permission,"edit")
                    ->where(FormMasterUsers::form_master_id, $formmaster->id);

            $formCreatorCheckObj =  FormMaster::where(FormMaster::form_slug, $paramObj->formSlug)
                    ->where(FormMaster::creator_user_id, $user->id)
                    ->first();

            if(empty($formCreatorCheckObj) && empty($permissionObj)){
                throw new \Exception("you don't have permission to edit this form.");
            }

            $formmaster->{FormMaster::form_title} = $paramObj->title;
            $formmaster->{FormMaster::description} = $paramObj->description;
            $formStatusObj = FormStatus::where(FormStatus::status_name, $paramObj->formStatus)->firstOrFail();
            $formmaster->{FormMaster::form_status_id} = $formStatusObj->id;
            $formAccessTypeObj = FormAccessType::where(FormAccessType::name, $paramObj->formAccessType)->firstOrFail();
            $formmaster->{FormMaster::form_access_type_id} = $formAccessTypeObj->id;
            $formmaster->{FormMaster::is_template} = false; //@TODO
            $formmaster->{FormMaster::is_archived} = $paramObj->isArchived;
            $formmaster->{FormMaster::is_published} = $paramObj->isPublished;
            $formmaster->{FormMaster::allow_multi_submit} = !empty($paramObj->allowMultiSubmit)?$paramObj->allowMultiSubmit:false;
            $formmaster->saveOrFail();
        } elseif($paramObj->action == 'delete') {

            $formmaster = FormMaster::where(FormMaster::form_slug, $paramObj->formSlug)->firstOrFail();

            $permissionObj = FormMasterUsers::where(FormMasterUsers::user_id,$user->id)
                    ->where(FormMasterUsers::form_permission,"edit")
                    ->where(FormMasterUsers::form_master_id, $formmaster->id);

            $formCreatorCheckObj =  FormMaster::where(FormMaster::form_slug, $paramObj->formSlug)
                    ->where(FormMaster::creator_user_id, $user->id)
                    ->first();

            if(empty($formCreatorCheckObj) && empty($permissionObj)){
                throw new \Exception("you don't have permission to delete this form.");
            }

            $formmaster->delete();
        }
        return $formmaster;
    }

    public function getPageComponentObj($param, $formmaster, $formComponentId) {
        if ($param['action'] == 'create') {
            $formPageObj = new FormPage;
            $formPageObj->{FormPage::form_master_id} = $formmaster->id;
            $formPageObj->{FormPage::form_page_slug} = Utilities::getUniqueId();
            $formPageObj->{FormPage::form_components_id} = $formComponentId;
            $formPageObj->{FormPage::page_title} = !empty($param['page']['title']) ? $param['page']['title'] : "";
            $formPageObj->saveOrFail();
        } elseif($param['action'] == 'update') {
            $formPageObj = FormPage::where(FormPage::form_components_id, $formComponentId)->firstOrFail();
            $formPageObj->{FormPage::form_master_id} = $formmaster->id;
            $formPageObj->{FormPage::page_title} = !empty($param['page']['title']) ? $param['page']['title'] : "";
            $formPageObj->saveOrFail();
        } elseif($param['action'] == 'delete') {
            $formPageObj = FormPage::where(FormPage::form_components_id, $formComponentId)->firstOrFail();
            $formPageObj->delete();
            $formPageObj = null;
        }
        return $formPageObj;
    }

}
