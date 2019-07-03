<?php

namespace Modules\CRM\Repositories\LeadForm;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\UserManagement\Entities\User;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\FormManagement\Repositories\FormCreatorRepositoryInterface;
use Modules\OrgManagement\Entities\Organization;
use Modules\Common\Utilities\Utilities;
use Modules\CRM\Repositories\LeadFormRepositoryInterface;
use Modules\CRM\Entities\CRMLeadForm;
use Modules\PartnerManagement\Entities\Partner;

class LeadFormRepository implements LeadFormRepositoryInterface {

    protected $formRepository;
    public function __construct(FormCreatorRepositoryInterface $formRepository) {
        $this->formRepository = $formRepository;
    }

    public function editLeadForm(Request $request) {
        try {
            DB::beginTransaction();
            $organisationObj = DB::table(Organization::table)
                    ->join(Partner::table, Partner::table.'.id','=', Organization::table.'.'.Organization::partner_id)
                    ->where(Organization::slug, '=',$request->orgSlug)
                    ->select(
                        Organization::table.'.id AS orgId',
                        Partner::table.'.'.Partner::user_id.' AS partnerUserId'
                        )
                    ->first();
            if(empty($organisationObj)){
                throw new \Exception("Invalid Organisation");
            }
            
            $crmFormObj = DB::table(CRMLeadForm::table)
                ->where(CRMLeadForm::org_id, '=',$organisationObj->orgId)
                ->first();
            
            if(!empty($crmFormObj)){ // default form already exist
                $request->formAccessType = "crmLeadForm";
                $formRepositoryArr = $this->formRepository->create($request);
            } else {
                $request->formAccessType = "crmLeadForm";
                $formRepositoryArr = $this->formRepository->create($request);

                $formMasterId = $formRepositoryArr['data']['formId'];

                $crmFormObj = new CRMLeadForm;
                $crmFormObj->{CRMLeadForm::org_id} = $organisationObj->orgId;
                $crmFormObj->{CRMLeadForm::form_master_id} = $formMasterId;
                $crmFormObj->{CRMLeadForm::addedby_user_id} = $organisationObj->partnerUserId;
                $crmFormObj->save();
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
                "crmFormMasterId"=> $crmFormObj->{CRMLeadForm::form_master_id},
                "crmFormMapId"=> $crmFormObj->id                
               ),
            "code" => Response::HTTP_OK);
    }

}
