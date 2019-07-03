<?php

namespace Modules\PartnerManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Common\Entities\Country;
use Modules\Common\Utilities\Utilities;
use Modules\PartnerManagement\Entities\Partner;
use Modules\UserManagement\Entities\Roles;
use Modules\UserManagement\Entities\User;

class PartnerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $users = User::role(Roles::PARTNER)->get();
        $country = Country::where(Country::name, 'India')->first();

        $data = [];
        foreach($users as $user) {
            $data[] = [
                Partner::name => $user->{User::name},
                Partner::partner_slug => Utilities::getUniqueId(),
                Partner::phone => '9999999999',
                Partner::user_id => $user->id,
                Partner::country_id => $country->id,
                'created_at'          => now(),
                'updated_at'          => now()
            ];
        }

        foreach ($data as $value) {
            $partner = Partner::where(Partner::user_id,'=',$value[Partner::user_id])
                ->first();
            if(empty($partner)){
                Partner::create($value);
            }
        }
    }
}
