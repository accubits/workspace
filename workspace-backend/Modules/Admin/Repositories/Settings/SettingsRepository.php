<?php

namespace Modules\Admin\Repositories\Settings;


use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Entities\SuperadminSettings;
use Modules\Admin\Repositories\SettingsRepositoryInterface;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;

class SettingsRepository implements SettingsRepositoryInterface
{
    protected $s3BasePath;
    public function __construct()
    {
        $this->s3BasePath = env('S3_PATH');
    }

    public function fetchDashboardSettings(Request $request)
    {
        try {
            $data = array();

            $user = Auth::user();

            $settings = DB::table(SuperadminSettings::table)
                ->where(SuperadminSettings::table. '.' .SuperadminSettings::user_id, $user->id);

            $settings = $settings
                ->join(User::table, User::table. '.id', '=', SuperadminSettings::table. '.' .SuperadminSettings::user_id)
                ->leftjoin(UserProfile::table, UserProfile::table. '.' .UserProfile::user_id, '=', User::table. '.id')
                ->select(
                SuperadminSettings::table. '.' .SuperadminSettings::dashboard_msg. ' as dashboardMsg',
                DB::raw('concat("'.$this->s3BasePath.'",'.SuperadminSettings::table. '.'. SuperadminSettings::dashboard_img_path.') as imageUrl'),
                SuperadminSettings::table. '.' .SuperadminSettings::slug. ' as settingsSlug',
                UserProfile::table. '.' .UserProfile::timezone. ' as timezone'
            )->first();


            $data['data']   =  array('settings' => $settings);
            $data['code']   =  200;
            $data['status'] =  "OK";
            return $data;

        } catch (QueryException $e) {
            return $this->throwError('Something went wrong, Failed to fetch superadmin settings', 422);
        } catch (\Exception $e) {
            return $this->throwError('Something went wrong, Failed to fetch superadmin settings', 422);
        }
    }
}