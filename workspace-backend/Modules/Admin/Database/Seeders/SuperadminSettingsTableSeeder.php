<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Entities\SuperadminSettings;
use Modules\Common\Utilities\Utilities;
use Modules\UserManagement\Entities\User;

class SuperadminSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Model::unguard();
        $user = DB::table(User::table)->where(User::email, 'shamon@accubits.com')->first();
        if (!empty($user)) {
            $settings = DB::table(SuperadminSettings::table)->first();
            if (empty($settings)){
                $settings = new SuperadminSettings;
                $settings->{SuperadminSettings::slug} = Utilities::getUniqueId();
                $settings->{SuperadminSettings::user_id} = $user->id;
                $settings->{SuperadminSettings::dashboard_img_path} = 'bgDefaultImage/bg.jpeg';
                $settings->{SuperadminSettings::dashboard_img}      = 'bg.jpeg';
                $settings->{SuperadminSettings::is_default_dashboard_img}   = true;
                $settings->save();
            }
        }
        // $this->call("OthersTableSeeder");
    }
}
