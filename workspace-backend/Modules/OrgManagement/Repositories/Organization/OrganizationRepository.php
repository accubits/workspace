<?php

namespace Modules\OrgManagement\Repositories\Organization;

use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Common\Utilities\FileUpload;
use Modules\Common\Utilities\ResponseStatus;
use Modules\HrmManagement\Entities\HrmWorkReportFrequency;
use Modules\HrmManagement\Entities\HrmWorkReportSettings;
use Modules\OrgManagement\Entities\Organization;
use Modules\Common\Entities\Vertical;
use Modules\Common\Entities\Country;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\OrgManagement\Entities\OrgEmployeeStatus;
use Modules\OrgManagement\Entities\OrgLicense;
use Modules\OrgManagement\Entities\OrgLicenseMapping;
use Modules\OrgManagement\Entities\OrgLicenseRequest;
use Modules\OrgManagement\Entities\OrgLicenseRequestsMap;
use Modules\OrgManagement\Entities\OrgLicenseType;
use Modules\OrgManagement\Jobs\OrgEmailNotificationQueue;
use Modules\PartnerManagement\Entities\Partner;
use Modules\OrgManagement\Repositories\OrganizationRepositoryInterface;
use Modules\Common\Utilities\Utilities;

use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\OrgAdmin;
use Illuminate\Support\Facades\DB;
use Modules\UserManagement\Entities\UserProfile;
use Modules\UserManagement\Entities\Roles;

class OrganizationRepository implements OrganizationRepositoryInterface
{

    protected $s3BasePath;

    public function __construct()
    {
        $this->s3BasePath = env('S3_PATH');
    }

    public function setVertical(Request $request)
    {
        DB::beginTransaction();
        try {
            $validActions = array('create', 'update', 'delete');
            
            if(!in_array($request->action, $validActions)){
                throw new \Exception("action is invalid");
            }
            
            if($request->action == 'create'){
                $vertical = new Vertical;
                $vertical->{Vertical::slug} = Utilities::getUniqueId();
                $vertical->{Vertical::name} = $request->name;
                $vertical->{Vertical::description} = $request->description;
                $vertical->{Vertical::is_active} = $request->isActive;
                $vertical->save();
                $msg = 'Vertical created successfully';
            } else if($request->action == 'update'){
                $vertical = Vertical::where(Vertical::slug, $request->slug)->first();
                if(empty($vertical)){
                    throw new \Exception("vertical slug is invalid");
                }
                $vertical->{Vertical::name} = $request->name;
                $vertical->{Vertical::description} = $request->description;
                $vertical->{Vertical::is_active} = $request->isActive;
                $vertical->save();
                $msg = 'Vertical updated successfully';
            } else if($request->action == 'delete'){
                $vertical = Vertical::where(Vertical::slug, $request->slug)->first();
                if(empty($vertical)){
                    throw new \Exception("vertical slug is invalid");
                }
                
                // check if an organization already created under this vertical.
                $count = Organization::where(Organization::vertical_id, $vertical->id)->count();
                if(!empty($count)){
                    throw new \Exception($count. " organization exist under this vertical. cannot delete!");
                }
                
                $vertical->delete();
                $msg = 'Vertical deleted successfully';
            }
            
            DB::commit();

            $resp=array();
            $resp['data']   = array( 
                "msg" => $msg,
                "slug"=>$vertical->{Vertical::slug}
                );
            $resp['code']   =  200;
            $resp['status']   =  "OK";
            return $resp;

        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }

    }

    public function listVertical(Request $request)
    {

        try {

            $verticalCount = Vertical::count();

            $verticalData = Vertical::select(Vertical::slug, Vertical::name, Vertical::description.' AS description')->skip(Utilities::getParams()['offset']) //$request['offset']
                ->take(Utilities::getParams()['perPage']) //$request['perPage']
                ->get();

            $paginatedData = Utilities::paginate($verticalData, Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $verticalCount)->toArray();

            $formatedData = $this->reformatVerticalsData($paginatedData);

            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['data'] = $formatedData;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;
        
        } catch (QueryException $e) {
            return $this->throwError('Something went wrong, Failed to fetch Organizations', 422);
        } catch (\Exception $e) {
            return $this->throwError('Something went wrong, Failed to fetch Organizations', 422);
        }
    }
    
    
    public function listOrganization(Request $request)
    {
 
        try {
            $partner = Partner::where(Partner::partner_slug, $request->partnerSlug)->first();
            if(empty($partner)){
                return $this->throwError('Invalid Partner', 422);
            }

            if (!in_array($request->tab, ['allOrg', 'unlicensedOrg', 'licensedOrg'])) {
                return $this->throwError('Invalid Tab', 422);
            }
            
            DB::statement("SET sql_mode = ''");
            $baseQuery = DB::table(Organization::table)
                    ->join(Partner::table, Partner::table . ".id", '=', Organization::table . '.' . Organization::partner_id)
                    ->join(Vertical::table, Vertical::table . ".id", '=', Organization::table . '.' . Organization::vertical_id)
                    ->join(Country::table, Country::table . ".id", '=', Organization::table . '.' . Organization::country_id)
                    ->join(User::table.' AS partnerUser', "partnerUser.id", '=', Partner::table . '.' . Partner::user_id);


/*            $orgCount = $baseQuery->where(Organization::table. '.' .Organization::partner_id, $partner->id)
                ->whereNull(Organization::table. '.deleted_at')
                ->count();*/

            $orgData = $baseQuery
                    ->leftJoin(OrgAdmin::table, Organization::table . ".id", '=', OrgAdmin::table . '.' . OrgAdmin::org_id)
                    ->leftJoin(User::table.' AS OrgAdminUser', "OrgAdminUser.id", '=', OrgAdmin::table . '.' . OrgAdmin::user_id)
                    //->leftJoin(OrgLicenseMapping::table, OrgLicenseMapping::table. '.' .OrgLicenseMapping::org_id, '=', Organization::table . '.id')
                    //->leftJoin(OrgLicense::table. ' AS License', 'License.'. OrgLicense::org_id, '=', Organization::table . '.id')
                    //->leftJoin(OrgLicenseType::table, OrgLicenseType::table. '.id', '=', 'License.' .OrgLicense::license_type_id)
                    ->leftjoin(UserProfile::table, 'OrgAdminUser.id', '=', UserProfile::table. '.'. UserProfile::user_id)
                    //->leftjoin(OrgLicenseRequestsMap::table, OrgLicenseRequestsMap::table. '.' .OrgLicenseRequestsMap::org_license_id, '=', 'License.id')
                    //->leftjoin(OrgLicenseRequest::table, OrgLicenseRequest::table. '.id', '=', OrgLicenseRequestsMap::table. '.' .OrgLicenseRequestsMap::license_request_id)
                    ->select(
                        Organization::table.'.'.Organization::slug.' AS orgSlug',
                        Organization::table.'.'.Organization::name .' AS name',
                        Organization::table.'.'.Organization::description .' AS description',
                        DB::raw("unix_timestamp(".Organization::table . '.'.Organization::CREATED_AT.") AS orgCreatedAt"),
                        Partner::table.'.'.Partner::partner_slug. ' AS partnerSlug',
                        "partnerUser.".User::name. ' AS partnerUserName',
                        Vertical::table .'.'. Vertical::slug. ' AS verticalSlug',
                        Vertical::table .'.'. Vertical::name. ' AS verticalName',
                        Country::table .'.' . Country::slug. ' AS countrySlug',
                        Country::table .'.' . Country::name. ' AS countryName',
                        DB::raw('GROUP_CONCAT( OrgAdminUser.' .User::slug . ') AS adminUserSlugs'),
                        DB::raw('GROUP_CONCAT( OrgAdminUser.' .User::name . ') AS adminUserNames'),
                        DB::raw('GROUP_CONCAT( OrgAdminUser.' .User::email . ') AS adminEmails'),
                        DB::raw('concat("'.$this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as adminUserImage')
                    );


            if ($request->tab == 'unlicensedOrg') {
                //org created by partner -- orgIdArr
                $orgIdArr = DB::table(Organization::table)
                    ->join(OrgLicense::table, Organization::table. '.id', '=', OrgLicense::table. '.' .OrgLicense::org_id)
                    ->where(OrgLicense::table. '.' .OrgLicense::license_status, true)
                    ->where(Organization::table. '.' .Organization::partner_id, $partner->id)
                    ->select(Organization::table. '.id')
                    ->groupBy(Organization::table. '.id')
                    ->get()
                    ->pluck('id')
                    ->toArray();

                $orgData = $orgData->whereNotIn(Organization::table. '.id', $orgIdArr);

            } else if ($request->tab == 'licensedOrg') {
                //org created by partner -- orgIdArr
                $orgIdArr = DB::table(Organization::table)
                    ->join(OrgLicense::table, Organization::table. '.id', '=', OrgLicense::table. '.' .OrgLicense::org_id)
                    ->where(OrgLicense::table. '.' .OrgLicense::license_status, true)
                    ->where(Organization::table. '.' .Organization::partner_id, $partner->id)
                    ->select(Organization::table. '.id')
                    ->groupBy(Organization::table. '.id')
                    ->get()
                    ->pluck('id')
                    ->toArray();

                $orgData = $orgData->whereIn(Organization::table. '.id', $orgIdArr);
            }

            $orgData = $orgData
                ->where(Organization::table. '.' .Organization::partner_id, $partner->id)
                ->whereNull(Organization::table. '.deleted_at')
                ->groupBy(Organization::table.'.id');

            //sorting
            if ($request->sortBy) {
                $key = [
                    'organization' => Organization::table. '.' .Organization::name,
                    'country'       => Country::table. '.' .Country::name,
                    'vertical'  => Vertical::table. '.' .Vertical::name,
                    'admin' => 'adminUserNames',
                    'createdOn' => Organization::table. '.' .Organization::CREATED_AT
                ];

                if (!array_key_exists($request->sortBy, $key)) {
                    throw new \Exception('Invalid sort key', 422);
                }

                $request->sortBy = $key[$request->sortBy];
            } else {
                $orgData = $orgData->orderBy(Organization::table. '.' .Organization::CREATED_AT, 'desc');
            }

            $orgData = Utilities::sort($orgData);
            $orgCount = $orgData->get()->count();

            $orgData = $orgData->skip(Utilities::getParams()['offset']) //$request['offset']
                ->take(Utilities::getParams()['perPage']) //$request['perPage']
                ->get();

            //dd(DB::getQueryLog());
            $paginatedData = Utilities::paginate($orgData, Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $orgCount)->toArray();

            $formatedData = $this->reformatData($paginatedData);

            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['data'] = $formatedData;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;

        } catch (QueryException $e) {
            return $this->throwError($e->getMessage(), 422);//throwError('Something went wrong, Failed to fetch Organizations', 422);
        } catch (\Exception $e) {
            return $this->throwError($e->getMessage(), 422);
        }
    }

     public function reformatData($dataArr) {
        $dataArr['organizations'] = $dataArr['data'];
        unset($dataArr['data']);
        $dataArr = Utilities::unsetResponseData($dataArr);
        return $dataArr;
    }   
    public function reformatVerticalsData($dataArr) {
        $dataArr['verticals'] = $dataArr['data'];
        unset($dataArr['data']);
        $dataArr = Utilities::unsetResponseData($dataArr);
        return $dataArr;
    }

    public function getOrganization($org_slug)
    {
        try {

            $orgData = Organization::where(Organization::slug, $org_slug)
                ->first();
            
            if(empty($orgData)){
                return $this->throwError('Invalid Organization', 422);
            }

            $formatedData = $orgData;
            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['data'] = $formatedData;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;
        
        } catch (QueryException $e) {
            return $this->throwError('Something went wrong, Failed to fetch Organizations', 422);
        } catch (\Exception $e) {
            return $this->throwError('Something went wrong, Failed to fetch Organizations', 422);
        }
    }
    /**
     * Add Organization
     * @param Request $request
     * @return array
     */
    public function addOrganization(Request $request)
    {
        DB::beginTransaction();
        try {
            $organization = new Organization;
            $organization->{Organization::slug} = uniqid();
            $organization = $this->setOrganizationFactory($request, $organization);
            DB::commit();
            $resp=array();
            $resp['data']   = array( "msg" => 'Organization created successfully');
            $resp['code']   =  201;
            $resp['status']   =  "OK";
            return $resp;

        } catch (QueryException $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);//throwError('Something went wrong, Failed to add Organization', 422);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }

    }

    public function throwError($msg, $code) : array
    {
        $resp=array();
        $resp['error'] = array('msg'=>$msg);
        $resp['code']  = $code;
        $resp['status']   =  "ERROR";
        return $resp;
    }
    
    private function setOrganizationFactory($request, $organization){
        $verticalObj = Vertical::where(Vertical::slug, $request->verticalSlug)->firstOrFail();
        $partnerObj = Partner::where(Partner::partner_slug, $request->partnerSlug)->firstOrFail();
        $countryObj = Country::where(Country::slug, $request->countrySlug)->firstOrFail();    
        
        $organization->{Organization::name} = $request->name;
        $organization->{Organization::description} = $request->description;
        $organization->{Organization::vertical_id} = $verticalObj->id;
        $organization->{Organization::partner_id} = $partnerObj->id;
        $organization->{Organization::country_id} = $countryObj->id;
        $organization->save();
        
        
        if(!empty($request->adminEmail)){
            if (!User::where('email', $request->adminEmail)->exists()) { // new user
                $user = new User;
                $user->name     = $request->adminEmail;
                $user->email    = $request->adminEmail;
                $user->password = bcrypt(env("ORGADMIN_DEFAULTPWD"));
                $user->save();

                $userProfile = new UserProfile([
                    UserProfile::first_name => $request->adminEmail,
                    UserProfile::last_name  => ""
                ]);

                $user->userProfile()->save($userProfile);
                $user->assignRole([Roles::ORG_ADMIN, Roles::ORG_EMPLOYEE]);

                $employeeStatus = OrgEmployeeStatus::where(OrgEmployeeStatus::name, OrgEmployeeStatus::WORKING)->first();

                //OrgEmployee
                $employee = new OrgEmployee;
                $employee->{OrgEmployee::name} = $request->adminEmail;
                $employee->{OrgEmployee::slug} = Utilities::getUniqueId();
                $employee->{OrgEmployee::user_id} = $user->id;
                $employee->{OrgEmployee::org_id}  = $organization->id;
                $employee->{OrgEmployee::employee_status_id} = $employeeStatus->id;
                $employee->{OrgEmployee::joining_date}       = now();
                $employee->save();

                OrgAdmin::updateOrCreate(
                        [OrgAdmin::org_id => $organization->id, OrgAdmin::user_id=>$user->id],
                        [OrgAdmin::org_id => $organization->id, OrgAdmin::user_id=>$user->id]
                );

                $emailParams = array('user' => $user, 'orgName' => $request->name, 'orgAdminDefaultPassword' => env("ORGADMIN_DEFAULTPWD"));

                /*$user['orgName'] = $request->name;
                $user->orgAdminDefaultPassword = env("ORGADMIN_DEFAULTPWD");*/

                dispatch(new OrgEmailNotificationQueue($user, $emailParams));
                //TODO sent email
            } else { //already existing email
                
                $user = User::where('email', $request->adminEmail)->first();
                
                $orgAdminObj = OrgAdmin::where(OrgAdmin::user_id, $user->id)->first();
                
                if(!empty($orgAdminObj)){
                    throw new \Exception($request->adminEmail." is already an OrgAdmin of an organization.");
                }

                if(!$user->hasAnyRole(Roles::ORG_ADMIN)){
                    $user->assignRole(Roles::ORG_ADMIN);
                }
                OrgAdmin::updateOrCreate(
                        [OrgAdmin::org_id => $organization->id, OrgAdmin::user_id=>$user->id],
                        [OrgAdmin::org_id => $organization->id, OrgAdmin::user_id=>$user->id]
                );
            }
        }

        //map using adminUserSlug
        if(!empty($request->adminUserSlug)){
            $adminUserObj = User::where(User::slug, $request->adminUserSlug)->firstOrFail();
            if(!$adminUserObj->hasAnyRole(Roles::ORG_ADMIN)){
                $adminUserObj->assignRole(Roles::ORG_ADMIN);
            }
            OrgAdmin::updateOrCreate(
                        [OrgAdmin::org_id => $organization->id, OrgAdmin::user_id=>$adminUserObj->id],
                        [OrgAdmin::org_id => $organization->id, OrgAdmin::user_id=>$adminUserObj->id]
                );
        }
        return $organization;
    }


    /**
     * Update Organization details
     * @param Request $request
     * @return array
     */
    public function updateOrganization(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $organization = Organization::where(Organization::slug, $request->orgSlug)->firstOrFail();            
            $organization = $this->setOrganizationFactory($request, $organization);
            DB::commit();
            $resp=array();
            $resp['data']   = array( "msg" => 'Organization updated successfully');
            $resp['code']   =  200;
            $resp['status']   =  "OK";
            return $resp;

        } catch (QueryException $e) {
            DB::rollback();
            return $this->throwError('Something went wrong, Failed to update Organization', 422);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }

    }

    /**
     * delete an Organization
     * @param Organization
     * @return array
     */
    public function deleteOrganization($request)
    {
        $organization = Organization::where(Organization::slug, $request->org_slug)->first();

        if (!$organization){
            return $this->throwError('No Organization Found', 422);
        }

        $organization->delete();
        $data =array();
        $data['data']   =  array( "msg" =>'Organization deleted successfully');
        $data['code']   =  200;
        $data['status'] =  "OK";

        return $data;
    }

    public function bulkDeleteOrg(Request $request)
    {
        DB::beginTransaction();
        $data =array();
        try {
            $organization = Organization::whereIn(Organization::slug, $request->orgSlugs);

            if ($organization->doesntExist()){
                return $this->throwError('No Organization Found', 422);
            }

            $organization->delete();

            DB::commit();

            $data['data']   =  array( "msg" =>'Organization deleted successfully');
            $data['code']   =  200;
            $data['status'] =  "OK";
            return $data;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $data['error']   =  array('msg' => $e->getMessage());
            $data['code']    =  422;
            $data['status']  = ResponseStatus::ERROR;
            return $data;
        } catch (\Exception $e) {
            DB::rollBack();
            $data['error']   =  array('msg' => $e->getMessage());
            $data['code']    =  422;
            $data['status']  = ResponseStatus::ERROR;
            return $data;
        }
    }

    public function saveOrgSettings(Request $request)
    {
        DB::beginTransaction();
        $data =array();
        try {
            $org = Organization::where(Organization::slug, $request->orgSlug)->first();

            if (empty($org)) {
                return $this->throwError('No Organization Found', 422);
            }

            //work report
            if ($request->has('workReport')) {
                $this->setWorkReportSettings($org, $request);
            }

            //Dashboard Message
            if ($request->dashboardMsg) {
                $org->{Organization::dashboard_message}      = $request->dashboardMsg;
            }

            if ($request->has('timezone') && $request->timezone) {
                $org->{Organization::timezone} = $request->timezone;
            }

            //Background Image
            $file       = $request->file('file');
            if (($file) && !($request->resetToDefault)) {
                $fileName   = $file->getClientOriginalName();

                $fileUpload = new FileUpload;
                $folder     = "{$request->orgSlug}/backgroundImage";
                $fileUpload->setPath($folder);
                $fileUpload->setFile($file);
                $fileUpload->s3Upload();

                $org->{Organization::bg_image_path} = $folder.'/'.$fileName;
                $org->{Organization::bg_image}      = $fileName;
                $org->{Organization::is_bg_default_img}      = false;
            }

            if ($request->resetToDefault) {
                $org->{Organization::bg_image_path} = 'bgDefaultImage/bg.jpeg';
                $org->{Organization::bg_image}      = 'bg.jpeg';
                $org->{Organization::is_bg_default_img}   = true;
            }

            $org->save();

            DB::commit();

            $data['data']   =  array( "msg" =>'Organization settings changed');
            $data['code']   =  200;
            $data['status'] =  "OK";
            return $data;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $data['error']   =  array('msg' => 'Something went wrong');
            $data['code']    =  422;
            $data['status']  = ResponseStatus::ERROR;
            return $data;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            $data['error']   =  array('msg' => 'Something went wrong, Failed to update Organization settings');
            $data['code']    =  422;
            $data['status']  = ResponseStatus::ERROR;
            return $data;
        }
    }

    public function fetchOrgSettings(Request $request)
    {
        try {
            $data = array();

            $org = DB::table(Organization::table)
                ->where(Organization::slug, $request->orgSlug);

            if ($org->doesntExist()) {
                return $this->throwError('No Organization Found', 422);
            }

            $orgSettings = $org->leftjoin(HrmWorkReportSettings::table, HrmWorkReportSettings::table. '.' .HrmWorkReportSettings::org_id, '=',
                Organization::table. '.id')
                ->leftjoin(HrmWorkReportFrequency::table, HrmWorkReportFrequency::table. '.id', '=',
                    HrmWorkReportSettings::table. '.' .HrmWorkReportSettings::report_frequency_id)
                ->select(
                    DB::raw('concat("'.$this->s3BasePath.'",'.Organization::table. '.'. Organization::bg_image_path.') as imageUrl'),
                    Organization::table. '.' .Organization::dashboard_message. '  as dashboardMsg',
                    Organization::table. '.' .Organization::timezone,
                    HrmWorkReportFrequency::frequency_name. ' as frequencyName'
                )
                ->first();


            $data['data']   =  array('dashboardSettings' => $orgSettings);
            $data['code']   =  200;
            $data['status'] =  "OK";
            return $data;

        } catch (QueryException $e) {
            return $this->throwError('Something went wrong, Failed to fetch partner dashboard data', 422);
        }
    }

    public function partnerDashboardSettings(Request $request)
    {
        DB::beginTransaction();
        $data =array();
        try {
            $partner = Partner::where(Partner::partner_slug, $request->partnerSlug)->first();

            if (empty($partner)) {
                return $this->throwError('Invalid partner', 422);
            }

            //Dashboard Message
            if ($request->dashboardMsg) {
                $partner->{Partner::dashboard_msg}      = $request->dashboardMsg;
            }

            if ($request->has('timeZone') && $request->timeZone) {
                DB::table(UserProfile::table)->where(UserProfile::user_id, $partner->{Partner::user_id})
                    ->update([UserProfile::timezone => $request->timeZone]);
            }

            //Background Image
            $file       = $request->file('file');
            if (($file) && !($request->resetToDefault)) {
                $fileName   = $file->getClientOriginalName();

                $fileUpload = new FileUpload;
                $folder     = "partnerBgImage/{$request->partnerSlug}";
                $fileUpload->setPath($folder);
                $fileUpload->setFile($file);
                $fileUpload->s3Upload();

                $partner->{Partner::bg_image_path} = $folder.'/'.$fileName;
                $partner->{Partner::bg_image}          = $fileName;
                $partner->{Partner::is_bg_default_img} = false;
            }

            if ($request->resetToDefault) {
                $partner->{Partner::bg_image_path} = 'bgDefaultImage/bg.jpeg';
                $partner->{Partner::bg_image}      = 'bg.jpeg';
                $partner->{Partner::is_bg_default_img}   = true;
            }

            $partner->save();

            DB::commit();

            $data['data']   =  array( "msg" =>'Partner Dashboard settings changed');
            $data['code']   =  200;
            $data['status'] =  "OK";
            return $data;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $data['error']   =  array('msg' => 'Something went wrong');
            $data['code']    =  422;
            $data['status']  = ResponseStatus::ERROR;
            return $data;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            $data['error']   =  array('msg' => 'Something went wrong, Failed to update Organization settings');
            $data['code']    =  422;
            $data['status']  = ResponseStatus::ERROR;
            return $data;
        }
    }

    public function fetchPartnerDashboardSettings(Request $request)
    {
        try {
            $data = array();

            $partner = DB::table(Partner::table)
                ->select(
                    DB::raw('concat("'.$this->s3BasePath.'",'.Partner::table. '.'. Partner::bg_image_path.') as imageUrl'),
                    Partner::table. '.' .Partner::dashboard_msg. ' as dashboardMsg',
                    UserProfile::table. '.' .UserProfile::timezone
                )
                ->leftjoin(UserProfile::table, UserProfile::table. '.' .UserProfile::user_id, '=', Partner::table. '.' .Partner::user_id)
                ->where(Partner::partner_slug, $request->partnerSlug)->first();

            if (empty($partner)) {
                return $this->throwError('Invalid partner', 422);
            }

            $data['data']   =  array('dashboardSettings' => $partner);
            $data['code']   =  200;
            $data['status'] =  "OK";
            return $data;

        } catch (QueryException $e) {
            return $this->throwError('Something went wrong, Failed to fetch partner dashboard data', 422);
        }
    }

    public function setWorkReportSettings($org, $request)
    {
        if (!in_array($request->workReport, ['daily', 'monthly', 'weekly', NULL])) {
            return $this->throwError('Error in work report tab', 422);
        }

        if (empty($request->workReport)) {
            $hrmWorkReportSetting = HrmWorkReportSettings::where(HrmWorkReportSettings::org_id, $org->id)
                ->whereNull(HrmWorkReportSettings::user_id)->delete();
            return;
        }

        $hrmWorkReportSetting = HrmWorkReportSettings::where(HrmWorkReportSettings::org_id, $org->id)
            ->whereNull(HrmWorkReportSettings::user_id)->first();

        if (empty($hrmWorkReportSetting))
            $hrmWorkReportSetting = new HrmWorkReportSettings();
        

        $reportFreq = DB::table(HrmWorkReportFrequency::table)
            ->select('id')
            ->where(HrmWorkReportFrequency::frequency_name, $request->workReport)->first();

        if (empty($reportFreq)) {
            return $this->throwError('Error Report Frequency', 422);
        }

        $hrmWorkReportSetting->{HrmWorkReportSettings::slug} = Utilities::getUniqueId();
        $hrmWorkReportSetting->{HrmWorkReportSettings::org_id} = $org->id;
        $hrmWorkReportSetting->{HrmWorkReportSettings::report_frequency_id} = $reportFreq->id;
        $hrmWorkReportSetting->save();
    }

    public function orgSettingWorkReport(Request $request)
    {
        $data =array();
        DB::beginTransaction();
        try {
            if (!in_array($request->workReport, ['daily', 'monthly', 'weekly', NULL])) {
                return $this->throwError('Error in work report tab', 422);
            }

            if (empty($request->workReport) && (empty($request->reportSettingsSlug))) {
                $hrmWorkReportSetting = HrmWorkReportSettings::where($request->reportSettingsSlug)->first();
                if (empty($hrmWorkReportSetting)) {
                    return $this->throwError('Error Work report setting slug', 422);
                }
            }

            if (empty($request->workReport) && !empty($request->reportSettingsSlug)) {
                $hrmWorkReportSetting = HrmWorkReportSettings::where($request->reportSettingsSlug)->delete();
            }

            if (!empty($request->workReport)) {
                $org = Organization::where(Organization::slug, $request->orgSlug)->first();

                if (empty($org)) {
                    return $this->throwError('No Organization Found', 422);
                }

                $reportFreq = DB::table(HrmWorkReportFrequency::table)
                    ->select('id')
                    ->where(HrmWorkReportFrequency::frequency_name, $request->workReport)->first();

                if (empty($reportFreq)) {
                    return $this->throwError('Error Report Frequency', 422);
                }


                if ($request->reportSettingsSlug)
                    $hrmWorkReportSetting = HrmWorkReportSettings::where(HrmWorkReportSettings::slug, $request->reportSettingsSlug)->first();
                else
                    $hrmWorkReportSetting = new HrmWorkReportSettings();

                /*HrmWorkReportSettings::updateOrCreate(
                    [HrmWorkReportSettings::slug => $request->reportSettingsSlug],
                    [
                        HrmWorkReportSettings::slug => !empty($request->reportSettingsSlug)?? Utilities::getUniqueId(),
                        HrmWorkReportSettings::org_id => $org->id,
                        HrmWorkReportSettings::report_frequency_id =>  $reportFreq->id
                    ]
                );*/


                $hrmWorkReportSetting->{HrmWorkReportSettings::slug}   = Utilities::getUniqueId();
                $hrmWorkReportSetting->{HrmWorkReportSettings::org_id} = $org->id;
                $hrmWorkReportSetting->{HrmWorkReportSettings::report_frequency_id} = $reportFreq->id;
                $hrmWorkReportSetting->save();
            }

            DB::commit();

            $data['data']   =  array('msg' => 'Work Report');
            $data['code']    =  201;
            $data['status']  = ResponseStatus::OK;
            return $data;
        } catch (QueryException $e) {
            DB::rollBack();
            $data['error']   =  array('msg' => 'Something went wrong');
            $data['code']    =  422;
            $data['status']  = ResponseStatus::ERROR;
            return $data;
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            $data['error']   =  array('msg' => 'Something went wrong, Failed to update Organization');
            $data['code']    =  422;
            $data['status']  = ResponseStatus::ERROR;
            return $data;
        }
    }
}