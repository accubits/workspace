<?php

namespace Modules\OrgManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class OrgLicenseRequest extends Model
{
    const request_slug = "request_slug";
    const max_users       = 'max_users';
    const license_type_id = 'license_type_id';
    const org_id      = 'org_id';
    const requesting_user_id      = 'requesting_user_id';
    const is_approved      = 'is_approved';
    const is_cancelled     = 'is_cancelled';
    const parent_request_id  = 'parent_request_id';
    const to_user_group    = 'to_user_group';
    const is_forward       = 'is_forward';
    const is_renewal       = 'is_renewal';

    const table           = 'org_license_requests';

    const TOPARTNERGROUP     = 'PARTNER';
    const TOSUPERADMINGROUP  = 'TO_SUPERADMIN';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = OrgLicenseRequest::table;
    protected $fillable = [
        OrgLicenseRequest::org_id,
        OrgLicenseRequest::license_type_id,
        OrgLicenseRequest::max_users,
        OrgLicenseRequest::requesting_user_id,
        OrgLicenseRequest::is_cancelled,
        OrgLicenseRequest::to_user_group,
        OrgLicenseRequest::is_approved
        ];
}
