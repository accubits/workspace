<?php

namespace Modules\OrgManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Common\Entities\Country;
use Modules\Common\Entities\Vertical;
use Modules\Common\Utilities\Utilities;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\Roles;
use Modules\UserManagement\Entities\User;

class OrganizationTableSeeder extends Seeder
{
    const ORGANIZATION_NAME = 'Icosys';
    const ORGANIZATION_NAME_ACCUBITS = 'Accubits';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $verticals = Vertical::where(Vertical::name, 'Schools')->first();
        $country   = Country::where(Country::name, 'India')->first();
        $user      = User::role(Roles::PARTNER)->get();


        $data = [
            [
                Organization::name     => OrganizationTableSeeder::ORGANIZATION_NAME,
                Organization::slug     => Utilities::getUniqueId(),
                Organization::description => OrganizationTableSeeder::ORGANIZATION_NAME,
                Organization::vertical_id => $verticals->id,
                Organization::country_id => $country->id,
                Organization::partner_id => $user->get(0)->partner->id,
                'created_at'          => now(),
                'updated_at'          => now()
            ],
            [
                Organization::name     => OrganizationTableSeeder::ORGANIZATION_NAME_ACCUBITS,
                Organization::slug     => Utilities::getUniqueId(),
                Organization::description => 'Accubits',
                Organization::vertical_id => $verticals->id,
                Organization::country_id => $country->id,
                Organization::partner_id => $user->get(1)->partner->id,
                'created_at'          => now(),
                'updated_at'          => now()
            ]
        ];

        foreach ($data as $value) {
            $org = Organization::where(Organization::name,'=',$value[Organization::name])
                ->where(Organization::partner_id,'=',$value[Organization::partner_id])
                ->first();
            if(empty($org)){
                Organization::create($value);
            }
        }
    }
}
