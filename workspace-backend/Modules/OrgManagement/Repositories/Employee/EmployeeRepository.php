<?php
/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 7/2/18
 * Time: 12:04 PM
 */

namespace Modules\OrgManagement\Repositories\Employee;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\Common\Entities\Country;
use Modules\HrmManagement\Entities\HrmAbsence;
use Modules\HrmManagement\Entities\HrmAbsentCount;
use Modules\HrmManagement\Entities\HrmLeaveType;
use Modules\HrmManagement\Entities\HrmLeaveTypeCategory;
use Modules\OrgManagement\Emails\UserVerifyMail;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\OrgManagement\Entities\OrgEmployeeStatus;
use Modules\OrgManagement\Entities\OrgLicense;
use Modules\OrgManagement\Entities\OrgLicenseMapping;
use Modules\OrgManagement\Http\Requests\CreateEmployeeRequest;
use Modules\OrgManagement\Http\Requests\UpdateEmployeeRequest;
use Modules\OrgManagement\Jobs\VerifyUser;
use Modules\OrgManagement\Repositories\EmployeeRepositoryInterface;
use Modules\UserManagement\Entities\Interest;
use Modules\UserManagement\Entities\Permissions;
use Modules\UserManagement\Entities\Roles;
use Modules\UserManagement\Entities\User;
use Modules\Common\Utilities\Utilities;
use Modules\OrgManagement\Entities\OrgEmployeeDepartment;
use Illuminate\Support\Facades\DB;
use Modules\OrgManagement\Entities\OrgDepartment;
use Modules\UserManagement\Entities\UserProfile;
use Modules\UserManagement\Entities\UserProfileAddress;
use Modules\UserManagement\Entities\UserProfileInterest;

class EmployeeRepository implements EmployeeRepositoryInterface
{

    protected $content;
    protected $s3BasePath;

    public function __construct()
    {
        $this->content = array();
        $this->s3BasePath = env('S3_PATH');
    }

    /**
     * Add employee
     * @param CreateEmployeeRequest $request
     * @return array
     */
    public function addEmployee(CreateEmployeeRequest $request)
    {
        DB::beginTransaction();
        try {
            $organization   = $this->getOrganization($request->orgSlug);
            if (!$organization) {
                throw new \Exception('No Organizaion Found');
            }

            $validOrgLicenseDataArr = $this->getValidOrganizationLicenseMappingId($organization);

            if (empty($validOrgLicenseDataArr)){
                throw new \Exception('No Active License found for this organization', 422);
            }

            $licenseId      = $validOrgLicenseDataArr['orgLicenseId'];
            $orgLicenseMapId      = $validOrgLicenseDataArr['orgLicenseMapId'];

            $employeeStatus = OrgEmployeeStatus::where(OrgEmployeeStatus::name, OrgEmployeeStatus::WORKING)->first();

            $user = $this->addUser($request);

            $reportManagerEmp = $this->getReportManagerEmployee($request);

            $employee = new OrgEmployee;
            $employee->{OrgEmployee::name} = $request->name;
            $employee->{OrgEmployee::slug} = Utilities::getUniqueId();
            $employee->{OrgEmployee::user_id} = $user->id;
            $employee->{OrgEmployee::org_id}  = $organization->id;
            $employee->{OrgEmployee::employee_status_id} = $employeeStatus->id;
            $employee->{OrgEmployee::org_license_id}     = $licenseId;
            $employee->{OrgEmployee::org_license_map_id} = $orgLicenseMapId;
            $employee->{OrgEmployee::joining_date}       = now();

            if ($reportManagerEmp) {
                $employee->{OrgEmployee::reporting_manager_id}       = $reportManagerEmp->id;
            }
            $employee->save();

            dispatch(new VerifyUser($user));


            if(!empty($request->departmentSlugs)){ //if not empty add employee to given department
                foreach ($request->departmentSlugs as $departmentSlug) {
                    $departmentObj = OrgDepartment::where(OrgDepartment::slug, $departmentSlug)->select('id')->first();
                    if(empty($departmentObj)){
                        throw new \Exception('Invalid Department slug:'.$departmentSlug);
                    }
                    $OrgEmployeeDepartment = OrgEmployeeDepartment::where(OrgEmployeeDepartment::org_employee_id, $employee->id)
                            ->where(OrgEmployeeDepartment::org_department_id, $departmentObj->id)->select(OrgEmployeeDepartment::org_department_id)->first();            

                    if(empty($OrgEmployeeDepartment)){
                        $OrgEmployeeDepartment = new OrgEmployeeDepartment;
                        $OrgEmployeeDepartment->{OrgEmployeeDepartment::org_employee_id} = $employee->id;
                        $OrgEmployeeDepartment->{OrgEmployeeDepartment::org_department_id} = $departmentObj->id;
                        $OrgEmployeeDepartment->{OrgEmployeeDepartment::is_head} = FALSE;
                        $OrgEmployeeDepartment->save();
                    }
                }
            }
            DB::commit();
            
            $this->content['data']   =  'Employee created successfully';
            $this->content['code']   =  200;
            return $this->content;

        } catch (QueryException $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }

    }

    /**
     * inviteEmployee 
     * @param Request $request
     * @return array
     */
    public function inviteEmployee(Request $request)
    {
        DB::beginTransaction();
        try {
            $organization   = $this->getOrganization($request->orgSlug);
            if (!$organization) {
                throw new \Exception('No Organizaion Found');
            }

            $validOrgLicenseDataArr = $this->getValidOrganizationLicenseMappingId($organization);

            if (empty($validOrgLicenseDataArr)){
                throw new \Exception('No Active License found for this organization');
            }

            $licenseId      = $validOrgLicenseDataArr['orgLicenseId'];
            $orgLicenseMapId      = $validOrgLicenseDataArr['orgLicenseMapId'];

            $employeeStatus = OrgEmployeeStatus::where(OrgEmployeeStatus::name, OrgEmployeeStatus::WORKING)->first();

            $reportManagerEmp = $this->getReportManagerEmployee($request);

            $user = $this->addUser($request);

            dispatch(new VerifyUser($user));

            $employee = new OrgEmployee;
            $employee->{OrgEmployee::name} = $request->name;
            $employee->{OrgEmployee::slug} = Utilities::getUniqueId();
            $employee->{OrgEmployee::user_id} = $user->id;
            $employee->{OrgEmployee::org_id}  = $organization->id;
            $employee->{OrgEmployee::employee_status_id} = $employeeStatus->id;
            $employee->{OrgEmployee::reporting_manager_id} = $employeeStatus->id;
            $employee->{OrgEmployee::org_license_id}     = $licenseId;
            $employee->{OrgEmployee::org_license_map_id} = $orgLicenseMapId;
            $employee->{OrgEmployee::joining_date}       = now();

            if ($reportManagerEmp) {
                $employee->{OrgEmployee::reporting_manager_id}  = $reportManagerEmp->id;
            }
            $employee->save();
         
            DB::commit();
            
            $this->content['data']   =  'Employee invited successfully';
            $this->content['code']   =  200;
            return $this->content;

        } catch (QueryException $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }

    }

    public function getReportManagerEmployee($request)
    {
        $reportManagerEmp = null;
        if ($request->reportManagerEmpSlug) {
            $reportManagerEmp = DB::table(OrgEmployee::table)
                ->select('id')
                ->where(OrgEmployee::slug, $request->reportManagerEmpSlug)->first();
            if (empty($reportManagerEmp)) {
                throw new \Exception('Invalid reporting manger slug');
            }
        }
        return $reportManagerEmp;
    }
    
    public function throwError($data, $code) : array
    {
        $this->content['error'] = array('msg'=>$data);
        $this->content['code']  = $code;
        $this->content['status']  = 'ERROR';
        return $this->content;
    }

    public function getOrganization($slug)
    {
        return Organization::where(Organization::slug, $slug)->first();
    }

    /*
     * return array with orgLicenseId , orgLicenseMapId
     */
    public function getValidOrganizationLicenseMappingId($organization)
    {
        $validByDateQueryBuilder = OrgLicenseMapping::where(OrgLicenseMapping::org_id, $organization->id)
            ->where(OrgLicenseMapping::end_date, '>', now());

        $getAllValidByDateOrgLicenseMapIds = $validByDateQueryBuilder->select(OrgLicenseMapping::license_id)
            ->pluck(OrgLicenseMapping::license_id)->toArray();

        $licenseDetailsResult = DB::table(OrgEmployee::table)
            ->join(OrgLicenseMapping::table, OrgLicenseMapping::table.'.'.OrgLicenseMapping::license_id,'=', OrgEmployee::table.'.'.OrgEmployee::org_license_id)
            ->join(OrgLicense::table, OrgLicense::table.'.id', '=', OrgEmployee::table.'.'.OrgEmployee::org_license_id)
            ->whereIn(OrgEmployee::table.'.'.OrgEmployee::org_license_id, $getAllValidByDateOrgLicenseMapIds)
            ->where(OrgLicense::table.'.'.OrgLicense::license_status, '=', TRUE)
            ->select(
                    OrgLicense::table.'.id AS orgLicenseId',
                    OrgLicenseMapping::table.'.id AS orgLicenseMapId',
                    OrgLicense::max_users.' AS maxUsers',
                    OrgLicense::license_status
                )->get();

        $currentEmployeeIds =  OrgEmployee::where(OrgEmployee::org_id,$organization->id)->select('id')->get()->toArray();
        $currentEmployeeCount = count($currentEmployeeIds);

        $licenseArray = array();
        $licenseDetailsResult->each(function ($item) use(&$licenseArray){
            $licenseArray[$item->orgLicenseMapId] = (array)$item;
        });
        $validOrgLicenseDataArr = array();
        $maxUserLimitReachedOrgLicenseIds = array();
        foreach ($licenseArray as $orgLicenseMapId => $item) { //check for max user
            if( $currentEmployeeCount < $item['maxUsers']){
                $validOrgLicenseDataArr['orgLicenseMapId'] = $orgLicenseMapId;
                $validOrgLicenseDataArr['orgLicenseId'] = $item['orgLicenseId'];
            } else {
                array_push($maxUserLimitReachedOrgLicenseIds, $item['orgLicenseId']);
            }
        }

        if(empty($validOrgLicenseDataArr)){ // check for other license
            $tempResp = $validByDateQueryBuilder->whereNotIn(OrgLicenseMapping::license_id,$maxUserLimitReachedOrgLicenseIds)
                ->select("id as orgLicenseMapId",OrgLicenseMapping::license_id.' as orgLicenseId')->first();
            if(!empty($tempResp)){
                $validOrgLicenseDataArr['orgLicenseMapId'] = $tempResp->orgLicenseMapId;
                $validOrgLicenseDataArr['orgLicenseId'] = $tempResp->orgLicenseId;
            }
        }
        return $validOrgLicenseDataArr;
    }

    public function getOrganizationEmployee($slug)
    {
        return OrgEmployee::where(OrgEmployee::slug, $slug)->first();
    }

    /**
     * add user to user table for employee
     * @param $request
     * @return array|User
     */
    public function addUser($request)
    {
        try {
            $user = new User;
            $user->{User::slug}     = Utilities::getUniqueId();
            $user->{User::name}     = $request->name;
            $user->{User::email}    = $request->email;
            if(empty($request->password)){
                $tempPassword = "qwerty@123";
            } else {
                $tempPassword = $request->password;
            }
            $user->{User::password} = bcrypt($tempPassword);
            $user->remember_token   = $user->generateVerificationCode();
            $user->save();

            $user->assignRole(Roles::ORG_EMPLOYEE);
            return $user;
        } catch (QueryException $e) {
            throw new \Exception('user already exist');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Update employee details
     * @param UpdateEmployeeRequest $request
     * @return array
     */
    public function updateEmployee(Request $request)
    {
        
        DB::beginTransaction();
        try {
            $organization   = $this->getOrganization($request->orgSlug);
            if (empty($organization)) {
                throw new \Exception('No Organizaion Found');
            }

            $orgEmployee = $this->getOrganizationEmployee($request->employeeSlug);

            if (empty($orgEmployee)){
                throw new \Exception('No Employee Found');
            }

            $reportManagerEmp = $this->getReportManagerEmployee($request);
            $orgEmployee->{OrgEmployee::reporting_manager_id} = (empty($reportManagerEmp)) ? null : $reportManagerEmp->id;


            $orgEmployee->{OrgEmployee::name} = $request->name;
            $orgEmployee->save();

            $orgEmployee->user->name = $request->name;
            $orgEmployee->user->save();

            $savedDepartmentIdsArr=array();
            if(!empty($request->departmentSlugs)){ //if not empty add employee to given department

                $departments = (array)$request->departmentSlugs;
                
                foreach ($departments as $key => $departmentSlug) {
                    
                    $departmentObj = OrgDepartment::where(OrgDepartment::slug, $departmentSlug)->select('id')->first();
                    if(empty($departmentObj)){
                        throw new \Exception('Invalid Department slug:'.$departmentSlug);
                    }
                    array_push($savedDepartmentIdsArr, $departmentObj->id);
                    
                    $OrgEmployeeDepartment = OrgEmployeeDepartment::where(OrgEmployeeDepartment::org_employee_id, $orgEmployee->id)
                            ->where(OrgEmployeeDepartment::org_department_id, $departmentObj->id)->select(OrgEmployeeDepartment::org_department_id)->first();            

                    if(empty($OrgEmployeeDepartment)){
                        $OrgEmployeeDepartment = new OrgEmployeeDepartment;
                        $OrgEmployeeDepartment->{OrgEmployeeDepartment::org_employee_id} = $orgEmployee->id;
                        $OrgEmployeeDepartment->{OrgEmployeeDepartment::org_department_id} = $departmentObj->id;
                        $OrgEmployeeDepartment->{OrgEmployeeDepartment::is_head} = FALSE;
                        $OrgEmployeeDepartment->save();
                    }
                }

                $delOrgEmpList = OrgEmployeeDepartment::where(OrgEmployeeDepartment::org_employee_id, '=', $orgEmployee->id)
                        ->select(OrgEmployeeDepartment::org_department_id)->get();
                $delOrgEmpList->each(function($OrgEmpDep) use($orgEmployee, $savedDepartmentIdsArr) {
                    
                    if(!in_array($OrgEmpDep->{OrgEmployeeDepartment::org_department_id}, $savedDepartmentIdsArr)){
                        OrgEmployeeDepartment::where(OrgEmployeeDepartment::org_employee_id, '=', $orgEmployee->id)
                        ->where(OrgEmployeeDepartment::org_department_id, '=', $OrgEmpDep->{OrgEmployeeDepartment::org_department_id})
                        ->delete();
                    }
                });
                
                
            } else {
                OrgEmployeeDepartment::where(OrgEmployeeDepartment::org_employee_id, '=', $orgEmployee->id)
                    ->delete();
            }
            DB::commit();

        } catch (QueryException $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }
        $this->content['data']   =  'Employee updated successfully';
        $this->content['code']   =  200;

        return $this->content;
    }

    /**
     * soft delete an employee
     * @param $employee
     * @return array
     */
    public function deleteEmployee($employee)
    {
        $orgEmployee = $this->getOrganizationEmployee($employee);

        if (empty($orgEmployee)){
            return $this->throwError('No Employee Found', 422);
        }

        $orgEmployee->delete();
        $this->content['data']   =  'Employee deleted successfully';
        $this->content['code']   =  200;

        return $this->content;
    }
    
    
    /**
     * get organization users
     * @return array
     */
    public function getEmployeeUsers(Request $request)
    {
        $s3BasePath = env('S3_PATH');
        //selects only employees under an organization
        try {
            $organization   = $this->getOrganization($request->orgSlug);
            if (!$organization) {
                throw new \Exception('No Organizaion Found');
            }

            $orgEmployees = OrgEmployee::where(OrgEmployee::table. '.'. OrgEmployee::org_id, $organization->id)
                    ->select(
                    OrgEmployee::table. '.' .OrgEmployee::name.' as employeeName',
                    OrgEmployee::table. '.' .OrgEmployee::slug.' as employeeSlug',
                    User::table. '.' .User::slug.' as userSlug',
                    User::table. '.' .User::email.' as employeeEmail',
                    DB::raw('concat("'.$s3BasePath.'",employeeImage.'. UserProfile::image_path .') as employeeImage '),
                    DB::raw('GROUP_CONCAT('. OrgDepartment::table. '.'. OrgDepartment::slug.') as departmentSlugs'),
                    DB::raw('GROUP_CONCAT('. OrgDepartment::table. '.'. OrgDepartment::name.') as departmentNames'),
                    DB::raw('GROUP_CONCAT('. OrgEmployeeDepartment::table. '.'. OrgEmployeeDepartment::is_head.') as departmentHeadStatus'),
                    'reportingManager.' .OrgEmployee::name.' as reportingManagerName',
                    'reportingManager.' .OrgEmployee::slug.' as reportingManagerSlug',
                    DB::raw('concat("'.$s3BasePath.'",reportingManagerProfile.'. UserProfile::image_path .') as reportingManagerImage ')
                )
                ->join(User::table, User::table. '.id', '=', OrgEmployee::table. '.'. OrgEmployee::user_id)
                ->leftJoin(UserProfile::table. ' as employeeImage', User::table. '.id', '=', 'employeeImage.' .UserProfile::user_id)
                ->leftJoin(OrgEmployeeDepartment::table, OrgEmployeeDepartment::table.'.'.OrgEmployeeDepartment::org_employee_id, '=', OrgEmployee::table.'.id')
                ->leftJoin(OrgDepartment::table, OrgDepartment::table.'.id', '=', OrgEmployeeDepartment::table.'.'.OrgEmployeeDepartment::org_department_id)
                ->leftJoin(OrgEmployee::table." AS reportingManager", 'reportingManager.id', '=', OrgEmployee::table.'.'.OrgEmployee::reporting_manager_id)
                ->leftJoin(User::table . ' AS reportingManagerUser', 'reportingManagerUser.id', '=', 'reportingManager.'. OrgEmployee::user_id)
                ->leftJoin(UserProfile::table. ' AS reportingManagerProfile', 'reportingManagerUser.id', '=', 'reportingManagerProfile.' .UserProfile::user_id);

            if ($request->q) {
                $query = $request->q;
                $orgEmployees->Where(OrgEmployee::table. '.'. OrgEmployee::name, 'like', "%{$query}%");
            }
            $orgEmployees->groupBy('employeeSlug');
            
            if(empty($request->sortOrder)){
                $request->sortOrder = 'asc';
            }
            
            if($request->sortBy == 'employeeName'){
                $orgEmployees = Utilities::sort($orgEmployees);
            }
            
            $orgEmployeeDataArr = $orgEmployees->get();

            $orgEmployeeDataArr->transform(function($orgEmployee){
                $empDepartmentsArr=array();
                if(!empty($orgEmployee->departmentNames)){
                    $departmentsArr = explode(',', $orgEmployee->departmentNames);
                    $departmentHeadStatusArr = explode(',', $orgEmployee->departmentHeadStatus);
                    $departmentSlugsArr = explode(',', $orgEmployee->departmentSlugs);
                    
                    foreach ($departmentsArr as $index => $departmentName){
                        array_push($empDepartmentsArr, 
                                array(
                                    "slug" => $departmentSlugsArr[$index],
                                    "name"=>$departmentName,
                                    "isHead"=>(boolean)$departmentHeadStatusArr[$index],
                                )
                            );
                    }
                }
                $orgEmployee->employeeDepartments = $empDepartmentsArr;
                unset($orgEmployee->departmentSlugs);
                unset($orgEmployee->departmentNames);
                unset($orgEmployee->departmentHeadStatus);
                return $orgEmployee;
            });
            
            return $this->content = array(
                'data'   => array(
                        "count"=>count($orgEmployeeDataArr),
                        "employees" =>$orgEmployeeDataArr
                    ),
                'code'   => 200,
                'status' => "OK"
            );
        
        } catch (\Exception $e) {
            return $this->throwError($e->getMessage(), 422);
        }
    }

    public function fetchEmployeeInfo(Request $request)
    {
        try {
           $orgEmp =  DB::table(OrgEmployee::table)
                ->select(OrgEmployee::user_id)
                ->where(OrgEmployee::slug, $request->employeeSlug)->first();

            $userProfile = DB::table(User::table)
                ->join(OrgEmployee::table, OrgEmployee::table. '.' .OrgEmployee::user_id, '=', User::table. '.id')
                ->leftjoin(UserProfile::table, UserProfile::table.'.'.UserProfile::user_id, '=', User::table.'.'. User::id)
                ->leftjoin(UserProfileAddress::table, UserProfile::table.'.'.UserProfile::user_profile_address_id, '=', UserProfileAddress::table.'.id')
                ->leftjoin(Country::table, Country::table.'.id', '=', UserProfileAddress::table.'.' .UserProfileAddress::country_id)

                ->select(
                    User::table. '.'. User::name,
                    User::table. '.'. User::email,
                    User::table. '.'. User::verified. ' as userStatus',
                    UserProfile::table. '.'. UserProfile::first_name .' as firstName',
                    UserProfile::table. '.'. UserProfile::last_name  .' as lastName',

                    DB::raw("unix_timestamp(".UserProfile::table . '.'.UserProfile::birth_date.") AS birthDate"),
                    UserProfile::table. '.'. UserProfile::user_image .' as userImage',
                    UserProfile::table.'.id as profileId',
                    UserProfile::table. '.' .UserProfile::phone. ' as phone',

                    DB::raw('concat("'.$this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as imageUrl'),
                    OrgEmployee::table. '.' .OrgEmployee::reporting_manager_id. ' as reportingManagerId',
                    OrgEmployee::table. '.id as employeeId',
                    DB::raw("unix_timestamp(".OrgEmployee::table . '.'.OrgEmployee::CREATED_AT.") AS joiningDate"),
                    UserProfileAddress::table. '.' .UserProfileAddress::street_address. ' as streetAddress',
                    UserProfileAddress::table. '.' .UserProfileAddress::address_line2. ' as addressLine2',
                    UserProfileAddress::table. '.' .UserProfileAddress::city,
                    UserProfileAddress::table. '.' .UserProfileAddress::state,
                    UserProfileAddress::table. '.' .UserProfileAddress::zip_code. ' as zipcode',
                    Country::table. '.' .Country::name. ' as country'

                )
                ->where(User::table.'.'. User::id, $orgEmp->{OrgEmployee::user_id})
                ->first();

            $userProfileInterestArr = [];
            //user interests
            if ($userProfile->profileId) {
                $userProfileInterestArr = DB::table(UserProfileInterest::table)
                    ->join(Interest::table, UserProfileInterest::table . '.' . UserProfileInterest::user_interest_id, '=', Interest::table . '.id')
                    ->select(

                        Interest::table . '.' . Interest::interest_title,
                        UserProfileInterest::table . '.' . UserProfileInterest::slug

                    )
                    ->where(UserProfileInterest::table . '.' . UserProfileInterest::user_profile_id, $userProfile->profileId)
                    ->get();
            }

            $userProfileArr = (array)$userProfile;
            $userProfileArr['interest'] =$userProfileInterestArr;
            $userProfileArr['reportingManagerDetails'] = new \stdClass();

            //reportingManger
            if ($userProfile->reportingManagerId) {
                $reportingManagerQuery = DB::table(OrgEmployee::table)
                    ->select(
                        OrgEmployee::table. '.' .OrgEmployee::slug. ' as reportingManagerSlug',
                        OrgEmployee::table. '.' .OrgEmployee::name. ' as reportingManagerName',
                        DB::raw('concat("'.$this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as reportingManagerImgUrl')
                    )
                    ->join(UserProfile::table, UserProfile::table. '.' .UserProfile::user_id, '=', OrgEmployee::table. '.' .OrgEmployee::user_id)
                    ->where(OrgEmployee::table. '.id', $userProfile->reportingManagerId)
                    ->first();

                $userProfileArr['reportingManagerDetails'] = $reportingManagerQuery;
            }

            //employee departments
            $empDepartments = DB::table(OrgEmployeeDepartment::table)
                ->select(
                    OrgDepartment::table. '.' .OrgDepartment::name. ' as departmentName',
                    OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::is_head. ' as isHead'
                )
                ->join(OrgDepartment::table, OrgDepartment::table. '.id', '=', OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::org_department_id)
                ->where(OrgEmployeeDepartment::table. '.' .OrgEmployeeDepartment::org_employee_id, $userProfile->employeeId)
                ->get();

            $empDepartments->transform(function ($depts) {
                $depts = (array) $depts;
                $depts['isdepartmentHead'] = (bool) $depts['isHead'];
                unset($depts['isHead']);
                return $depts;
            });

            $userProfileArr['departments'] = $empDepartments;

            $userProfileArr['userStatus'] = ($userProfileArr['userStatus']) ? true : false;
            unset($userProfileArr['reportingManagerId']);
            unset($userProfileArr['employeeId']);
            unset($userProfileArr['profileId']);

            $this->content['data']  =  $userProfileArr;
            $this->content['code']  =  200;
            $this->content['status'] =  "OK";
            return $this->content;

        } catch (\Exception $e) {
            return $this->throwError($e->getMessage(), 422);
        }

    }

    public function fetchEmployeeLeaveInfo(Request $request)
    {
        try {
            $orgEmp =  DB::table(OrgEmployee::table)
                ->select(OrgEmployee::user_id)
                ->where(OrgEmployee::slug, $request->employeeSlug)->first();

            if (empty($orgEmp)) {
                throw new \Exception('Invalid employee slug');
            }

            $userId = $orgEmp->{OrgEmployee::user_id};

            //DB::enableQueryLog();
            $leaves = DB::table(HrmAbsence::table)
                ->select(
                    HrmLeaveType::table. '.' .HrmLeaveType::name,
                    HrmLeaveTypeCategory::table. '.' .HrmLeaveTypeCategory::category_name. ' as type',
                    HrmLeaveType::table. '.' .HrmLeaveType::leave_count. ' as maxLeave',
                    DB::raw(HrmLeaveType::table. '.' .HrmLeaveType::leave_count . '-' ."sum(" . HrmAbsentCount::table. '.' .HrmAbsentCount::absent_days .") as balance")
                )
                ->join(HrmLeaveType::table, HrmLeaveType::table. '.id', '=', HrmAbsence::table. '.' .HrmAbsence::leave_type_id)
                ->join(HrmLeaveTypeCategory::table, HrmLeaveType::table. '.' .HrmLeaveType::type_category_id, '=', HrmLeaveTypeCategory::table. '.id')
                ->join(HrmAbsentCount::table, HrmAbsentCount::table. '.' .HrmAbsentCount::absence_id, '=', HrmAbsence::table. '.id')
                ->where(HrmAbsence::table. '.' .HrmAbsence::user_id, $userId)
                ->whereYear(HrmAbsence::table. '.' .HrmAbsence::absent_start_date_time, $request->year)
                ->groupBy(HrmAbsentCount::table. '.' .HrmAbsence::leave_type_id)
                ->get();
            //dd(DB::getQueryLog());

            $this->content['data']['leaveInfo']  =  $leaves;
            $this->content['code']  =  200;
            $this->content['status'] =  "OK";
            return $this->content;

        } catch (\Exception $e) {
            return $this->throwError($e->getMessage(), 422);
        }

    }
}