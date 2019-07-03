<?php

namespace Modules\Admin\Repositories\Partner;


use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Entities\SubadminRoles;
use Modules\Admin\Repositories\PartnerRepositoryInterface;
use Modules\Common\Entities\Country;
use Modules\Common\Entities\Vertical;
use Modules\Common\Utilities\Utilities;
use Modules\OrgManagement\Entities\OrgLicense;
use Modules\OrgManagement\Jobs\VerifyUser;
use Modules\PartnerManagement\Entities\Partner;
use Modules\PartnerManagement\Entities\PartnerManager;
use Modules\PartnerManagement\Entities\PartnerManagerPartnerMap;
use Modules\UserManagement\Entities\Permissions;
use Modules\UserManagement\Entities\Roles;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;
use Symfony\Component\HttpKernel\Profiler\Profile;

class PartnerRepository implements PartnerRepositoryInterface
{
    protected $s3BasePath;
    public function __construct()
    {
        $this->s3BasePath = env('S3_PATH');
    }

    public function createOrDeletePartner(Request $request)
    {
        try {
            $responseData = array();

            if (!in_array($request->action, ['create', 'delete'])) {
                throw new \Exception('Invalid action');
            }

            DB::beginTransaction();

            if ($request->action == 'create') {

                $user = DB::table(User::table)->where(User::email, $request->email)->exists();
                if ($user) {
                    throw new \Exception('User already exists, try another email');
                }

                //user
                $user = new User;
                $user->{User::slug}     = Utilities::getUniqueId();
                $user->{User::name}     = $request->parnerName;
                $user->{User::email}    = $request->email;
                $tempPassword     = "qwerty@123";
                $user->{User::password} = bcrypt($tempPassword);

                $user->remember_token   = $user->generateVerificationCode();
                $user->save();

                //userprofile
                $userProfile = new UserProfile([
                    UserProfile::first_name => $request->parnerName,
                    UserProfile::user_id  => $user->id
                ]);

                $user->userProfile()->save($userProfile);

                $country = DB::table(Country::table)
                    ->select('id')
                    ->where(Country::slug, $request->countrySlug)
                    ->first();

                if (!$country) {
                    throw new \Exception('Invalid Country');
                }

                /*$verical = DB::table(Vertical::table)
                    ->select('id')
                    ->where(Vertical::slug, $request->verticalSlug)
                    ->first();*/


                //partner
                $partner = new Partner;
                $partner->{Partner::partner_slug} = Utilities::getUniqueId();
                $partner->{Partner::name} = $request->parnerName;
                $partner->{Partner::phone} = $request->phone;
                $partner->{Partner::country_id} = $country->id;
                $partner->{Partner::user_id} = $user->id;
                $partner->save();

                $user->assignRole(Roles::PARTNER);

                dispatch(new VerifyUser($user));
                $responseData['data']['msg'] = 'Partner created sucessfully';

            } else if ($request->action == 'delete') {
                if (empty($request->partnerSlug)) {
                    throw new \Exception('Invalid partner slug');
                }

                $partner = DB::table(Partner::table)
                    ->where(Partner::partner_slug, $request->partnerSlug);

                if ($partner->doesntExist()) {
                    throw new \Exception('Invalid partner slug');
                }

                $partner->delete();
                $responseData['data']['msg'] = 'Partner deleted sucessfully';
            }


            DB::commit();

            $responseData['status'] = 'OK';
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;

        } catch (QueryException $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }
    }

    public function fetchAllPartners(Request $request)
    {
        try {
            $partnersQuery = DB::table(Partner::table)
                ->select(
                    User::table.'.'.User::name.' AS partnerName',
                    Partner::table.'.'.Partner::partner_slug.' AS partnerSlug',
                    User::table.'.'.User::email.' AS partnerEmail',
                    DB::raw('concat("'.$this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as partnerImage'),
                    DB::raw("COUNT(". OrgLicense::table. ".id" .") as orgCount")
                )

                ->join(OrgLicense::table, OrgLicense::table. '.' .OrgLicense::partner_id, '=', Partner::table. '.id')
                ->join(User::table, User::table. '.id', '=', Partner::table. '.' .Partner::user_id)
                ->leftjoin(UserProfile::table, User::table. '.id', '=', UserProfile::table. '.' .UserProfile::user_id)
                ->groupBy(OrgLicense::table. '.' .OrgLicense::partner_id);

            $partnersQueryCount = $partnersQuery->count();
            $partnersQuery = Utilities::sort($partnersQuery);
            $partners = $partnersQuery
                ->skip(Utilities::getParams()['offset']) //$request['offset']
                ->take(Utilities::getParams()['perPage']) //$request['perPage']
                ->get();

            $paginatedData = Utilities::paginate($partners, Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $partnersQueryCount)->toArray();
            $formattedData = $this->reformatData($paginatedData);

            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['data'] = $formattedData;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;
        } catch (QueryException $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }
    }

    /**@TODO - sorting is peding
     * @param Request $request
     * @return array
     */
    public function fetchSubadmin(Request $request)
    {
        $rolesArr = [Roles::PARTNER_MANAGER, Roles::COMMUNICATION_MANAGER,
            Roles::LICENSE_MANAGER, Roles::WORKFLOW_MANAGER, Roles::FORM_MANAGER, Roles::PERMISSIONS_MANAGER];

        try {
            $subadminUsers = User::role($rolesArr)
                ->select(
                    User::table. '.' .User::name. ' as userName',
                    User::table. '.' .User::slug. ' as userSlug',
                    DB::raw('concat("'.$this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as userImage'),
                    User::table. '.' .User::email,
                    UserProfile::table. '.' .UserProfile::phone,
                    DB::raw("LCASE(REPLACE(um_roles.name, '_', ' ')) as role")

                )
                ->join('um_user_roles', User::table. '.id', '=', 'model_id')
                ->join('um_roles', 'um_user_roles.role_id', '=', 'um_roles.id')
                ->leftjoin(UserProfile::table, UserProfile::table. '.' .UserProfile::user_id, '=', User::table. '.id');

            $subadminUsersCnt = $subadminUsers->count();

            //$partnersQuery = Utilities::sort($partnersQuery);
            $subadmins = $subadminUsers
                ->skip(Utilities::getParams()['offset']) //$request['offset']
                ->take(Utilities::getParams()['perPage']) //$request['perPage']
                ->get();

            $paginatedData = Utilities::paginate($subadmins, Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $subadminUsersCnt)->toArray();

            $tempArr = array();
            $tempArr = $paginatedData['data'];
            unset($paginatedData['data']);
            $paginatedData = Utilities::unsetResponseData($paginatedData);
            $paginatedData['subadmins'] = $tempArr;
            
            $responseData = array();
            $responseData['status'] = 'OK';
            $responseData['data'] = $paginatedData;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;

        } catch (QueryException $e) {
            return $this->throwError($e->getMessage(), 422);
        } catch (\Exception $e) {
            return $this->throwError($e->getMessage(), 422);
        }


    }

    /**
     * create, update & delete subadmin
     * pending check subadmin role working
     * @param Request $request
     * @return array
     */
    public function subadmin(Request $request)
    {
        try {
            $responseData = array();

            if (!in_array($request->action, ['create', 'update', 'delete'])) {
                throw new \Exception('Invalid action');
            }

            $rolesArr = [Roles::PARTNER_MANAGER, Roles::COMMUNICATION_MANAGER,
                Roles::LICENSE_MANAGER, Roles::WORKFLOW_MANAGER, Roles::FORM_MANAGER, Roles::PERMISSIONS_MANAGER];

            if (!in_array($request->role, $rolesArr)) {
                throw new \Exception('invalid role');
            }

            DB::beginTransaction();

            if ($request->action == 'create') {
                $this->subadminFactory($request);

                $role = strtolower(str_replace('_', ' ', $request->role));
                $role = ucwords($role);

                //dispatch(new VerifyUser($user));
                $responseData['data']['msg'] = $role. ' created sucessfully';

            } else if ($request->action == 'update') {
                $this->subadminFactory($request);
                $responseData['data']['msg'] = 'Partner updated sucessfully';
            } else if ($request->action == 'delete') {
                if (empty($request->userSlug)) {
                    throw new \Exception('Invalid user');
                }

                $user = User::where(User::slug, $request->userSlug);

                if ($user->doesntExist()) {
                    throw new \Exception('Invalid user slug');
                }

                if ($request->role == 'partnermanager')
                    $responseData['data']['msg'] = 'partner manager deleted sucessfully';
                else if ($request->role == 'subadmin')
                    $responseData['data']['msg'] = 'subadmin deleted sucessfully';

                $user->delete();
            }

            DB::commit();

            $responseData['status'] = 'OK';
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;

        } catch (QueryException $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }
    }

    public function subadminFactory($request)
    {

        if (empty($request->email)) {
            throw new \Exception('invalid email');
        }

        if (empty($request->phone)) {
            throw new \Exception('invalid phone number');
        }

        if (empty($request->name)) {
            throw new \Exception('invalid username');
        }

        $user = User::where(User::email, $request->email)->first();
        if ($request->action == 'create') {
            if (!empty($user)) {
                throw new \Exception('User already exists, try another email');
            }

            $user = new User;
            $user->{User::email}    = $request->email;
            $tempPassword     = "qwerty@123";
            $user->{User::password} = bcrypt($tempPassword);
            $user->remember_token   = $user->generateVerificationCode();
            $user->{User::slug}     = Utilities::getUniqueId();
            $user->{User::name}     = $request->name;
            $user->save();

            $profile = new UserProfile;
            $profile->{UserProfile::first_name} = $request->name;
            $profile->{UserProfile::user_id}    = $user->id;
            $profile->{UserProfile::phone}      = $request->phone;
            $profile->save();


            if ($request->role == Roles::PARTNER_MANAGER) {
                //partner manager
                $partnerManager = new PartnerManager;
                $partnerManager->{PartnerManager::partner_manager_slug} = Utilities::getUniqueId();
                $partnerManager->{PartnerManager::name} = $request->name;
                $partnerManager->{Partner::user_id} = $user->id;
                $partnerManager->save();

                $partners = DB::table(Partner::table)
                    ->select('id')
                    ->whereIn(Partner::partner_slug, $request->partners)->get();

                foreach($partners as $partner) {
                    PartnerManagerPartnerMap::updateOrCreate(
                        [
                            PartnerManagerPartnerMap::partner_manager_id => $partnerManager->id,
                            PartnerManagerPartnerMap::partner_id => $partner->id
                        ],
                        [
                            PartnerManagerPartnerMap::partner_manager_id => $partnerManager->id,
                            PartnerManagerPartnerMap::partner_id => $partner->id
                        ]
                    );
                }

                $user->assignRole(Roles::PARTNER_MANAGER);
            } else if ($request->role == Roles::COMMUNICATION_MANAGER) {
                $user->assignRole(Roles::COMMUNICATION_MANAGER);
            } else if ($request->role == Roles::LICENSE_MANAGER) {
                $user->assignRole(Roles::LICENSE_MANAGER);
            } else if ($request->role == Roles::WORKFLOW_MANAGER) {
                $user->assignRole(Roles::WORKFLOW_MANAGER);
            } else if ($request->role == Roles::FORM_MANAGER) {
                $user->assignRole(Roles::FORM_MANAGER);
            } else if ($request->role == Roles::PERMISSIONS_MANAGER) {
                $user->assignRole(Roles::PERMISSIONS_MANAGER);
            }

        } else if ($request->action == 'update') {
            if (empty($user)) {
                throw new \Exception('User not exists');
            }

            $user->{User::name}     = $request->name;
            $user->save();

            DB::table(UserProfile::table)->where(UserProfile::user_id, $user->id)
                ->update([
                    UserProfile::first_name => $request->name,
                    UserProfile::phone => $request->phone
                ]);

            //ROLE: partnermanager, subadmin
            if ($request->role == Roles::PARTNER_MANAGER) {
                //partner manager
                $partnerManager = PartnerManager::where(PartnerManager::user_id, $user->id)->first();
                $partnerManager->{PartnerManager::name} = $request->name;
                $partnerManager->save();

                $partners = DB::table(Partner::table)
                    ->select('id')
                    ->whereIn(Partner::partner_slug, $request->partners)->get();

                $partnerIdArr = $partners->pluck('id')->toArray();

                DB::table(PartnerManagerPartnerMap::table)
                    ->where(PartnerManagerPartnerMap::partner_manager_id, $partnerManager->id)
                    ->whereNotIn(PartnerManagerPartnerMap::partner_id, $partnerIdArr)->delete();

                foreach($partners as $partner) {
                    PartnerManagerPartnerMap::updateOrCreate(
                        [
                            PartnerManagerPartnerMap::partner_manager_id => $partnerManager->id,
                            PartnerManagerPartnerMap::partner_id => $partner->id
                        ],
                        [
                            PartnerManagerPartnerMap::partner_manager_id => $partnerManager->id,
                            PartnerManagerPartnerMap::partner_id => $partner->id
                        ]
                    );
                }

                if (!$user->hasRole(Roles::PARTNER))
                    $user->syncRoles([Roles::PARTNER]);

            } else if ($request->role == 'subadmin') {
                if (!$user->hasRole(Roles::SUB_ADMIN))
                    $user->syncRoles([Roles::SUB_ADMIN]);
            }

        }
    }

    public function saveSubadminPermissions(Request $request)
    {
        try {
            $user = User::where(User::slug, $request->userSlug)->first();
            if (empty($user)) {
                throw new \Exception('Invalid user');
            }

            $permissions = $request->permissions;
            $this->subadminPermissionFactory($user, $permissions);

            return $this->content = array(
                'data'   => array('msg' => 'permissions updated'),
                'code'   => Response::HTTP_OK,
                'status' => "OK"
            );

        } catch (QueryException $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }
    }

    public function subadminPermissionFactory($user, $permissions)
    {

        $tempPermissionArr = [];
        //Permissions::
        //world level posts
        if ($permissions['communication']['worldLevelPosts']['create']) {
            array_push($tempPermissionArr, Permissions::SUBADMIN_COMM_WLPOSTS_CREATE);
        }
        if ($permissions['communication']['worldLevelPosts']['read']) {
            array_push($tempPermissionArr, Permissions::SUBADMIN_COMM_WLPOSTS_READ);
        }
        if ($permissions['communication']['worldLevelPosts']['edit']) {
            array_push($tempPermissionArr, Permissions::SUBADMIN_COMM_WLPOSTS_EDIT);
        }
        if ($permissions['communication']['worldLevelPosts']['delete']) {
            array_push($tempPermissionArr, Permissions::SUBADMIN_COMM_WLPOSTS_DELETE);
        }

        //vertical level posts
        if ($permissions['communication']['verticalLevelPosts']['create']) {
            array_push($tempPermissionArr, Permissions::SUBADMIN_COMM_VLPOSTS_CREATE);
        }
        if ($permissions['communication']['verticalLevelPosts']['read']) {
            array_push($tempPermissionArr, Permissions::SUBADMIN_COMM_VLPOSTS_READ);
        }
        if ($permissions['communication']['verticalLevelPosts']['edit']) {
            array_push($tempPermissionArr, Permissions::SUBADMIN_COMM_VLPOSTS_EDIT);
        }
        if ($permissions['communication']['verticalLevelPosts']['delete']) {
            array_push($tempPermissionArr, Permissions::SUBADMIN_COMM_VLPOSTS_DELETE);
        }


        $user->syncPermissions($tempPermissionArr);
        return $user;
    }

    public function fetchAllSubadminRoles(Request $request)
    {
        $roles = DB::table(SubadminRoles::table)
            ->join(Roles::table, SubadminRoles::table. '.' .SubadminRoles::role_id, '=' , Roles::table. '.id')
            ->select(
                SubadminRoles::table. '.' .SubadminRoles::name. ' as roleDisplayName',
                Roles::table. '.' .Roles::name. ' as roleName'
            )
            ->orderBy(SubadminRoles::table. '.' .SubadminRoles::name, 'asc');

        if ($request->q) {
            $query = request()->q;
            $roles->Where(SubadminRoles::table. '.'. SubadminRoles::name, 'like', "%{$query}%");
        }

        return $this->content = array(
            'data'   => array('roles' => $roles->get()),
            'code'   => Response::HTTP_OK,
            'status' => "OK"
        );
    }

    public function reformatData($dataArr) {
        $dataArr['partners'] = $dataArr['data'];
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
}