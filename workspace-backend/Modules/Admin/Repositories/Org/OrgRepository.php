<?php

namespace Modules\Admin\Repositories\Org;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Repositories\OrgRepositoryInterface;
use Modules\Common\Entities\Country;
use Modules\Common\Entities\Vertical;
use Modules\Common\Utilities\FileUpload;
use Modules\Common\Utilities\Utilities;
use Modules\OrgManagement\Entities\OrgAdmin;
use Modules\OrgManagement\Entities\Organization;
use Modules\PartnerManagement\Entities\Partner;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;

class OrgRepository implements OrgRepositoryInterface
{
    protected $s3BasePath;
    public function __construct()
    {
        $this->s3BasePath = env('S3_PATH');
    }

    public function fetchAllOrg(Request $request)
    {
        try {
            $org = DB::table(Organization::table)
                ->leftjoin(Partner::table, Partner::table . ".id", '=', Organization::table . '.' . Organization::partner_id)
                ->leftjoin(Country::table, Country::table . ".id", '=', Organization::table . '.' . Organization::country_id)
                ->leftjoin(Vertical::table, Vertical::table . ".id", '=', Organization::table . '.' . Organization::vertical_id)
                ->leftjoin(User::table.' AS partnerUser', "partnerUser.id", '=', Partner::table . '.' . Partner::user_id)
                ->leftjoin(UserProfile::table. ' as partnerProfile', 'partnerProfile.' .UserProfile::user_id, '=', 'partnerUser.id')
                ->leftJoin(OrgAdmin::table, Organization::table . ".id", '=', OrgAdmin::table . '.' . OrgAdmin::org_id)
                ->leftJoin(User::table.' AS OrgAdminUser', "OrgAdminUser.id", '=', OrgAdmin::table . '.' . OrgAdmin::user_id)
                ->leftjoin(UserProfile::table. ' as OrgAdminUserProfile', 'OrgAdminUserProfile.' .UserProfile::user_id, '=', 'OrgAdminUser.id')
                ->select(
                    Organization::name. ' as orgName',
                    Organization::slug. ' as orgSlug',
                    Country::table. '.' .Country::name. ' as countryName',
                    Vertical::table. '.' .Vertical::name. ' as verticalName',
                    'partnerUser.name as partnerName',
                    'OrgAdminUser.' .User::name. '  as adminUserNames',
                    'OrgAdminUser.' .User::email. '  as adminUserEmail',
                    DB::raw('concat("'.$this->s3BasePath.'",partnerProfile.'. UserProfile::image_path .') as partnerImage'),
                    DB::raw('concat("'.$this->s3BasePath.'",OrgAdminUserProfile.'. UserProfile::image_path .') as adminUserImage')
            );

            $orgCount = $org->count();

            $org = $org->skip(Utilities::getParams()['offset']) //$request['offset']
            ->take(Utilities::getParams()['perPage']) //$request['perPage']
            ->get();

            $paginatedData = Utilities::paginate($org, Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $orgCount)->toArray();

            $responseData = array();
            $response = Utilities::unsetResponseData($paginatedData);
            $response['organization'] = $paginatedData['data'];

            $responseData['status'] = 'OK';
            $responseData['data'] = $response;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;

        } catch (QueryException $e) {
            return $this->throwError('Something went wrong, Failed to fetch Organizations', 422);
        } catch (\Exception $e) {
            return $this->throwError('Something went wrong, Failed to fetch Organizations', 422);
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

            $orgSettings = $org->select(
                Organization::table. '.' .Organization::dashboard_message. ' as dashboardMsg',
                DB::raw('concat("'.$this->s3BasePath.'",'.Organization::table. '.'. Organization::bg_image_path.') as imageUrl'),
                Organization::table. '.' .Organization::storage
            )->first();


            $data['data']   =  array('settings' => $orgSettings);
            $data['code']   =  200;
            $data['status'] =  "OK";
            return $data;

        } catch (QueryException $e) {
            return $this->throwError('Something went wrong, Failed to fetch partner dashboard data', 422);
        }
    }

    /**
     * @TODO validation with storage size, calculate size from database
     * @param Request $request
     * @return array
     */
    public function saveOrgSettings(Request $request)
    {
        DB::beginTransaction();
        $data =array();
        try {
            $org = Organization::where(Organization::slug, $request->orgSlug)->first();

            if (empty($org)) {
                return $this->throwError('No Organization Found', 422);
            }


            //Dashboard Message
            if ($request->dashboardMsg) {
                $org->{Organization::dashboard_message}      = $request->dashboardMsg;
            }

            //Storage Size
            if ($request->storageSize) {
                $org->{Organization::storage}      = $request->storageSize;
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

    public function throwError($msg, $code) : array
    {
        $resp=array();
        $resp['error'] = array('msg'=>$msg);
        $resp['code']  = $code;
        $resp['status']   =  "ERROR";
        return $resp;
    }

}