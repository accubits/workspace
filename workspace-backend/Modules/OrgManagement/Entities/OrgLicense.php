<?php

namespace Modules\OrgManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\PartnerManagement\Entities\Partner;

class OrgLicense extends Model
{
    const name            = 'license_name';
    const slug            = 'license_slug';
    const key             = 'license_key';
    const max_users       = 'max_users';
    const license_type_id = 'license_type_id';
    const partner_id      = 'partner_id';
    const org_id          = 'org_id';
    const license_status  = 'license_status';
    const upcoming_license  = 'upcoming_license';

    const table           = 'org_license';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = orgLicense::table;

    protected $fillable = [];

    public function orgLicenseType()
    {
        return $this->belongsTo(OrgLicenseType::class, OrgLicense::license_type_id);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
