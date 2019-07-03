<?php

namespace Modules\OrgManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class OrgEmployeeDesignation extends Model
{
    const org_employee_id     = 'org_employee_id';
    const org_id              = 'org_id';
    const org_designation_id  = 'org_designation_id';
    const is_active           = 'is_active';

    const table               = 'org_employee_designation';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = OrgEmployeeDesignation::table;

    protected $fillable = [];
}
