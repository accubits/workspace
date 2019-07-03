<?php

namespace Modules\HrmManagement\Repositories\Performance;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\ResponseStatus;
use Modules\Common\Utilities\Utilities;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;
use Modules\HrmManagement\Entities\HrmKraModule;
use Modules\HrmManagement\Repositories\PerformanceRepositoryInterface;
use Modules\UserManagement\Entities\UserProfile;
use Modules\HrmManagement\Entities\HrmAppraisalCycleApplicable;
use Modules\HrmManagement\Entities\HrmAppraisalCycleMaster;
use Modules\HrmManagement\Entities\HrmACMainModuleWeightage;
use Modules\HrmManagement\Entities\HrmAppraisalCyclePeriod;
use Modules\HrmManagement\Entities\HrmAcKraModuleWeightage;
use Modules\HrmManagement\Entities\HrmAppraisalForEmployee;
use Modules\HrmManagement\Entities\HrmAppraisalForDepartment;
use Modules\HrmManagement\Entities\HrmAppraisalCycleReviewerEmployee;
use Modules\HrmManagement\Entities\HrmAppraisalCycleReviewerDepartment;
use Modules\OrgManagement\Entities\OrgDepartment;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\HrmManagement\Entities\HrmAppraisalMainModule;

class PerformanceRepository implements PerformanceRepositoryInterface
{

    protected $content;
    protected $statusArray;

    public function __construct()
    {
        $this->content = array();
        $this->statusArray = array();
    }

    /**
     * create, update, delete a Appraisal Cycle
     * @param Request $request
     * @return array
     */
    public function setAppraisalCycle(Request $request)
    {
        $user  = Auth::user();
        $msg = "invalid action / action missing";
        $appraisalCycleSlug = null;

        try {
            DB::beginTransaction();
            
            if($request->action == "create"){
                $appraisalCycleSlug = $this->createAppraisalCycle($request, $user);
                $msg = "Appraisal cycle created successfully";
            } else if($request->action == "update"){
                $appraisalCycleSlug = $this->updateAppraisalCycle($request, $user);
                $msg = "Appraisal cycle updated successfully";                
            } else if($request->action == "delete"){
                $appraisalCycleSlug = $this->deleteAppraisalCycle($request, $user);
                $msg = "Appraisal cycle deleted successfully";
            }

            DB::commit();

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $this->content['error']   =  array(
                "msg" => $e->getMessage());
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
                "appraisalCycleSlug"=>$appraisalCycleSlug
                ),
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );

    }

    private function deleteAppraisalCycle($request, $user) {
        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        }
        $appraisalCycleObj = HrmAppraisalCycleMaster::where(HrmAppraisalCycleMaster::slug, '=',$request->appraisalCycleSlug)
                ->where(HrmAppraisalCycleMaster::org_id, '=',$organisationObj->id)
                ->first();
        if(empty($appraisalCycleObj)){
            throw new \Exception("Invalid AppraisalCycle");
        }
        $appraisalCycleObj->delete();
        return $appraisalCycleObj->{HrmAppraisalCycleMaster::slug};
    }
    private function createAppraisalCycle($request, $user) {

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        }
        $appraisalCycleMasterObj = new HrmAppraisalCycleMaster;
        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::slug} = Utilities::getUniqueId();

        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::org_id}=$organisationObj->id;
        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::name} = $request->title;
        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::description} = $request->description;

        $hrmAppraisalCyclePeriodObj = HrmAppraisalCyclePeriod::where(HrmAppraisalCyclePeriod::period_type, '=',$request->cycle['type'])
                ->select('id')->first();
        if(empty($hrmAppraisalCyclePeriodObj)){
            throw new \Exception("Invalid appraisal cycle period type");
        }
        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::appraisal_cycle_period_id} = $hrmAppraisalCyclePeriodObj->id;
        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::cycle_start_date} = !empty($request->cycle['startDate']) ? date('Y-m-d H:i:s', $request->cycle['startDate']):null;
        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::cycle_end_date} = !empty($request->cycle['endDate']) ? date('Y-m-d H:i:s', $request->cycle['endDate']):null;

        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::processing_start_date} = !empty($request->cycle['processingStartDate']) ? date('Y-m-d H:i:s', $request->cycle['processingStartDate']):null;
        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::processing_end_date} = !empty($request->cycle['processingEndDate']) ? date('Y-m-d H:i:s', $request->cycle['processingEndDate']):null;

        $hrmAppraisalCycleApplicableObj = HrmAppraisalCycleApplicable::where(HrmAppraisalCycleApplicable::applicable_type, '=',$request->applicable['type'])
                ->select('id')->first();
        if(empty($hrmAppraisalCycleApplicableObj)){
            throw new \Exception("Invalid Applicable type");
        }
        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::applicable_id} = $hrmAppraisalCycleApplicableObj->id;

        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::review_by_department_head} = $request->reviewers['includeDepartmentHead'];
        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::review_by_employee} = $request->reviewers['includeEmployee'];
        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::creator_user_id} = $user->id;
        $appraisalCycleMasterObj->save();

        //create applicable departments, employees
        $this->createForDepartmentsAndEmployee($request, $appraisalCycleMasterObj, $organisationObj);
        //create reviwer departments , employees
        $this->createReviewerDepartmentsAndEmployee($request, $appraisalCycleMasterObj, $organisationObj);
        //create mainmodule weightages
        $this->createMainModuleWeightages($request, $appraisalCycleMasterObj, $organisationObj);
        //create performanceindicatormodule weightages
        $this->createKraModuleWeightages($request, $appraisalCycleMasterObj, $organisationObj);
        
        return $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::slug};
    }

    private function createForDepartmentsAndEmployee($request, $appraisalCycleMasterObj, $organisationObj) {

        if($request->applicable['type']== "wholeOrganisation"){
            return NULL; // no need to add department or employee
        }

        if($request->applicable['type']== "department"){

            $departmentsCol= OrgDepartment::whereIn(OrgDepartment::slug, $request->applicable['departments'])
            ->where(OrgDepartment::org_id, $organisationObj->id)
                    ->select('id', OrgDepartment::slug)->get();
            $departmentIdSlugMap = array();
            $departmentsCol->each(function($departmentItem) use (&$departmentIdSlugMap){
                $departmentIdSlugMap[$departmentItem->slug] = $departmentItem->id;
            });

            $savedHrmAppraisalForDepartmentIdArr =array();
            foreach ($request->applicable['departments'] as $departmentSlug) {
                $hrmAppraisalForDepartment = new HrmAppraisalForDepartment;
                $hrmAppraisalForDepartment->{HrmAppraisalForDepartment::org_id}=$organisationObj->id;
                $hrmAppraisalForDepartment->{HrmAppraisalForDepartment::appraisal_cycle_id}=$appraisalCycleMasterObj->id;

                if(empty($departmentIdSlugMap[$departmentSlug])){
                    throw new \Exception("Invalid department slug found!");
                }
                $hrmAppraisalForDepartment->{HrmAppraisalForDepartment::department_id}=$departmentIdSlugMap[$departmentSlug];
                $hrmAppraisalForDepartment->save();
                array_push($savedHrmAppraisalForDepartmentIdArr, $hrmAppraisalForDepartment->id);
            }
            if(empty($savedHrmAppraisalForDepartmentIdArr)){
                throw new \Exception("no applicable for departments set!");
            }
        }
        if($request->applicable['type']== "employee"){

            $employeesCol= OrgEmployee::whereIn(OrgEmployee::slug, $request->applicable['employees'])
            ->where(OrgEmployee::org_id, $organisationObj->id)
                    ->select('id', OrgEmployee::slug)->get();
            $employeeIdSlugMap = array();
            $employeesCol->each(function($employeeItem) use (&$employeeIdSlugMap){
                $employeeIdSlugMap[$employeeItem->slug] = $employeeItem->id;
            });

            $savedHrmAppraisalForEmployeeIdArr =array();
            foreach ($request->applicable['employees'] as $employeeSlug) {
                $hrmAppraisalForEmployee = new HrmAppraisalForEmployee;
                $hrmAppraisalForEmployee->{HrmAppraisalForEmployee::org_id}=$organisationObj->id;
                $hrmAppraisalForEmployee->{HrmAppraisalForEmployee::appraisal_cycle_id}=$appraisalCycleMasterObj->id;

                if(empty($employeeIdSlugMap[$employeeSlug])){
                    throw new \Exception("Invalid employee slug found!");
                }
                $hrmAppraisalForEmployee->{HrmAppraisalForEmployee::employee_id} = $employeeIdSlugMap[$employeeSlug];
                $hrmAppraisalForEmployee->save();
                array_push($savedHrmAppraisalForEmployeeIdArr, $hrmAppraisalForEmployee->id);
            }
            if(empty($savedHrmAppraisalForDepartmentIdArr)){
                throw new \Exception("no applicable for employee set!");
            }
        }
        

    }
    
    
    
    private function createReviewerDepartmentsAndEmployee($request, $appraisalCycleMasterObj, $organisationObj) {

        if($request->reviewers['includeDepartmentHead']){

            $departmentsCol= OrgDepartment::whereIn(OrgDepartment::slug, $request->reviewers['departments'])
            ->where(OrgDepartment::org_id, $organisationObj->id)
                    ->select('id', OrgDepartment::slug)->get();
            $departmentIdSlugMap = array();
            $departmentsCol->each(function($departmentItem) use (&$departmentIdSlugMap){
                $departmentIdSlugMap[$departmentItem->slug] = $departmentItem->id;
            });

            foreach ($request->reviewers['departments'] as $departmentSlug) {
                $HrmAppraisalCycleReviewerDepartment = new HrmAppraisalCycleReviewerDepartment;
                $HrmAppraisalCycleReviewerDepartment->{HrmAppraisalCycleReviewerDepartment::org_id}=$organisationObj->id;
                $HrmAppraisalCycleReviewerDepartment->{HrmAppraisalCycleReviewerDepartment::appraisal_cycle_id}=$appraisalCycleMasterObj->id;

                if(empty($departmentIdSlugMap[$departmentSlug])){
                    throw new \Exception("Invalid department slug found in reviewer departments!");
                }
                $HrmAppraisalCycleReviewerDepartment->{HrmAppraisalCycleReviewerDepartment::department_id} = $departmentIdSlugMap[$departmentSlug];
                $HrmAppraisalCycleReviewerDepartment->save();
            }
        }

        if($request->reviewers['includeEmployee']){

            $employeesCol= OrgEmployee::whereIn(OrgEmployee::slug, $request->reviewers['employees'])
            ->where(OrgEmployee::org_id, $organisationObj->id)
                    ->select('id', OrgEmployee::slug)->get();
            $employeeIdSlugMap = array();
            $employeesCol->each(function($employeeItem) use (&$employeeIdSlugMap){
                $employeeIdSlugMap[$employeeItem->slug] = $employeeItem->id;
            });

            foreach ($request->reviewers['employees'] as $employeeSlug) {
                $hrmAppraisalCycleReviewerEmployee = new HrmAppraisalCycleReviewerEmployee;
                $hrmAppraisalCycleReviewerEmployee->{HrmAppraisalCycleReviewerEmployee::org_id}=$organisationObj->id;
                $hrmAppraisalCycleReviewerEmployee->{HrmAppraisalCycleReviewerEmployee::appraisal_cycle_id}=$appraisalCycleMasterObj->id;

                if(empty($employeeIdSlugMap[$employeeSlug])){
                    throw new \Exception("Invalid employee slug found in reviewers!");
                }
                $hrmAppraisalCycleReviewerEmployee->{HrmAppraisalCycleReviewerEmployee::employee_id} = $employeeIdSlugMap[$employeeSlug];
                $hrmAppraisalCycleReviewerEmployee->save();
            }
        }

    }
    
    private function createMainModuleWeightages($request, $appraisalCycleMasterObj, $organisationObj) {
        
        $weightagePercentSum=0;
        foreach ($request->mainModules as $item) {
            $weightagePercentSum+= $item['weightagePercent'];
        }
        if($weightagePercentSum!=100){
            throw new \Exception("Sum of module weightage percents should be 100!");
        }
        foreach ($request->mainModules as $item) {
            $mainModuleObj = DB::table(HrmAppraisalMainModule::table)
                    ->where(HrmAppraisalMainModule::module_name, '=',$item['mainModule'])
                    ->select('id')
                    ->first();
            if(empty($mainModuleObj)){
                throw new \Exception("Invalid main module name ".$item['mainModule']);
            }            
            $hrmACMainModuleWeightage = new HrmACMainModuleWeightage;
            $hrmACMainModuleWeightage->{HrmACMainModuleWeightage::org_id} = $organisationObj->id;
            $hrmACMainModuleWeightage->{HrmACMainModuleWeightage::appraisal_cycle_id} = $appraisalCycleMasterObj->id;
            $hrmACMainModuleWeightage->{HrmACMainModuleWeightage::appraisal_main_module_id} = $mainModuleObj->id;
            $hrmACMainModuleWeightage->{HrmACMainModuleWeightage::score_percent} = $item['weightagePercent'];
            $hrmACMainModuleWeightage->save();
        }
    }
    
    private function createKraModuleWeightages($request, $appraisalCycleMasterObj, $organisationObj) {
        
        $weightagePercentSum=0;
        foreach ($request->performanceIndicator as $item) {
            $weightagePercentSum+= $item['weightagePercent'];
        }
        if($weightagePercentSum!=100){
            throw new \Exception("Sum of performance indicator module percents should be 100!");
        }
        foreach ($request->performanceIndicator as $item) {
            $kraModuleObj = DB::table(HrmKraModule::table)
                    ->where(HrmKraModule::slug, '=',$item['kraModuleSlug'])
                    ->select('id')
                    ->first();
            if(empty($kraModuleObj)){
                throw new \Exception("Invalid performance indicator slug");
            }            
            $hrmAcKraModuleWeightage = new HrmAcKraModuleWeightage;
            $hrmAcKraModuleWeightage->{HrmAcKraModuleWeightage::org_id} = $organisationObj->id;
            $hrmAcKraModuleWeightage->{HrmAcKraModuleWeightage::appraisal_cycle_id} = $appraisalCycleMasterObj->id;
            $hrmAcKraModuleWeightage->{HrmAcKraModuleWeightage::kra_module_id} = $kraModuleObj->id;
            $hrmAcKraModuleWeightage->{HrmAcKraModuleWeightage::score_percent} = $item['weightagePercent'];
            $hrmAcKraModuleWeightage->save();
        }
    }
    
    
    
    private function updateAppraisalCycle($request, $user) {

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        }
        $appraisalCycleMasterObj = HrmAppraisalCycleMaster::where(HrmAppraisalCycleMaster::slug, '=',$request->appraisalCycleSlug)
        ->where(HrmAppraisalCycleMaster::org_id, '=',$organisationObj->id)
        ->first();
        if(empty($appraisalCycleMasterObj)){
            throw new \Exception("Invalid AppraisalCycle");
        }
        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::name} = $request->title;
        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::description} = $request->description;

        $hrmAppraisalCyclePeriodObj = HrmAppraisalCyclePeriod::where(HrmAppraisalCyclePeriod::period_type, '=',$request->cycle['type'])
                ->select('id')->first();
        if(empty($hrmAppraisalCyclePeriodObj)){
            throw new \Exception("Invalid appraisal cycle period type");
        }
        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::appraisal_cycle_period_id} = $hrmAppraisalCyclePeriodObj->id;
        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::cycle_start_date} = !empty($request->cycle['startDate']) ? date('Y-m-d H:i:s', $request->cycle['startDate']):null;
        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::cycle_end_date} = !empty($request->cycle['endDate']) ? date('Y-m-d H:i:s', $request->cycle['endDate']):null;

        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::processing_start_date} = !empty($request->cycle['processingStartDate']) ? date('Y-m-d H:i:s', $request->cycle['processingStartDate']):null;
        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::processing_end_date} = !empty($request->cycle['processingEndDate']) ? date('Y-m-d H:i:s', $request->cycle['processingEndDate']):null;

        $hrmAppraisalCycleApplicableObj = HrmAppraisalCycleApplicable::where(HrmAppraisalCycleApplicable::applicable_type, '=',$request->applicable['type'])
                ->select('id')->first();
        if(empty($hrmAppraisalCycleApplicableObj)){
            throw new \Exception("Invalid Applicable type");
        }
        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::applicable_id} = $hrmAppraisalCycleApplicableObj->id;

        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::review_by_department_head} = $request->reviewers['includeDepartmentHead'];
        $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::review_by_employee} = $request->reviewers['includeEmployee'];
        $appraisalCycleMasterObj->save();

        //update applicable departments, employees
        $this->updateForDepartmentsAndEmployee($request, $appraisalCycleMasterObj, $organisationObj);
        //update reviwer departments , employees
        $this->updateReviewerDepartmentsAndEmployee($request, $appraisalCycleMasterObj, $organisationObj);
        //update mainmodule weightages
        $this->updateMainModuleWeightages($request, $appraisalCycleMasterObj, $organisationObj);
        //update performanceindicatormodule weightages
        $this->updateKraModuleWeightages($request, $appraisalCycleMasterObj, $organisationObj);
        
        return $appraisalCycleMasterObj->{HrmAppraisalCycleMaster::slug};
    }
    
    private function updateForDepartmentsAndEmployee($request, $appraisalCycleMasterObj, $organisationObj) {

        if($request->applicable['type']== "wholeOrganisation"){
            return NULL; // no need to add department or employee
        }

        if($request->applicable['type']== "department"){

            $departmentsCol= OrgDepartment::whereIn(OrgDepartment::slug, $request->applicable['departments'])
            ->where(OrgDepartment::org_id, $organisationObj->id)
                    ->select('id', OrgDepartment::slug)->get();
            $departmentIdSlugMap = array();
            $departmentsCol->each(function($departmentItem) use (&$departmentIdSlugMap){
                $departmentIdSlugMap[$departmentItem->slug] = $departmentItem->id;
            });

            $savedHrmAppraisalForDepartmentIdArr =array();
            foreach ($request->applicable['departments'] as $departmentSlug) {
                if(empty($departmentIdSlugMap[$departmentSlug])){
                    throw new \Exception("Invalid department slug found!");
                }                
                $hrmAppraisalForDepartment = HrmAppraisalForDepartment::where(HrmAppraisalForDepartment::appraisal_cycle_id,$appraisalCycleMasterObj->id)
                        ->where(HrmAppraisalForDepartment::department_id,$departmentIdSlugMap[$departmentSlug])
                        ->first();
                if(empty($hrmAppraisalForDepartment)){
                    $hrmAppraisalForDepartment = new HrmAppraisalForDepartment;
                    $hrmAppraisalForDepartment->{HrmAppraisalForDepartment::org_id}=$organisationObj->id;
                    $hrmAppraisalForDepartment->{HrmAppraisalForDepartment::appraisal_cycle_id}=$appraisalCycleMasterObj->id;
                    $hrmAppraisalForDepartment->{HrmAppraisalForDepartment::department_id}=$departmentIdSlugMap[$departmentSlug];
                    $hrmAppraisalForDepartment->save();
                }
                array_push($savedHrmAppraisalForDepartmentIdArr, $hrmAppraisalForDepartment->id);
            }
            if(empty($savedHrmAppraisalForDepartmentIdArr)){
                throw new \Exception("no applicable for departments set!");
            }
            DB::table(HrmAppraisalForDepartment::table)
                        ->where(HrmAppraisalForDepartment::appraisal_cycle_id, '=', $appraisalCycleMasterObj->id)
                        ->whereNotIn(HrmAppraisalForDepartment::id, $savedHrmAppraisalForDepartmentIdArr)
                        ->delete();
        }
        if($request->applicable['type']== "employee"){

            $employeesCol= OrgEmployee::whereIn(OrgEmployee::slug, $request->applicable['employees'])
            ->where(OrgEmployee::org_id, $organisationObj->id)
                    ->select('id', OrgEmployee::slug)->get();
            $employeeIdSlugMap = array();
            $employeesCol->each(function($employeeItem) use (&$employeeIdSlugMap){
                $employeeIdSlugMap[$employeeItem->slug] = $employeeItem->id;
            });

            $savedHrmAppraisalForEmployeeIdArr =array();
            foreach ($request->applicable['employees'] as $employeeSlug) {
                if(empty($employeeIdSlugMap[$employeeSlug])){
                    throw new \Exception("Invalid employee slug found!");
                }                
                $hrmAppraisalForEmployee = HrmAppraisalForEmployee::where(HrmAppraisalForEmployee::appraisal_cycle_id,$appraisalCycleMasterObj->id)
                        ->where(HrmAppraisalForEmployee::employee_id , $employeeIdSlugMap[$employeeSlug])
                        ->first();       
                if(empty($hrmAppraisalForEmployee)){
                    $hrmAppraisalForEmployee = new HrmAppraisalForEmployee;
                    $hrmAppraisalForEmployee->{HrmAppraisalForEmployee::org_id}=$organisationObj->id;
                    $hrmAppraisalForEmployee->{HrmAppraisalForEmployee::appraisal_cycle_id}=$appraisalCycleMasterObj->id;
                    $hrmAppraisalForEmployee->{HrmAppraisalForEmployee::employee_id} = $employeeIdSlugMap[$employeeSlug];
                    $hrmAppraisalForEmployee->save();
                }
                array_push($savedHrmAppraisalForEmployeeIdArr, $hrmAppraisalForEmployee->id);
            }
            if(empty($savedHrmAppraisalForDepartmentIdArr)){
                throw new \Exception("no applicable for employee set!");
            }
            DB::table(HrmAppraisalForEmployee::table)
                        ->where(HrmAppraisalForEmployee::appraisal_cycle_id, '=', $appraisalCycleMasterObj->id)
                        ->whereNotIn(HrmAppraisalForEmployee::id, $savedHrmAppraisalForEmployeeIdArr)
                        ->delete();
        }
        
    }
    
    
    private function updateReviewerDepartmentsAndEmployee($request, $appraisalCycleMasterObj, $organisationObj) {

        if($request->reviewers['includeDepartmentHead']){

            $departmentsCol= OrgDepartment::whereIn(OrgDepartment::slug, $request->reviewers['departments'])
            ->where(OrgDepartment::org_id, $organisationObj->id)
                    ->select('id', OrgDepartment::slug)->get();
            $departmentIdSlugMap = array();
            $departmentsCol->each(function($departmentItem) use (&$departmentIdSlugMap){
                $departmentIdSlugMap[$departmentItem->slug] = $departmentItem->id;
            });

            $savedHrmAppraisalReviewerDepartmentIdArr = array();
            foreach ($request->reviewers['departments'] as $departmentSlug) {
                if(empty($departmentIdSlugMap[$departmentSlug])){
                    throw new \Exception("Invalid department slug found in reviewer departments!");
                }

                $hrmAppraisalCycleReviewerDepartment = HrmAppraisalCycleReviewerDepartment::where(HrmAppraisalCycleReviewerDepartment::appraisal_cycle_id,$appraisalCycleMasterObj->id)
                        ->where(HrmAppraisalCycleReviewerDepartment::department_id,$departmentIdSlugMap[$departmentSlug])
                        ->first();
                if(empty($hrmAppraisalCycleReviewerDepartment)){                
                    $hrmAppraisalCycleReviewerDepartment = new HrmAppraisalCycleReviewerDepartment;
                    $hrmAppraisalCycleReviewerDepartment->{HrmAppraisalCycleReviewerDepartment::org_id}=$organisationObj->id;
                    $hrmAppraisalCycleReviewerDepartment->{HrmAppraisalCycleReviewerDepartment::appraisal_cycle_id}=$appraisalCycleMasterObj->id;
                    $hrmAppraisalCycleReviewerDepartment->{HrmAppraisalCycleReviewerDepartment::department_id} = $departmentIdSlugMap[$departmentSlug];
                    $hrmAppraisalCycleReviewerDepartment->save();
                    array_push($savedHrmAppraisalReviewerDepartmentIdArr, $hrmAppraisalCycleReviewerDepartment->id);
                }
            }
            DB::table(HrmAppraisalCycleReviewerDepartment::table)
            ->where(HrmAppraisalCycleReviewerDepartment::appraisal_cycle_id, '=', $appraisalCycleMasterObj->id)
            ->whereNotIn(HrmAppraisalCycleReviewerDepartment::id, $savedHrmAppraisalReviewerDepartmentIdArr)
            ->delete();
        }

        if($request->reviewers['includeEmployee']){
            $employeesCol= OrgEmployee::whereIn(OrgEmployee::slug, $request->reviewers['employees'])
            ->where(OrgEmployee::org_id, $organisationObj->id)
                    ->select('id', OrgEmployee::slug)->get();
            $employeeIdSlugMap = array();
            $employeesCol->each(function($employeeItem) use (&$employeeIdSlugMap){
                $employeeIdSlugMap[$employeeItem->slug] = $employeeItem->id;
            });

            $savedHrmAppraisalReviewerEmployeeIdArr = array();
            foreach ($request->reviewers['employees'] as $employeeSlug) {
                if(empty($employeeIdSlugMap[$employeeSlug])){
                    throw new \Exception("Invalid employee slug found in reviewers!");
                }
                $hrmAppraisalCycleReviewerEmployee = HrmAppraisalCycleReviewerEmployee::where(HrmAppraisalCycleReviewerEmployee::appraisal_cycle_id,$appraisalCycleMasterObj->id)
                        ->where(HrmAppraisalCycleReviewerEmployee::employee_id,$employeeIdSlugMap[$employeeSlug])
                        ->first();
                if(empty($hrmAppraisalCycleReviewerEmployee)){
                    $hrmAppraisalCycleReviewerEmployee = new HrmAppraisalCycleReviewerEmployee;
                    $hrmAppraisalCycleReviewerEmployee->{HrmAppraisalCycleReviewerEmployee::org_id}=$organisationObj->id;
                    $hrmAppraisalCycleReviewerEmployee->{HrmAppraisalCycleReviewerEmployee::appraisal_cycle_id}=$appraisalCycleMasterObj->id;
                    $hrmAppraisalCycleReviewerEmployee->{HrmAppraisalCycleReviewerEmployee::employee_id} = $employeeIdSlugMap[$employeeSlug];
                    $hrmAppraisalCycleReviewerEmployee->save();
                }
                array_push($savedHrmAppraisalReviewerEmployeeIdArr, $hrmAppraisalCycleReviewerEmployee->id);
            }
            DB::table(HrmAppraisalCycleReviewerEmployee::table)
            ->where(HrmAppraisalCycleReviewerEmployee::appraisal_cycle_id, '=', $appraisalCycleMasterObj->id)
            ->whereNotIn(HrmAppraisalCycleReviewerEmployee::id, $savedHrmAppraisalReviewerEmployeeIdArr)
            ->delete();
        }
    }
    
    private function updateMainModuleWeightages($request, $appraisalCycleMasterObj, $organisationObj) {
        
        $weightagePercentSum=0;
        foreach ($request->mainModules as $item) {
            $weightagePercentSum+= $item['weightagePercent'];
        }
        if($weightagePercentSum!=100){
            throw new \Exception("Sum of module weightage percents should be 100!");
        }
        $hrmACMainModuleWeightageIdArr = array(); 
        foreach ($request->mainModules as $item) {
            $mainModuleObj = DB::table(HrmAppraisalMainModule::table)
                    ->where(HrmAppraisalMainModule::module_name, '=',$item['mainModule'])
                    ->select('id')
                    ->first();
            if(empty($mainModuleObj)){
                throw new \Exception("Invalid main module name ".$item['mainModule']);
            }
            $hrmACMainModuleWeightage = HrmACMainModuleWeightage::where(HrmACMainModuleWeightage::appraisal_cycle_id,$appraisalCycleMasterObj->id)
                        ->where(HrmACMainModuleWeightage::appraisal_main_module_id , $mainModuleObj->id)
                        ->first();
            if(!empty($hrmACMainModuleWeightage)){
                $hrmACMainModuleWeightage->{HrmACMainModuleWeightage::score_percent} = $item['weightagePercent'];
                $hrmACMainModuleWeightage->save();
            } else {
                $hrmACMainModuleWeightage = new HrmACMainModuleWeightage;
                $hrmACMainModuleWeightage->{HrmACMainModuleWeightage::org_id} = $organisationObj->id;
                $hrmACMainModuleWeightage->{HrmACMainModuleWeightage::appraisal_cycle_id} = $appraisalCycleMasterObj->id;
                $hrmACMainModuleWeightage->{HrmACMainModuleWeightage::appraisal_main_module_id} = $mainModuleObj->id;
                $hrmACMainModuleWeightage->{HrmACMainModuleWeightage::score_percent} = $item['weightagePercent'];
                $hrmACMainModuleWeightage->save();
            }
            array_push($hrmACMainModuleWeightageIdArr, $hrmACMainModuleWeightage->id);
        }
        DB::table(HrmACMainModuleWeightage::table)
            ->where(HrmACMainModuleWeightage::appraisal_cycle_id, '=', $appraisalCycleMasterObj->id)
            ->whereNotIn(HrmACMainModuleWeightage::id, $hrmACMainModuleWeightageIdArr)
            ->delete();
    }
    
    private function updateKraModuleWeightages($request, $appraisalCycleMasterObj, $organisationObj) {
        
        $weightagePercentSum=0;
        foreach ($request->performanceIndicator as $item) {
            $weightagePercentSum+= $item['weightagePercent'];
        }
        if($weightagePercentSum!=100){
            throw new \Exception("Sum of performance indicator module percents should be 100!");
        }
        $savedHrmAcKraModuleWeightageArr = array();
        foreach ($request->performanceIndicator as $item) {
            $kraModuleObj = DB::table(HrmKraModule::table)
                    ->where(HrmKraModule::slug, '=',$item['kraModuleSlug'])
                    ->select('id')
                    ->first();
            if(empty($kraModuleObj)){
                throw new \Exception("Invalid performance indicator slug");
            }

            $hrmAcKraModuleWeightage = HrmAcKraModuleWeightage::where(HrmAcKraModuleWeightage::appraisal_cycle_id,$appraisalCycleMasterObj->id)
                        ->where(HrmAcKraModuleWeightage::kra_module_id , $kraModuleObj->id)
                        ->first();
            if(!empty($hrmAcKraModuleWeightage)){
                $hrmAcKraModuleWeightage->{HrmAcKraModuleWeightage::score_percent} = $item['weightagePercent'];
                $hrmAcKraModuleWeightage->save();
            } else {            
                $hrmAcKraModuleWeightage = new HrmAcKraModuleWeightage;
                $hrmAcKraModuleWeightage->{HrmAcKraModuleWeightage::org_id} = $organisationObj->id;
                $hrmAcKraModuleWeightage->{HrmAcKraModuleWeightage::appraisal_cycle_id} = $appraisalCycleMasterObj->id;
                $hrmAcKraModuleWeightage->{HrmAcKraModuleWeightage::kra_module_id} = $kraModuleObj->id;
                $hrmAcKraModuleWeightage->{HrmAcKraModuleWeightage::score_percent} = $item['weightagePercent'];
                $hrmAcKraModuleWeightage->save();
            }
            array_push($savedHrmAcKraModuleWeightageArr, $hrmAcKraModuleWeightage->id);
        }
        DB::table(HrmAcKraModuleWeightage::table)
            ->where(HrmAcKraModuleWeightage::appraisal_cycle_id, '=', $appraisalCycleMasterObj->id)
            ->whereNotIn(HrmAcKraModuleWeightage::id, $savedHrmAcKraModuleWeightageArr)
            ->delete();
    }
    /**
     * get AppraisalCycle
     * @return array
     */
    public function getAppraisalCycle(Request $request)
    {
        
        try {
            $s3BasePath = env('S3_PATH');
            $organisationObj = DB::table(Organization::table)
                    ->where(Organization::slug, '=',$request->orgSlug)
                    ->first();
            if(empty($organisationObj)){
                throw new \Exception("Invalid Organisation");
            }

            $appraisalCycleCOL = DB::table(HrmAppraisalCycleMaster::table)
                ->join(HrmAppraisalCycleApplicable::table, HrmAppraisalCycleApplicable::table . ".id", '=', HrmAppraisalCycleMaster::table . '.' . HrmAppraisalCycleMaster::applicable_id)
                ->join(HrmAppraisalCyclePeriod::table, HrmAppraisalCyclePeriod::table . ".id", '=', HrmAppraisalCycleMaster::table . '.' . HrmAppraisalCycleMaster::appraisal_cycle_period_id)
                ->leftJoin(User::table, User::table . ".id", '=', HrmAppraisalCycleMaster::table . '.' . HrmAppraisalCycleMaster::creator_user_id)
                ->leftJoin(UserProfile::table, User::table . ".id", '=', UserProfile::table . '.' . UserProfile::user_id)
                ->select(
                        HrmAppraisalCycleMaster::table . '.'.HrmAppraisalCycleMaster::slug." AS appraisalCycleSlug",
                        HrmAppraisalCycleMaster::table . '.'.HrmAppraisalCycleMaster::name." AS title",
                        HrmAppraisalCycleMaster::table . '.'.HrmAppraisalCycleMaster::description." AS description",
                        HrmAppraisalCyclePeriod::period_type ." AS periodType",
                        
                        //fix for y2k38
                        //https://zavaboy.org/2013/01/29/alternative-for-mysql-unix_timestamp/
                        DB::raw("TO_SECONDS(".HrmAppraisalCycleMaster::table . '.'.HrmAppraisalCycleMaster::cycle_start_date.")-62167219200+TO_SECONDS(UTC_TIMESTAMP())-TO_SECONDS(NOW())  AS startDate"),
                        DB::raw("TO_SECONDS(".HrmAppraisalCycleMaster::table . '.'.HrmAppraisalCycleMaster::cycle_end_date.")-62167219200+TO_SECONDS(UTC_TIMESTAMP())-TO_SECONDS(NOW())  AS endDate"),

                        DB::raw("TO_SECONDS(".HrmAppraisalCycleMaster::table . '.'.HrmAppraisalCycleMaster::processing_start_date.")-62167219200+TO_SECONDS(UTC_TIMESTAMP())-TO_SECONDS(NOW())  AS processingStartDate"),
                        DB::raw("TO_SECONDS(".HrmAppraisalCycleMaster::table . '.'.HrmAppraisalCycleMaster::processing_end_date.")-62167219200+TO_SECONDS(UTC_TIMESTAMP())-TO_SECONDS(NOW())  AS processingEndDate"),

                        HrmAppraisalCycleApplicable::applicable_type .' AS applicableType',
                        
                        HrmAppraisalCycleMaster::table . '.'.HrmAppraisalCycleMaster::review_by_department_head." AS includeDepartmentHead",
                        HrmAppraisalCycleMaster::table . '.'.HrmAppraisalCycleMaster::review_by_employee." AS includeEmployee",
                        
                        User::table . '.'.User::slug." AS creatorUserSlug",
                        User::table . '.'.User::name." AS creatorUserName",
                        DB::raw('concat("'. $this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as creatorImageUrl')
                         )
                ->where(HrmAppraisalCycleMaster::org_id, $organisationObj->id)
                ->where(HrmAppraisalCycleMaster::slug, $request->appraisalCycleSlug)
                ->get();
            
            return $this->content = array(
                'data'   => array(
                    "appraisalCycles"=>$appraisalCycleCOL
                    ),
                'code'   => 200,
                'status' => "OK"
            );
        
        } catch (\Exception $e) {
            $content = array();
            $content['error']   =  array('msg' => $e->getMessage());
            $content['code']    =  422;
            $content['status']  = ResponseStatus::ERROR;
            return $content;
        }
    }
    
    /**
     * get AppraisalCycles
     * @return array
     */
    public function getAppraisalCycles(Request $request)
    {
        
        try {
            $s3BasePath = env('S3_PATH');
            $organisationObj = DB::table(Organization::table)
                    ->where(Organization::slug, '=',$request->orgSlug)
                    ->first();
            if(empty($organisationObj)){
                throw new \Exception("Invalid Organisation");
            }

            $appraisalCyclesCOL = DB::table(HrmAppraisalCycleMaster::table)
                ->join(HrmAppraisalCycleApplicable::table, HrmAppraisalCycleApplicable::table . ".id", '=', HrmAppraisalCycleMaster::table . '.' . HrmAppraisalCycleMaster::applicable_id)
                ->join(HrmAppraisalCyclePeriod::table, HrmAppraisalCyclePeriod::table . ".id", '=', HrmAppraisalCycleMaster::table . '.' . HrmAppraisalCycleMaster::appraisal_cycle_period_id)
                ->leftJoin(User::table, User::table . ".id", '=', HrmAppraisalCycleMaster::table . '.' . HrmAppraisalCycleMaster::creator_user_id)
                ->leftJoin(UserProfile::table, User::table . ".id", '=', UserProfile::table . '.' . UserProfile::user_id)
                ->select(
                        HrmAppraisalCycleMaster::table . '.'.HrmAppraisalCycleMaster::slug." AS appraisalCycleSlug",
                        HrmAppraisalCycleMaster::table . '.'.HrmAppraisalCycleMaster::name." AS title",
                        HrmAppraisalCycleMaster::table . '.'.HrmAppraisalCycleMaster::description." AS description",
                        HrmAppraisalCyclePeriod::period_type ." AS periodType",
                        
                        //fix for y2k38
                        //https://zavaboy.org/2013/01/29/alternative-for-mysql-unix_timestamp/
                        DB::raw("TO_SECONDS(".HrmAppraisalCycleMaster::table . '.'.HrmAppraisalCycleMaster::cycle_start_date.")-62167219200+TO_SECONDS(UTC_TIMESTAMP())-TO_SECONDS(NOW())  AS startDate"),
                        DB::raw("TO_SECONDS(".HrmAppraisalCycleMaster::table . '.'.HrmAppraisalCycleMaster::cycle_end_date.")-62167219200+TO_SECONDS(UTC_TIMESTAMP())-TO_SECONDS(NOW())  AS endDate"),

                        DB::raw("TO_SECONDS(".HrmAppraisalCycleMaster::table . '.'.HrmAppraisalCycleMaster::processing_start_date.")-62167219200+TO_SECONDS(UTC_TIMESTAMP())-TO_SECONDS(NOW())  AS processingStartDate"),
                        DB::raw("TO_SECONDS(".HrmAppraisalCycleMaster::table . '.'.HrmAppraisalCycleMaster::processing_end_date.")-62167219200+TO_SECONDS(UTC_TIMESTAMP())-TO_SECONDS(NOW())  AS processingEndDate"),

                        HrmAppraisalCycleApplicable::applicable_type .' AS applicableType',
                        
                        HrmAppraisalCycleMaster::table . '.'.HrmAppraisalCycleMaster::review_by_department_head." AS includeDepartmentHead",
                        HrmAppraisalCycleMaster::table . '.'.HrmAppraisalCycleMaster::review_by_employee." AS includeEmployee",
                        
                        User::table . '.'.User::slug." AS creatorUserSlug",
                        User::table . '.'.User::name." AS creatorUserName",
                        DB::raw('concat("'. $this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as creatorImageUrl')
                         )
                ->where(HrmAppraisalCycleMaster::org_id, $organisationObj->id)
                ->get();
            
            return $this->content = array(
                'data'   => array(
                    "appraisalCycles"=>$appraisalCyclesCOL
                    ),
                'code'   => 200,
                'status' => "OK"
            );
        
        } catch (\Exception $e) {
            $content = array();
            $content['error']   =  array('msg' => $e->getMessage());
            $content['code']    =  422;
            $content['status']  = ResponseStatus::ERROR;
            return $content;
        }
    }
}