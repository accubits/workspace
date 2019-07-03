<?php

namespace Modules\OrgManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class OrgDepartment extends Model
{
    const slug          = 'slug';
    const name          = 'department_name';
    const org_id        = 'org_id';
    const parent_department_id = 'parent_department_id';
    const root_department_id = 'root_department_id';
    const path_enum = 'path_enum';

    const table         = 'org_departments';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = OrgDepartment::table;

    protected $fillable = [
        OrgDepartment::slug,
        OrgDepartment::name,
        OrgDepartment::org_id,
        OrgDepartment::parent_department_id
    ];
}
