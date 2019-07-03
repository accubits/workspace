<?php

namespace Modules\OrgManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class OrgEmployeeDepartment extends Model
{
    const org_employee_id            = 'org_employee_id';
    const org_department_id          = 'org_department_id';
    const is_head                    = 'is_head';

    const table         = 'org_employee_department';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = OrgEmployeeDepartment::table;

    protected $fillable = [
        OrgEmployeeDepartment::org_employee_id,
        OrgEmployeeDepartment::org_department_id
    ];

    public $timestamps = false;
    
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            ->where(OrgEmployeeDepartment::org_department_id, '=', $this->getAttribute(OrgEmployeeDepartment::org_department_id))
            ->where(OrgEmployeeDepartment::org_employee_id, '=', $this->getAttribute(OrgEmployeeDepartment::org_employee_id));
        return $query;
    }
    
}
