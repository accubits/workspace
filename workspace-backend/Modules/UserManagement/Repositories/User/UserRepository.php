<?php

/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 4/1/18
 * Time: 11:49 AM
 */
namespace Modules\UserManagement\Repositories\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Admin\Entities\SuperadminSettings;
use Modules\Common\Entities\Country;
use Modules\Common\Utilities\ResponseStatus;
use Modules\OrgManagement\Entities\OrgAdmin;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\OrgManagement\Entities\OrgEmployeeDepartment;
use Modules\UserManagement\Emails\ForgotPassword;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;
use Modules\UserManagement\Entities\UserProfileAddress;
use Modules\UserManagement\Jobs\ForgotPasswordQueue;
use Modules\UserManagement\Repositories\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Image;
use Modules\UserManagement\Entities\Interest;
use Modules\UserManagement\Entities\UserProfileInterest;
use Modules\Common\Utilities\Utilities;
use Modules\Common\Utilities\FileUpload;


class UserRepository implements UserRepositoryInterface
{
    protected $user;
    protected $content;
    protected $fileUpload;


    public function __construct(FileUpload $fileUpload)
    {
        $this->user    = new User;
        $this->content = array();
        $this->fileUpload = $fileUpload;
    }

    public function getParams()
    {
        $page    = 1;
        $perPage = 10;

        if (request()->has('page')) {
            $page = (int)request()->page;
        }

        if (request()->has('per_page')) {
            $perPage = (int)request()->per_page;
        }

        $offset    = ($page * $perPage) - $perPage;

        return array('page' => $page, 'perPage' => $perPage, 'offset' => $offset);
    }

    /**
     * @return $this
     */
    public function getAllUsers()
    {

        $userCount = $this->user->where('id', '<>', 1)->count();

        $users = $this->user->where('id', '<>', 1)
            ->skip($this->getParams()['offset'])
            ->take($this->getParams()['perPage'])
            ->get();

        return $this->paginate($users,
            $this->getParams()['perPage'], $this->getParams()['page'], array(), $userCount
            );

    }

    public function authenticate(array $credentials)
    {
        $content = array();
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $user = Auth::user();

            if ($user) {
                $roles = $user->getRoleNames();
                $this->content['data'] = [
                    'token' => $user->createToken('TEST')->accessToken,
                    'name'  => $user->name,
                    'email'  => $user->email,
                    'roles'  => $roles,
                    'user_slug' => $user->slug
                ];

                $orgEmployeSlug = $this->getOrgEmployee($user);

                if ($orgEmployeSlug->isNotEmpty()) {
                    //is OrgEmployee
                    $employeeId = $orgEmployeSlug->pluck('employeeId')[0];
                    $isHod = $this->isHOD($employeeId);

                    $this->content['data']['org_slug'] = $orgEmployeSlug->pluck(Organization::slug);
                    $this->content['data']['isDepartmentHead'] = $isHod;
                } else {
                    //is OrgAdmin
                    $orgAdmin = $this->getOrgAdmin($user);
                    $this->content['data']['org_slug'] = $orgAdmin->pluck(Organization::slug);
                    $this->content['data']['isDepartmentHead'] = false;
                }

                $this->content['code']  =  Response::HTTP_OK;
                $this->content['status'] = ResponseStatus::OK;
            } else {
                $this->content['code']  =  Response::HTTP_UNAUTHORIZED;
                $this->content['error'] =  'Invalid Login';
                $this->content['status'] = ResponseStatus::ERROR;
            }

        } else {
            $this->content['code']  =  Response::HTTP_UNAUTHORIZED;
            $this->content['error'] =  'Invalid Login';
            $this->content['status'] = ResponseStatus::ERROR;
        }

        return $this->content;
    }

    public function isHOD($employeeId)
    {
        $isHead = DB::table(OrgEmployeeDepartment::table)
            ->where(OrgEmployeeDepartment::org_employee_id, $employeeId)
            ->where(OrgEmployeeDepartment::is_head, true)->exists();
        if ($isHead) return true;

        $isReportingManager = DB::table(OrgEmployee::table)
            ->where(OrgEmployee::reporting_manager_id, $employeeId)->exists();

        if ($isReportingManager) return true;

        return false;
    }

    public function getOrgEmployee(User $user)
    {
        return DB::table(OrgEmployee::table)
            ->join(Organization::table, OrgEmployee::table.'.'.OrgEmployee::org_id, '=', Organization::table.'.id')
            ->select(
                Organization::table. '.'. Organization::slug,
                OrgEmployee::table. '.id as employeeId'
            )
            ->where(OrgEmployee::table.'.'. OrgEmployee::user_id, $user->id)
            ->get();
    }

    public function getOrgAdmin(User $user)
    {
        return DB::table(OrgAdmin::table)
            ->join(Organization::table, OrgAdmin::table.'.'.OrgAdmin::org_id, '=', Organization::table.'.id')
            ->select(
                Organization::table. '.'. Organization::slug
            )
            ->where(OrgAdmin::table.'.'. OrgAdmin::user_id, $user->id)
            ->where(OrgAdmin::table.'.'. OrgAdmin::is_active, true)
            ->get();
    }

    /**
     * @return array
     */
    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->authAcessToken()->delete();
            $this->content['data']  =  'User Logout Successfully';
            $this->content['code']  =  200;
        } else {
            $this->content['code']  =  401;
            $this->content['error'] =  'Unauthenticated';
        }

        return $this->content;
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        if (Hash::check($request->current_password, $user->password)) {
            $this->content['data']  =  'Password Changed Successfully';
            $this->content['code']  =  200;
            $user->password = bcrypt($request->new_password);
            $user->save();
        } else {
            $this->content['error']  =  array('msg' => 'Your current password is incorrect');
            $this->content['code']   =  422;
        }

        return $this->content;
    }

    public function getUserFromEmail($email)
    {
        $user = $this->user->where('email', $email)->firstOrFail();
        $user->remember_token = $user->generateVerificationCode();
        $user->save();

        //Mail::to($user->email)->send(new ForgotPassword($user));
        dispatch(new ForgotPasswordQueue($user));
        $this->content['data']  =  'Forgot password email sent';
        $this->content['code']  =  200;

        return $this->content;
    }

    public function resetPassword(Request $request)
    {

        $user = $this->user->where('remember_token', $request->token)->first();

        if (!$user) {
            $this->content['error']  =  'Token is expired';
            $this->content['code']  =  422;
        } else {
            $user->password         = bcrypt($request->new_password);
            $user->remember_token   = User::generateVerificationCode();
            $user->save();

            $this->content['data']  =  'Password Change Sucessfully';
            $this->content['code']  =  200;
        }

        return $this->content;
    }

    public function verifyAndChangePassword(Request $request)
    {
        try {
            $user = User::where('remember_token', $request->token)->first();

            if (!$user) {
                throw new \Exception('Invalid Token');
            }

            $user->{User::name}     = $request->name;
            $user->password         = bcrypt($request->password);
            $user->remember_token   = User::generateVerificationCode();
            $user->save();

            $this->content['data']  =  array ('msg' => 'Password Change Sucessfully');
            $this->content['code']  =  200;
        } catch (QueryException $ex) {
            $this->content['error']  = array ('msg' => $ex->getmessage());//'Something went wrong';
            $this->content['code']   =  422;
            $this->content['status'] =  ResponseStatus::ERROR;
        } catch (\Exception $ex) {
            $this->content['error']  = array ('msg' => $ex->getmessage());//'Something went wrong';
            $this->content['code']   =  422;
            $this->content['status'] =  ResponseStatus::ERROR;
        }
        return $this->content;
    }


    public function getUserProfile()
    {
        $user = Auth::user();
        $s3BasePath= env('S3_PATH');
        //DB::enableQueryLog();
        try {
            $userProfile = DB::table(User::table)
                ->leftjoin(UserProfile::table, UserProfile::table.'.'.UserProfile::user_id, '=', User::table.'.'. User::id)
                ->leftjoin(OrgEmployee::table, OrgEmployee::table. '.' .OrgEmployee::user_id, '=', User::table. '.id')
                ->select(
                    User::table. '.'. User::name,
                    User::table. '.'. User::email,
                    UserProfile::table. '.'. UserProfile::first_name .' as firstName',
                    UserProfile::table. '.'. UserProfile::last_name  .' as lastName',

                    DB::raw("unix_timestamp(".UserProfile::table . '.'.UserProfile::birth_date.") AS birthDate"),
                    UserProfile::table. '.'. UserProfile::user_image .' as userImage',
                    UserProfile::table.'.id',

                    DB::raw('concat("'.$s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as imageUrl'),
                    OrgEmployee::table. '.' .OrgEmployee::reporting_manager_id. ' as reportingManagerId'

                )
                ->where(User::table.'.'. User::id, $user->id)
                ->first();

            if (empty($userProfile)) {
                throw new \Exception('Invalid User Profile');
            }


//dd(DB::getQueryLog());

            $userProfileInterestArr = DB::table(UserProfileInterest::table)
                ->join(Interest::table,UserProfileInterest::table.'.'.UserProfileInterest::user_interest_id,'=',Interest::table.'.id')
                ->select(

                    Interest::table.'.'. Interest::interest_title,
                    UserProfileInterest::table.'.'. UserProfileInterest::slug

                )
                ->where(UserProfileInterest::table.'.'.UserProfileInterest::user_profile_id,$userProfile->id)
                ->get();


            $userProfileArr = (array)$userProfile;
            $userProfileArr['interest'] =$userProfileInterestArr;
            $userProfileArr['reportingManagerDetails'] = new \stdClass();

            //reportingManger
            if (!empty($userProfile->reportingManagerId)) {
                $reportingManagerQuery = DB::table(OrgEmployee::table)
                    ->select(
                        OrgEmployee::table. '.' .OrgEmployee::slug. ' as reportingManagerSlug',
                        OrgEmployee::table. '.' .OrgEmployee::name. ' as reportingManagerName',
                        DB::raw('concat("'.$s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as reportingManagerImgUrl')
                    )
                    ->join(UserProfile::table, UserProfile::table. '.' .UserProfile::user_id, '=', OrgEmployee::table. '.' .OrgEmployee::user_id)
                    ->where(OrgEmployee::table. '.id', $userProfile->reportingManagerId)
                    ->first();

                $userProfileArr['reportingManagerDetails'] = $reportingManagerQuery;
            }

            if (!empty($userProfileArr)) {
                unset($userProfileArr['id']);
                unset($userProfileArr['reportingManagerId']);
            }

            $this->content['data']  =  $userProfileArr;
            $this->content['code']  =  200;
            $this->content['status'] =  ResponseStatus::OK;
            return $this->content;
        } catch (QueryException $ex) {
            $this->content['error']  = array ('msg' => $ex->getmessage());//'Something went wrong';
            $this->content['code']   =  422;
            $this->content['status'] =  ResponseStatus::ERROR;
        } catch (\Exception $ex) {
            $this->content['error']  = array ('msg' => $ex->getmessage());//'Something went wrong';
            $this->content['code']   =  422;
            $this->content['status'] =  ResponseStatus::ERROR;
        }

        return $this->content;

    }

    public function setInterest($InterestActions,$userProfileId)
    {
        $user = Auth::user();

        if($InterestActions['action'] == 'create')
        {
            $interestObj= Interest::where(Interest::interest_title, $InterestActions['interest'])->first();
            if(empty($interestObj))
            {    
                DB::table(UserProfileInterest::table)
                ->join(Interest::table,UserProfileInterest::table.'.'.UserProfileInterest::user_interest_id,'=',Interest::table.'.id')
                ->select(
                    
                    Interest::table.'.'. Interest::interest_title,
                    UserProfileInterest::table.'.'. UserProfileInterest::slug
                        
                    )
                ->where(UserProfileInterest::table.'.'.UserProfileInterest::user_interest_id,$user->id)
                ->first();   
                $interestObj = new Interest;
                $interestObj->{Interest::interest_title }= $InterestActions['interest'];
                $interestObj->save();

            }
            
            $userprofileinterestObj = new UserProfileInterest;
            $userprofileinterestObj->{UserProfileInterest::slug} = Utilities::getUniqueId();;
            $userprofileinterestObj->{UserProfileInterest::user_interest_id} = $interestObj->id;
            $userprofileinterestObj->{UserProfileInterest:: user_profile_id }= $userProfileId;
            $userprofileinterestObj->save();
            return  $userprofileinterestObj;
        }

        if($InterestActions['action'] == 'edit')
        {
           
            $interestObj=Interest::where(Interest::interest_title, $InterestActions['interest'])->first();
            if(empty($interestObj))
            {
                $interestObj = new Interest;
                $interestObj->{Interest::interest_title }= $InterestActions['interest'];
                $interestObj->save();
            } 

            // DB::connection()->enableQueryLog();
            $userprofileinterestObj= UserProfileInterest::where(UserProfileInterest::slug,$InterestActions['slug'] )->first();
             if($interestObj->id !== $userprofileinterestObj->{UserProfileInterest::user_interest_id})
            {

                $userprofileno = UserProfileInterest::where(UserProfileInterest::user_interest_id,$userprofileinterestObj->{UserProfileInterest::user_interest_id} )->count();
                 if($userprofileno > 1)
                    {
                        $userprofileinterestObj->{UserProfileInterest::user_interest_id} = $interestObj->id;
                    
                    }
                 else{

                    $oldinterestObj=Interest::where('id',$userprofileinterestObj->{UserProfileInterest::user_interest_id})->first();
                    $oldinterestObj->delete();
                    $userprofileinterestObj->{UserProfileInterest::user_interest_id} = $interestObj->id;
                    $userprofileinterestObj->save();
                    }
            }
            return  $userprofileinterestObj;

            // dd(DB::getQueryLog());
        }

       

    }
    public function editUserProfile(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            try {

                if (empty($user->userProfile)) {
                    $userProfile = new UserProfile;
                } else {
                    $userProfile = UserProfile::where(UserProfile::user_id, $user->id)->first();
                }

                if ($request->file) {
                    $image     = $request->file('file');
                    // File upload handling
                    if( !empty($image) )
                    {
                        $imageName = $image->getClientOriginalName();
                        $imageName = preg_replace('/\s+/', '_', $imageName);
                        $folder     = "UserProfile";
        
                        $thumbpath=$folder.'/thumb300x300/'. $imageName;
                        $org_img = Image::make($image->getRealPath());
                        $org_img->encode('jpg');
                        $s3fileObj = Storage::disk('s3')->put( $folder.'/original/'. $imageName, $image->__toString(), 'public');    
                        $thumb_img = Image::make($image->getRealPath())->resize(100, 100, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $thumb_img->encode('jpg');
        
                        $s3fileName = Storage::disk('s3')->put( $folder.'/thumb100x100/'. $imageName, $thumb_img->__toString(), 'public');
                 
        
                        $thumb_img = Image::make($image->getRealPath())->resize(300, 300,function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $thumb_img->encode('jpg');
        
                        $s3fileName = Storage::disk('s3')->put( $folder.'/thumb300x300/'. $imageName, $thumb_img->__toString(), 'public');
                        $userProfile->{UserProfile::image_path}=$thumbpath;
                        $userProfile->{UserProfile::user_image}  = $imageName;
                    }
                   
                }

                //additional info for employees only
                if (!empty($request->additionalInfo)) {

                    //reporting manager update
                    if ($request->additionalInfo['reportingManagerSlug']) {
                        $loggedEmployee = OrgEmployee::where(OrgEmployee::user_id, $user->id)->first();

                        $orgEmployee = DB::table(OrgEmployee::table)
                            ->select('id')
                            ->where(OrgEmployee::slug, $request->additionalInfo['reportingManagerSlug'])
                            ->first();

                        if (empty($orgEmployee)) {
                            throw new \Exception('invalid reporting manager slug');
                        }

                        $loggedEmployee->{OrgEmployee::reporting_manager_id} = $orgEmployee->id;
                    }

                    if ($request->additionalInfo['joiningDate']) {
                        $loggedEmployee->{OrgEmployee::joining_date} = Utilities::createDateTimeFromUtc($request->additionalInfo['joiningDate']);
                    }

                    $loggedEmployee->save();

                    if ($request->additionalInfo['addressInfo']) {
                        $addressInfo = $request->additionalInfo['addressInfo'];

                        $userProfileAddressId = ($user->userProfile) ? $user->userProfile->{UserProfile::user_profile_address_id} : null;
                        if (!empty($userProfileAddressId)) {
                            $profileAddress = UserProfileAddress::where('id', $userProfileAddressId)->first();
                        } else {
                            $profileAddress = new UserProfileAddress;
                        }

                        if (!empty($addressInfo['countrySlug'])) {
                            $country = DB::table(Country::table)
                                ->select('id')
                                ->where(Country::slug, $addressInfo['countrySlug'])->first();
                            $profileAddress->{UserProfileAddress::country_id} = $country->id;
                        }

                        if (!empty($addressInfo['streetAddress'])) {
                            $profileAddress->{UserProfileAddress::street_address} = $addressInfo['streetAddress'];
                        }

                        if (!empty($addressInfo['addressLine2'])) {
                            $profileAddress->{UserProfileAddress::address_line2} = $addressInfo['addressLine2'];
                        }

                        if (!empty($addressInfo['city'])) {
                            $profileAddress->{UserProfileAddress::city} = $addressInfo['city'];
                        }

                        if (!empty($addressInfo['state'])) {
                            $profileAddress->{UserProfileAddress::state} = $addressInfo['state'];
                        }

                        if (!empty($addressInfo['zipcode'])) {
                            $profileAddress->{UserProfileAddress::zip_code} = $addressInfo['zipcode'];
                        }

                        $profileAddress->save();
                        $userProfile->{UserProfile::user_profile_address_id} = $profileAddress->id;
                    }

                }


                $userProfile->{UserProfile::first_name} = $request->first_name;
                $userProfile->{UserProfile::last_name}  = $request->last_name;
                // $user->userProfile->{UserProfile::birth_date}  = Utilities::createDateTimeFromUtc($request->birth_date);

                if ($request->birth_date) {
                    $birthday = Utilities::createDateTimeFromUtc($request->birth_date);
                    $userProfile->{UserProfile::birth_date} =  $birthday ;
                }

               /* dd("sd");
                 if(!empty($birthday))
                 {
                    $user->userProfile->{UserProfile::birth_date} =  $birthday ; 
                 }*/

                $userProfile->{UserProfile::user_id} = $user->id;
                $userProfile->save();
                $userProfileId = $userProfile->id;

                $interestsArr = json_decode($request->interests, true);
                $intrstarry= collect($interestsArr);
                $userProfileIdArr=[];
                // dd($intrstarry);
                $intrstarry->map(function($interest) use ( $userProfileId,&$userProfileIdArr) {
               
                    $userProfileObj = 
                    $this->setInterest($interest, $userProfileId);
                    // dd( $userProfileObj);
                    array_push($userProfileIdArr,$userProfileObj->{UserProfileInterest::slug});


                });

          DB::table(UserProfileInterest::table)
         ->where(UserProfileInterest::user_profile_id, '=', $userProfile->id)
         ->whereNotIn('slug', $userProfileIdArr)
         ->delete();


         $Interestids = DB::table(UserProfileInterest::table)
             ->select(UserProfileInterest::user_interest_id)
             ->get();

             $intIds = array();
             foreach($Interestids as $Interest)
             {

                array_push($intIds,$Interest->user_interest_id);

             }
            //  dd( $intIds);
         

             DB::table(Interest::table)
         ->whereNotIn('id', $intIds)
         ->delete();

                
                $this->content['message']  =  'User Profile updated successfully';
                $this->content['code']   =  200;
                $this->content['status'] =  ResponseStatus::OK;

            } catch (QueryException $ex) {
                
                $this->content['error']  = $ex->getmessage();//'Something went wrong';
                $this->content['code']   =  500;
                $this->content['status'] =  ResponseStatus::ERROR;

            }

        } else {
            $this->content['error']  =  'Invalid User';
            $this->content['code']   =  422;
        }
        return $this->content;
    }

    public function deleteProfileImg()
    {
        $user = Auth::user();
        $folder     = "UserProfile";
        $imageName = $user->userProfile->{UserProfile::user_image};
        
        $fullPath = $user->userProfile->image_path;
        if(!empty($fullPath))
        {
            $arr=array();
            array_push($arr, $fullPath);
            array_push($arr,$folder.'/original/'. $imageName); 
            
            array_push($arr,$folder.'/thumb100x100/'. $imageName);
    
            $this->fileUpload->deleteFiles($arr);
            $user->userProfile->image_path = null;
            $user->userProfile->user_image = null;

            $user->userProfile->save();
            return array(
                'status'=>'OK',
                'data'=>array('msg'=>'deleted successfully'),
                'code'=>Response::HTTP_OK
            );
        }
        
        return array(
            'status'=>'ERROR',
            'data'=>array('msg'=>'Image does not exist '),
            'code'=>Response::HTTP_OK
        );

    }


    private function paginate($items, $perPage = 10, $page = null, $options = [], $count)
    {
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $paginated = new LengthAwarePaginator($items, $count, $perPage, $page, $options);
        return $paginated->appends(request()->all());
    }


    public function userFileUpload($collectionObj, $task, $orgSlug)
    {
        $fileUpload = new FileUpload;
        $folder     = "{$orgSlug}/task/{$task->slug}";

        return $collectionObj->map(function ($file) use ($fileUpload, $folder, $task) {
            $fileUpload->setPath($folder);
            $fileUpload->setFile($file);
            $fileUpload->s3Upload();

            return array(
                TaskFile::taskfile_slug => Utilities::getUniqueId(),
                TaskFile::task_id   => $task->id,
                TaskFile::filename  => $file->getClientOriginalName(),
                'created_at' => now(),
                'updated_at' => now()
            );
        })->all();
    }

    public function prelogin()
    {
        $s3BasePath = env('S3_PATH');
        $setting = DB::table(SuperadminSettings::table)->first();
        $dashboardImg = $setting->{SuperadminSettings::dashboard_img_path};
        $img = $s3BasePath.$dashboardImg;

        return array(
            'status'=>'OK',
            'data'=>array('settings'=>array('dashboardImg' => $img)),
            'code'=>Response::HTTP_OK
        );

        // TODO: Implement prelogin() method.
    }
}

