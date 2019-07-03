<?php

namespace Modules\OrgManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\Utilities;
use Modules\OrgManagement\Entities\OrgLicenseType;

class LicenseTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $data = [
            [
                OrgLicenseType::name     => 'Annual',
                OrgLicenseType::slug     => Utilities::getUniqueId(),
                OrgLicenseType::duration => 365,
                'created_at'          => now(),
                'updated_at'          => now()
            ]
        ];

        foreach ($data as $value) {
            $orgLicenseType = OrgLicenseType::where(OrgLicenseType::name,'=',$value[OrgLicenseType::name])->first();
            if(empty($orgLicenseType)){
                OrgLicenseType::create($value);
            }
        }
    }
}
