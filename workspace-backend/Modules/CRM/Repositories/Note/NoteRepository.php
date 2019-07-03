<?php

namespace Modules\CRM\Repositories\Lead;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\UserManagement\Entities\User;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\OrgManagement\Entities\Organization;
use Modules\Common\Utilities\Utilities;
use Modules\CRM\Repositories\NoteRepositoryInterface;
use Modules\CRM\Entities\CRMLead;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\CRM\Entities\CRMLeadNote;

class NoteRepository implements NoteRepositoryInterface {


    public function __construct() {
    }

    public function setNote(Request $request) {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $organizationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
            if(empty($organizationObj)){
                throw new \Exception("Invalid Organisation");
            }
            
            if($request->action == "delete"){
                $noteObj = $this->deleteNote($request, $organizationObj, $user);
                $msg = "Note deleted successfully";
            } else if($request->action == "create"){
                $noteObj = $this->createNote($request, $organizationObj, $user);
                $msg = "Note created successfully";
            } else if($request->action == "update"){
                $noteObj = $this->updateNote($request, $organizationObj, $user);
                $msg = "Note updated successfully";
            } else {
                throw new \Exception("Invalid action");
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
                "msg" => $msg,
                "noteSlug"=> $noteObj->{CRMLeadNote::slug}           
               ),
            "code" => Response::HTTP_OK);
    }
    
    private function createNote($request,$organizationObj, $user) {
        $crmLeadObj = CRMLead::where(CRMLead::slug,$request->leadSlug)
            ->where(CRMLead::org_id,$organizationObj->id)
            ->first();
        if(empty($crmLeadObj)){
            throw new \Exception("Invalid leadSlug");
        }

        $leadNoteObj = new CRMLeadNote;
        $leadNoteObj->{CRMLeadNote::slug} = Utilities::getUniqueId();
        $leadNoteObj->{CRMLeadNote::org_id} = $organizationObj->id;
        $leadNoteObj->{CRMLeadNote::crm_lead_id} = $crmLeadObj->id;
        $leadNoteObj->{CRMLeadNote::description} = $request->description;
        $leadNoteObj->{CRMLeadNote::creator_user_id} = $user->id;
        $leadNoteObj->save();

        return $leadNoteObj;
    }

    private function updateNote($request,$organizationObj, $user) {
        $crmLeadObj = CRMLead::where(CRMLead::slug,$request->leadSlug)
            ->where(CRMLead::org_id,$organizationObj->id)
            ->first();
        if(empty($crmLeadObj)){
            throw new \Exception("Invalid leadSlug");
        }
        $leadNoteObj = CRMLeadNote::where(CRMLeadNote::slug,$request->noteSlug)
            ->first();
        if(empty($leadNoteObj)){
            throw new \Exception("Invalid noteSlug");
        }
        $leadNoteObj->{CRMLeadNote::description} = $request->description;
        $leadNoteObj->save();

        return $leadNoteObj;
    }

    private function deleteNote($request,$organizationObj, $user) {
        $crmLeadObj = CRMLead::where(CRMLead::slug,$request->leadSlug)
            ->where(CRMLead::org_id,$organizationObj->id)
            ->first();
        if(empty($crmLeadObj)){
            throw new \Exception("Invalid leadSlug");
        }
        $crmLeadNoteObj = CRMLeadNote::where(CRMLeadNote::org_id,$organizationObj->id)
            ->where(CRMLeadNote::slug,$request->noteSlug)
            ->where(CRMLeadNote::crm_lead_id, $crmLeadObj->id)
            ->first();
        if(empty($crmLeadNoteObj)){
            throw new \Exception("Invalid noteSlug");
        }
        $crmLeadNoteObj->delete();
        return $crmLeadNoteObj;
    }
    
    public function getNotes(Request $request) {
        try {
            $user = Auth::user();
            $organizationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
            if(empty($organizationObj)){
                throw new \Exception("Invalid Organisation");
            }

            $crmLeadObj = CRMLead::where(CRMLead::slug,$request->leadSlug)
                ->where(CRMLead::org_id,$organizationObj->id)
                ->first();
            if(empty($crmLeadObj)){
                throw new \Exception("Invalid leadSlug");
            }

            $queryBuilder = CRMLeadNote::where(CRMLeadNote::org_id,$organizationObj->id)
                    ->where(CRMLeadNote::crm_lead_id, $crmLeadObj->id);
 
            //search query
            if($request->q){
                $crmLeadNotes = $queryBuilder->where(CRMLeadNote::description,'like','%'.$request->q.'%')->get();
            } else if($request->noteSlug){
                $crmLeadNotes = $queryBuilder->where(CRMLeadNote::slug,$request->noteSlug )->first();
            } else {
                $crmLeadNotes = $queryBuilder->get();
            }
            
        } catch (\Exception $e) {
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
                "notes" => $crmLeadNotes          
               ),
            "code" => Response::HTTP_OK);
    }

}
