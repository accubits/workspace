<?php

namespace Modules\OrgManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class OrgEmployeeDesignationDepartment extends Model
{
    const org_id              = 'org_id';
    const org_department_id   = 'org_department_id';
    const org_designation_id  = 'org_designation_id';

    const table               = 'org_employee_designation_department';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = OrgEmployeeDesignationDepartment::table;

    protected $fillable = [];
}
