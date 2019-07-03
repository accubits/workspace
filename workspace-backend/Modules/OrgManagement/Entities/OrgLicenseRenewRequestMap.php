<?php

namespace Modules\OrgManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class OrgLicenseRenewRequestMap extends Model
{
    const org_license_id       = 'org_license_id';
    const license_request_id   = 'license_request_id';
    const creator_id           = 'creator_id';

    const table            = 'org_license_renew_request_map';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = OrgLicenseRenewRequestMap::table;

    protected $fillable = [];
}
