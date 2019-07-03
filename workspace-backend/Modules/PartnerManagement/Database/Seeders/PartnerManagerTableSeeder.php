<?php

namespace Modules\PartnerManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\Utilities;
use Modules\PartnerManagement\Entities\Partner;
use Modules\PartnerManagement\Entities\PartnerManager;
use Modules\PartnerManagement\Entities\PartnerManagerPartnerMap;
use Modules\UserManagement\Entities\Roles;
use Modules\UserManagement\Entities\User;

class PartnerManagerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $users = User::role(Roles::PARTNER_MANAGER)->get();

        $data = [];
        foreach($users as $user) {
            $data[] = [
                PartnerManager::name => $user->{User::name},
                PartnerManager::partner_manager_slug => Utilities::getUniqueId(),
                PartnerManager::user_id => $user->id,
                'created_at'          => now(),
                'updated_at'          => now()
            ];
        }

        foreach ($data as $value) {
            $partnerManager = PartnerManager::where(PartnerManager::user_id,'=',$value[PartnerManager::user_id])
                ->first();
            if(empty($partnerManager)){
                PartnerManager::create($value);
            }
        }

        //partner partnermanager map
        $partnerEmailArr  = ['icosyspartner1@mailinator.com', 'icosyspartner2@mailinator.com'];

        $partnerManager = DB::table(PartnerManager::table)->join(User::table, User::table. '.id', '=', PartnerManager::table. '.' .PartnerManager::user_id)
            ->where(User::table. '.' .User::email, 'icosyspartnermanager@mailinator.com')
            ->select(PartnerManager::table. '.id')
            ->first();

        $partners = DB::table(Partner::table)->join(User::table, User::table. '.id', '=', Partner::table. '.' .Partner::user_id)
            ->whereIn(User::table. '.' .User::email, $partnerEmailArr)
            ->select(Partner::table. '.id')
            ->get();

        foreach($partners as $partner) {
            $PmPmap = DB::table(PartnerManagerPartnerMap::table)->where(PartnerManagerPartnerMap::partner_id, $partner->id)
                ->where(PartnerManagerPartnerMap::partner_manager_id, $partnerManager->id)
                ->first();

            if(empty($PmPmap)){
                PartnerManagerPartnerMap::insert([
                    PartnerManagerPartnerMap::partner_id => $partner->id,
                    PartnerManagerPartnerMap::partner_manager_id => $partnerManager->id
                ]);
            }
        }

    }
}
