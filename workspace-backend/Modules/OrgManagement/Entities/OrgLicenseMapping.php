<?php

namespace Modules\OrgManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class OrgLicenseMapping extends Model
{
    const org_id         = 'org_id';
    const license_id     = 'license_id';
    const start_date     = 'start_date';
    const end_date       = 'end_date';

    const table          = 'org_license_mapping';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = OrgLicenseMapping::table;

    protected $fillable = [];
}
