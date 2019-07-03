<?php

namespace Modules\OrgManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Common\Utilities\Utilities;
use Modules\OrgManagement\Entities\OrgLicense;
use Modules\OrgManagement\Entities\OrgLicenseType;
use Modules\UserManagement\Entities\Roles;
use Modules\UserManagement\Entities\User;

class LicenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $user        = User::role(Roles::PARTNER)->first();
        $licenseType = OrgLicenseType::where(OrgLicenseType::name,'Annual')->first();


        $data = [
            [
                OrgLicense::name      => 'Test License',
                OrgLicense::slug      => Utilities::getUniqueId(),
                OrgLicense::key       => User::generateVerificationCode(),
                OrgLicense::max_users => 50,
                OrgLicense::license_type_id => $licenseType->id,
                OrgLicense::partner_id      => $user->partner->id,
                'created_at'          => now(),
                'updated_at'          => now()
            ]
        ];

        foreach ($data as $value) {
            $orgLicense = OrgLicense::where(OrgLicense::name,'=',$value[OrgLicense::name])->first();
            if(empty($orgLicense)){
                OrgLicense::create($value);
            }
        }
    }
}
