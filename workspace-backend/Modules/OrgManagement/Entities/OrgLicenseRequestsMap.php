<?php

namespace Modules\OrgManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class OrgLicenseRequestsMap extends Model
{
    const license_request_id = "license_request_id";
    const org_license_id     = "org_license_id";

    const table           = 'org_license_requests_map';


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = OrgLicenseRequestsMap::table;

    protected $fillable = [
        OrgLicenseRequestsMap::license_request_id,
        OrgLicenseRequestsMap::org_license_id
    ];
}
