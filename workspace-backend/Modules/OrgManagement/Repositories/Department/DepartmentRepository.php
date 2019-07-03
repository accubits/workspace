<?php

namespace Modules\OrgManagement\Repositories\Department;

use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\OrgManagement\Entities\OrgDepartment;
use Modules\OrgManagement\Entities\Organization;
use Modules\PartnerManagement\Entities\Partner;
use Modules\OrgManagement\Repositories\DepartmentRepositoryInterface;
use Modules\Common\Utilities\Utilities;
use Illuminate\Support\Facades\DB;
use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\OrgEmployeeDepartment;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\UserManagement\Entities\UserProfile;

class DepartmentRepository implements DepartmentRepositoryInterface
{


    public $s3BasePath;
    public function __construct()
    {
        $this->s3BasePath= env('S3_PATH');
    }

    public function listDepartment(Request $request)
    {
 
        try {
            $organisation = Organization::where(Organization::slug, $request->orgSlug)->first();
            if(empty($organisation)){
                return $this->throwError('Invalid Organisation', 422);
            }

            $utilParams=Utilities::getParams();
            $orgDepartmentCount = OrgDepartment::where(OrgDepartment::org_id, $organisation->id)->count();

            $orgDepartmentData = OrgDepartment::where(OrgDepartment::table .'.'.OrgDepartment::org_id, $organisation->id)
                ->leftJoin(Organization::table, Organization::table . ".id", '=',  OrgDepartment::table.".".OrgDepartment::org_id)
                ->leftJoin(OrgDepartment::table.' AS DepRoot', OrgDepartment::table . ".".OrgDepartment::root_department_id, '=',  'DepRoot.id')
                ->leftJoin(OrgDepartment::table.' AS DepParent', OrgDepartment::table . ".".OrgDepartment::parent_department_id, '=',  'DepParent.id')
                ->select(
                        OrgDepartment::table.'.'.OrgDepartment::slug.' AS departmentSlug',
                        OrgDepartment::table.'.'.OrgDepartment::name.' AS departmentName',
                        Organization::table.'.'.Organization::slug.' AS orgSlug',
                        'DepParent.'.OrgDepartment::slug.' AS parentDepartmentSlug',
                        'DepRoot.'.OrgDepartment::slug.' AS rootDepartmentSlug')
                ->skip($utilParams['offset']) //$request['offset']
                ->take($utilParams['perPage']) //$request['perPage']
                ->get();

            $paginatedData = Utilities::paginate($orgDepartmentData, $utilParams['perPage'], $utilParams['page'], array(), $orgDepartmentCount)->toArray();

            $formatedData = $this->reformatData($paginatedData);

            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['data'] = $formatedData;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;

        } catch (QueryException $e) {
            return $this->throwError('Something went wrong, Failed to fetch Departments', 422);
        } catch (\Exception $e) {
            return $this->throwError('Something went wrong, Failed to fetch Departments', 422);
        }
    }

    public function listDepartmentTree(Request $request)
    {
 
        try {
            $organisation = Organization::where(Organization::slug, $request->orgSlug)->first();
            if(empty($organisation)){
                return $this->throwError('Invalid Organisation', 422);
            }

            $queryBuilderObj=null;
            //DB::enableQueryLog();
            
            if(!empty(($request->departmentSlug))){ //start with given department as root
                $departmentObj = OrgDepartment::where(OrgDepartment::slug, $request->departmentSlug)
                        ->where(OrgDepartment::org_id, $organisation->id)->first();
                if(empty($departmentObj)){
                    return $this->throwError('Invalid Department', 422);
                }
                
                $queryBuilderObj = OrgDepartment::where(OrgDepartment::table .'.id', $departmentObj->id);
            } else { // if no or null deparmentSlug start with root department(parent_department_id is null)
                $rootDepartmentObj = OrgDepartment::whereNull(OrgDepartment::parent_department_id)
                        ->where(OrgDepartment::org_id, $organisation->id)->first();
                
                if(empty($rootDepartmentObj)){
                    $queryBuilderObj = OrgDepartment::whereNull(OrgDepartment::table.'.'.OrgDepartment::parent_department_id)
                        ->where(OrgDepartment::table.'.'.OrgDepartment::org_id, $organisation->id);
                } else {
                    $queryBuilderObj = OrgDepartment::where(OrgDepartment::table.'.id',$rootDepartmentObj->id);
                }
            }
            
            $orgParentAndChildDepartmentData = $queryBuilderObj
                ->leftJoin(OrgDepartment::table.' AS DepRoot', OrgDepartment::table . ".".OrgDepartment::root_department_id, '=',  'DepRoot.id')
                ->leftJoin(Organization::table, Organization::table . ".id", '=',  OrgDepartment::table.".".OrgDepartment::org_id)
                ->leftJoin(OrgEmployeeDepartment::table, function ($join){
                    $join->on(OrgDepartment::table . ".id", '=',   OrgEmployeeDepartment::table . "." .OrgEmployeeDepartment::org_department_id)
                            ->where(OrgEmployeeDepartment::is_head,TRUE);
                })
                ->leftJoin(OrgEmployee::table, OrgEmployeeDepartment::table . ".".OrgEmployeeDepartment::org_employee_id, '=',  OrgEmployee::table.'.id')
                ->leftJoin(User::table, OrgEmployee::table . "." . OrgEmployee::user_id, '=',  User::table.'.id')
                ->leftJoin(UserProfile::table, UserProfile::table . "." . UserProfile::user_id, '=',  User::table.'.id')

                // child department and child department head  
                ->leftJoin(OrgDepartment::table.' AS DepChild', "DepChild.".OrgDepartment::parent_department_id, '=',  OrgDepartment::table . '.id')
                ->leftJoin(OrgEmployeeDepartment::table. ' AS DepChildOrgEmployeeDepartment', function ($join){
                    $join->on( "DepChild.id", '=',   "DepChildOrgEmployeeDepartment." .OrgEmployeeDepartment::org_department_id)
                        ->where("DepChildOrgEmployeeDepartment.".OrgEmployeeDepartment::is_head,TRUE);
                })
                ->leftJoin(OrgEmployee::table.' AS DepChildOrgEmployee', "DepChildOrgEmployeeDepartment.".OrgEmployeeDepartment::org_employee_id, '=',  'DepChildOrgEmployee.id')
                ->leftJoin(User::table.' AS DepChildUser', "DepChildOrgEmployee." . OrgEmployee::user_id, '=',  'DepChildUser.id')
                ->leftJoin(UserProfile::table.' AS DepChildUserProfile', "DepChildUserProfile." . UserProfile::user_id, '=',  'DepChildUser.id')  

                ->select(                      

                        OrgDepartment::table.'.'.OrgDepartment::slug.' AS departmentSlug',
                        OrgDepartment::table.'.'.OrgDepartment::name.' AS departmentName',
                        Organization::table.'.'.Organization::slug.' AS orgSlug',
                        UserProfile::table.'.'.UserProfile::first_name.' AS departmentHeadName',
                        DB::raw('concat("'. $this->s3BasePath.'", '.UserProfile::table.'.'. UserProfile::image_path.') as departmentHeadImageUrl'),
                        User::table.'.'.User::slug.' AS departmentHeadUserSlug',
                        User::table.'.'.User::email.' AS departmentHeadEmail',
                        'DepRoot.'.OrgDepartment::slug.' AS rootDepartmentSlug',

                        //child departments
                        'DepChild.'.OrgDepartment::slug.' AS childDepartmentSlug',
                        'DepChild.'.OrgDepartment::name.' AS childDepartmentName',
                        'DepChildUserProfile.'.UserProfile::first_name.' AS childDepartmentHeadName',
                        DB::raw('concat("'. $this->s3BasePath.'", '.'DepChildUserProfile.'. UserProfile::image_path.') as childDepartmentHeadImageUrl'),
                        'DepChildUser.'.User::slug.' AS childDepartmentHeadUserSlug',
                        'DepChildUser.'.User::email.' AS childDepartmentHeadEmail',
                        
                         //child department's child count  subquery     
                        DB::raw('( select count('. OrgDepartment::table. '.' .OrgDepartment::slug .
                                               ') from '.OrgDepartment::table.
                                ' where '.OrgDepartment::table.'.'.OrgDepartment::org_id.' = '.
                                ' DepChild.'.OrgDepartment::org_id.' AND '.OrgDepartment::table.'.'.OrgDepartment::parent_department_id.' = DepChild.id )'.
                                ' AS childCount'),

                        // department root member's count subquery     
                        DB::raw('( select count(*) from '.OrgEmployeeDepartment::table.
                                ' where '.OrgEmployeeDepartment::table.'.'.OrgEmployeeDepartment::org_department_id.' = '. OrgDepartment::table.'.id )'.
                                ' AS memberCount'),
                        
                        // department root member's count subquery     
                        DB::raw('( select count(*) from '.OrgEmployeeDepartment::table.
                                ' where '.OrgEmployeeDepartment::table.'.'.OrgEmployeeDepartment::org_department_id.' = '.'DepChild.id )'.
                                ' AS childMemberCount')
                        )
                ->get();

            $departmentArr = array();
            
            $orgParentAndChildDepartmentData->each(function($item) use( &$departmentArr ){

                if(empty($departmentArr)){
                    $departmentArr = array(
                        'departmentSlug' => $item->departmentSlug,
                        'departmentName' => $item->departmentName,
                        'departmentHeadName' => $item->departmentHeadName,
                        'departmentHeadImageUrl' => $item->departmentHeadImageUrl,
                        'departmentHeadEmail' => $item->departmentHeadEmail,
                        'departmentHeadUserSlug' => $item->departmentHeadUserSlug,
                        'memberCount'=>  $item->memberCount,
                        'child' => array()
                        );
                }
                if(!empty($item->childDepartmentSlug)){
                    array_push($departmentArr['child'], array(
                        'departmentSlug' => $item->childDepartmentSlug,
                        'departmentName' => $item->childDepartmentName,
                        'departmentHeadName' => $item->childDepartmentHeadName,
                        'departmentHeadImageUrl' => $item->childDepartmentHeadImageUrl,
                        'departmentHeadEmail' => $item->childDepartmentHeadEmail,
                        'departmentHeadUserSlug' => $item->childDepartmentHeadUserSlug,
                        'memberCount'=>  $item->childMemberCount,
                        'childCount' => $item->childCount
                    ));
                }
            });
            //dd(DB::getQueryLog());

            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['data'] = array("departments" => $departmentArr);
            //$responseData['data'] = $orgParentAndChildDepartmentData;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;

        } catch (QueryException $e) {
            return $this->throwError($e->getMessage(), 422);
        } catch (\Exception $e) {
            return $this->throwError($e->getMessage(), 422);
        }
    }

    public function reformatData($dataArr) {
        $dataArr['departments'] = $dataArr['data'];
        unset($dataArr['data']);
        $dataArr = Utilities::unsetResponseData($dataArr);
        return $dataArr;
    }

    public function getDepartment(Request $request)
    {
        try {

            $departmentData = OrgDepartment::where(OrgDepartment::table.'.'.OrgDepartment::slug, $request->departmentSlug)
                ->leftJoin(Organization::table, Organization::table . ".id", '=',  OrgDepartment::table.".".OrgDepartment::org_id)
                ->leftJoin(OrgDepartment::table.' AS DepRoot', OrgDepartment::table . ".".OrgDepartment::root_department_id, '=',  'DepRoot.id')
                ->leftJoin(OrgDepartment::table.' AS DepParent', OrgDepartment::table . ".".OrgDepartment::parent_department_id, '=',  'DepParent.id')
                ->select(
                        OrgDepartment::table.'.'.OrgDepartment::slug.' AS departmentSlug',
                        OrgDepartment::table.'.'.OrgDepartment::name.' AS departmentName',
                        Organization::table.'.'.Organization::slug.' AS orgSlug',
                        'DepParent.'.OrgDepartment::slug.' AS parentDepartmentSlug',
                        'DepRoot.'.OrgDepartment::slug.' AS rootDepartmentSlug')
                ->first();

            if(empty($departmentData)){
                return $this->throwError('Invalid Department', 422);
            }

            $formatedData = $departmentData;
            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['data'] = $formatedData;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;

        } catch (QueryException $e) {
            return $this->throwError('Something went wrong, Failed to fetch Department', 422);
        } catch (\Exception $e) {
            return $this->throwError('Something went wrong, Failed to fetch Department', 422);
        }
    }

    /**
     * Add Department
     * @param Request $request
     * @return array
     */
    public function addDepartment(Request $request)
    {

        DB::beginTransaction();
        try {

            $orgObj = Organization::where(Organization::slug, $request->orgSlug)->first();
            if(empty($orgObj)){
                throw new \Exception('Invalid Organisation');
            }

            $parentDepartmentId = null;
            $pathEnum = null;
            if(!empty($request->parentDepartmentSlug)){
                $parentDepartmentObj = OrgDepartment::where(OrgDepartment::slug, $request->parentDepartmentSlug)->first();
                if(empty($parentDepartmentObj)){
                    throw new \Exception('Invalid Parent Department');
                }

                $pathEnum = $this->getEnumPath($parentDepartmentObj);
                $parentDepartmentId = $parentDepartmentObj->id;
            }

            $rootDepartmentId = null;
            if(!empty($request->rootDepartmentSlug)){
                $rootDepartmentObj = OrgDepartment::where(OrgDepartment::slug, $request->rootDepartmentSlug)
                    ->where(OrgDepartment::parent_department_id, null)->first();
                if(empty($rootDepartmentObj)){
                    throw new \Exception('Invalid Root Department');
                }
                $rootDepartmentId = $rootDepartmentObj->id;
            }
            
            $rootDepObj = OrgDepartment::where(OrgDepartment::parent_department_id, NULL)
                    ->where(OrgDepartment::root_department_id, NULL)
                    ->where(OrgDepartment::org_id, $orgObj->id)->first(); //get root department
            if(!empty($rootDepObj)){
                if(empty($request->rootDepartmentSlug)){
                     return $this->throwError('Invalid Root Department', 422);
                }
                if(empty($request->parentDepartmentSlug)){
                     return $this->throwError('Invalid parent Department', 422);
                }
            }

            if(empty($rootDepartmentId) && !empty($parentDepartmentId)){
                throw new \Exception('Invalid Input, Parent Department cannot be set without Root Department');
            }
            
            if(empty($request->employeeSlug)){
                throw new \Exception('Department head employee slug missing');
            }
            
            $employeeObj = OrgEmployee::where(OrgEmployee::slug, $request->employeeSlug)->first();
            if(empty($employeeObj)){
                throw new \Exception('Invalid Employee');
            }


            if(empty($request->name)){
                throw new \Exception('Invalid department name');
            }
            $departmentNameObj = OrgDepartment::where(OrgDepartment::name, $request->name)
                ->where(OrgDepartment::org_id, $orgObj->id)->first();
            if(!empty($departmentNameObj)){
                throw new \Exception('Department name '.$request->name.' already exist!');
            }

            $departmentObj = new OrgDepartment;
            $departmentObj->{OrgDepartment::name} = $request->name;
            $departmentObj->{OrgDepartment::org_id} = $orgObj->id;
            $departmentObj->{OrgDepartment::slug} = Utilities::getUniqueId();
            $departmentObj->{OrgDepartment::parent_department_id} = $parentDepartmentId;
            $departmentObj->{OrgDepartment::root_department_id} = $rootDepartmentId;
            $departmentObj->{OrgDepartment::path_enum} = $pathEnum;
            $departmentObj->save();

            $isRootDepartment = empty($departmentObj->{OrgDepartment::root_department_id})?true:false;

            
            $OrgEmployeeDepartment = new OrgEmployeeDepartment;
            $OrgEmployeeDepartment->{OrgEmployeeDepartment::org_employee_id} = $employeeObj->id;
            $OrgEmployeeDepartment->{OrgEmployeeDepartment::org_department_id} = $departmentObj->id;
            $OrgEmployeeDepartment->{OrgEmployeeDepartment::is_head} = TRUE;
            $OrgEmployeeDepartment->save();
            
            
            DB::commit();
            $resp=array();
            $resp['data']   =  array(
                "msg"=>'Department created successfully',
                "departmentSlug"=>$departmentObj->{OrgDepartment::slug},
                "isRootDepartment"=> $isRootDepartment
                );
            $resp['code']   =  200;
            $resp['status']   =  "OK";
            return $resp;

        } catch (QueryException $e) {
            return $this->throwError('Something went wrong, Failed to add Department', 422);
        } catch (\Exception $e) {
            return $this->throwError($e->getMessage(), 422);
        }

    }

    public function getEnumPath($parentDepartmentObj) {
        $parentPath = !empty($parentDepartmentObj->{OrgDepartment::path_enum})? $parentDepartmentObj->{OrgDepartment::path_enum}."." : "";
        return  $parentPath.$parentDepartmentObj->id;
    }

    public function throwError($data, $code) : array
    {
        DB::rollBack();
        $resp=array();
        $resp['error'] = $data;
        $resp['code']  = $code;
        $resp['status']   =  "ERROR";
        return $resp;
    }


    /**
     * Update Department details
     * @param Request $request
     * @return array
     */
    public function updateDepartment(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $departmentObj = OrgDepartment::where(OrgDepartment::slug, $request->departmentSlug)->first();
            if(empty($departmentObj)){
                return $this->throwError('Invalid Department', 422);
            }

            $orgObj = Organization::where(Organization::slug, $request->orgSlug)->first();
            if(empty($orgObj)){
                return $this->throwError('Invalid Organisation', 422);
            }
            $parentDepartmentId = null;
            $pathEnum = null;
            if(!empty($request->parentDepartmentSlug)){
                $parentDepartmentObj = OrgDepartment::where(OrgDepartment::slug, $request->parentDepartmentSlug)->first();
                if(empty($parentDepartmentObj)){
                    return $this->throwError('Invalid Parent Department', 422);
                }

                if($departmentObj->id == $parentDepartmentObj->id){// prevent self referencing
                    return $this->throwError('Self referencing found, Invalid Parent Department', 422);
                }

                //construct path enumeration
                $pathEnum = $this->getEnumPath($parentDepartmentObj);
                $parentDepartmentId = $parentDepartmentObj->id;

                //update all child node
                $this->updateAllChild($departmentObj, $pathEnum);
            }
            
            $rootDepObj = OrgDepartment::where(OrgDepartment::parent_department_id, NULL)
                    ->where(OrgDepartment::root_department_id, NULL)
                    ->where(OrgDepartment::org_id, $orgObj->id)->first(); //get root department
            if($rootDepObj->{OrgDepartment::slug}!=$request->departmentSlug){
                if(empty($request->rootDepartmentSlug)){
                     return $this->throwError('Invalid Root Department', 422);
                }
                if(empty($request->parentDepartmentSlug)){
                     return $this->throwError('Invalid parent Department', 422);
                }
            }

            $rootDepartmentId = null;
            if(!empty($request->rootDepartmentSlug)){
                $rootDepartmentObj = OrgDepartment::where(OrgDepartment::slug, $request->rootDepartmentSlug)
                    ->where(OrgDepartment::parent_department_id, null)->first();

                if(empty($rootDepartmentObj)){
                    return $this->throwError('Invalid Root Department', 422);
                }
                if($departmentObj->id == $rootDepartmentObj->id){// prevent self referencing
                    return $this->throwError('Self referencing found, Invalid Root Department', 422);
                }

                $rootDepartmentId = $rootDepartmentObj->id;
            }

            if(empty($rootDepartmentId) && !empty($parentDepartmentId)){
                return $this->throwError('Invalid Input, Parent Department cannot be set without Root Department', 422);
            }
            
            
            if(empty($request->employeeSlug)){
                throw new \Exception('Department head employee slug missing');
            }
            
            $employeeObj = OrgEmployee::where(OrgEmployee::slug, $request->employeeSlug)->first();
            if(empty($employeeObj)){
                throw new \Exception('Invalid Employee');
            }

            if(empty($request->name)){
                throw new \Exception('Invalid department name');
            }
 
            $departmentNameObj = OrgDepartment::where(OrgDepartment::name, $request->name)
                ->where(OrgDepartment::org_id, $orgObj->id)
                ->where('id', '!=', $departmentObj->id)->first();
            if(!empty($departmentNameObj)){
                throw new \Exception('Department name '.$request->name.' already exist!');
            }


            $departmentObj->{OrgDepartment::name} = $request->name;
            $departmentObj->{OrgDepartment::org_id} = $orgObj->id;
            $departmentObj->{OrgDepartment::parent_department_id} = $parentDepartmentId;
            $departmentObj->{OrgDepartment::root_department_id} = $rootDepartmentId;
            $departmentObj->{OrgDepartment::path_enum} = $pathEnum;
            $departmentObj->save();

            $isRootDepartment = empty($departmentObj->{OrgDepartment::root_department_id})?true:false;
            
            
            $OrgEmployeeDepartment = OrgEmployeeDepartment::where(OrgEmployeeDepartment::org_employee_id, $employeeObj->id)
                    ->where(OrgEmployeeDepartment::org_department_id, $departmentObj->id)->first();
            
            $OrgEmployeeDepartmentHead = OrgEmployeeDepartment::where(OrgEmployeeDepartment::is_head,TRUE)
                    ->where(OrgEmployeeDepartment::org_department_id, $departmentObj->id)->first();
            if(!empty($OrgEmployeeDepartmentHead)){ // has a department head
                if(!empty($OrgEmployeeDepartment)){

                    //remove head status of all if any
                    OrgEmployeeDepartment::where(OrgEmployeeDepartment::org_department_id, $departmentObj->id)
                            ->where(OrgEmployeeDepartment::is_head,TRUE)
                            ->update([OrgEmployeeDepartment::is_head => false]);
                    $OrgEmployeeDepartment->{OrgEmployeeDepartment::is_head} = TRUE;
                    $OrgEmployeeDepartment->save();
                } else {
                    //remove head status of all if any
                    OrgEmployeeDepartment::where(OrgEmployeeDepartment::org_department_id, $departmentObj->id)
                                ->where(OrgEmployeeDepartment::is_head,TRUE)
                                ->update([OrgEmployeeDepartment::is_head => false]);
                    $OrgEmployeeDepartment = new OrgEmployeeDepartment;
                    $OrgEmployeeDepartment->{OrgEmployeeDepartment::org_employee_id} = $employeeObj->id;
                    $OrgEmployeeDepartment->{OrgEmployeeDepartment::org_department_id} = $departmentObj->id;
                    $OrgEmployeeDepartment->{OrgEmployeeDepartment::is_head} = TRUE;
                    $OrgEmployeeDepartment->save();
                }
            } else { // no department head
                
                if(empty($OrgEmployeeDepartment)){
                    $OrgEmployeeDepartment = new OrgEmployeeDepartment;
                    $OrgEmployeeDepartment->{OrgEmployeeDepartment::org_employee_id} = $employeeObj->id;
                    $OrgEmployeeDepartment->{OrgEmployeeDepartment::org_department_id} = $departmentObj->id;
                    $OrgEmployeeDepartment->{OrgEmployeeDepartment::is_head} = TRUE;
                    $OrgEmployeeDepartment->save();
                } else {
                    $OrgEmployeeDepartment->{OrgEmployeeDepartment::is_head} = TRUE;
                    $OrgEmployeeDepartment->save();
                }
            }

            DB::commit();
            $resp = array();
            $resp['data']   =  array(
			"msg"=>'Department updated successfully',
			"departmentSlug"=>$departmentObj->{OrgDepartment::slug},
			"isRootDepartment"=> $isRootDepartment);
            $resp['code']   =  200;
            $resp['status']   =  "OK";
            return $resp;

        } catch (QueryException $e) {
            return $this->throwError($e->getMessage(), 422);
            //return $this->throwError('Something went wrong, Failed to update Department', 422);
        } catch (\Exception $e) {
            return $this->throwError($e->getMessage(), 422);
        }

    }

    
    public function updateAllChild($departmentObj, $pathEnum) {
        $previousChildNodePath = $departmentObj->{OrgDepartment::path_enum}.'.'.$departmentObj->id;
        $allChildDepartmentArr = OrgDepartment::where(OrgDepartment::path_enum, 'like', $previousChildNodePath.'%')->get();

        foreach ($allChildDepartmentArr as $child) {            
            $tempDepartmentObj = OrgDepartment::where("id", $child->id)
                                ->first();
            $newBasePath = $pathEnum.'.'.$departmentObj->id;

            $updatedPath = str_replace($previousChildNodePath, $newBasePath, $tempDepartmentObj->{OrgDepartment::path_enum});
            $tempDepartmentObj->{OrgDepartment::path_enum} = $updatedPath;
            $tempDepartmentObj->save();
        }        
    }
    
    /**
     * delete an Department
     * @param Department
     * @return array
     */
    public function deleteDepartment($request)
    {
        $departmentObj = OrgDepartment::where(OrgDepartment::slug, $request->departmentSlug)->first();

        if (!$departmentObj){
            return $this->throwError('No Department Found', 422);
        }
        $departmentObj->delete();

        $data =array();
        $data['data']   =  'Department deleted successfully';
        $data['code']   =  200;
        $data['status'] =  "OK";

        return $data;
    }

    /**
     * add/update/delete EmployeeToDepartment
     * @param Request $request
     * @return array
     */
    public function setEmployeeToDepartment(Request $request)
    {

        $validActions = array('create', 'update', 'delete');

        if(!in_array($request->action, $validActions)){
            return $this->throwError("action is invalid", 422);
        }
        DB::beginTransaction();
        try {

            $orgObj = Organization::where(Organization::slug, $request->orgSlug)->first();
            if(empty($orgObj)){
                return $this->throwError('Invalid Organisation', 422);
            }

            $departmentObj = OrgDepartment::where(OrgDepartment::slug, $request->departmentSlug)
                    ->where(OrgDepartment::org_id, $orgObj->id)->first();
            if(empty($departmentObj)){
                return $this->throwError('Invalid Department', 422);
            }

            $employeeObj = OrgEmployee::where(OrgEmployee::slug, $request->employeeSlug)
                    ->where(OrgEmployee::org_id, $orgObj->id)->first();
            if(empty($employeeObj)){
                return $this->throwError('Invalid Employee', 422);
            }

            $msg = "";
            if($request->action == "delete"){
                $EmpDepartmentObj = OrgEmployeeDepartment::where(
                        [OrgEmployeeDepartment::org_department_id => $departmentObj->id,
                           OrgEmployeeDepartment::org_employee_id => $employeeObj->id]
                        )->first();
                $EmpDepartmentObj->delete();
                $msg = "Employee removed from department";

            } else if($request->action == "create"){ //create

                if(!isset( $request->isHead )){
                    return $this->throwError('isHead key missing', 422);
                }
                $empDepartmentObj = OrgEmployeeDepartment::where(
                        [OrgEmployeeDepartment::org_department_id => $departmentObj->id,
                           OrgEmployeeDepartment::org_employee_id => $employeeObj->id]
                        )->first();

                if(!empty($empDepartmentObj)){
                    return $this->throwError('Employee already added to this department', 422);
                }
                $EmpDepartmentObj = new OrgEmployeeDepartment;
                $EmpDepartmentObj->{OrgEmployeeDepartment::org_department_id} = $departmentObj->id;
                $EmpDepartmentObj->{OrgEmployeeDepartment::org_employee_id} = $employeeObj->id;
                $EmpDepartmentObj->{OrgEmployeeDepartment::is_head} = $request->isHead;
                $EmpDepartmentObj->save();
                
                if($request->isHead){
                    $msg = 'Employee added to department as department head';
                } else {
                    $msg = 'Employee added to department successfully';
                }
            } else if($request->action == "update"){ //update
                if(!isset( $request->isHead )){
                    return $this->throwError('isHead key missing', 422);
                }
                $EmpDepartmentObj = OrgEmployeeDepartment::where(
                        [OrgEmployeeDepartment::org_department_id => $departmentObj->id,
                           OrgEmployeeDepartment::org_employee_id => $employeeObj->id]
                        )->first();
                if(empty($EmpDepartmentObj)){
                    return $this->throwError("Invalid update, Employee doesn't exist in this department", 422);
                }
                $EmpDepartmentObj->{OrgEmployeeDepartment::org_department_id} = $departmentObj->id;
                $EmpDepartmentObj->{OrgEmployeeDepartment::org_employee_id} = $employeeObj->id;
                $EmpDepartmentObj->{OrgEmployeeDepartment::is_head} = $request->isHead;
                $EmpDepartmentObj->save();
                if($request->isHead){
                    $msg = 'updated, employee set as department head';
                } else {
                    $msg = 'updated employee to department';
                }
            }

            DB::commit();
            $resp=array();
            $resp['data']   =  array(
                "msg"=>$msg
                );
            $resp['code']   =  200;
            $resp['status']   =  "OK";
            return $resp;

        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(),422);
            //'Something went wrong, Failed to add employee to department', 422);
        }

    }

    public function listDepartmentEmployees(Request $request)
    {
 
        try {
            $organisation = Organization::where(Organization::slug, $request->orgSlug)->first();
            if(empty($organisation)){
                return $this->throwError('Invalid Organisation', 422);
            }
            $departmentObj = OrgDepartment::where(OrgDepartment::slug, $request->departmentSlug)
                    ->where(OrgDepartment::org_id, $organisation->id)->first();
            if(empty($departmentObj)){
                return $this->throwError('Invalid Department', 422);
            }

            //DB::enableQueryLog();
            
            //fetch parent, child departments and respective department heads
            $orgParentAndChildDepartmentData = OrgDepartment::where(OrgDepartment::table .'.id', $departmentObj->id)
                ->leftJoin(OrgDepartment::table.' AS DepRoot', OrgDepartment::table . ".".OrgDepartment::root_department_id, '=',  'DepRoot.id')
                ->leftJoin(Organization::table, Organization::table . ".id", '=',  OrgDepartment::table.".".OrgDepartment::org_id)
                ->leftJoin(OrgEmployeeDepartment::table, function ($join){
                    $join->on(OrgDepartment::table . ".id", '=',   OrgEmployeeDepartment::table . "." .OrgEmployeeDepartment::org_department_id)
                            ->where(OrgEmployeeDepartment::is_head,TRUE);
                })
                ->leftJoin(OrgEmployee::table, OrgEmployeeDepartment::table . ".".OrgEmployeeDepartment::org_employee_id, '=',  OrgEmployee::table.'.id')
                ->leftJoin(User::table, OrgEmployee::table . "." . OrgEmployee::user_id, '=',  User::table.'.id')
                ->leftJoin(UserProfile::table, UserProfile::table . "." . UserProfile::user_id, '=',  User::table.'.id')

                   // parent department and parent department head  
                ->leftJoin(OrgDepartment::table.' AS DepParent', OrgDepartment::table . ".".OrgDepartment::parent_department_id, '=',  'DepParent.id')
                ->leftJoin(OrgEmployeeDepartment::table. ' AS DepParentOrgEmployeeDepartment', function ($join){
                    $join->on( "DepParent.id", '=',   "DepParentOrgEmployeeDepartment." .OrgEmployeeDepartment::org_department_id)
                        ->where("DepParentOrgEmployeeDepartment.".OrgEmployeeDepartment::is_head,TRUE);
                })
                ->leftJoin(OrgEmployee::table.' AS DepParentOrgEmployee', "DepParentOrgEmployeeDepartment.".OrgEmployeeDepartment::org_employee_id, '=',  'DepParentOrgEmployee.id')
                ->leftJoin(User::table.' AS DepParentUser', "DepParentOrgEmployee." . OrgEmployee::user_id, '=',  'DepParentUser.id')
                ->leftJoin(UserProfile::table.' AS DepParentUserProfile', "DepParentUserProfile." . UserProfile::user_id, '=',  'DepParentUser.id')                    
                
                // child department and child department head  
                ->leftJoin(OrgDepartment::table.' AS DepChild', "DepChild.".OrgDepartment::parent_department_id, '=',  OrgDepartment::table . '.id')
                ->leftJoin(OrgEmployeeDepartment::table. ' AS DepChildOrgEmployeeDepartment', function ($join){
                    $join->on( "DepChild.id", '=',   "DepChildOrgEmployeeDepartment." .OrgEmployeeDepartment::org_department_id)
                        ->where("DepChildOrgEmployeeDepartment.".OrgEmployeeDepartment::is_head,TRUE);
                })
                ->leftJoin(OrgEmployee::table.' AS DepChildOrgEmployee', "DepChildOrgEmployeeDepartment.".OrgEmployeeDepartment::org_employee_id, '=',  'DepChildOrgEmployee.id')
                ->leftJoin(User::table.' AS DepChildUser', "DepChildOrgEmployee." . OrgEmployee::user_id, '=',  'DepChildUser.id')
                ->leftJoin(UserProfile::table.' AS DepChildUserProfile', "DepChildUserProfile." . UserProfile::user_id, '=',  'DepChildUser.id')  

                ->select(
                        OrgDepartment::table.'.'.OrgDepartment::slug.' AS departmentSlug',
                        OrgDepartment::table.'.'.OrgDepartment::name.' AS departmentName',
                        Organization::table.'.'.Organization::slug.' AS orgSlug',
                        UserProfile::table.'.'.UserProfile::first_name.' AS departmentHeadName',
                        DB::raw('concat("'. $this->s3BasePath.'", '.UserProfile::table.'.'. UserProfile::image_path.') as departmentHeadImageUrl'),
                        User::table.'.'.User::slug.' AS departmentHeadUserSlug',
                        User::table.'.'.User::email.' AS departmentHeadEmail',
                        'DepRoot.'.OrgDepartment::slug.' AS rootDepartmentSlug',

                        //parent department
                        'DepParent.'.OrgDepartment::slug.' AS parentDepartmentSlug',
                        'DepParent.'.OrgDepartment::name.' AS parentDepartmentName',
                        'DepParentUserProfile.'.UserProfile::first_name.' AS parentDepartmentHeadName',
                        DB::raw('concat("'. $this->s3BasePath.'", '.'DepParentUserProfile.'. UserProfile::image_path.') as parentDepartmentHeadImageUrl'),
                        'DepParentUser.'.User::slug.' AS parentDepartmentHeadUserSlug',
                        'DepParentUser.'.User::email.' AS parentDepartmentHeadEmail',
                        
                        //child departments
                        'DepChild.'.OrgDepartment::slug.' AS childDepartmentSlug',
                        'DepChild.'.OrgDepartment::name.' AS childDepartmentName',
                        'DepChildUserProfile.'.UserProfile::first_name.' AS childDepartmentHeadName',
                        DB::raw('concat("'. $this->s3BasePath.'", '.'DepChildUserProfile.'. UserProfile::image_path.') as childDepartmentHeadImageUrl'),
                        'DepChildUser.'.User::slug.' AS childDepartmentHeadUserSlug',
                        'DepChildUser.'.User::email.' AS childDepartmentHeadEmail'
                        
                        )
                ->get();
            
            //dd(DB::getQueryLog());
                
            $basicDetailsArr = array();
            $childDepartments = array();
            $orgParentAndChildDepartmentData->each(function($item) use(&$basicDetailsArr, &$childDepartments){
                $basicDetailsArr = $item->toArray();
                
                array_push($childDepartments, array(
                    'childDepartmentSlug' => $basicDetailsArr['childDepartmentSlug'],
                    'childDepartmentName' => $basicDetailsArr['childDepartmentName'],
                    'childDepartmentHeadName' => $basicDetailsArr['childDepartmentHeadName'],
                    'childDepartmentHeadImageUrl' => $basicDetailsArr['childDepartmentHeadImageUrl'],
                    'childDepartmentHeadUserSlug' => $basicDetailsArr['childDepartmentHeadUserSlug'],
                    'childDepartmentHeadEmail' => $basicDetailsArr['childDepartmentHeadEmail']

                ));
                unset($basicDetailsArr['childDepartmentSlug']);
                unset($basicDetailsArr['childDepartmentName']);
                unset($basicDetailsArr['childDepartmentHeadName']);
                unset($basicDetailsArr['childDepartmentHeadImageUrl']);
                unset($basicDetailsArr['childDepartmentHeadUserSlug']);
                unset($basicDetailsArr['childDepartmentHeadEmail']);

            });
            $basicDetailsArr['childDepartments']=$childDepartments;

            $orderByCol = "name"; //default
            if($request->sortBy=="departmentName"){
                $orderByCol = $request->sortBy;
            } else if($request->sortBy=="name"){
                $orderByCol = $request->sortBy;
            }
            $sortOrder = 'asc';
            if($request->sortOrder == 'desc'){
                $sortOrder = $request->sortOrder;
            }

            $utilParams=Utilities::getParams();
            $orgDepartmentEmpCount = OrgEmployeeDepartment::where(OrgEmployeeDepartment::org_department_id, $departmentObj->id)->count();

            //DB::statement("SET sql_mode = ''");
            $orgDepartmentEmpData = OrgEmployeeDepartment::where(OrgEmployeeDepartment::table .'.'.OrgEmployeeDepartment::org_department_id, $departmentObj->id)
                ->leftJoin(OrgDepartment::table, OrgDepartment::table . ".id", '=',   OrgEmployeeDepartment::table . "." .OrgEmployeeDepartment::org_department_id)
                ->leftJoin(OrgDepartment::table.' AS DepRoot', OrgDepartment::table . ".".OrgDepartment::root_department_id, '=',  'DepRoot.id')
                ->leftJoin(Organization::table, Organization::table . ".id", '=',  OrgDepartment::table.".".OrgDepartment::org_id)
                ->leftJoin(OrgDepartment::table.' AS DepParent', OrgDepartment::table . ".".OrgDepartment::parent_department_id, '=',  'DepParent.id')
                ->leftJoin(OrgEmployee::table, OrgEmployeeDepartment::table . ".".OrgEmployeeDepartment::org_employee_id, '=',  OrgEmployee::table.'.id')
                ->leftJoin(User::table, OrgEmployee::table . "." . OrgEmployee::user_id, '=',  User::table.'.id')
                ->leftJoin(UserProfile::table, UserProfile::table . "." . UserProfile::user_id, '=',  User::table.'.id')
                ->leftJoin(OrgEmployeeDepartment::table .' AS MemberDepartmentsMap', "MemberDepartmentsMap." . OrgEmployeeDepartment::org_employee_id, '=',  OrgEmployee::table.'.id')
                ->leftJoin(OrgDepartment::table.' AS MemberDepartments', "MemberDepartmentsMap.".OrgEmployeeDepartment::org_department_id, '=',  'MemberDepartments.id')
                ->select(
                        OrgDepartment::table.'.'.OrgDepartment::slug.' AS departmentSlug',
                        OrgDepartment::table.'.'.OrgDepartment::name.' AS departmentName',
                        Organization::table.'.'.Organization::slug.' AS orgSlug',
                        'DepParent.'.OrgDepartment::slug.' AS parentDepartmentSlug',
                        'DepParent.'.OrgDepartment::name.' AS parentDepartmentName',
                        'DepRoot.'.OrgDepartment::slug.' AS rootDepartmentSlug',
                        OrgEmployee::table.'.'.OrgEmployee::name.' AS name',
                        User::table.'.'.User::slug.' AS userSlug',
                        OrgEmployee::table.'.'.OrgEmployee::slug.' AS employeeSlug',
                        User::table.'.'.User::email.' AS email',
                        DB::raw('concat("'. $this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as imageUrl'),
                        OrgEmployeeDepartment::table.'.'.OrgEmployeeDepartment::is_head. ' AS isHead',
                        DB::raw('GROUP_CONCAT(MemberDepartments.' .OrgDepartment::name . ') AS memberDepartmentNames'),
                        DB::raw('GROUP_CONCAT(MemberDepartmentsMap.' .OrgEmployeeDepartment::is_head . ') AS memberDepartmentIsHeads')
                        )
                ->groupBy(OrgEmployee::table.'.id')
                ->orderBy($orderByCol, $sortOrder)
                ->skip($utilParams['offset']) //$request['offset']
                ->take($utilParams['perPage']) //$request['perPage']
                ->get();

            //dd($orgDepartmentEmpData);
            $orgDepartmentEmpData->transform(function($item){

                $memberDepartmentsArr = explode(',',$item->memberDepartmentNames);
                $memberDepartmentIsHeadsArr = explode(',',$item->memberDepartmentIsHeads);

                unset($item->memberDepartmentNames);
                unset($item->memberDepartmentIsHeads);

                $item->isHead = (boolean)$item->isHead;
                $memberDepartmentsDtlsArr = array();

                foreach ($memberDepartmentsArr as $key=>$value) {
                    array_push($memberDepartmentsDtlsArr, 
                            array("departmentName"=>$value,
                                "isHead"=>(boolean)$memberDepartmentIsHeadsArr[$key]
                            ));
                }

                $item->memberDepartments = $memberDepartmentsDtlsArr;
                return $item;

            });
            //dd($orgParentAndChildDepartmentData);
            

            $paginatedData = Utilities::paginate($orgDepartmentEmpData, $utilParams['perPage'], $utilParams['page'], array(), $orgDepartmentEmpCount)->toArray();

            $formatedData = $this->reformatEmpData($paginatedData, $basicDetailsArr);

            

            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['data'] = $formatedData;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;

        } catch (\Exception $e) {
            return $this->throwError($e->getMessage(),422);
            //return $this->throwError('Something went wrong, Failed to fetch Department Employees', 422);
        }
    }

    public function reformatEmpData($paginatedData, $basicDetailsArr) {
        $paginatedData['departmentDetails'] = $basicDetailsArr;
        $paginatedData['members'] = $paginatedData['data'];
        unset($paginatedData['data']);
        $dataArr = Utilities::unsetResponseData($paginatedData);
        return $dataArr;
    }
    
    
    
    public function getAllDepartmentsTree(Request $request)
    {
 
        try {
            $organisation = Organization::where(Organization::slug, $request->orgSlug)->first();
            if(empty($organisation)){
                return $this->throwError('Invalid Organisation', 422);
            }


            //DB::enableQueryLog();
            
            $queryBuilderObj = OrgDepartment::where(OrgDepartment::table .'.'.OrgDepartment::org_id, $organisation->id);
            
            $allDepartmentDetailsCol = $queryBuilderObj
                ->leftJoin(Organization::table, Organization::table . ".id", '=',  OrgDepartment::table.".".OrgDepartment::org_id)
                ->leftJoin(OrgEmployeeDepartment::table, function ($join){
                    $join->on(OrgDepartment::table . ".id", '=',   OrgEmployeeDepartment::table . "." .OrgEmployeeDepartment::org_department_id)
                            ->where(OrgEmployeeDepartment::is_head,TRUE);
                })
                ->leftJoin(OrgEmployee::table, OrgEmployeeDepartment::table . ".".OrgEmployeeDepartment::org_employee_id, '=',  OrgEmployee::table.'.id')
                ->leftJoin(User::table, OrgEmployee::table . "." . OrgEmployee::user_id, '=',  User::table.'.id')
                ->leftJoin(UserProfile::table, UserProfile::table . "." . UserProfile::user_id, '=',  User::table.'.id')

                ->leftJoin(OrgDepartment::table .' AS parentDepartment', OrgDepartment::table . "." . OrgDepartment::parent_department_id, '=',  'parentDepartment.id')
                ->leftJoin(OrgDepartment::table .' AS rootDepartment', OrgDepartment::table . "." . OrgDepartment::root_department_id, '=',  'rootDepartment.id')        

                ->select(                     
                        OrgDepartment::table.'.id AS departmentId',
                        OrgDepartment::table.'.'.OrgDepartment::parent_department_id.' AS parentDepartmentId',
                        'parentDepartment.'.OrgDepartment::slug.' AS parentDepartmentSlug',
                        'rootDepartment.'.OrgDepartment::slug.' AS rootDepartmentSlug',
                        OrgDepartment::table.'.'.OrgDepartment::slug.' AS departmentSlug',
                        OrgDepartment::table.'.'.OrgDepartment::name.' AS departmentName',
                        Organization::table.'.'.Organization::slug.' AS orgSlug',
                        UserProfile::table.'.'.UserProfile::first_name.' AS departmentHeadName',
                        DB::raw('concat("'. $this->s3BasePath.'", '.UserProfile::table.'.'. UserProfile::image_path.') as departmentHeadImageUrl'),
                        User::table.'.'.User::slug.' AS departmentHeadUserSlug',
                        User::table.'.'.User::email.' AS departmentHeadEmail',
                        OrgEmployee::table.'.'.OrgEmployee::slug.' AS departmentHeadEmployeeSlug',

                        OrgDepartment::table.'.'.OrgDepartment::path_enum. ' AS pathEnum',

                        // department root member's count subquery     
                        DB::raw('( select count(*) from '.OrgEmployeeDepartment::table.
                                ' where '.OrgEmployeeDepartment::table.'.'.OrgEmployeeDepartment::org_department_id.' = '. OrgDepartment::table.'.id )'.
                                ' AS memberCount')

                        )
                ->get();

            $allDepartmentDetailsArr = $allDepartmentDetailsCol->toArray(); 

            $departmentTree = $this->buildTree($allDepartmentDetailsArr);

            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['data'] = array("departments" => $departmentTree);
            //$responseData['data'] = $orgParentAndChildDepartmentData;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;

        } catch (\Exception $e) {
            return $this->throwError($e->getMessage(), 422);
        }
    }
    
    public function buildTree(array $elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            $element['isPopupActive'] = false;
            $element['isActive'] = false;
            $element['showMemberPopup'] = false;

            if(empty($element['pathEnum'])){
                $element['depth'] = 0;
            } else {
                $element['depth'] = count(explode('.', $element['pathEnum']));
            }

            if ($element['parentDepartmentId'] == $parentId) {
                $children = $this->buildTree($elements, $element['departmentId']);
                if (!empty($children)) {
                    $element['isParent'] = true;
                    $element['child'] = $children;
                } else {
                    $element['isParent'] = false;
                    $element['child'] = [];
                }

                $branch[] = $element;
            }
        }
        return $branch;
    }
}