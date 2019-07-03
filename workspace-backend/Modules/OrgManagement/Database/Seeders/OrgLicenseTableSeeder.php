<?php

namespace Modules\OrgManagement\Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgLicense;
use Modules\OrgManagement\Entities\OrgLicenseMapping;

class OrgLicenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $organization = Organization::where(Organization::name, 'Icosys')->first();
        $license      = OrgLicense::where(OrgLicense::name, 'Test License')->first();
        $duration = $license->orgLicenseType->duration;

        $data = [
            [
                OrgLicenseMapping::org_id     => $organization->id,
                OrgLicenseMapping::license_id     => $license->id,
                OrgLicenseMapping::start_date => now(),
                OrgLicenseMapping::end_date => now()->addDays($duration),
                'created_at'          => now(),
                'updated_at'          => now()
            ]
        ];

        foreach ($data as $value) {
            $valueArray = $value;
            unset($value['created_at']);
            unset($value['updated_at']);
            unset($value[OrgLicenseMapping::start_date]);
            unset($value[OrgLicenseMapping::end_date]);
            $mainKeysArray = $value;

            OrgLicenseMapping::updateOrCreate($mainKeysArray, $valueArray);
        }
    }
}
