<?php

namespace Modules\CRM\Repositories\Lead;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\UserManagement\Entities\User;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\FormManagement\Repositories\FormCreatorRepositoryInterface;
use Modules\OrgManagement\Entities\Organization;
use Modules\Common\Utilities\Utilities;
use Modules\CRM\Repositories\LeadRepositoryInterface;
use Modules\CRM\Entities\CRMLeadForm;
use Modules\CRM\Entities\CRMLead;
use Modules\CRM\Entities\CRMLeadResponsiblePerson;
use Modules\CRM\Entities\CRMLeadUserType;
use Modules\CRM\Entities\CRMLeadStatus;
use Modules\CRM\Entities\CRMLeadAttachment;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\CRM\Entities\CRMLeadLog;

class LeadRepository implements LeadRepositoryInterface {

    protected $formRepository;
    public function __construct(FormCreatorRepositoryInterface $formRepository) {
        $this->formRepository = $formRepository;
    }

    public function setLead(Request $request) {
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
                $leadObj = $this->deleteLead($request, $organizationObj, $user);
                $msg = "Lead deleted successfully";
            } else if($request->action == "create"){
                $leadObj = $this->createLead($request, $organizationObj, $user);
                $msg = "Lead created successfully";
            } else if($request->action == "update"){
                $leadObj = $this->updateLead($request, $organizationObj, $user);
                $msg = "Lead updated successfully";
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
                "leadSlug"=> $leadObj->{CRMLead::slug}           
               ),
            "code" => Response::HTTP_OK);
    }
    
    private function createLead($request,$organizationObj, $user) {
        $crmLeadStatusObj = CRMLeadStatus::where(CRMLeadStatus::status_name,$request->status)
            ->first();
        if(empty($crmLeadStatusObj)){
            throw new \Exception("Invalid lead status");
        }
        $crmLeadUserTypeObj = CRMLeadUserType::where(CRMLeadUserType::type_name,$request->userType)
            ->first();
        if(empty($crmLeadUserTypeObj)){
            throw new \Exception("Invalid userType");
        }
        $leadObj = new CRMLead;
        $leadObj->{CRMLead::slug} = Utilities::getUniqueId();
        $leadObj->{CRMLead::org_id} = $organizationObj->id;

        $leadObj->{CRMLead::name} = $request->name;
        $leadObj->{CRMLead::lead_status_id} = $crmLeadStatusObj->id;
        $leadObj->{CRMLead::lead_user_type_id} = $crmLeadUserTypeObj->id;
        $leadObj->{CRMLead::email} = $request->email;
        $leadObj->{CRMLead::phone} = $request->phone;
        $leadObj->{CRMLead::creator_user_id} = $user->id;
        $leadObj->{CRMLead::date_of_birth} = $request->dob;
        $leadObj->{CRMLead::image_path} = $request->file;
        $leadObj->save();
        
        $responsibles = collect($request->responsibleEmployeeSlugs);

        //add responsibles
        $responsibles->each(function($item) use($organizationObj, $leadObj, $user) {

            $orgEmpObj = OrgEmployee::where(OrgEmployee::slug,$item)
                ->where(OrgEmployee::org_id,$organizationObj->id)
                ->first();
            if(empty($orgEmpObj)){
                throw new \Exception("Invalid employeeSlug");
            }

            $responsible = new CRMLeadResponsiblePerson;
            $responsible->{CRMLeadResponsiblePerson::org_id} = $organizationObj->id;
            $responsible->{CRMLeadResponsiblePerson::crm_lead_id} = $leadObj->id;
            $responsible->{CRMLeadResponsiblePerson::crm_employee_id} = $orgEmpObj->id;
            $responsible->{CRMLeadResponsiblePerson::addedby_user_id} = $user->id;
            $responsible->save();
        });

        return $leadObj;
    }

    private function updateLead($request,$organizationObj, $user) {
        $leadObj = CRMLead::where(CRMLead::slug,$request->status)
            ->where(CRMLead::org_id,$organizationObj->id)
            ->first();
        if(empty($leadObj)){
            throw new \Exception("Invalid lead");
        }

        $crmLeadStatusObj = CRMLeadStatus::where(CRMLeadStatus::status_name,$request->status)
            ->first();
        if(empty($crmLeadStatusObj)){
            throw new \Exception("Invalid lead status");
        }
        $crmLeadUserTypeObj = CRMLeadUserType::where(CRMLeadUserType::type_name,$request->userType)
            ->first();
        if(empty($crmLeadUserTypeObj)){
            throw new \Exception("Invalid userType");
        }
        
        $leadObj->{CRMLead::name} = $request->name;
        $leadObj->{CRMLead::lead_status_id} = $crmLeadStatusObj->id;
        $leadObj->{CRMLead::lead_user_type_id} = $crmLeadUserTypeObj->id;
        $leadObj->{CRMLead::email} = $request->email;
        $leadObj->{CRMLead::phone} = $request->phone;
        $leadObj->{CRMLead::creator_user_id} = $user->id;
        $leadObj->{CRMLead::date_of_birth} = $request->dob;
        $leadObj->{CRMLead::image_path} = $request->file;
        $leadObj->save();
        
        $responsibles = collect($request->responsibleEmployeeSlugs);
        $responsiblesIdArr = array();
        
        //add/update responsibles
        $responsibles->each(function($item) use($organizationObj, $leadObj, $user, $responsiblesIdArr) {
            $orgEmpObj = OrgEmployee::where(OrgEmployee::slug,$item)
                ->where(OrgEmployee::org_id,$organizationObj->id)
                ->first();
            if(empty($orgEmpObj)){
                throw new \Exception("Invalid employeeSlug");
            }

            $responsible = CRMLeadResponsiblePerson::where(CRMLeadResponsiblePerson::crm_employee_id,$orgEmpObj->id)
                ->where(CRMLeadResponsiblePerson::org_id,$organizationObj->id)
                ->where(CRMLeadResponsiblePerson::crm_lead_id,$leadObj->id)
                ->first();
            if(empty($responsible)){ //add
                $responsible = new CRMLeadResponsiblePerson;
                $responsible->{CRMLeadResponsiblePerson::org_id} = $organizationObj->id;
                $responsible->{CRMLeadResponsiblePerson::crm_lead_id} = $leadObj->id;
                $responsible->{CRMLeadResponsiblePerson::crm_employee_id} = $orgEmpObj->id;
                $responsible->{CRMLeadResponsiblePerson::addedby_user_id} = $user->id;
                $responsible->save();
            }          
            
            array_push($responsiblesIdArr, $responsible->id);
        });

        //delete all other if any
        DB::table(CRMLeadResponsiblePerson::table)
            ->where(CRMLeadResponsiblePerson::org_id,$organizationObj->id)
            ->where(CRMLeadResponsiblePerson::crm_lead_id,$leadObj->id)
            ->whereNotIn('id',$responsiblesIdArr)
            ->delete();
        return $leadObj;
    }

    private function deleteLead($request,$organizationObj, $user) {
        $crmLeadObj = CRMLead::where(CRMLead::org_id,$organizationObj->id)
            ->where(CRMLead::slug,$request->leadSlug)
            ->first();
        if(empty($crmLeadObj)){
            throw new \Exception("Invalid leadSlug");
        }
        $crmLeadObj->delete();
        return $crmLeadObj;
    }
    
    public function setLeadStatus(Request $request) {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $organizationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
            if(empty($organizationObj)){
                throw new \Exception("Invalid Organisation");
            }

            $leadObj = CRMLead::where(CRMLead::slug,$request->leadSlug)
                ->where(CRMLead::org_id,$organizationObj->id)
                ->first();
            if(empty($leadObj)){
                throw new \Exception("Invalid leadSlug");
            }

            $crmLeadStatusObj = CRMLeadStatus::where(CRMLeadStatus::status_name,$request->status)
                ->first();
            if(empty($crmLeadStatusObj)){
                throw new \Exception("Invalid lead status");
            }
            
            $leadObj->{CRMLead::lead_status_id} = $crmLeadStatusObj->id;
            $leadObj->save();
            
            $crmLeadLogObj = new CRMLeadLog;
            $crmLeadLogObj->{CRMLeadLog::org_id} = $organizationObj->id;
            $crmLeadLogObj->{CRMLeadLog::crm_lead_id} = $leadObj->id;
            $crmLeadLogObj->{CRMLeadLog::creator_user_id} = $user->id;
            $crmLeadLogObj->{CRMLeadLog::log_date} = date('Y-m-d H:i:s');
            $crmLeadLogObj->{CRMLeadLog::description} = "LeadStatus updated to ".$crmLeadStatusObj->{CRMLeadStatus::status_name};
            $crmLeadLogObj->save();
            
            DB::commit();
            $msg = "Lead Status updated successfully!";
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
                "leadSlug"=> $leadObj->{CRMLead::slug}           
               ),
            "code" => Response::HTTP_OK);
    }
    
    public function getLeads(Request $request) {
        try {
            $user = Auth::user();
            $organizationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
            if(empty($organizationObj)){
                throw new \Exception("Invalid Organisation");
            }

            $queryBuilder = CRMLead::where(CRMLead::org_id,$organizationObj->id)
                ->join(CRMLeadStatus::table, CRMLeadStatus::table.'.id', "=", CRMLead::table.'.'.CRMLead::lead_status_id)
                ->join(CRMLeadUserType::table, CRMLeadUserType::table.'.id', "=", CRMLead::table.'.'.CRMLead::lead_user_type_id);
 
                     //search query
            if($request->q){
                $queryBuilder = $queryBuilder->where(CRMLead::name,'like','%'.$request->q.'%');
            }
            
            $crmLeads = $queryBuilder->get();
            
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
                "leads" => $crmLeads          
               ),
            "code" => Response::HTTP_OK);
    }
    
    public function getCustomers(Request $request) {
        try {
            $user = Auth::user();
            $organizationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
            if(empty($organizationObj)){
                throw new \Exception("Invalid Organisation");
            }
            $statusType = "customer";
            $crmLeadStatusObj = CRMLeadStatus::where(CRMLeadStatus::status_name, $statusType)
                ->first();
            if(empty($crmLeadStatusObj)){
                throw new \Exception("Invalid customer status");
            }
            $queryBuilder = CRMLead::where(CRMLead::org_id,$organizationObj->id)
                    ->where(CRMLead::lead_status_id,$crmLeadStatusObj->id)
                ->join(CRMLeadStatus::table, CRMLeadStatus::table.'.id', "=", CRMLead::table.'.'.CRMLead::lead_status_id)
                ->join(CRMLeadUserType::table, CRMLeadUserType::table.'.id', "=", CRMLead::table.'.'.CRMLead::lead_user_type_id);
                
             
                     //search query
            if($request->q){
                $queryBuilder = $queryBuilder->where(CRMLead::name,'like','%'.$request->q.'%');
            }
            
            $crmCustomers = $queryBuilder->get();
            
            
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
                "customers" => $crmCustomers
               ),
            "code" => Response::HTTP_OK);
    }

    public function getLeadDetails(Request $request) {
        try {
            $user = Auth::user();
            $organizationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
            if(empty($organizationObj)){
                throw new \Exception("Invalid Organisation");
            }

            $crmLead = CRMLead::where(CRMLead::org_id,$organizationObj->id)
                ->where(CRMLead::slug,$request->leadSlug)
                ->join(CRMLeadStatus::table, CRMLeadStatus::table.'.id', "=", CRMLead::table.'.'.CRMLead::lead_status_id)
                ->join(CRMLeadUserType::table, CRMLeadUserType::table.'.id', "=", CRMLead::table.'.'.CRMLead::lead_user_type_id)
                ->first();
            if(empty($organizationObj)){
                throw new \Exception("Invalid Organisation");
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
                "lead" => $crmLead        
               ),
            "code" => Response::HTTP_OK);
    }
}
