<?php

namespace Modules\OrgManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class OrgLicenseType extends Model
{
    const name          = 'license_type_name';
    const slug          = 'license_type_slug';
    const duration      = 'duration';
    const Annual        = 'Annual';
    const Trial         = 'Trial';

    const table         = 'org_license_type';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = OrgLicenseType::table;

    protected $fillable = [Organization::name];

    public function orgLicense()
    {
        return $this->hasMany(OrgLicense::class);
    }
}
