<?php

/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 14/1/19
 * Time: 4:19 PM
 */

namespace Modules\OrgManagement\Cron;


use Illuminate\Support\Facades\DB;
use Modules\OrgManagement\Entities\OrgLicense;
use Modules\OrgManagement\Entities\OrgLicenseMapping;

class CronLicenseExpireStatus
{
    public function __construct()
    {

    }

    public function process()
    {
        DB::table(OrgLicense::table)->join(OrgLicenseMapping::table, OrgLicense::table. '.id', '=',
            OrgLicenseMapping::table. '.' .OrgLicenseMapping::license_id)
            ->WhereDate(OrgLicenseMapping::table. '.' .OrgLicenseMapping::end_date, '<=', now())
            ->update([OrgLicense::table. '.' .OrgLicense::license_status => false]);
    }

}