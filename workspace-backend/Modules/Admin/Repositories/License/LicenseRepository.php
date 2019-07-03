<?php

namespace Modules\Admin\Repositories\License;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Repositories\LicenseRepositoryInterface;
use Modules\Common\Utilities\Utilities;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgLicense;
use Modules\OrgManagement\Entities\OrgLicenseMapping;
use Modules\OrgManagement\Entities\OrgLicenseRequest;
use Modules\OrgManagement\Entities\OrgLicenseRequestsMap;
use Modules\OrgManagement\Entities\OrgLicenseType;
use Modules\PartnerManagement\Entities\Partner;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;

class LicenseRepository implements LicenseRepositoryInterface
{
    protected $s3BasePath;
    public function __construct()
    {
        $this->s3BasePath = env('S3_PATH');
    }

    /**
     * Fetch All License
     * @param Request $request
     * @return array
     */
    public function fetchAllLicense(Request $request)
    {
        try {
            if (!in_array($request->tab, ['currentLicenses', 'expired', 'licenseRequests', 'adminLicenseRequests', 'awaitingLicenseAct'])) {
                return $this->throwError('Invalid Tab', 422);
            }

            $tabIsRequest = false;
            if (in_array($request->tab, ['currentLicenses', 'expired', 'awaitingLicenseAct'])) {
                $baseQuery = DB::table(OrgLicense::table)
                    ->select(
                        OrgLicenseType::table.'.'.OrgLicenseType::name.' AS licenseType',
                        OrgLicense::table. '.' .OrgLicense::key. ' as key',
                        OrgLicense::table. '.' .OrgLicense::max_users.' AS maxUsers',
                        User::table.'.'.User::name.' AS partnerName',
                        DB::raw('concat("'.$this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as partnerImage'),
                        Organization::table.'.'.Organization::name.' AS orgName',
                        Organization::table.'.'.Organization::slug.' AS orgSlug',
                        DB::raw("unix_timestamp(".OrgLicenseMapping::table . '.'.OrgLicenseMapping::start_date.") AS startedOn"),
                        DB::raw("unix_timestamp(".OrgLicenseRequest::table . '.'.OrgLicenseRequest::CREATED_AT.") AS requestedOn"),
                        DB::raw("unix_timestamp(".OrgLicense::table . '.'.OrgLicense::CREATED_AT.") AS approvedOn"),
                        DB::raw("unix_timestamp(".OrgLicenseMapping::table . '.'.OrgLicenseMapping::end_date.") AS expiresOn")
                    )
                    ->join(OrgLicenseType::table, OrgLicenseType::table. '.id', '=', OrgLicense::table. '.' .OrgLicense::license_type_id)
                    ->join(OrgLicenseMapping::table, OrgLicenseMapping::table. '.' .OrgLicenseMapping::license_id, '=', OrgLicense::table. '.id')
                    ->join(Organization::table, Organization::table. '.id', '=', OrgLicense::table. '.' .OrgLicense::org_id)
                    ->join(Partner::table, Partner::table. '.id', '=', OrgLicense::table. '.' .OrgLicense::partner_id)
                    ->join(User::table, User::table. '.id', '=', Partner::table. '.' .Partner::user_id)
                    ->leftjoin(UserProfile::table, UserProfile::table. '.' .UserProfile::user_id, '=', User::table. '.id')
                    ->leftjoin(OrgLicenseRequestsMap::table, OrgLicenseRequestsMap::table. '.' .OrgLicenseRequestsMap::org_license_id, '=', OrgLicense::table. '.id')
                    ->leftjoin(OrgLicenseRequest::table, OrgLicenseRequest::table. '.id', '=', OrgLicenseRequestsMap::table. '.' .OrgLicenseRequestsMap::license_request_id)
                    ->whereNull(Organization::table. '.deleted_at');

                if ($request->tab == 'currentLicenses') {
                    $baseQuery->where(OrgLicense::table. '.' .OrgLicense::license_status, true)
                        ->where(OrgLicenseRequest::table. '.' .OrgLicenseRequest::is_approved, true);
                    $baseQuery->addSelect(
                        DB::raw("TO_DAYS(". OrgLicenseMapping::table . '.'.OrgLicenseMapping::end_date . ") - TO_DAYS(NOW()) as onGoingdaysLeft")
                    );

                    if ($request->sortBy) {
                        $request->sortBy = $this->fetchAllLicenseSort($request->tab, $request->sortBy);
                    } else {
                        $baseQuery = $baseQuery->orderBy(DB::raw('ABS(DATEDIFF(expiresOn, NOW()))'));
                    }
                }

                if ($request->tab == 'expired') {
                    $baseQuery->where(OrgLicense::table. '.' .OrgLicense::license_status, false)
                        ->whereDate(OrgLicenseMapping::table. '.' .OrgLicenseMapping::end_date, '<', now());

                    if ($request->sortBy) {
                        $request->sortBy = $this->fetchAllLicenseSort($request->tab, $request->sortBy);
                    } else {
                        $baseQuery = $baseQuery->orderBy(DB::raw('ABS(DATEDIFF(expiresOn, NOW()))'));
                    }
                }

                if ($request->tab == 'awaitingLicenseAct') {
                    $baseQuery->where(OrgLicense::table. '.' .OrgLicense::license_status, false)
                        ->where(OrgLicenseRequest::table. '.' .OrgLicenseRequest::is_approved, false);

                    if ($request->sortBy) {
                        $request->sortBy = $this->fetchAllLicenseSort($request->tab, $request->sortBy);
                    } else {
                        $baseQuery = $baseQuery->orderBy('approvedOn', 'asc');
                    }
                }

            } else if ($request->tab == 'licenseRequests') {
                $tabIsRequest = true;
                $baseQuery = DB::table(OrgLicenseRequest::table)
                    ->select(
                        OrgLicenseRequest::table. '.' .OrgLicenseRequest::request_slug.' as licenseRequestSlug',
                        OrgLicenseType::table.'.'.OrgLicenseType::name.' AS licenseType',
                        OrgLicenseRequest::max_users.' AS maxUsers',
                        Organization::table.'.'.Organization::name.' AS orgName',
                        User::table.'.'.User::name.' AS partnerName',
                        DB::raw('concat("'.$this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as partnerImage'),
                        Partner::table.'.'.Partner::partner_slug.' AS partnerSlug',
                        Organization::table.'.'.Organization::slug.' AS orgSlug',
                        DB::raw("unix_timestamp(".OrgLicenseRequest::table . '.'.OrgLicenseRequest::CREATED_AT.") AS requestsOn")
                    )
                    ->join(OrgLicenseType::table, OrgLicenseType::table . ".id", '=', OrgLicenseRequest::table . '.' . OrgLicenseRequest::license_type_id)
                    //->join(User::table, User::table . ".id", '=', OrgLicenseRequest::table . '.' . OrgLicenseRequest::requesting_user_id)
                    ->join(Organization::table, Organization::table . ".id", '=', OrgLicenseRequest::table . '.' . OrgLicenseRequest::org_id)
                    ->join(Partner::table, Partner::table . ".id", '=', Organization::table . '.' . Organization::partner_id)
                    ->join(User::table, User::table. '.id', '=', Partner::table. '.' .Partner::user_id)
                    ->leftjoin(UserProfile::table, UserProfile::table. '.' .UserProfile::user_id, '=', User::table. '.id')
                    ->where(OrgLicenseRequest::to_user_group, OrgLicenseRequest::TOSUPERADMINGROUP)
                    ->where(OrgLicenseRequest::is_forward, false)
                    ->where(OrgLicenseRequest::is_approved, false)
                    ->where(OrgLicenseRequest::is_cancelled, false);

                if ($request->sortBy) {
                    $request->sortBy = $this->fetchAllLicenseSort($request->tab, $request->sortBy);
                } else {
                    $baseQuery = $baseQuery->orderBy('requestsOn', 'asc');
                }
            }

            if ($request->filter) {
                //type trial,annual
                $baseQuery->where(function ($q) use ($request) {
                    if ($request->filter['trial']) {
                        $q->where(OrgLicenseType::table. '.'. OrgLicenseType::name, OrgLicenseType::Trial);
                    }

                    if ($request->filter['annual']) {
                        $q->orWhere(OrgLicenseType::table. '.'. OrgLicenseType::name, OrgLicenseType::Annual);
                    }
                });

                //user min,max filter
                $baseQuery->Where(function ($q) use ($request, $tabIsRequest) {
                    $isRequestTableSwitch = ($tabIsRequest)? OrgLicenseRequest::table. '.'. OrgLicenseRequest::max_users : OrgLicense::table. '.'. OrgLicense::max_users;

                    if (!empty($request->filter['minUsers']) && !empty($request->filter['maxusers'])) {
                        $q->WhereBetween($isRequestTableSwitch, [$request->filter['minUsers'], $request->filter['maxusers']]);
                    } else if (!empty($request->filter['minUsers'])) {
                        $q->where($isRequestTableSwitch, '>=', $request->filter['minUsers']);
                    } else if (!empty($request->filter['maxusers'])) {
                        $q->where($isRequestTableSwitch, '<=', $request->filter['maxusers']);
                    }
                });

                //user min,max filter
                $baseQuery->Where(function ($q) use ($request, $tabIsRequest) {
                    $isRequestTableSwitch = ($tabIsRequest)? OrgLicenseRequest::table. '.'. OrgLicenseRequest::max_users : OrgLicense::table. '.'. OrgLicense::max_users;

                    if (!empty($request->filter['minUsers']) && !empty($request->filter['maxusers'])) {
                        $q->WhereBetween($isRequestTableSwitch, [$request->filter['minUsers'], $request->filter['maxusers']]);
                    } else if (!empty($request->filter['minUsers'])) {
                        $q->where($isRequestTableSwitch, '>=', $request->filter['minUsers']);
                    } else if (!empty($request->filter['maxusers'])) {
                        $q->where($isRequestTableSwitch, '<=', $request->filter['maxusers']);
                    }
                });

                //partner filter
                $baseQuery->Where(function ($q) use ($request, $tabIsRequest) {

                    if (isset($request->filter['partnerSlug'])) {
                        $q->where(Partner::table. '.'. Partner::partner_slug, $request->filter['partnerSlug']);
                    }
                });
            }

            $baseQueryCount = $baseQuery->count();
            $baseQuery = Utilities::sort($baseQuery);
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
        $key = array();
        if (in_array($tab, ['currentLicenses', 'expired'])) {
            $key = [
                'partner'   => User::table. '.' .User::name,
                'organization' => Organization::table. '.' .Organization::name,
                'key'       => OrgLicense::table. '.' .OrgLicense::key,
                'maxUsers'  => OrgLicense::table. '.' .OrgLicense::max_users,
                'type'      => OrgLicenseType::table. '.id',
                'expiresOn' => OrgLicenseMapping::table . '.'.OrgLicenseMapping::end_date
            ];
        } else if (in_array($tab, ['licenseRequests'])) {
            $key = [
                'partner'   => User::table. '.' .User::name,
                'organization' => Organization::table. '.' .Organization::name,
                'maxUsers'     => OrgLicenseRequest::table. '.' .OrgLicenseRequest::max_users,
                'type'      => OrgLicenseType::table. '.id',
                'requestedOn'  => OrgLicenseRequest::table . '.'.OrgLicenseRequest::CREATED_AT
            ];
        } else if ($request->tab == 'awaitingLicenseAct') {
            $key = [
                'partner'   => User::table. '.' .User::name,
                'organization' => Organization::table. '.' .Organization::name,
                'key' => OrgLicense::table. '.' .OrgLicense::key,
                'maxUsers'     => OrgLicense::table. '.' .OrgLicense::max_users,
                'type'      => OrgLicenseType::table. '.id',
                'requestedOn'  => OrgLicenseRequest::table . '.'.OrgLicenseRequest::CREATED_AT
            ];
        }

        return $key[$sortBy];
    }

    public function reformatData($dataArr) {
        $dataArr['license'] = $dataArr['data'];
        unset($dataArr['data']);
        $dataArr = Utilities::unsetResponseData($dataArr);
        return $dataArr;
    }

    public function throwError($msg, $code) : array
    {
        $resp=array();
        $resp['error'] = array('msg'=>$msg);
        $resp['code']  = $code;
        $resp['status']   =  "ERROR";
        return $resp;
    }

    public function generateLicenseKey() {
        return sprintf( '%04x%04x',mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ));
    }

    public function approveLicenseRequest(Request $request)
    {
        try {
            $licenseRequest = DB::table(OrgLicenseRequest::table)
                ->where(OrgLicenseRequest::table. '.' .OrgLicenseRequest::request_slug, $request->licenseRequestSlug)
                ->first();

            if (empty($licenseRequest)) {
                return $this->throwError('Invalid License Request', 422);
            }

            $this->setLicenseFactory($request, $licenseRequest);

            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['data'] = array('msg' => 'Created and Approved requested License');
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;
        } catch (QueryException $e) {
            return $this->throwError('Something went wrong, Failed to fetch LicenseRequest', 422);
        } catch (\Exception $e) {
            return $this->throwError($e->getMessage(), 422);
        }
    }

    private function setLicenseFactory($request, $licenseReqObj)
    {

        $licenseObj = new OrgLicense;
        $licenseObj->{OrgLicense::name} = 'DUMMY';
        $licenseObj->{OrgLicense::slug} = Utilities::getUniqueId();
        $licenseObj->{OrgLicense::key}  = $this->generateLicenseKey();
        $licenseObj->{OrgLicense::license_type_id} = $licenseReqObj->{OrgLicenseRequest::license_type_id};

        $partnerObj = Partner::where(Partner::partner_slug, $request->partnerSlug)->firstOrFail();

        $licenseObj->{OrgLicense::partner_id} = $partnerObj->id;
        $licenseObj->{OrgLicense::org_id}     = $licenseReqObj->{OrgLicenseRequest::org_id};
        $licenseObj->{OrgLicense::max_users}  = $licenseReqObj->{OrgLicenseRequest::max_users};

        $licenseObj->save();

        return $licenseObj;
    }

    public function licenseHistory(Request $request)
    {
        $orgLicenseHistoryBaseQuery = DB::table(OrgLicense::table)
            ->select(
                Organization::table. '.' .Organization::name. ' as orgName',
                OrgLicense::table. '.' .OrgLicense::key. ' as licenseKey',
                Partner::table. '.' .Partner::name. ' as partnerName',
                OrgLicense::table. '.' .OrgLicense::max_users. ' as maxUsers',
                DB::raw("unix_timestamp(".OrgLicenseMapping::table . '.'.OrgLicenseMapping::start_date.") AS startedOn"),
                DB::raw("unix_timestamp(".OrgLicenseRequest::table . '.'.OrgLicenseRequest::CREATED_AT.") AS requestedOn"),
                DB::raw("unix_timestamp(".OrgLicense::table . '.'.OrgLicense::CREATED_AT.") AS approvedOn"),
                DB::raw("unix_timestamp(".OrgLicenseMapping::table . '.'.OrgLicenseMapping::end_date.") AS expiresOn"),
                OrgLicense::table. '.' .OrgLicense::license_status. ' as licenseStatus',
                OrgLicense::table. '.' .OrgLicense::upcoming_license. ' as upcomingLicense'
            )
            ->join(OrgLicenseMapping::table, OrgLicenseMapping::table. '.' .OrgLicenseMapping::license_id, '=', OrgLicense::table. '.id')
            ->join(OrgLicenseType::table, OrgLicenseType::table. '.id', '=', OrgLicense::table. '.' .OrgLicense::license_type_id)
            ->join(Partner::table, Partner::table. '.id', '=', OrgLicense::table. '.' .OrgLicense::partner_id)
            ->join(Organization::table, Organization::table. '.id', '=', OrgLicense::table. '.' .OrgLicense::org_id)
            ->leftjoin(OrgLicenseRequestsMap::table, OrgLicenseRequestsMap::table. '.' .OrgLicenseRequestsMap::org_license_id, '=', OrgLicense::table. '.id')
            ->leftjoin(OrgLicenseRequest::table, OrgLicenseRequest::table. '.id', '=', OrgLicenseRequestsMap::table. '.' .OrgLicenseRequestsMap::license_request_id)
            ->where(Organization::table. '.' .Organization::slug, $request->orgSlug);

        $orgLicenseHistoryCount = $orgLicenseHistoryBaseQuery->count();
        $orgLicenseHistory = $orgLicenseHistoryBaseQuery
            ->skip(Utilities::getParams()['offset']) //$request['offset']
            ->take(Utilities::getParams()['perPage']) //$request['perPage']
            ->get();


        $orgLicenseHistory = $orgLicenseHistory->mapWithKeys(function ($licenses) {
            $licenses->licenseStatusStr = 'expired';
            if (!$licenses->licenseStatus &&  $licenses->upcomingLicense) {
                $licenses->licenseStatusStr = 'upcoming';
            } else if ($licenses->licenseStatus &&  !$licenses->upcomingLicense) {
                $licenses->licenseStatusStr = 'current';
            }
            return $licenses;
        });



        $paginatedData = Utilities::paginate($orgLicenseHistory, Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $orgLicenseHistoryCount)->toArray();

        $formatedData = $this->reformatData($paginatedData);

        $responseData = array();
        $responseData['status'] = 'OK';
        $responseData['data'] = $formatedData;
        $responseData['code'] = Response::HTTP_OK;
        return $responseData;


    }

    /**
     * fetch all partners for create license
     * @param Request $request
     * @return array
     */
    public function fetchAllPartnersList(Request $request)
    {
        try {
            $partnersQuery = DB::table(Partner::table)
                ->select(
                    User::table.'.'.User::name.' AS partnerName',
                    Partner::table.'.'.Partner::partner_slug.' AS partnerSlug',
                    User::table.'.'.User::email.' AS partnerEmail',
                    DB::raw('concat("'.$this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as partnerImage')
                )
                ->join(User::table, User::table. '.id', '=', Partner::table. '.' .Partner::user_id)
                ->leftjoin(UserProfile::table, User::table. '.id', '=', UserProfile::table. '.' .UserProfile::user_id)
                ->orderBy(Partner::table. '.' .Partner::name, 'asc')
                ->get();

            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['data'] = $partnersQuery;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;
        } catch (QueryException $e) {
            return $this->throwError($e->getMessage(), 422);
        } catch (\Exception $e) {
            return $this->throwError($e->getMessage(), 422);
        }
    }

    public function fetchAllOrgsFromPartner(Request $request)
    {
        try {

            if (empty($request->partnerSlug)) {
                return $this->throwError('invalid partner slug', 422);
            }

            $partner = DB::table(Partner::table)
                ->select('id')
                ->where(Partner::partner_slug, $request->partnerSlug)->first();


            if (empty($partner)) {
                return $this->throwError('invalid partner slug', 422);
            }

            $orgListQuery = DB::table(Organization::table)
                ->select(
                    Organization::table.'.'.Organization::name.' AS orgName',
                    Organization::table.'.'.Organization::slug.' AS orgSlug'
                )
                ->where(Organization::table. '.' .Organization::partner_id, $partner->id)
                ->orderBy(Organization::table. '.' .Organization::name, 'asc')
                ->get();

            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['data'] = $orgListQuery;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;
        } catch (QueryException $e) {
            return $this->throwError($e->getMessage(), 422);
        } catch (\Exception $e) {
            return $this->throwError($e->getMessage(), 422);
        }
    }

}