<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmLeaveType extends Model
{
    const slug   = 'category_slug';
    const org_id = 'org_id';
    const name   = 'name';
    const type_category_id = 'type_category_id';
    const description = 'description';
    const creator_id  = 'creator_id';
    const period      = 'period';
    const to_all_employee   = 'to_all_employee';
    const leave_count       = 'leave_count';
    const is_active         = 'is_active';
    const is_applicable_for = 'is_applicable_for';
    const color_code        = 'color_code';

    const Annual = 'Annual';
    const Monthly = 'Monthly';


    const table = 'hrm_leave_type';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmLeaveType::table;

    protected $fillable = [
        HrmLeaveType::slug,
        HrmLeaveType::org_id,
        HrmLeaveType::description,
        HrmLeaveType::type_category_id,
        HrmLeaveType::name,
        HrmLeaveType::leave_count,
        HrmLeaveType::to_all_employee,
        HrmLeaveType::period,
        HrmLeaveType::is_active,
        HrmLeaveType::is_applicable_for,
        HrmLeaveType::color_code
    ];
}
