<?php

namespace Modules\OrgManagement\Repositories\License;

use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Common\Utilities\ResponseStatus;
use Modules\OrgManagement\Entities\Organization;
use Modules\Common\Entities\Vertical;
use Modules\Common\Entities\Country;
use Modules\OrgManagement\Entities\OrgLicenseRenewRequestMap;
use Modules\OrgManagement\Entities\OrgLicenseRequestsMap;
use Modules\OrgManagement\Transformers\OrgAdminLicenseResource;
use Modules\PartnerManagement\Entities\Partner;
use Modules\OrgManagement\Repositories\LicenseRepositoryInterface;
use Modules\Common\Utilities\Utilities;

use Modules\OrgManagement\Entities\OrgLicense;
use Modules\OrgManagement\Entities\OrgLicenseType;
use Modules\OrgManagement\Entities\OrgLicenseMapping;

use Modules\OrgManagement\Entities\OrgLicenseRequest;

use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\OrgAdmin;
use Illuminate\Support\Facades\DB;
use Modules\UserManagement\Entities\UserProfile;
use Modules\UserManagement\Entities\Roles;

class LicenseRepository implements LicenseRepositoryInterface
{

    protected $s3BasePath;
    public function __construct()
    {
        $this->s3BasePath = env('S3_PATH');
    }

    public function listLicense(Request $request)
    {
 
        try {
            $baseQuery = DB::table(OrgLicense::table)
                    ->join(Partner::table, Partner::table . ".id", '=', OrgLicense::table . '.' . OrgLicense::partner_id)
                    ->join(OrgLicenseType::table, OrgLicenseType::table . ".id", '=', OrgLicense::table . '.' . OrgLicense::license_type_id);

            $orgCount = $baseQuery->count();

            $orgData = $baseQuery
                ->skip(Utilities::getParams()['offset']) //$request['offset']
                ->take(Utilities::getParams()['perPage']) //$request['perPage']
                ->get();

            $paginatedData = Utilities::paginate($orgData, Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $orgCount)->toArray();

            $formatedData = $this->reformatData($paginatedData);

            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['data'] = $formatedData;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;

        } catch (QueryException $e) {
            return $this->throwError($e->getMessage(), 422);
        } catch (\Exception $e) {
            return $this->throwError($e->getMessage(), 422);
        }
    }

     public function reformatData($dataArr) {
        $dataArr['license'] = $dataArr['data'];
        unset($dataArr['data']);
        $dataArr = Utilities::unsetResponseData($dataArr);
        return $dataArr;
    }   

    public function getLicense($licenseSlug)
    {
        try {

            $licenseData = OrgLicense::where(OrgLicense::slug, $licenseSlug)
                ->first();
            
            if(empty($licenseData)){
                return $this->throwError('Invalid License', 422);
            }

            $formatedData = $licenseData;
            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['data'] = $formatedData;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;
        
        } catch (QueryException $e) {
            return $this->throwError('Something went wrong, Failed to fetch License', 422);
        } catch (\Exception $e) {
            return $this->throwError('Something went wrong, Failed to fetch License', 422);
        }
    }


    /**
     * Add License
     * @param Request $request
     * @return array
     */
    public function addLicense(Request $request)
    {
        DB::beginTransaction();
        try {
            $genrateLicenseKey = $this->generateLicenseKey();
            $license = new OrgLicense;
            $license->{OrgLicense::slug} = Utilities::getUniqueId();
            $license->{OrgLicense::key} = $genrateLicenseKey;
            $license = $this->setLicenseFactory($request, $license);
            DB::commit();
            $resp=array();
            $resp['data']   = array(
                "msg" => 'License created successfully',
                "licenseKey" => $genrateLicenseKey
            );
            $resp['code']   =  201;
            $resp['status']   =  "OK";
            return $resp;

        } catch (QueryException $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }

    }
    
    /* 
     * generate 8 character alphanumeric random number 
     */
    public function generateLicenseKey() {
        return sprintf( '%04x%04x',mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )); 
    }

    public function throwError($msg, $code) : array
    {
        $resp=array();
        $resp['error'] = array('msg'=>$msg);
        $resp['code']  = $code;
        $resp['status']   =  "ERROR";
        return $resp;
    }
    
    private function setLicenseFactory($request, $licenseObj){

        $licenseTypeObj = OrgLicenseType::where(OrgLicenseType::name, $request->licenseType)->firstOrFail();

        if ($request->orgSlug) {
            $org = Organization::where(Organization::slug, $request->orgSlug)->firstOrFail();
            $licenseObj->{OrgLicense::org_id} = $org->id;
        }
        
        $licenseObj->{OrgLicense::name} = $request->name;
        $licenseObj->{OrgLicense::license_type_id} = $licenseTypeObj->id;

        $partnerObj = Partner::where(Partner::partner_slug, $request->partnerSlug)->firstOrFail(); 
        
        $licenseObj->{OrgLicense::partner_id} = $partnerObj->id;

        $licenseObj->{OrgLicense::max_users} = $request->maxUsers;
        
        $licenseObj->save();

        return $licenseObj;
    }


    /**
     * Update License details
     * @param Request $request
     * @return array
     */
    public function updateLicense(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $license = OrgLicense::where(OrgLicense::slug, $request->LicenseSlug)->firstOrFail();            
            $license = $this->setLicenseFactory($request, $license);
            DB::commit();
            $resp=array();
            $resp['data']   = array( "msg" => 'License updated successfully');
            $resp['code']   =  200;
            $resp['status']   =  "OK";
            return $resp;

        } catch (QueryException $e) {
            DB::rollback();
            return $this->throwError('Something went wrong, Failed to update License', 422);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }

    }

    /**
     * delete an License
     * @param License
     * @return array
     */
    public function deleteLicense($request)
    {
        $license = OrgLicense::where(OrgLicense::slug, $request->licenseSlug)->first();

        if (!$license){
            return $this->throwError('No License Found', 422);
        }

        $license->delete();
        $data =array();
        $data['data']   =  array( "msg" =>'License deleted successfully');
        $data['code']   =  200;
        $data['status'] =  "OK";

        return $data;
    }
    
    ////////////////////////////////////////////////////////////////////////////
    /**
     * create a License Request
     * @param Request $request
     * @return array
     */
    public function createLicenseRequest(Request $request)
    {
        DB::beginTransaction();
        $user = Auth::user();
        try {
            $licenseRequest = new OrgLicenseRequest();
            $licenseRequest = $this->setLicenseRequestFactory($request, $licenseRequest, $user);
            DB::commit();
            $resp=array();
            $resp['data']   = array(
                "msg" => 'License Request created successfully',
                "licenseRequestSlug" => $licenseRequest->{OrgLicenseRequest::request_slug},
                "orgSlug" => $request->orgSlug
            );
            $resp['code']   =  201;
            $resp['status']   =  "OK";
            return $resp;

        } catch (QueryException $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }

    }
    
    /**
     * Update License Request details
     * @param Request $request
     * @return array
     */
    public function updateLicenseRequest(Request $request)
    {
        DB::beginTransaction();
        $user = Auth::user();
        try {

            $licenseRequest = OrgLicenseRequest::where(OrgLicenseRequest::request_slug, $request->licenseRequestSlug)->first(); 
            if(empty($licenseRequest)){
                throw new \Exception("licenseRequestSlug is invalid");
            }
            $licenseRequest = $this->setLicenseRequestFactory($request, $licenseRequest, $user, 'update');
            DB::commit();
            $resp=array();
            $resp['data']   = array( "msg" => 'LicenseRequest updated successfully');
            $resp['code']   =  200;
            $resp['status']   =  "OK";
            return $resp;

        } catch (QueryException $e) {
            DB::rollback();
            return $this->throwError('Something went wrong, Failed to update LicenseRequest', 422);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }

    }

    public function forwardLicenseRequest(Request $request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {

            $licenseRequest = OrgLicenseRequest::where(OrgLicenseRequest::request_slug, $request->licenseRequestSlug)->first();

            if(empty($licenseRequest)){
                throw new \Exception("licenseRequestSlug is invalid");
            }

            if($licenseRequest->{OrgLicenseRequest::is_forward}){
                throw new \Exception("licenseRequest already forwarded");
            }

            $licenseRequest->{OrgLicenseRequest::is_forward} = true;
            $licenseRequest->save();

            $licenseRequestForward = $licenseRequest->replicate();
            $licenseRequestForward->{OrgLicenseRequest::request_slug} = Utilities::getUniqueId();
            $licenseRequestForward->{OrgLicenseRequest::requesting_user_id} = $user->id;
            $licenseRequestForward->{OrgLicenseRequest::parent_request_id}  = $licenseRequest->id;
            $licenseRequestForward->{OrgLicenseRequest::to_user_group} = OrgLicenseRequest::TOSUPERADMINGROUP;
            $licenseRequestForward->{OrgLicenseRequest::is_forward}    = false;
            $licenseRequestForward->save();
            DB::commit();
            $resp=array();
            $resp['data']   = array( "msg" => 'LicenseRequest forwarded successfully');
            $resp['code']   =  200;
            $resp['status'] =  "OK";
            return $resp;

        } catch (QueryException $e) {
            DB::rollback();
            return $this->throwError('Something went wrong, Failed to update LicenseRequest', 422);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }
    }

    /**
     * action = create or update
     * @param $request
     * @param $licenseRequestObj
     * @param $loggedInUser
     * @param string $action
     * @return mixed
     * @throws \Exception
     */
    private function setLicenseRequestFactory($request, $licenseRequestObj, $loggedInUser, $action = 'create'){

        $licenseTypeObj = OrgLicenseType::where(OrgLicenseType::name, $request->licenseType)->first();
        if(empty($licenseTypeObj)){
            throw new \Exception("licenseType is invalid");
        }        
        $licenseRequestObj->{OrgLicenseRequest::license_type_id} = $licenseTypeObj->id;

        if ($action == 'create') {
            $licenseRequestObj->{OrgLicenseRequest::request_slug}    = Utilities::getUniqueId();
            $toUserGrp = ($loggedInUser->hasRole([Roles::PARTNER, Roles::PARTNER_MANAGER])) ? OrgLicenseRequest::TOSUPERADMINGROUP : OrgLicenseRequest::TOPARTNERGROUP;

            $licenseRequestObj->{OrgLicenseRequest::to_user_group} = $toUserGrp;
            $licenseRequestObj->{OrgLicenseRequest::requesting_user_id} = $loggedInUser->id;
        }

        if(empty($request->maxUsers)){
            throw new \Exception("maxUsers cannot be zero or empty!");
        }

        $licenseRequestObj->{OrgLicenseRequest::max_users} = $request->maxUsers;

        $orgObj = Organization::where(Organization::slug, $request->orgSlug)->first();

        if (!$orgObj) {
            throw new \Exception("orgSlug is invalid");
        }

        if(!empty($orgObj)){
            $licenseRequestObj->{OrgLicenseRequest::org_id} = $orgObj->id;
        }

        
        $licenseRequestObj->save();

        return $licenseRequestObj;
    }

    /**
     * delete an LicenseRequest
     * @param request
     * @return array
     */
    public function deleteLicenseRequest($request)
    {
        $licenseReq = OrgLicenseRequest::where(OrgLicenseRequest::request_slug, $request->licenseRequestSlug)->first();

        if (!$licenseReq){
            return $this->throwError('No LicenseRequest Found', 422);
        }

        $licenseReq->delete();
        $data =array();
        $data['data']   =  array( "msg" =>'LicenseRequest deleted successfully');
        $data['code']   =  200;
        $data['status'] =  "OK";

        return $data;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function bulkDeleteLicenseRequest($request)
    {
        DB::beginTransaction();
        $data =array();
        try {
            $licenseReq = OrgLicenseRequest::where(OrgLicenseRequest::request_slug, $request->licenseRequestSlugs);

            if ($licenseReq->doesntExist()){
                return $this->throwError('No License Request Found', 422);
            }

            $licenseReq->delete();

            DB::commit();

            $data['data']   =  array( "msg" =>'LicenseRequest deleted successfully');
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
    
    public function reformatLRData($dataArr) {
        $dataArr['license'] = $dataArr['data'];
        unset($dataArr['data']);
        $dataArr = Utilities::unsetResponseData($dataArr);
        return $dataArr;
    }

    public function cancelLicenseRequest(Request $request)
    {
        $licenseReq = OrgLicenseRequest::where(OrgLicenseRequest::request_slug, $request->licenseRequestSlug)->first();

        if (!$licenseReq){
            return $this->throwError('No LicenseRequest Found', 422);
        }

        //if request is a forwared request
        if ($licenseReq->{OrgLicenseRequest::parent_request_id}) {
            $licenseReq->{OrgLicenseRequest::is_cancelled}  = true;
            $licenseReq->save();

            $parentLicenseReq = OrgLicenseRequest::where('id', $licenseReq->{OrgLicenseRequest::parent_request_id})->first();
            $parentLicenseReq->{OrgLicenseRequest::is_forward}    = false;
            $parentLicenseReq->{OrgLicenseRequest::to_user_group} = OrgLicenseRequest::TOPARTNERGROUP;
            $parentLicenseReq->save();

        } else {
            $licenseReq->{OrgLicenseRequest::is_cancelled}  = true;
            $licenseReq->{OrgLicenseRequest::is_forward}    = false;
            $licenseReq->{OrgLicenseRequest::to_user_group} = OrgLicenseRequest::TOPARTNERGROUP;
            $licenseReq->save();
        }


        $data =array();
        $data['data']   =  array( "msg" =>'LicenseRequest cancelled');
        $data['code']   =  200;
        $data['status'] =  "OK";

        return $data;
    }

    public function listLicenseRequest(Request $request)
    {
 
        try {
            $baseQuery = DB::table(OrgLicenseRequest::table)
                    ->join(OrgLicenseType::table, OrgLicenseType::table . ".id", '=', OrgLicenseRequest::table . '.' . OrgLicenseRequest::license_type_id)
                    ->join(User::table, User::table . ".id", '=', OrgLicenseRequest::table . '.' . OrgLicenseRequest::requesting_user_id)
                    ->leftJoin(Organization::table, Organization::table . ".id", '=', OrgLicenseRequest::table . '.' . OrgLicenseRequest::org_id)
                    ->select(OrgLicenseRequest::request_slug.' AS licenseRequestSlug',
                            OrgLicenseType::table.'.'.OrgLicenseType::name.' AS licenseType', 
                            OrgLicenseRequest::max_users.' AS maxUsers',
                            OrgLicenseRequest::is_approved.' AS isApproved',
                            Organization::table.'.'.Organization::name.' AS orgName',
                            User::table.'.'.User::slug.' AS requestingUserSlug',
                            User::table.'.'.User::name.' AS requestingUserName',
                            User::table.'.'.User::email.' AS requestingUserEmail'
                            )
                    ->where(OrgLicenseRequest::table. '.' .OrgLicenseRequest::is_forward, false);

            $orgCount = $baseQuery->count();

            $orgData = $baseQuery
                ->skip(Utilities::getParams()['offset']) //$request['offset']
                ->take(Utilities::getParams()['perPage']) //$request['perPage']
                ->get();

            $paginatedData = Utilities::paginate($orgData, Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $orgCount)->toArray();

            $formatedData = $this->reformatLRData($paginatedData);

            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['data'] = $formatedData;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;

        } catch (QueryException $e) {
            return $this->throwError($e->getMessage(), 422);
        } catch (\Exception $e) {
            return $this->throwError($e->getMessage(), 422);
        }
    }
    
    public function getLicenseRequest(Request $request){
        try {

            $licenseRequestData = DB::table(OrgLicenseRequest::table)
                    ->join(OrgLicenseType::table, OrgLicenseType::table . ".id", '=', OrgLicenseRequest::table . '.' . OrgLicenseRequest::license_type_id)
                    ->join(User::table, User::table . ".id", '=', OrgLicenseRequest::table . '.' . OrgLicenseRequest::requesting_user_id)
                    ->leftJoin(Organization::table, Organization::table . ".id", '=', OrgLicenseRequest::table . '.' . OrgLicenseRequest::org_id)
                    ->select(OrgLicenseRequest::request_slug.' AS licenseRequestSlug',
                            OrgLicenseType::table.'.'.OrgLicenseType::name.' AS licenseType', 
                            OrgLicenseRequest::max_users.' AS maxUsers',
                            OrgLicenseRequest::is_approved.' AS isApproved',
                            Organization::table.'.'.Organization::name.' AS orgName',
                            User::table.'.'.User::slug.' AS requestingUserSlug',
                            User::table.'.'.User::name.' AS requestingUserName',
                            User::table.'.'.User::email.' AS requestingUserEmail'
                            )
                    ->where(OrgLicenseRequest::request_slug, $request->licenseRequestSlug)
                ->first();
            
            if(empty($licenseRequestData)){
                return $this->throwError('Invalid LicenseRequestSlug', 422);
            }

            $formatedData = $licenseRequestData;
            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['data'] = $formatedData;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;
        
        } catch (QueryException $e) {
            return $this->throwError('Something went wrong, Failed to fetch LicenseRequest', 422);
        } catch (\Exception $e) {
            return $this->throwError($e->getMessage(), 422);
        }        
    }

    /** TODO (remove OrgLicenseMapping if not needed)
     * @param Request $request
     * @return array
     */
    public function fetchAllPartnerLicense(Request $request)
    {
        try {
            $partner = DB::table(Partner::table)->select('id')->where(Partner::partner_slug, $request->partnerSlug)->first();

            if(empty($partner)){
                return $this->throwError('Invalid Partner', 422);
            }

            if (!in_array($request->tab, ['currentLicenses', 'expired', 'licenseRequests', 'adminLicenseRequests', 'approved'])) {
                return $this->throwError('Invalid Tab', 422);
            }

            if (in_array($request->tab, ['currentLicenses', 'expired'])) {
                $baseQuery = DB::table(OrgLicenseMapping::table)
                    ->select(Organization::table. '.' .Organization::name. ' as orgName',
                        OrgLicense::table. '.' .OrgLicense::key. ' as key',
                        OrgLicense::table. '.' .OrgLicense::max_users. ' as maxUsers',
                        OrgLicenseType::table. '.' .OrgLicenseType::name. ' as licenseType',
                        DB::raw("unix_timestamp(".OrgLicenseMapping::table . '.'.OrgLicenseMapping::start_date.") AS startedOn"),
                        DB::raw("unix_timestamp(".OrgLicenseMapping::table . '.'.OrgLicenseMapping::end_date.") AS expiresOn")
                    )
                    ->leftjoin(Organization::table, Organization::table. '.id', '=', OrgLicenseMapping::table. '.' .OrgLicenseMapping::org_id)
                    ->join(OrgLicense::table, OrgLicense::table. '.id', '=', OrgLicenseMapping::table. '.' .OrgLicenseMapping::license_id)
                    ->join(OrgLicenseType::table, OrgLicenseType::table. '.id', '=', OrgLicense::table. '.' .OrgLicense::license_type_id)
                    ->where(Organization::table. '.' .Organization::partner_id, $partner->id)
                    ->whereNull(Organization::table. '.deleted_at');

                if ($request->tab == 'currentLicenses') {
                    $baseQuery->where(OrgLicense::table. '.' .OrgLicense::license_status, true);
                    $baseQuery->addSelect(
                        DB::raw("TO_DAYS(". OrgLicenseMapping::table . '.'.OrgLicenseMapping::end_date . ") - TO_DAYS(NOW()) as onGoingdaysLeft")
                    );
                }

                if ($request->tab == 'expired') {
                    $baseQuery->where(OrgLicense::table. '.' .OrgLicense::license_status, false);
                    $baseQuery->whereDate(OrgLicenseMapping::table. '.' .OrgLicenseMapping::end_date, '<', now());
                }
            } else if (in_array($request->tab, ['licenseRequests', 'adminLicenseRequests'])) {
                $baseQuery = DB::table(OrgLicenseRequest::table)
                    ->join(OrgLicenseType::table, OrgLicenseType::table . ".id", '=', OrgLicenseRequest::table . '.' . OrgLicenseRequest::license_type_id)
                    ->leftJoin(Organization::table, Organization::table . ".id", '=', OrgLicenseRequest::table . '.' . OrgLicenseRequest::org_id)
                    ->select(OrgLicenseRequest::request_slug.' AS licenseRequestSlug',
                        OrgLicenseType::table.'.'.OrgLicenseType::name.' AS licenseType',
                        OrgLicenseRequest::max_users.' AS maxUsers',
                        OrgLicenseRequest::is_approved.' AS isApproved',
                        Organization::table.'.'.Organization::name.' AS orgName',
                        Organization::table.'.'.Organization::slug.' AS orgSlug',
                        DB::raw("unix_timestamp(".OrgLicenseRequest::table . '.'.OrgLicenseRequest::CREATED_AT.") AS requestsOn")
                    )
                    ->where(Organization::table. '.' .Organization::partner_id, $partner->id)
                    ->where(OrgLicenseRequest::table. '.' .OrgLicenseRequest::is_forward, false)
                    ->where(OrgLicenseRequest::table. '.' .OrgLicenseRequest::is_approved, false)
                    ->where(OrgLicenseRequest::table. '.' .OrgLicenseRequest::is_cancelled, false)
                    ->whereNull(Organization::table. '.deleted_at');

                if ($request->tab == 'licenseRequests') {
                    $baseQuery->where(OrgLicenseRequest::table. '.' .OrgLicenseRequest::to_user_group, OrgLicenseRequest::TOSUPERADMINGROUP);
                }

                if ($request->tab == 'adminLicenseRequests') {
                    $baseQuery->where(OrgLicenseRequest::table. '.' .OrgLicenseRequest::to_user_group, OrgLicenseRequest::TOPARTNERGROUP);
                }
            } else if ($request->tab == 'approved') {
                //DB::enableQueryLog();
                $baseQuery = DB::table(OrgLicense::table)
                    ->join(OrgLicenseType::table, OrgLicenseType::table . ".id", '=', OrgLicense::table . '.' . OrgLicense::license_type_id)
                    ->leftJoin(Organization::table, Organization::table . ".id", '=', OrgLicense::table . '.' . OrgLicense::org_id)
                    ->leftJoin(OrgLicenseMapping::table, OrgLicense::table . ".id", '=', OrgLicenseMapping::table . '.' . OrgLicenseMapping::license_id)
                    ->select(Organization::table. '.' .Organization::name. ' as orgName',
                        Organization::table. '.' .Organization::slug. ' as orgSlug',
                        OrgLicense::table. '.' .OrgLicense::key. ' as key',
                        OrgLicense::table. '.' .OrgLicense::max_users. ' as maxUsers',
                        OrgLicenseType::table. '.' .OrgLicenseType::name. ' as licenseType',
                        DB::raw("unix_timestamp(".OrgLicense::table . '.'.OrgLicense::CREATED_AT.") AS approvedOn")
                    )
                    ->where(Organization::table. '.' .Organization::partner_id, $partner->id)
                    ->whereNull(Organization::table. '.deleted_at')
                    ->whereNull(OrgLicenseMapping::table. '.id');
                /*$baseQuery->get();
                dd(DB::getQueryLog());*/
            }

            //sorting
            if (in_array($request->tab, ['currentLicenses', 'expired'])) {

                $baseQuery->addSelect(
                    DB::raw("unix_timestamp(".OrgLicenseRequest::table . '.'.OrgLicenseRequest::CREATED_AT.") AS requestedOn"),
                    DB::raw("unix_timestamp(".OrgLicense::table . '.'.OrgLicense::CREATED_AT.") AS approvedOn")
                )
                    ->leftjoin(OrgLicenseRequestsMap::table, OrgLicenseRequestsMap::table. '.' .OrgLicenseRequestsMap::org_license_id, '=', OrgLicense::table. '.id')
                    ->leftjoin(OrgLicenseRequest::table, OrgLicenseRequest::table. '.id', '=', OrgLicenseRequestsMap::table. '.' .OrgLicenseRequestsMap::license_request_id);

                if ($request->sortBy) {
                    $request->sortBy = $this->fetchAllLicenseSort($request->tab, $request->sortBy);
                } else {
                    $baseQuery = $baseQuery->orderBy(DB::raw('ABS(DATEDIFF(expiresOn, NOW()))'));
                }

            }

            if (in_array($request->tab, ['licenseRequests', 'adminLicenseRequests'])) {
                if ($request->sortBy) {
                    $request->sortBy = $this->fetchAllLicenseSort($request->tab, $request->sortBy);
                } else {
                    $baseQuery = $baseQuery->orderBy('requestsOn', 'asc');
                }
            }

            if ($request->tab == 'approved') {
                
                $baseQuery->addSelect(
                    DB::raw("unix_timestamp(".OrgLicenseRequest::table . '.'.OrgLicenseRequest::CREATED_AT.") AS requestedOn"),
                    DB::raw("unix_timestamp(".OrgLicense::table . '.'.OrgLicense::CREATED_AT.") AS approvedOn")
                )
                    ->leftjoin(OrgLicenseRequestsMap::table, OrgLicenseRequestsMap::table. '.' .OrgLicenseRequestsMap::org_license_id, '=', OrgLicense::table. '.id')
                    ->leftjoin(OrgLicenseRequest::table, OrgLicenseRequest::table. '.id', '=', OrgLicenseRequestsMap::table. '.' .OrgLicenseRequestsMap::license_request_id);

                if ($request->sortBy) {
                    $request->sortBy = $this->fetchAllLicenseSort($request->tab, $request->sortBy);
                } else {
                    $baseQuery = $baseQuery->orderBy('approvedOn', 'asc');
                }
            }

            $baseQuery = Utilities::sort($baseQuery);
            $baseQueryCount = $baseQuery->count();
            $partnerLicenseData = $baseQuery
                ->skip(Utilities::getParams()['offset']) //$request['offset']
                ->take(Utilities::getParams()['perPage']) //$request['perPage']
                ->get();

            $paginatedData = Utilities::paginate($partnerLicenseData, Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $baseQueryCount)->toArray();

            $formatedData = $this->reformatData($paginatedData);

            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['data'] = $formatedData;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;

        } catch (QueryException $e) {
            return $this->throwError('Something went wrong, Failed to fetch LicenseRequest', 422);
        } catch (\Exception $e) {
            return $this->throwError($e->getMessage(), 422);
        }
    }

    public function fetchAllLicenseSort($tab, $sortBy)
    {
        $key = [];
        if (in_array($tab, ['currentLicenses', 'expired'])) {
            $key = [
                'organization' => Organization::table. '.' .Organization::name,
                'key'       => OrgLicense::table. '.' .OrgLicense::key,
                'maxUsers'  => OrgLicense::table. '.' .OrgLicense::max_users,
                'type'      => OrgLicenseType::table. '.id',
                'expiresOn' => OrgLicenseMapping::table . '.'.OrgLicenseMapping::end_date
            ];
        } else if (in_array($tab, ['licenseRequests', 'adminLicenseRequests'])) {
            $key = [
                'organization' => Organization::table. '.' .Organization::name,
                'maxUsers'     => OrgLicenseRequest::table. '.' .OrgLicenseRequest::max_users,
                'type'      => OrgLicenseType::table. '.id',
                'requestedOn'  => OrgLicenseRequest::table . '.'.OrgLicenseRequest::CREATED_AT
            ];
        } else if (in_array($tab, ['approved'])) {
            $key = [
                'organization' => Organization::table. '.' .Organization::name,
                'key'       => OrgLicense::table. '.' .OrgLicense::key,
                'maxUsers'  => OrgLicense::table. '.' .OrgLicense::max_users,
                'type'      => OrgLicenseType::table. '.id',
                'approved'  => Organization::table . '.'.Organization::CREATED_AT
            ];
        }

        if (!array_key_exists($sortBy, $key)) {
            throw new \Exception('Invalid sort key', 422);
        }
        return $key[$sortBy];
    }

    public function renewLicense(Request $request)
    {
        DB::beginTransaction();

        try {
            $loggedUser = Auth::user();
            $license = OrgLicense::select(OrgLicense::table. '.id',
                OrgLicense::table. '.' .OrgLicense::name,
                OrgLicense::table. '.' .OrgLicense::key,
                OrgLicense::table. '.' .OrgLicense::max_users,
                OrgLicense::table. '.' .OrgLicense::org_id,
                OrgLicense::table. '.' .OrgLicense::license_type_id)
                ->join(OrgLicenseType::table, OrgLicenseType::table. '.id', '=', OrgLicense::table. '.' .OrgLicense::license_type_id)
                ->where(OrgLicense::table. '.' .OrgLicense::key, $request->licenseKey)->first();


            if(!$license) {
                return $this->throwError('Invalid License Key', 422);
            }

            //create orglicense request

            $orgLicenseRequest = new OrgLicenseRequest;
            $orgLicenseRequest->{OrgLicenseRequest::request_slug} = Utilities::getUniqueId();
            $orgLicenseRequest->{OrgLicenseRequest::license_type_id} = $license->{OrgLicense::license_type_id};
            $orgLicenseRequest->{OrgLicenseRequest::max_users}  = $request->maxUsers;
            $orgLicenseRequest->{OrgLicenseRequest::requesting_user_id} = $loggedUser->id;
            $orgLicenseRequest->{OrgLicenseRequest::org_id} = $license->{OrgLicense::org_id};
            $orgLicenseRequest->{OrgLicenseRequest::to_user_group}      = OrgLicenseRequest::TOSUPERADMINGROUP;
            $orgLicenseRequest->{OrgLicenseRequest::is_renewal} = true;

            $orgLicenseRequest->save();

            $orgLicenseRenewRequestMap = new OrgLicenseRenewRequestMap;
            $orgLicenseRenewRequestMap->{OrgLicenseRenewRequestMap::org_license_id} = $license->id;
            $orgLicenseRenewRequestMap->{OrgLicenseRenewRequestMap::license_request_id} = $orgLicenseRequest->id;
            $orgLicenseRenewRequestMap->{OrgLicenseRenewRequestMap::creator_id} = $loggedUser->id;

            $orgLicenseRenewRequestMap->save();
            DB::commit();

            $resp=array();
            $resp['data']   = array( "msg" => 'License renewal request is sent');
            $resp['code']   =  201;
            $resp['status'] =  "OK";
            return $resp;
        } catch (QueryException $e) {
            DB::rollback();
            return $this->throwError('Something went wrong, Failed to fetch Org License', 422);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }
    }

    public function activateLicense(Request $request)
    {
        $resp=array();
        DB::beginTransaction();
        try {
            $license = DB::table(OrgLicense::table)
                ->select(OrgLicense::table. '.id',
                    OrgLicenseType::table. '.' .OrgLicenseType::duration)
                ->join(OrgLicenseType::table, OrgLicenseType::table. '.id', '=', OrgLicense::table. '.' .OrgLicense::license_type_id)
                ->where(OrgLicense::table. '.' .OrgLicense::key, $request->licenseKey)->first();
            $org     = DB::table(Organization::table)->select('id')->where(Organization::slug, $request->orgSlug)->first();

            if (empty($license)){
                return $this->throwError('Invalid License', 422);
            }

            if (empty($org)) {
                return $this->throwError('Invalid Organization', 422);
            }

            $orgLicenseMap = DB::table(OrgLicenseMapping::table)->where(OrgLicenseMapping::license_id, $license->id)->exists();

            if ($orgLicenseMap) {
                return $this->throwError('License already activated', 422);
            }

            $orgLicenses = DB::table(Organization::table)
                ->join(OrgLicense::table, Organization::table. '.id', '=', OrgLicense::table. '.' .OrgLicense::org_id)
                ->where(OrgLicense::table. '.' .OrgLicense::org_id, $org->id)->get();

            $licenseStatus   = $orgLicenses->pluck('license_status')->sum();
            $upcomingLicense = $orgLicenses->pluck('upcoming_license')->sum();


            if ($licenseStatus >= 1 && $upcomingLicense == 0) {
                DB::table(OrgLicense::table)->where(OrgLicense::key, $request->licenseKey)
                    ->update([OrgLicense::upcoming_license => true]);

                $resp['data']['msg'] = 'This license can be used after the current license is expired only!';
                $resp['code'] = 200;
                $resp['status']   =  "OK";

            } else if ($licenseStatus >= 1 && $upcomingLicense >= 1) {
                $resp['data']['msg'] = 'Cannot activate more than one license!';
                $resp['code'] = 200;
                $resp['status']   =  "OK";
            } else {
                DB::table(OrgLicense::table)->where(OrgLicense::key, $request->licenseKey)
                    ->update([OrgLicense::license_status => true]);

                $orgLicenseMap = new OrgLicenseMapping;
                $orgLicenseMap->{OrgLicenseMapping::org_id}     = $org->id;
                $orgLicenseMap->{OrgLicenseMapping::license_id} = $license->id;
                $orgLicenseMap->{OrgLicenseMapping::start_date} = now();
                $orgLicenseMap->{OrgLicenseMapping::end_date}   = now()->addDays($license->duration);
                $orgLicenseMap->save();
                $resp['data']['msg'] = 'License activated successfully';
                $resp['code'] = 201;
                $resp['status']   =  "OK";
            }

            DB::commit();


            /*$resp['data']   = array( "msg" => 'License activated successfully');
            $resp['code']   =  201;
            $resp['status']   =  "OK";*/
            return $resp;
        } catch (QueryException $e) {
            DB::rollback();
            return $this->throwError('Something went wrong, Failed to fetch LicenseRequest', 422);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }


    }

    public function approveLicense(Request $request)
    {
        try {
            DB::beginTransaction();
            $licenseRequest = OrgLicenseRequest::where(OrgLicenseRequest::request_slug, $request->licenseRequestSlug)->first();

            if (empty($licenseRequest)) {
                return $this->throwError('Invalid License Request', 422);
            }

            $orgLicense     = OrgLicense::where(OrgLicense::key, $request->licenseKey)->first();

            if (empty($orgLicense)) {
                return $this->throwError('Invalid License', 422);
            }


            $orgLicenseRequestMapExists = DB::table(OrgLicenseRequestsMap::table)
                ->where(OrgLicenseRequestsMap::org_license_id, $orgLicense->id)
                ->where(OrgLicenseRequestsMap::license_request_id, $licenseRequest->id)
                ->exists();

            if ($orgLicenseRequestMapExists) {
                return $this->throwError('License already approved', 422);
            }

            $licenseRequest->{OrgLicenseRequest::is_approved} = true;
            $licenseRequest->save();

            $orgLicenseRequestMap = new OrgLicenseRequestsMap();
            $orgLicenseRequestMap->{OrgLicenseRequestsMap::org_license_id}     = $orgLicense->id;
            $orgLicenseRequestMap->{OrgLicenseRequestsMap::license_request_id} = $licenseRequest->id;
            $orgLicenseRequestMap->save();


            DB::commit();
            $resp = array();
            $resp['data']['msg'] = 'License activated successfully';
            $resp['code'] = 201;
            $resp['status']   =  "OK";
            return $resp;

        } catch (QueryException $e) {
            DB::rollback();
            return $this->throwError('Something went wrong, Failed to fetch LicenseRequest', 422);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }



    }

    public function fetchOrgLicense(Request $request)
    {
        try {

            $orgPartnerQuery = DB::table(Organization::table)
                ->join(Partner::table, Organization::table. '.' .Organization::partner_id, '=', Partner::table. '.id')
                ->join(User::table, Partner::table. '.' .Partner::user_id, '=', User::table. '.id')
                ->leftjoin(UserProfile::table, UserProfile::table. '.' .UserProfile::user_id, '=', User::table. '.id')
                ->where(Organization::table. '.' .Organization::slug, $request->orgSlug);

            $orgPartner = clone $orgPartnerQuery;


            $orgLicenseQuery = $orgPartnerQuery
                ->join(OrgLicense::table, OrgLicense::table. '.' .OrgLicense::org_id, '=', Organization::table. '.id')
                ->join(OrgLicenseType::table, OrgLicense::table. '.' .OrgLicense::license_type_id, '=', OrgLicenseType::table. '.id')
                ->leftjoin(OrgLicenseMapping::table, OrgLicenseMapping::table. '.' .OrgLicenseMapping::license_id, '=', OrgLicense::table. '.id')
                ->leftjoin(OrgLicenseRequestsMap::table, OrgLicenseRequestsMap::table. '.' .OrgLicenseRequestsMap::org_license_id, '=', OrgLicense::table. '.id')
                ->leftjoin(OrgLicenseRequest::table, OrgLicenseRequest::table. '.id', '=', OrgLicenseRequestsMap::table. '.' .OrgLicenseRequestsMap::license_request_id)
                ->groupBy(OrgLicense::table. '.id');

            $orgLicenseQueryCnt = $orgLicenseQuery->count();



            $orgLicense = $orgLicenseQuery->select(
                OrgLicense::table. '.id',
                Organization::table. '.id as orgId',
                OrgLicense::table. '.' .OrgLicense::license_status,
                OrgLicense::table. '.' .OrgLicense::key,
                OrgLicense::table. '.' .OrgLicense::max_users,
                OrgLicenseType::table. '.' .OrgLicenseType::name,
                User::table. '.' .User::name,
                DB::raw("unix_timestamp(" . OrgLicenseMapping::table. '.' .OrgLicenseMapping::end_date .") as expiresOn"),
                DB::raw("unix_timestamp(" . OrgLicenseMapping::table. '.' .OrgLicenseMapping::start_date .") as startedOn"),
                DB::raw("unix_timestamp(" . OrgLicenseRequest::table. '.' .OrgLicenseRequest::CREATED_AT .") as requestedOn")
            )->where(OrgLicense::license_status, true)
            ->first();

            if (!empty($orgLicense)) {
                $orgLicense->licenseCnt = $orgLicenseQueryCnt;
            }


            //dd($orgPartner->toSql());
            $orgPartnerDetails = $orgPartner->select(
                Organization::table. '.' .Organization::name. ' as orgName',
                User::table. '.' .User::name. ' as partnerName',
                DB::raw('concat("'.$this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as partnerImage')
            )->first();

            //if ($orgLicenseQueryCnt == 0 ) $orgLicenseQueryCnt = null;


            $orgAdminLicense = new OrgAdminLicenseResource($orgLicense, $orgLicenseQueryCnt, $orgPartnerDetails);

            $resp = array();
            $resp['data'] = $orgAdminLicense;
            $resp['code'] = 200;
            $resp['status']   =  "OK";
            return $resp;

        } catch (QueryException $e) {
            return $this->throwError('Something went wrong, Failed to fetch Organization admin license details', 422);
        } catch (\Exception $e) {
            return $this->throwError($e->getMessage(), 422);
        }
    }
}