<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmLeaveRequests extends Model
{
    const slug             = 'leave_request_slug';
    const org_id           = 'org_id';
    const requesting_user_id         = 'requesting_user_id';
    const request_to_user_id         = 'request_to_user_id';
    const request_leave_start_date   = 'request_leave_start_date';
    const request_leave_end_date     = 'request_leave_end_date';
    const is_request_leave_start_halfday      = 'is_request_leave_start_halfday';
    const is_request_leave_ends_halfday       = 'is_request_leave_ends_halfday';
    const leave_type_id    = 'leave_type_id';
    const reason           = 'reason';
    const is_approved      = 'is_approved';
    const is_cancelled     = 'is_cancelled';


    const table     = 'hrm_leave_requests';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmLeaveRequests::table;

    protected $fillable = [
        HrmLeaveRequests::slug,
        HrmLeaveRequests::org_id,
        HrmLeaveRequests::request_to_user_id,
        HrmLeaveRequests::leave_type_id,
        HrmLeaveRequests::is_request_leave_start_halfday,
        HrmLeaveRequests::is_request_leave_ends_halfday,
        HrmLeaveRequests::request_leave_start_date,
        HrmLeaveRequests::request_leave_end_date,
        HrmLeaveRequests::reason,
        HrmLeaveRequests::is_approved,
        HrmLeaveRequests::is_cancelled
    ];
}
