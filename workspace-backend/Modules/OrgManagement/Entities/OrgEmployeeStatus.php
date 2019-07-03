<?php

namespace Modules\OrgManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class OrgEmployeeStatus extends Model
{
    const name                = 'name';
    const WORKING             = 'CURRENTLY_WORKING';
    const EX_EMPLOYEE         = 'EX_EMPLOYEE';
    const ON_LEAVE            = 'ON_LEAVE';

    const table               = 'org_employee_status';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = OrgEmployeeStatus::table;

    protected $fillable = [];
}
