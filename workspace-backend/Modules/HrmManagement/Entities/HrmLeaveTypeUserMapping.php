<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmLeaveTypeUserMapping extends Model
{
    const org_id     = 'org_id';
    const leave_type_id = 'leave_type_id';
    const user_id       = 'user_id';

    const table      = 'hrm_leave_type_user_map';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmLeaveTypeUserMapping::table;

    protected $fillable = [
        HrmLeaveTypeUserMapping::org_id,
        HrmLeaveTypeUserMapping::leave_type_id,
        HrmLeaveTypeUserMapping::user_id
    ];
}
